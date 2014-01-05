<?php
	/**
	 * This will store the formstate in a memcached server.
	 * The memcached server should be accesible and defined by CACHE_PROVIDER_CLASS constant
	 */
	class QMemcacheFormStateHandler extends QBaseClass {
		/**
		 * The filename prefix to be used by all FormState files
		 *
		 * @var string FileNamePrefix
		 */
		public static $FileNamePrefix = 'qformstate_';
		
		/**
		 * The maximum value size memcached can accept
		 *
		 * @var integer MaxValueSize
		 */
		public static $MaxValueSize = 1048576; // 1 Megabyte

		/**
		 * The handler for values with size exceeds the MaxValueSize
		 *
		 * @var string BigValueHandler
		 */
		public static $BigValueHandler = __FORM_STATE_HANDLER_FALL_BACK_HANDLER__;
		
		public static function Save($strFormState, $blnBackButtonFlag) {
			$strFormStateSerialized = self::SerializeData($strFormState);

			// Figure Out Session Id (if applicable)
			$strSessionId = session_id();

			if (array_key_exists('Qform__FormState', $_POST) &&
				// the result of md5 has a 32 bytes length
				// This check fixes the case of switch handler from simple form state to file form state
				strlen($_POST['Qform__FormState']) == 32)
			{
				$strPageId = $_POST['Qform__FormState'];
			} else {
				// Calculate a new unique Page Id
				$strPageId = md5(microtime());
			}

			// Figure Out FilePath
			$strFilePath = sprintf('%s%s_%s',
				self::$FileNamePrefix,
				$strSessionId,
				$strPageId);
			
			if (strlen($strFormStateSerialized) >= self::$MaxValueSize) {
				// mark that $strFilePath should be handled by $BigValueHandler
				QApplication::$objCacheProvider->Set($strFilePath, self::$BigValueHandler);
				$strSaveCommand = array(self::$BigValueHandler, 'Save');
				return call_user_func($strSaveCommand, $strFormState, $blnBackButtonFlag);
			}
			$strFormStateOld = QApplication::$objCacheProvider->Get($strFilePath);
			if ($strFormStateOld) {
				$strFormStateOld = chunk_split(self::DeserializeData($strFormStateOld));
			}
			
			$strFormStateNew = chunk_split($strFormState);
			if (!$strFormStateOld || 0 != strcmp($strFormStateOld, $strFormStateNew)) {
//				$f_old = fopen("/tmp/dbg.old", "w");
//				$f_new = fopen("/tmp/dbg.new", "w");
//				fwrite($f_old, $strOld);
//				fwrite($f_new, $strNew);
				QApplication::$objCacheProvider->Set($strFilePath, $strFormStateSerialized);
			}

			// Return the Page Id
			// Because of the MD5-random nature of the Page ID, there is no need/reason to encrypt it
			return $strPageId;
		}

		public static function Cleanup($strPostDataState) {
			// Pull Out strPageId
			$strPageId = $strPostDataState;

			// Figure Out Session Id (if applicable)
			$strSessionId = session_id();

			// Figure Out FilePath
			$strFilePath = sprintf('%s%s_%s',
				self::$FileNamePrefix,
				$strSessionId,
				$strPageId);
			
			QApplication::$objCacheProvider->Delete($strFilePath);
		}

		/**
		 * Prepare data for writing it into the store
		 * @param string $strFormState The data to serialize
		 * @return string Serialized data
		 */
		public static function SerializeData($strFormState) {
			if (__FORM_STATE_HANDLER_USE_COMPRESSION__) {
				// Compress (if available)
				if (function_exists('gzcompress')) {
					$strFormState = gzcompress($strFormState, -1);
				}
			}
			
//			return base64_encode($strFormState);
			return $strFormState;
		}
		
		/**
		 * Prepare data for usage after reading it from a store
		 * @param string $strSerializedForm The serialized data
		 * @return string The deserialized data
		 */
		public static function DeserializeData($strSerializedForm) {
//			$strSerializedForm = base64_decode($strSerializedForm);
			if (__FORM_STATE_HANDLER_USE_COMPRESSION__) {
				// Uncompress (if available)
				if (function_exists('gzuncompress')) {
					$strSerializedForm = gzuncompress($strSerializedForm);
				}
			}
			return $strSerializedForm;
		}
		
		public static function Load($strPostDataState) {
			// Pull Out strPageId
			$strPageId = $strPostDataState;

			// Figure Out Session Id (if applicable)
			$strSessionId = session_id();

			// Figure Out FilePath
			$strFilePath = sprintf('%s%s_%s',
				self::$FileNamePrefix,
				$strSessionId,
				$strPageId);
			
			$strSerializedForm = QApplication::$objCacheProvider->Get($strFilePath);
			if (false === $strSerializedForm || // not set in cache. try to find it in the fallback cache. It can be a manual handlers switch.
				self::$BigValueHandler == $strSerializedForm
			) {
				// Check to avoid circular calls if we were called from another handler
				if (__FORM_STATE_HANDLER__ == 'QMemcacheFormStateHandler') {
					// value was saved to file and should be handled by $BigValueHandler
					$strLoadCommand = array(self::$BigValueHandler, 'Load');
					$strSerializedFormBigValue = call_user_func($strLoadCommand, $strPostDataState);
					if (self::$BigValueHandler != $strSerializedForm) {
						// The case of handlers switch. Needs to cleanup old value in the fallback handler, if any.
						$strCleanupCommand = array(self::$BigValueHandler, 'Cleanup');
						call_user_func($strCleanupCommand, $strPostDataState);
					}
					if (false !== $strSerializedFormBigValue && '' != $strSerializedFormBigValue && null !== $strSerializedFormBigValue) {
						QApplication::$objCacheProvider->Set($strFilePath, self::SerializeData($strSerializedFormBigValue));
					}
					return $strSerializedFormBigValue;
				}
				return null;
			}
			if ($strSerializedForm) {
				return self::DeserializeData($strSerializedForm);
			}
			return null;
		}
	}
?>