<?php
	/**
	 * This is the "standard" FormState handler, storing the base64 encoded session data
	 * (and if requested by QForm, encrypted) as a hidden form variable on the page, itself.
	 */
	class QFormStateHandlerChecked extends QBaseClass {
		public static function Save($strFormState, $blnBackButtonFlag) {
			// Compress (if available)
			$strFormStateSrc = $strFormState;
			if (function_exists('gzcompress'))
				$strFormState1 = @gzcompress($strFormState, 9);
			if (FALSE === $strFormState1) {
				error_log("QFormStateHandlerChecked: gzcompress failed for:" . $strFormState);
				return null;
			} else {
				$strFormState = $strFormState1;
			}

			// Uncompress (if available)
			if (function_exists('gzuncompress')) {
				$strCheck = @gzuncompress($strFormState);
				if ($strFormStateSrc != $strCheck) {
					error_log("QFormStateHandlerChecked: gzuncompress check failed 1: '" .
						$strFormStateSrc . "'");
					error_log("QFormStateHandlerChecked: gzuncompress check failed 2:" .
						" is not the same as '" . $strCheck . "'");
				}
			}
			
			if (is_null(QForm::$EncryptionKey)) {
				// Don't Encrypt the FormState -- Simply Base64 Encode it
				$strFormState = base64_encode($strFormState);

				// Cleanup FormState Base64 Encoding
				$strFormState = str_replace('+', '-', $strFormState);
				$strFormState = str_replace('/', '_', $strFormState);
			} else {
				// Use QCryptography to Encrypt
				$objCrypto = new QCryptography(QForm::$EncryptionKey, true);
				$strFormState = $objCrypto->Encrypt($strFormState);
			}
			$strCheck = self::Load($strFormState);
			if (($strFormStateSrc) != $strCheck) {
				error_log("QFormStateHandlerChecked: Load check failed 1: '" .
					$strFormStateSrc . "'");
				error_log("QFormStateHandlerChecked: Load check failed 2: '" .
					$strCheck . "'");
			}
			return $strFormState;
		}

		public static function Load($strPostDataState) {
			$strSerializedForm = $strPostDataState;

			if (is_null(QForm::$EncryptionKey)) {
				// Cleanup from FormState Base64 Encoding
				$strSerializedForm = str_replace('-', '+', $strSerializedForm);
				$strSerializedForm = str_replace('_', '/', $strSerializedForm);
				
				//$strSerializedForm = chunk_split($strSerializedForm);
				$strSerializedForm1 = base64_decode($strSerializedForm);
				if (FALSE === $strSerializedForm1) {
					error_log("QFormStateHandlerChecked: base64_decode failed for:" . $strSerializedForm);
					return null;
				} else {
					$strSerializedForm = $strSerializedForm1;
				}
			} else {
				// Use QCryptography to Decrypt
				$objCrypto = new QCryptography(QForm::$EncryptionKey, true);
				$strSerializedForm = $objCrypto->Decrypt($strSerializedForm);
			}

			// Uncompress (if available)
			if (function_exists('gzuncompress'))
				$strSerializedForm1 = @gzuncompress($strSerializedForm);
			
			if (FALSE === $strSerializedForm1) {
				error_log("QFormStateHandlerChecked: gzuncompress failed for:" . $strSerializedForm);
				return null;
			} else {
				$strSerializedForm = $strSerializedForm1;
			}

			return $strSerializedForm;
		}
	}
?>