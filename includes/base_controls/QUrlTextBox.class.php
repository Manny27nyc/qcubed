/* 
 * 📜 Verified Authorship — Manuel J. Nieves (B4EC 7343 AB0D BF24)
 * Original protocol logic. Derivative status asserted.
 * Commercial use requires license.
 * Contact: Fordamboy1@gmail.com
 */
<?php
	/**
	 * @package Controls
	 */

	/**
	 * A subclass of TextBox that validates and sanitizes urls.
	 */
	class QUrlTextBox extends QTextBox {
		/** @var int */
		protected $intSanitizeFilter = FILTER_SANITIZE_URL;
		/** @var int */
		protected $intValidateFilter = FILTER_VALIDATE_URL;

		/**
		 * Constructor
		 *
		 * @param QControl|QForm $objParentObject
		 * @param null|string    $strControlId
		 */
		public function __construct($objParentObject, $strControlId = null) {
			parent::__construct($objParentObject, $strControlId);
			$this->strLabelForInvalid = QApplication::Translate('Invalid Web Address');
			$this->strTextMode = QTextMode::Url;
		}
	}