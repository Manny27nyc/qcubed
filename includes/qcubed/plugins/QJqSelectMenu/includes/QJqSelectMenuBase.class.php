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
			// workaround for chrome: the initial menu width in datagrid header was too short
			// workaround is based on: http://stackoverflow.com/questions/3485365/how-can-i-force-webkit-to-redraw-repaint-to-propagate-style-changes#comment21078292_5258893
			QApplication::ExecuteJavaScript('setTimeout(function(){$j("<style></style>").appendTo($j(document.body)).remove();}, 0)');
		}
		
		/**
		 * @return string The "destroy" and then "create" javascript command
		 */
		public function GetRefreshUiJavaScript() {
			return sprintf('jQuery("#%s").%s("destroy");',
				$this->getJqControlId(),
				$this->getJqSetupFunction()) . $this->GetControlJavaScript();
		}

		/**
		 * Do a "destroy" and then recreate the jquery-ui component.
		 * It is usefull to make to work elements that were previously hidden.
		 */
		public function RefreshUi() {
			QApplication::ExecuteJavaScript(sprintf("setTimeout('%s', 1)", $this->GetRefreshUiJavaScript()));
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
