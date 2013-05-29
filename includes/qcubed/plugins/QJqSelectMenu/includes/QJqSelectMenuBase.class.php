<?php
	/**
	 * The base class for the QJqSelectMenu plugin.
	 * the basic functionality is implemented here.
	 */

	/**
	 * The base class for the QJqSelectMenu plugin.
	 * the basic functionality is implemented here.
	 */
	class QJqSelectMenuBase extends QJqSelectMenuGen {

		public function  __construct($objParentObject, $strControlId = null) {
			parent::__construct($objParentObject, $strControlId);
			$this->AddJavascriptFile("../../plugins/QJqSelectMenu/jquery.ui.selectmenu.js");
			$this->AddCssFile("../../plugins/QJqSelectMenu/jquery.ui.selectmenu.css");
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
