<?php

	/**
	 * Created by vaibhav on 1/28/12 (3:34 AM).
	 *
	 * This file contains the QDbBackedSessionHandler class.
	 *
	 * @package Sessions
	 */
	class QDbBackedSessionHandler extends QBaseClass {

		/**
		 * @var int The index in the database array
		 */
		protected static $intDbIndex;

		/**
		 * @var string The table name to be used for saving sessions.
		 */
		protected static $strTableName;
		
		protected static $blnInitializedInternal = false;

		protected static function getCache() {
			return QApplication::$objInMemoryCache;
		}
		
		/**
		 * @static
		 * @param string[] $strParamArray The parameters for this handler.
		 * @return boolean
		 */
		public static function Initialize($strParamArray) {
			self::InitializeInternal($strParamArray);
			
			// Set session handler functions
			$session_ok = session_set_save_handler(
				'QDbBackedSessionHandler::SessionOpen',
				'QDbBackedSessionHandler::SessionClose',
				'QDbBackedSessionHandler::SessionRead',
				'QDbBackedSessionHandler::SessionWrite',
				'QDbBackedSessionHandler::SessionDestroy',
				'QDbBackedSessionHandler::SessionGarbageCollect'
			);
			// could not register the session handler functions
			if (!$session_ok) {
				throw new QCallerException("session_set_save_handler function failed");
			}
			// Will be called before session ends.
			register_shutdown_function('session_write_close');
			return $session_ok;
		}

		/**
		 * @static
		 * @param string[] $strParamArray The parameters for this handler.
		 */
		protected static function InitializeInternal($strParamArray = null) {
			if (self::$blnInitializedInternal) {
				return;
			}
			if (!$strParamArray) {
				$strParamArray = array(
					// The database index where the Session storage tables are present. Remember, define it as an integer.
					"dbIndex" => 1
					// The table name to be used for session data storage (must meet the requirements laid out above)
					, "tableName" => "qc_session"
				);
			}
			$intDbIndex = $strParamArray['dbIndex'];
			if (0 == $intDbIndex) {
				return false;
			}
			$strTableName = $strParamArray['tableName'];
			self::$intDbIndex = QType::Cast($intDbIndex, QType::Integer);
			self::$strTableName = QType::Cast($strTableName, QType::String);
			// If the database index exists
			if (!array_key_exists(self::$intDbIndex, QApplication::$Database)) {
				throw new QCallerException('No database defined at DB_CONNECTION index ' . self::$intDbIndex . '. Correct your settings in configuration.inc.php.');
			}
			$objDatabase = QApplication::$Database[self::$intDbIndex];
			// see if the database contains a table with desired name
			if (!in_array(self::$strTableName, $objDatabase->GetTables())) {
				throw new QCallerException('Table ' . self::$strTableName . ' not found in database at DB_CONNECTION index ' . self::$intDbIndex . '. Correct your settings in configuration.inc.php.');
			}
			self::$blnInitializedInternal = true;
		}

		public static function SessionOpen($save_path, $session_name) {
			// Nothing to do
			return true;
		}

		public static function SessionClose() {
			// Nothing to do.
			return true;
		}

		public static function SessionRead($id) {
			self::InitializeInternal();

			$strData = QApplication::$objCacheProvider->Get('phpSession:' . $id);
			if (false === $strData) {
				$objDatabase = QApplication::$Database[self::$intDbIndex];
				$query = '
					SELECT
						' . $objDatabase->EscapeIdentifier('data') . '
					FROM
						' . $objDatabase->EscapeIdentifier(self::$strTableName) . '
					WHERE
						' . $objDatabase->EscapeIdentifier('id') . ' = ' . $objDatabase->SqlVariable($id);

				$result = $objDatabase->Query($query);

				$result_row = $result->FetchArray();

				// either the data was empty or the row was not found
				if (!$result_row) {
					// Check to avoid circular calls if we are a fallback handler
					if (__PHP_SESSION_HANDLER__ != 'QMemcacheBackedSessionHandler') {
						// Try to find it in the memcache. It can be a manual handlers switch.
						$strLoadCommand = array('QMemcacheBackedSessionHandler', 'SessionRead');
						$strMemcachedData = call_user_func($strLoadCommand, $id);
						if (null === $strMemcachedData || '' === $strMemcachedData) {
							return '';
						} else {
							// to enable safe switch back to memcached handler we should
							// clean up the value in the memcached, to prevent read of old data
							$strCleanupCommand = array('QMemcacheBackedSessionHandler', 'SessionDestroy');
							call_user_func($strCleanupCommand, $id);
						}
						self::getCache()->Set('PhpSessionCache:' . $id, $strMemcachedData);
						return $strMemcachedData;
					}
					return '';
				}
				$strData = $result_row['data'];
			}
			if (!$strData || !is_string($strData)) {
				return '';
			}
			// The session exists and was accessed. Return the data.
			// We do base64_decode because the write method had encoded it!
			$strData = base64_decode($strData);
			self::getCache()->Set('PhpSessionCache:' . $id, $strData);
			return $strData;
		}

		public static function SessionExists($id) {
			self::InitializeInternal();
			
			$objDatabase = QApplication::$Database[self::$intDbIndex];
			$query = '
				SELECT 1
				FROM
					' . $objDatabase->EscapeIdentifier(self::$strTableName) . '
				WHERE
					' . $objDatabase->EscapeIdentifier('id') . ' = ' . $objDatabase->SqlVariable($id);

			$result = $objDatabase->Query($query);

			$result_row = $result->FetchArray();

			// either the data was empty or the row was not found
			return !empty($result_row);
		}

		public static function SessionWrite($id, $strSessionData) {
			self::InitializeInternal();
			
			$strOldSessionData = self::getCache()->Get('PhpSessionCache:' . $id);
			if (false === $strOldSessionData || 0 != strcmp($strOldSessionData, $strSessionData) || !self::SessionExists($id)) {
				// We save base 64 encoded data in the database and there are reasons for it:
				// If you are having anything in session data which does not go well with the UTF-8 (default for most databases)
				// encoding, the database will start nagging and sessions will break. You may have blank pages as well.
				// Also, if you are using the QSessionFormStateHandler, compression of FormState converts the data to binary format
				// thus making it unfit to be saved to the database.
				// Base 64 encoding ensures that the data can be safely saved into the database as text.
				$objDatabase = QApplication::$Database[self::$intDbIndex];
				$strData = base64_encode($strSessionData);
				$objDatabase->InsertOrUpdate(
					self::$strTableName,
					array(
						'data' => $strData,
						'last_access_time' => time(),
						'id' => $id
					),
					'id');
				QApplication::$objCacheProvider->Set('phpSession:' . $id, $strData);
			}
			return true;
		}

		public static function SessionDestroy($id) {
			self::InitializeInternal();
			
			$objDatabase = QApplication::$Database[self::$intDbIndex];
			$query = '
				DELETE FROM
					' . $objDatabase->EscapeIdentifier(self::$strTableName) . '
				WHERE
					' . $objDatabase->EscapeIdentifier('id') . ' = ' . $objDatabase->SqlVariable($id);

			$objDatabase->NonQuery($query);
			
			QApplication::$objCacheProvider->Delete('phpSession:' . $id);
			
			return true;
		}

		public static function SessionGarbageCollect($intMaxSessionLifetime) {
			self::InitializeInternal();
			
			$objDatabase = QApplication::$Database[self::$intDbIndex];
			$old = time() - $intMaxSessionLifetime;

			$query = '
				DELETE FROM
					' . $objDatabase->EscapeIdentifier(self::$strTableName) . '
				WHERE
					' . $objDatabase->EscapeIdentifier('last_access_time') . ' < ' . $objDatabase->SqlVariable($old);

			$objDatabase->NonQuery($query);
			return true;
		}
	}

?>
