<?php
	/**
	 * Spinner Base File
	 * 
	 * The  QSpinnerBase class defined here provides an interface between the generated
	 * QSpinnerGen class, and QCubed. This file is part of the core and will be overwritten
	 * when you update QCubed. To override, make your changes to the QSpinner.class.php file in
	 * the controls folder instead.
	 * 
	 *
	 */

	/**
	 * 
	 * Implements a JQuery UI Spinner Object
	 * 
	 * A spinner object is a field that is accompanied by up/down arrows to allow numeric or 
	 * numberic like values to be quickly selected. You can specify how the arrows step, 
	 * and also specify a step value for page up and page down keys.
	 * 
	 * TBD: Create options to have this return a particular QCubed Type
	 * 
	 * @link http://jqueryui.com/spinner/
	 * @package Controls\Base
	 *
	 */
	class QSpinnerBase extends QSpinnerGen
	{

		public function GetControlJavaScript() {
			$strToReturn = parent::GetControlJavaScript();
			$strToReturn .=
				sprintf('.on("spinchange", function (e, ui) {qcubed.recordControlModification("%s", "_Text", jQuery("#%s").%s("value"))})'
					, $this->getJqControlId(), $this->getJqControlId(), $this->getJqSetupFunction());
			return $strToReturn;
		}

		public function __set($strName, $mixValue) {
			switch ($strName) {
				// For internal use only!
				case '_Text':
					try {
						$this->Text = QType::Cast($mixValue, QType::String);
						// To prevent it's overwrite in the ParsePostData method.
						$_POST[$this->strControlId] = $this->Text;
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					
				default:
					try {
						parent::__set($strName, $mixValue);
						break;
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
	}
?>