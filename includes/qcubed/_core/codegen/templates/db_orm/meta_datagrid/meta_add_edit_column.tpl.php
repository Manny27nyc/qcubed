/**
		 * Will add an "edit" link-based column, using a standard HREF link to redirect the user to a page
		 * that must be specified.
		 *
		 * @param string $strLinkUrl the URL to redirect the user to
		 * @param string $strLinkHtml the HTML of the link text
		 * @param string $strColumnTitle the HTML of the link text
		 * @param string $intArgumentType the method used to pass information to the edit page (defaults to PathInfo)
		 */
		public function MetaAddEditLinkColumn($strLinkUrl, $strLinkHtml = 'Edit', $strColumnTitle = 'Edit', $intArgumentType = QMetaControlArgumentType::PathInfo) {
			switch ($intArgumentType) {
				case QMetaControlArgumentType::QueryString:
					$strLinkUrl .= (strpos($strLinkUrl, '?') !== false ? '&' : '?').'<?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) {?><?php echo $objColumn->VariableName  ?>=" . urlencode($_ITEM-><?php echo $objColumn->PropertyName ?>) . "&<?php }?><?php GO_BACK(1); ?>';
					break;
				case QMetaControlArgumentType::PathInfo:
					$strLinkUrl .= '<?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) {?>/" . urlencode($_ITEM-><?php echo $objColumn->PropertyName ?>) . "<?php }?>';
					break;
				default:
					throw new QCallerException('Unable to pass arguments with this intArgumentType: ' . $intArgumentType);
			}

			$strHtml = sprintf('<?php print("<?="); ?> $_CONTROL->renderEditButton($_ITEM->Id, "%s", "%s") ?>', $strLinkHtml, $strLinkUrl);
			$colEditColumn = new QDataGridColumn($strColumnTitle, $strHtml, 'HtmlEntities=False');
			$this->AddColumn($colEditColumn);
			return $colEditColumn;
		}

		public function renderEditButton($intObjectId, $strLinkHtml, $strLinkUrl) {
			$objControlId = "editButton" . $intObjectId;

			if (!$objControl = $this->Form->GetControl($objControlId)) {
				$objControl = new QJqLinkButton($this, $objControlId);
				$objControl->Text = $strLinkHtml;
				$objControl->Href = $strLinkUrl;
			}

			// We pass the parameter of "false" to make sure the control doesn't render
			// itself RIGHT HERE - that it instead returns its string rendering result.
			return $objControl->Render(false);
		}

		/**
		 * Will add an "edit" control proxy-based column, calling any actions on a given control proxy
		 * that must be specified.
		 *
		 * @param QControlProxy $pxyControl the control proxy to use
		 * @param string $strLinkHtml the HTML of the link text
		 * @param string $strColumnTitle the HTML of the link text
		 */
		public function MetaAddEditProxyColumn(QControlProxy $pxyControl, $strLinkHtml = 'Edit', $strColumnTitle = 'Edit') {
			$strHtml = sprintf('<?php print("<?="); ?> $_CONTROL->renderProxyEditButton($_ITEM->Id, "%s", "%s") ?>', $strLinkHtml, $pxyControl->ControlId);
			$colEditColumn = new QDataGridColumn($strColumnTitle, $strHtml, 'HtmlEntities=False');
			$this->AddColumn($colEditColumn);
			return $colEditColumn;
		}

		public function renderProxyEditButton($intObjectId, $strLinkHtml, $strProxyControlId) {
			/**  @var QControlProxy */
			$pxyControl = $this->Form->GetControl($strProxyControlId);
			$strEvents = $pxyControl->RenderAsEvents($intObjectId, false);
			$objControlId = $pxyControl->TargetControlId;

			if (!$objControl = $this->Form->GetControl($objControlId)) {
				$objControl = new QJqButton($this, $objControlId);
				$objControl->Text = $strLinkHtml;
			}

			// We pass the parameter of "false" to make sure the control doesn't render
			// itself RIGHT HERE - that it instead returns its string rendering result.
			return $objControl->Render(false);
		}
