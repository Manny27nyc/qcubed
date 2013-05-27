<?php
	/**
	 * QJqButton
	 * 
	 * Put your customizations of the standard behavior here.
	 * 
	 * @package Controls
	 * @author 
	 * @copyright 2011
	 * @version $Id$
	 * @access public
	 */
     
	/**
	 * @package Controls  
	 * @author Qcubed
	 * @copyright 
	 * @version 2011
	 * @access public
	 * @property string $Href The URL to be placed in the href attribute
	 */
	class QJqLinkButton extends QJqButtonBase
	{
		/**
		 * @var string The URL to be placed in the href attribute
		 */
		protected $strHref;
		
		protected function GetControlHtml() {
			$strStyle = $this->GetStyleAttributes();
			if ($strStyle) {
				$strStyle = sprintf('style="%s"', $strStyle);
			}
			
			$strHref = "#";
			if ($this->strHref && strlen($this->strHref)) {
				$strHref = $this->strHref;
			}

			$strToReturn = sprintf('<a href="%s" name="%s" id="%s" %s%s > %s </a>',
				$strHref,
				$this->strControlId,
				$this->strControlId,
				$this->GetAttributes(),
				$strStyle,
				($this->blnHtmlEntities) ? QApplication::HtmlEntities($this->strText) : $this->strText
			);
  
			return $strToReturn;

		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				case "Href": return $this->strHref;
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

		/////////////////////////
		// Public Properties: SET
		/////////////////////////
		public function __set($strName, $mixValue) {
			$this->blnModified = true;

			switch ($strName) {
				case "Href":
					try {
						$this->strHref = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				default:
					try {
						parent::__set($strName, $mixValue);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;
			}
		}
	}
?>