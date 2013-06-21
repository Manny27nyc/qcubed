<?php
	/**
	 * This will store the formstate in a pre-specified table in the DB.
	 * This offers significant speed advantage over PHP SESSION because EACH form state
	 * is saved in its own row in the DB, and only the form state that is needed for loading will
	 * be accessed (as opposed to with session, ALL the form states are loaded into memory
	 * every time).
	 * 
	 * The downside is that because it doesn't utilize PHP's session management subsystem,
	 * this class must take care of its own garbage collection/deleting of old/outdated
	 * formstate files.
	 * 
	 * Because the index is randomy generated and MD5-hashed, there is no benefit from
	 * encrypting it -- therefore, the QForm encryption preferences are ignored when using
	 * QFileFormStateHandler.
	 */
	class QDbBackedFormStateHandler extends QBaseClass {

		/**
		 * The database index in configuration.inc.php where the formstates have to be managed
		 */
		public static $intDbIndex = __DB_BACKED_FORM_STATE_HANDLER_DB_INDEX__;

		/**
		 * The table name which will handle the formstates. It must have the following columns:
		 * 1. page_id: varchar(80)
		 * 2. save_time: integer
		 * 3. state_data: text
		 * 4. session_id: varchar(32)
		 */
		public static $strTableName = __DB_BACKED_FORM_STATE_HANDLER_TABLE_NAME__;
		/**
		 * The interval of hits before the garbage collection should kick in to delete
		 * old FormState files, or 0 if it should never be run.  The higher the number,
		 * the less often it runs (better aggregated-average performance, but requires more
		 * hard drive space).  The lower the number, the more often it runs (slower aggregated-average
		 * performance, but requires less hard drive space).
		 *
		 * @var integer GarbageCollectInterval
		 */
		public static $intGarbageCollectOnHitCount = 20000;

		/**
		 * The minimum age (in days) a formstate file has to be in order to be considered old enough
		 * to be garbage collected.  So if set to "1.5", then all formstate files older than 1.5 days
		 * will be deleted when the GC interval is kicked off.
		 * 
		 * Obviously, if the GC Interval is set to 0, then this GC Days Old value will be never used.
		 *
		 * @var integer GarbageCollectDaysOld
		 */
		public static $intGarbageCollectDaysOld = __FILE_FORM_STATE_HANDLER_GARBAGE_COLLECT_DAYS_OLD__;

		public static $objCacher;
		protected static function getCache() {
			if (!self::$objCacher) {
				self::$objCacher = new QCacheProviderLocalMemory(array());
			}
			return self::$objCacher;
		}

		private static $blnIsInitialized = false;
		private static function Initialize() {
			if (self::$blnIsInitialized) {
				return;
			}
			self::$blnIsInitialized = true;
			
			if (defined('__DB_BACKED_FORM_STATE_HANDLER_DB_INDEX__')) {
				self::$intDbIndex = __DB_BACKED_FORM_STATE_HANDLER_DB_INDEX__;
			}
			self::$intDbIndex = QType::Cast(self::$intDbIndex, QType::Integer);
			self::$strTableName = QType::Cast(self::$strTableName, QType::String);

			// If the database index exists
			if (!array_key_exists(self::$intDbIndex, QApplication::$Database)) {
				throw new QCallerException('No database defined at DB_CONNECTION index ' . self::$intDbIndex . '. Correct your settings in configuration.inc.php.');
			}
			$objDatabase = QApplication::$Database[self::$intDbIndex];
			// see if the database contains a table with desired name
			if (!in_array(self::$strTableName, $objDatabase->GetTables())) {
				throw new QCallerException('Table ' . self::$strTableName . ' not found in database at DB_CONNECTION index ' . self::$intDbIndex . '. Correct your settings in configuration.inc.php.');
			}
		}

		/**
		 * @static
		 * If PHP SESSION is enabled, then this method will delete all formstate files specifically
		 * for this SESSION user (and no one else).  This can be used in lieu of or in addition to the
		 * standard interval-based garbage collection mechanism.
		 * 
		 * Also, for standard web applications with logins, it might be a good idea to call
		 * this method whenever the user logs out.
		 */
		public static function DeleteFormStateForSession() {
			self::Initialize();
			// Figure Out Session Id (if applicable)
			$strSessionId = session_id();

			//Get database
			$objDatabase = QApplication::$Database[self::$intDbIndex];
			// Create the query
			$query = '
					DELETE FROM
						' . $objDatabase->EscapeIdentifier(self::$strTableName) . '
					WHERE
						' . $objDatabase->EscapeIdentifier('session_id') . ' = ' . $objDatabase->SqlVariable($strSessionId);

			$result = $objDatabase->Query($query);
		}

		/**
		 * This will delete all the formstate files that are older than $GarbageCollectDaysOld
		 * days old.
		 * @static
		 */
		private static function GarbageCollect() {
			self::Initialize();
			// Its not perfect and not sure but should be executed on expected intervals
			$objDatabase = QApplication::$Database[self::$intDbIndex];
			$query = '
					DELETE FROM
						' . $objDatabase->EscapeIdentifier(self::$strTableName) . '
					WHERE
						' . $objDatabase->EscapeIdentifier('save_time') . ' < ' . $objDatabase->SqlVariable(time() - 60 * 60 * 24 * self::$intGarbageCollectDaysOld);

			$result = $objDatabase->Query($query);
		}

		/**
		 * @static
		 *
		 * @param $strFormState
		 * @param $blnBackButtonFlag
		 *
		 * @return string
		 */
		public static function Save($strFormState, $blnBackButtonFlag) {
			self::Initialize();
			// First see if we need to perform garbage collection
			// Decide for garbage collection
			if ((self::$intGarbageCollectOnHitCount > 0) && (rand(1, self::$intGarbageCollectOnHitCount) == 1)) {
				self::GarbageCollect();
			}

			// Compress (if available)
			if (function_exists('gzcompress')) {
				$strFormState = gzcompress($strFormState, 9);
			}
			
			$strFormState = base64_encode($strFormState);

			// Figure Out Session Id (if applicable)
			$strSessionId = session_id();

			$blnUpdate = false;
			if (array_key_exists('Qform__FormState', $_POST) &&
				// the result of md5 has a 32 bytes length
				// This check fixes the case of switch handler from simple form state to file form state
				32/*md5*/ == strlen($_POST['Qform__FormState']))
			{
				$strPageId = $_POST['Qform__FormState'];
				$blnUpdate = true;
			} else {
				// Calculate a new unique Page Id
				$strPageId = md5(microtime());
			}

//			// Figure Out Page ID to be saved onto the database
//			$strPageId = sprintf('%s_%s',
//				$strSessionId,
//				$strPageId);
			
			$strSerializedForm = self::getCache()->Get( 'FormStateCache:' . $strSessionId . ':' . $strPageId);
			if (!$strSerializedForm || 0 != strcmp($strSerializedForm, $strFormState)) {
				//error_log("file_put_contents");
				
				// Save THIS formstate to the database
				//Get database
				$objDatabase = QApplication::$Database[self::$intDbIndex];
				// Create the query
				$strQuery = "";
				if ($blnUpdate) {
					$strQuery = '
								UPDATE
									' . $objDatabase->EscapeIdentifier(self::$strTableName) . '
								SET 
									' . $objDatabase->EscapeIdentifier('save_time') . '=
										' . $objDatabase->SqlVariable(time()) . ',
									' . $objDatabase->EscapeIdentifier('state_data') . '=
										' . $objDatabase->SqlVariable($strFormState) . '
								WHERE 
									' . $objDatabase->EscapeIdentifier('page_id') . '=
										' . $objDatabase->SqlVariable($strPageId) . ' AND
									' . $objDatabase->EscapeIdentifier('session_id') . '=
										' . $objDatabase->SqlVariable($strSessionId);
				} else {
					$strQuery = '
								INSERT INTO
									' . $objDatabase->EscapeIdentifier(self::$strTableName) . '
								(
									' . $objDatabase->EscapeIdentifier('page_id') . ',
									' . $objDatabase->EscapeIdentifier('session_id') . ',
									' . $objDatabase->EscapeIdentifier('save_time') . ',
									' . $objDatabase->EscapeIdentifier('state_data') . '
								)
								VALUES
								(
									' . $objDatabase->SqlVariable($strPageId) . ',
									' . $objDatabase->SqlVariable($strSessionId) . ',
									' . $objDatabase->SqlVariable(time()) . ',
									' . $objDatabase->SqlVariable($strFormState) . '
								)';
				}

				$result = $objDatabase->NonQuery($strQuery);
			}

			// Return the Page Id
			// Because of the MD5-random nature of the Page ID, there is no need/reason to encrypt it
			return $strPageId;
		}

		public static function Load($strPostDataState) {
			self::Initialize();
			// Pull Out strPageId
			$strPageId = $strPostDataState;

			// Figure Out Session Id (if applicable)
			$strSessionId = session_id();

			//Get database
			$objDatabase = QApplication::$Database[self::$intDbIndex];
			// The query to run
			$strQuery = '
						SELECT
							' . $objDatabase->EscapeIdentifier('state_data') . '
						FROM
							' . $objDatabase->EscapeIdentifier(self::$strTableName) . '
						WHERE
							' . $objDatabase->EscapeIdentifier('page_id') . ' = ' . $objDatabase->SqlVariable($strPageId);

			// Perform the Query
			$objDbResult = $objDatabase->Query($strQuery);

			$strFormStateRow = $objDbResult->FetchArray();

			if (empty($strFormStateRow)) {
				// The formstate with that page ID was not found
				return null;
			}
			$strSerializedForm = $strFormStateRow['state_data'];
			
			self::getCache()->Set( 'FormStateCache:' . $strSessionId . ':' . $strPageId, $strSerializedForm);
			$strSerializedForm = base64_decode($strSerializedForm);
			//* Uncompress (if available)
			if (function_exists('gzcompress')) {

				$strSerializedForm = gzuncompress($strSerializedForm);
			}

			return $strSerializedForm;
		}
	}

?>
