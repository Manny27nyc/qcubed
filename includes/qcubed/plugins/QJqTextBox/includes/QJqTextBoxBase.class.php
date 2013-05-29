<?php
	/**
	 * The base class for the QJqTextBox plugin.
	 * the basic functionality is implemented here.
	 */

	/**
	 * The base class for the QJqTextBox plugin.
	 * the basic functionality is implemented here.
	 */
	class QJqTextBoxBase extends QJqTextBoxGen {

		public function  __construct($objParentObject, $strControlId = null) {
			parent::__construct($objParentObject, $strControlId);
			$this->AddJavascriptFile("../../plugins/QJqTextBox/jquery.ui.inputtext.js");
			$this->AddCssFile("../../plugins/QJqTextBox/jquery.ui.inputtext.css");
			$this->AddCssClass("ui-inputtext-input");
		}

		protected function makeJqOptions() {
			$strJqOptions = parent::makeJqOptions();
			if (strlen($strJqOptions)) {
				$strJqOptions .= ',';
			}
//			$strJqOptions .= $this->makeJsProperty('Plugins', 'plugins');
			if ($strJqOptions) $strJqOptions = substr($strJqOptions, 0, -2);
			return $strJqOptions;
		}
		
		public function GetControlJavaScript() {
			$strToReturn =
				sprintf('jQuery("#%s").%s({%s})'
					, $this->getJqControlId(), $this->getJqSetupFunction(), $this->makeJqOptions());
			return $strToReturn;
		}

	}

?>
