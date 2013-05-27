<?php
	/**
	 * JqButtonCheckBox Base File
	 * 
	 * The  QJqCheckBoxBase class defined here provides an interface between the generated
	 * QJqCheckBoxGen class, and QCubed. This file is part of the core and will be overwritten
	 * when you update QCubed. To override, make your changes to the QJqCheckBox.class.php file instead.
	 *
	 */

	/**
	 * Implements a JQuery Ui Check Box
	 * 
	 * Use this as if you were creating a standard check box.
	 * 
	 * One of the QJqCheckBoxGen properties use the same names as standard QCubed properties.
	 * The text property is a boolean in the JqUi object that specifies whether
	 * to show text or just icons (provided icons are defined), and the Label property overrides
	 * the standard HTML of the button. Because of the name conflict, the JQ UI property is called
	 * ->JqText. You can also use ShowText as an alias to this as well so that your code is more readable.
	 * 	Text = standard html text of button
	 *  Label = override of standard HTML text, if you want a button to say something different when JS is on or off
	 *  ShowText = whether or not to hide the text of the button when icons are set
	 *  
	 * @property boolean $ShowText Causes text to be shown when icons are also defined.
	 * 
	 * @link http://jqueryui.com/button/#checkbox
	 * @package Controls\Base
	 */
	class QJqCheckBoxBase extends QJqCheckBoxGen
	{
		/**
		 * Renders the control with an attached name
		 *
		 * This will call {@link QControlBase::GetControlHtml()} for the bulk of the work, but will add layout html as well.  It will include
		 * the rendering of the Controls' name label, any errors or warnings, instructions, and html before/after (if specified).
		 * As this is the parent class of all controls, this method defines how ALL controls will render when rendered with a name.
		 * If you need certain controls to display differently, override this function in that control's class.
		 *
		 * @param boolean $blnDisplayOutput true to send to display buffer, false to just return then html
		 * @throws QCallerException
		 * @return string HTML of rendered Control
		 */
		public function RenderWithName($blnDisplayOutput = true) {
			////////////////////
			// Call RenderHelper
			$this->RenderHelper(func_get_args(), __FUNCTION__);
			////////////////////

			$strDataRel = '';
			$strWrapperAttributes = '';
			if (!$this->blnUseWrapper) {
				//there is no wrapper --> add the special attribute data-rel to the name control
				$strDataRel = sprintf('data-rel="#%s"',$this->strControlId);
				$strWrapperAttributes = 'data-hasrel="1"';
			}

			// Custom Render Functionality Here

			// Because this example RenderWithName will render a block-based element (e.g. a DIV), let's ensure
			// that IsBlockElement is set to true
			$this->blnIsBlockElement = true;

			// Render the Control's Dressing
			$strToReturn = '<div class="renderWithName" ' . $strDataRel . '>';
			
			$strCssClassSuffix = "";
			switch ($this->strFormLayoutMode) {
				case QFormLayoutMode::LeftRightMode :
					$strCssClassSuffix = "-lr";
					break;
				case QFormLayoutMode::TopBottomMode :
					$strCssClassSuffix = "-tb";
					break;
			}

			// Render the Left side
			$strLabelClass = "form-name" . $strCssClassSuffix;
			if ($this->blnRequired){
				$strLabelClass .= ' required ui-priority-primary';
			}
			if (!$this->blnEnabled){
				$strLabelClass .= ' disabled ui-state-disabled';
			}

			if ($this->strInstructions){
				$strInstructions = '<br/><span class="instructions">' . $this->strInstructions . '</span>';
			}else{
				$strInstructions = '';
			}
			
			$strToReturn .= sprintf('<div class="%s">&nbsp;</div>', $strLabelClass);

			// Render the Right side
			$strMessage = '';
			if ($this->strValidationError){
//				$strMessage = sprintf('<span class="error">%s</span>', $this->strValidationError);
				$strMessage = sprintf('
		<div class="ui-widget" style="float: left; margin-left: 0.7em; margin-top: 0.2em;">
			<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">
				<div style="padding-top: 0.5em; padding-bottom: 0.5em;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
				%s</div>
			</div>
		</div>', $this->strValidationError);
				$this->SetCustomStyle("float", "left");
				$this->AddCssFile(__JQUERY_CSS__);
			}else if ($this->strWarning){
//				$strMessage = sprintf('<span class="error">%s</span>', $this->strWarning);
				$strMessage = sprintf('
		<div class="ui-widget" style="float: left; margin-left: 0.7em; margin-top: 0.2em;">
			<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">
				<div style="padding-top: 0.5em; padding-bottom: 0.5em;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
				%s</div>
			</div>
		</div>', $this->strWarning);
				$this->SetCustomStyle("float", "left");
				$this->AddCssFile(__JQUERY_CSS__);
			}
			
			try {
				$strFieldClass = "form-field" . $strCssClassSuffix;
				$strLabel = sprintf('<div><label for="%s">%s</label>%s</div>', $this->strControlId, $this->strName, $strInstructions);
				$strToReturn .= sprintf('<div class="%s">%s%s%s%s%s</div><div style="clear:both"></div>',
					$strFieldClass,
					$this->strHtmlBefore,
					$this->GetControlHtml(),
					$strLabel,
					$this->strHtmlAfter,
					$strMessage);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			$strToReturn .= '</div>';

			////////////////////////////////////////////
			// Call RenderOutput, Returning its Contents
			return $this->RenderOutput($strToReturn, $blnDisplayOutput, false, $strWrapperAttributes);
			////////////////////////////////////////////
		}

		public function __get($strName) {
			switch ($strName) {
				case 'ShowText': return $this->JqText;
				default: 
					try { 
						return parent::__get($strName); 
					} catch (QCallerException $objExc) { 
						$objExc->IncrementOffset(); 
						throw $objExc; 
					}
			}
		}
		
		public function __set($strName, $mixValue) {
			switch ($strName) {
				case 'ShowText':	// true if the text should be shown when icons are defined
					$this->JqText = $mixValue;
					break;
										
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