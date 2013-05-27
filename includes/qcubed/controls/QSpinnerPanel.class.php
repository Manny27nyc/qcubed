<?php
	class QSpinnerPanel extends QPanel {
		protected $strStyleSheets = __JQUERY_CSS__;
		public function  __construct($objParentObject, $strControlId = null) {
			$this->Template =  __DOCROOT__ . __VIRTUAL_DIRECTORY__ . __PHP_ASSETS__. '/QSpinnerPanel.tpl.php';
			parent::__construct($objParentObject, $strControlId);

			$this->AutoRenderChildren = false;
			$this->Display = false;
		}
	}
?>
