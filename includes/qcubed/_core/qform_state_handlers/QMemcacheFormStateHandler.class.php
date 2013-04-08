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
		public static $BigValueHandler = 'QFileFormStateHandler';
		
		public static function Save($strFormState, $blnBackButtonFlag) {
			// Compress (if available)
			if (function_exists('gzcompress'))
				$strFormState = gzcompress($strFormState, 9);

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
			
			if (strlen($strFormState) >= self::$MaxValueSize) {
				// mark that $strFilePath should be handled by $BigValueHandler
				QApplication::$objCacheProvider->Set($strFilePath, self::$BigValueHandler);
				$strSaveCommand = array(self::$BigValueHandler, 'Save');
				return call_user_func($strSaveCommand, $strFormState, $blnBackButtonFlag);
			}
			$strFormStateOld = QApplication::$objCacheProvider->Get($strFilePath);
			$strOld = $strFormStateOld;
			if ($strFormStateOld) {
				$strOld = gzuncompress($strOld);
				$strOld = chunk_split($strOld);
			}
			
			$strNew = $strFormState;
			$strNew = gzuncompress($strNew);
			$strNew = chunk_split($strNew);
			if (!$strOld || 0 != strcmp($strOld, $strNew)) {
				$f_old = fopen("/tmp/dbg.old", "w");
				$f_new = fopen("/tmp/dbg.new", "w");
				fwrite($f_old, $strOld);
				fwrite($f_new, $strNew);
				QApplication::$objCacheProvider->Set($strFilePath, $strFormState);
			}

			// Return the Page Id
			// Because of the MD5-random nature of the Page ID, there is no need/reason to encrypt it
			return $strPageId;
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
			if (null === $strSerializedForm || // not set in cache. try to find it in the fallback cache. It can be a manual handlers switch.
				self::$BigValueHandler == $strSerializedForm) {
				// value was saved to file and should be handled by $BigValueHandler
				$strLoadCommand = array(self::$BigValueHandler, 'Load');
				return call_user_func($strLoadCommand, $strPostDataState);
			}
			if ($strSerializedForm) {
				// Uncompress (if available)
				if (function_exists('gzcompress'))
					$strSerializedForm = gzuncompress($strSerializedForm);
				return $strSerializedForm;
			}
			return null;
		}
	}
?>