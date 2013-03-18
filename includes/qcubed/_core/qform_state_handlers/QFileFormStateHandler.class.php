<?php
	/**
	 * This will store the formstate in a pre-specified directory on the file system.
	 * This offers significant speed advantage over PHP SESSION because EACH form state
	 * is saved in its own file, and only the form state that is needed for loading will
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
	class QFileFormStateHandler extends QBaseClass {
		/**
		 * The PATH where the FormState files should be saved
		 *
		 * @var string StatePath
		 */
		public static $StatePath = __FILE_FORM_STATE_HANDLER_PATH__;

		/**
		 * The filename prefix to be used by all FormState files
		 *
		 * @var string FileNamePrefix
		 */
		public static $FileNamePrefix = 'qformstate_';

		/**
		 * The interval of hits before the garbage collection should kick in to delete
		 * old FormState files, or 0 if it should never be run.  The higher the number,
		 * the less often it runs (better aggregated-average performance, but requires more
		 * hard drive space).  The lower the number, the more often it runs (slower aggregated-average
		 * performance, but requires less hard drive space).
		 *
		 * @var integer GarbageCollectInterval
		 */
		public static $GarbageCollectInterval =	__FILE_FORM_STATE_HANDLER_GARBAGE_COLLECT_INTERVAL__;

		/**
		 * The minimum age (in days) a formstate file has to be in order to be considered old enough
		 * to be garbage collected.  So if set to "1.5", then all formstate files older than 1.5 days
		 * will be deleted when the GC interval is kicked off.
		 * 
		 * Obviously, if the GC Interval is set to 0, then this GC Days Old value will be never used.
		 *
		 * @var integer GarbageCollectDaysOld
		 */
		public static $GarbageCollectDaysOld = __FILE_FORM_STATE_HANDLER_GARBAGE_COLLECT_DAYS_OLD__;
		
		public static $objCacher;
		protected static function getCache() {
			if (!self::$objCacher) {
				self::$objCacher = new QCacheProviderLocalMemory(array());
			}
			return self::$objCacher;
		}

		/**
		 * If PHP SESSION is enabled, then this method will delete all formstate files specifically
		 * for this SESSION user (and no one else).  This can be used in lieu of or in addition to the
		 * standard interval-based garbage collection mechanism.
		 * 
		 * Also, for standard web applications with logins, it might be a good idea to call
		 * this method whenever the user logs out.
		 */
		public static function DeleteFormStateForSession() {
			// Figure Out Session Id (if applicable)
			$strSessionId = session_id();

			$strPrefix = self::$FileNamePrefix . $strSessionId;

			// Go through all the files
			if (strlen($strSessionId)) {
				$objDirectory = dir(self::$StatePath);
				while (($strFile = $objDirectory->read()) !== false) {
					$intPosition = strpos($strFile, $strPrefix);
					if (($intPosition !== false) && ($intPosition == 0))
						unlink(sprintf('%s/%s', self::$StatePath, $strFile));
				}
			}
		}

		/**
		 * This will delete all the formstate files that are older than $GarbageCollectDaysOld
		 * days old.
		 */
		public static function GarbageCollect() {
			// Go through all the files
			$objDirectory = dir(self::$StatePath);
			while (($strFile = $objDirectory->read()) !== false) {
				if (!count(self::$FileNamePrefix))
					$intPosition = 0;
				else
					$intPosition = strpos($strFile, self::$FileNamePrefix);
				if (($intPosition !== false) && ($intPosition == 0)) {
					$strFile = sprintf('%s/%s', self::$StatePath, $strFile);
					$intTimeInterval = time() - (60 * 60 * 24 * self::$GarbageCollectDaysOld);
					$intModifiedTime = filemtime($strFile);

					if ($intModifiedTime < $intTimeInterval)
						unlink($strFile);
				}
			}
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
			// First see if we need to perform garbage collection
			// It is needed only if files were not deleted due to web-server crush
			if (self::$GarbageCollectInterval > 0) {
				// This is a crude interval-tester, but it works
				if (rand(1, self::$GarbageCollectInterval) == 1)
					self::GarbageCollect();
			}

			// Compress (if available)
			if (function_exists('gzcompress')) {
				$strFormState = gzcompress($strFormState, 9);
			}

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
			$strFilePath = sprintf('%s/%s%s_%s',
				self::$StatePath,
				self::$FileNamePrefix,
				$strSessionId,
				$strPageId);
			
			$strSerializedForm = self::getCache()->Get( 'FormStateCache:' . $strSessionId . ':' . $strPageId);
			if (!$strSerializedForm || 0 != strcmp($strSerializedForm, $strFormState)) {
				//error_log("file_put_contents");
				
				// Save THIS formstate to the file system
				// NOTE: if gzcompress is used, we are saving the *BINARY* data stream of the compressed formstate
				// In theory, this SHOULD work.  But if there is a webserver/os/php version that doesn't like
				// binary session streams, you can first base64_encode before saving to session (see note below).
				file_put_contents($strFilePath, $strFormState);
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
			$strFilePath = sprintf('%s/%s%s_%s',
				self::$StatePath,
				self::$FileNamePrefix,
				$strSessionId,
				$strPageId);

			if (file_exists($strFilePath)) {
				// Pull FormState from file system
				// NOTE: if gzcompress is used, we are restoring the *BINARY* data stream of the compressed formstate
				// In theory, this SHOULD work.  But if there is a webserver/os/php version that doesn't like
				// binary session streams, you can first base64_decode before restoring from session (see note above).
				$strSerializedForm = file_get_contents($strFilePath);

				self::getCache()->Set( 'FormStateCache:' . $strSessionId . ':' . $strPageId, $strSerializedForm);
				// Uncompress (if available)
				if (function_exists('gzcompress'))
					$strSerializedForm = gzuncompress($strSerializedForm);

				return $strSerializedForm;
			} else
				return null;
		}
	}
?>