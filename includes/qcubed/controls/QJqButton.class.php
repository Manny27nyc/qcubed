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
	 */
	class QJqButton extends QJqButtonBase {
		public function Hide( ) {
			$strJs = sprintf( 'jQuery("#%s").hide()', $this->getJqControlId() );
			QApplication::ExecuteJavaScript($strJs);
		}
		
		public function Show( ) {
			$strJs = sprintf( 'jQuery("#%s").show()', $this->getJqControlId() );
			QApplication::ExecuteJavaScript($strJs);
		}
	}
?>