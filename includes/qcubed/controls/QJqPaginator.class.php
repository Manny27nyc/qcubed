<?php
	class QJqPaginator extends QPaginatorBase {
		// APPEARANCE
		protected $intIndexCount = 10;

		protected $strLabelForPrevious;
		protected $strLabelForNext;

		protected $strCssClass = 'paginator';

		//////////
		// Methods
		//////////
		public function __construct($objParentObject, $strControlId = null) {
			parent::__construct($objParentObject, $strControlId);

			$this->strLabelForPrevious = QApplication::Translate('Previous');
			$this->strLabelForNext = QApplication::Translate('Next');
		}		
		
		public function GetControlHtml() {
			$this->objPaginatedControl->DataBind();

			$strStyle = $this->GetStyleAttributes();
			if ($strStyle)
				$strStyle = sprintf(' style="%s"', $strStyle);

			$strToReturn = sprintf('<span id="%s" %s%s>', $this->strControlId, $strStyle, $this->GetAttributes(true, false));

			if ($this->intPageNumber <= 1) {
				$strPrevious = $this->renderPageButton(null, $this->strLabelForPrevious, QApplication::$RequestUri . "/" . $this->intPageNumber, false, "Prev", JqIcon::CircleArrowW);
			} else {
				$strPrevious = $this->renderPageButton($this->intPageNumber - 1, $this->strLabelForPrevious, QApplication::$RequestUri . "/" . ($this->intPageNumber - 1), true, "Prev", JqIcon::CircleArrowW);
			}

			$strToReturn .= sprintf('<span class="arrow previous" style="padding-right:0.6em">%s</span>', $strPrevious);
			
			if ($this->PageCount <= $this->intIndexCount) {
				// We have less pages than total indexcount -- so let's go ahead
				// and just display all page indexes
				for ($intIndex = 1; $intIndex <= $this->PageCount; $intIndex++) {
					if ($this->intPageNumber == $intIndex) {
						$strToReturn .= $this->renderPageButton($intIndex, $intIndex, QApplication::$RequestUri . "/" . $intIndex, false);
					} else {
						$this->strActionParameter = $intIndex;
						$strToReturn .= $this->renderPageButton($intIndex, $intIndex, QApplication::$RequestUri . "/" . $intIndex);
					}
				}
			} else {
				// Figure Out Constants
				
				/**
				 * "Bunch" is defined as the collection of numbers that lies in between the pair of Ellipsis ("...")
				 * 
				 * LAYOUT
				 * 
				 * For IndexCount of 10
				 * 2   213   2 (two items to the left of the bunch, and then 2 indexes, selected index, 3 indexes, and then two items to the right of the bunch)
				 * e.g. 1 ... 5 6 *7* 8 9 10 ... 100
				 * 
				 * For IndexCount of 11
				 * 2   313   2
				 * 
				 * For IndexCount of 12
				 * 2   314   2
				 * 
				 * For IndexCount of 13
				 * 2   414   2
				 * 
				 * For IndexCount of 14
				 * 2   415   2
				 * 
				 * 
				 * 
				 * START/END PAGE NUMBERS FOR THE BUNCH
				 * 
				 * For IndexCount of 10
				 * 1 2 3 4 5 6 7 8 .. 100
				 * 1 .. 4 5 *6* 7 8 9 .. 100
				 * 1 .. 92 93 *94* 95 96 97 .. 100
				 * 1 .. 93 94 95 96 97 98 99 100
				 * 
				 * For IndexCount of 11
				 * 1 2 3 4 5 6 7 8 9 .. 100
				 * 1 .. 4 5 6 *7* 8 9 10 .. 100
				 * 1 .. 91 92 93 *94* 95 96 97 .. 100
				 * 1 .. 92 93 94 95 96 97 98 99 100
				 * 
				 * For IndexCount of 12
				 * 1 2 3 4 5 6 7 8 9 10 .. 100
				 * 1 .. 4 5 6 *7* 8 9 10 11 .. 100
				 * 1 .. 90 91 92 *93* 94 95 96 97 .. 100
				 * 1 .. 91 92 93 94 95 96 97 98 99 100
				 * 
				 * For IndexCount of 13
				 * 1 2 3 4 5 6 7 8 9 11 .. 100
				 * 1 .. 4 5 6 7 *8* 9 10 11 12 .. 100
				 * 1 .. 89 90 91 92 *93* 94 95 96 97 .. 100
				 * 1 .. 90 91 92 93 94 95 96 97 98 99 100
				 */
				$intMinimumEndOfBunch = $this->intIndexCount - 2;
				$intMaximumStartOfBunch = $this->PageCount - $this->intIndexCount + 3;
				
				$intLeftOfBunchCount = floor(($this->intIndexCount - 5) / 2);
				$intRightOfBunchCount = round(($this->intIndexCount - 5.0) / 2.0);

				$intLeftBunchTrigger = 4 + $intLeftOfBunchCount;
				$intRightBunchTrigger = $intMaximumStartOfBunch + round(($this->intIndexCount - 8.0) / 2.0);
				
				if ($this->intPageNumber < $intLeftBunchTrigger) {
					$intPageStart = 1;
					$strStartEllipsis = "";
				} else {
					$intPageStart = min($intMaximumStartOfBunch, $this->intPageNumber - $intLeftOfBunchCount);

					$strStartEllipsis = $this->renderPageButton(1, 1, QApplication::$RequestUri . "/" . 1);
					
					$strStartEllipsis .= '<span class="ellipsis">...</span>';
				}
				
				if ($this->intPageNumber > $intRightBunchTrigger) {
					$intPageEnd = $this->PageCount;
					$strEndEllipsis = "";
				} else {
					$intPageEnd = max($intMinimumEndOfBunch, $this->intPageNumber + $intRightOfBunchCount);
					$strEndEllipsis = '<span class="ellipsis">...</span>';

					$strEndEllipsis .= $this->renderPageButton($this->PageCount, $this->PageCount, QApplication::$RequestUri . "/" . $this->PageCount);
				}

				$strToReturn .= $strStartEllipsis;
				for ($intIndex = $intPageStart; $intIndex <= $intPageEnd; $intIndex++) {
					if ($this->intPageNumber == $intIndex) {
						$strToReturn .= $this->renderPageButton($intIndex, $intIndex, QApplication::$RequestUri . "/" . $intIndex, false);
					} else {
						$this->strActionParameter = $intIndex;
						$strToReturn .= $this->renderPageButton($intIndex, $intIndex, QApplication::$RequestUri . "/" . $intIndex);
					}
				}
				$strToReturn .= $strEndEllipsis;
			}
				
			if ($this->intPageNumber >= $this->PageCount) {
				$strNext = $this->renderPageButton(null, $this->strLabelForNext, QApplication::$RequestUri . "/" . $this->intPageNumber, false, "Next", JqIcon::CircleArrowE);
			} else {
				$strNext = $this->renderPageButton($this->intPageNumber + 1, $this->strLabelForNext, QApplication::$RequestUri . "/" . ($this->intPageNumber + 1), true, "Next", JqIcon::CircleArrowE);
			}

			$strToReturn .= sprintf('<span class="arrow next" style="padding-left:0.6em">%s</span>', $strNext);

			$strToReturn .= '</span>';

			return $strToReturn;
		}

		/**
		 * Render the button requested, and return the result as a string.
		 * @param int $intPageNumber The page number for wich button should be rendered
		 * @param string $strLinkHtml The text for the link to be shown
		 * @param string $strLinkUrl The URL to be set for the Href property
		 * @param boolean $blnSelected If false, the button whould be disabled
		 * @param string $strControlIdSuffix The additional text to be added to the ControlId to distinguish it from the already rendered one.
		 * @param string $strJqButtonClass The additional jqueryui-button CSS class to be applied to the button.
		 * @return string The rendered button HTML.
		 */
		public function renderPageButton($intPageNumber, $strLinkHtml, $strLinkUrl, $blnSelected = true, $strControlIdSuffix = "", $strJqButtonClass = "") {
			$objControlId = $this->ControlId . "Paginated" . $this->objPaginatedControl->ControlId . "PageButton" . $intPageNumber . $strControlIdSuffix;

			if (!$objControl = $this->Form->GetControl($objControlId)) {
				$objControl = new QJqLinkButton($this, $objControlId);
				$objControl->Text = $strLinkHtml;
				// link for SEO
				$objControl->Href = $strLinkUrl;
				$objControl->ActionParameter = $intPageNumber;
				if ($this->blnUseAjax) {
					$objControl->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'Page_Click'));
				}
				else {
					$objControl->AddAction(new QClickEvent(), new QServerControlAction($this, 'Page_Click'));
				}
				$objControl->AddAction(new QClickEvent, new QStopPropagationAction);
				// To prevent the link to follow
				$objControl->AddAction(new QClickEvent, new QTerminateAction);
				$objControl->Enabled = $blnSelected;
			} else {
				if ($objControl->Enabled != $blnSelected) {
					$objControl->Enabled = $blnSelected;
				}
			}
			
			if ($strJqButtonClass && strlen($strJqButtonClass)) {
				$objControl->ShowText = false;
				$objControl->Icons = array("primary" => $strJqButtonClass);
			}

			// We pass the parameter of "false" to make sure the control doesn't render
			// itself RIGHT HERE - that it instead returns its string rendering result.
			return $objControl->Render(false);
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				case 'IndexCount':
					return $this->intIndexCount;

				case 'LabelForNext':
					return $this->strLabelForNext;
				case 'LabelForPrevious':
					return $this->strLabelForPrevious;

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
			switch ($strName) {
				case 'IndexCount':
					$this->intIndexCount = QType::Cast($mixValue, QType::Integer);
					if ($this->intIndexCount < 7)
						throw new QCallerException('Paginator must have an IndexCount >= 7');
					return $this->intIndexCount;

				case 'LabelForNext':
					try {
						return ($this->strLabelForNext = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case 'LabelForPrevious':
					try {
						return ($this->strLabelForPrevious = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				default:
					try {
						return (parent::__set($strName, $mixValue));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;
			}
		}
	}
?>