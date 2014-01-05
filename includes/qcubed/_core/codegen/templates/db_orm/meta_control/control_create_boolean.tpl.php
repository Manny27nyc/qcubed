		/**
		 * Create and setup QJqCheckBox <?php echo $strControlId  ?>

		 * @param string $strControlId optional ControlId to use
		 * @return QJqCheckBox
		 */
		public function <?php echo $strControlId  ?>_Create($strControlId = null) {
			$this-><?php echo $strControlId  ?> = new QJqCheckBox($this->objParentObject, $strControlId);
			$this-><?php echo $strControlId  ?>->Name = QApplication::Translate('<?php echo QCodeGen::MetaControlLabelNameFromColumn($objColumn)  ?>');
			$this-><?php echo $strControlId ?>_Refresh();
			return $this-><?php echo $strControlId ?>;
		}

		/**
		 * Refresh QJqCheckBox <?php echo $strControlId ?>

		 * @return QJqCheckBox
		 */
		public function <?php echo $strControlId ?>_Refresh() {
			$this-><?php echo $strControlId ?>->Checked = $this-><?php echo $strObjectName ?>-><?php echo $objColumn->PropertyName ?>;
			return $this-><?php echo $strControlId ?>;
		}

		/**
		 * Create and setup QRadioButtonList <?php echo $strControlId  ?> to be used in a search panel.

		 * @param string $strControlId optional ControlId to use
		 * @return QRadioButtonList
		 */
		public function <?php echo $strControlId  ?>_CreateForSearch($strControlId = null) {
			$this-><?php echo $strControlId  ?> = new QRadioButtonList($this->objParentObject, $strControlId);
			$this-><?php echo $strControlId  ?>->Name = QApplication::Translate('<?php echo QCodeGen::MetaControlLabelNameFromColumn($objColumn)  ?>');
			$this-><?php echo $strControlId  ?>->ButtonMode = QCheckBoxList::ButtonModeSet;
			$this-><?php echo $strControlId ?>_RefreshForSearch();
			return $this-><?php echo $strControlId ?>;
		}

		/**
		 * Refresh QRadioButtonList <?php echo $strControlId ?> For Search

		 * @return QRadioButtonList
		 */
		public function <?php echo $strControlId ?>_RefreshForSearch() {
			$this-><?php echo $strControlId ?>->RemoveAllItems();
			$this-><?php echo $strControlId ?>->AddItem(QApplication::Translate("N/A"), null, true);
			$this-><?php echo $strControlId ?>->AddItem(QApplication::Translate("Yes"), true);
			$this-><?php echo $strControlId ?>->AddItem(QApplication::Translate("No"), false);
			return $this-><?php echo $strControlId ?>;
		}

		/**
		 * Reset QJqCheckBox <?php echo $strControlId ?>

		 * @return QJqCheckBox
		 */
		public function <?php echo $strControlId ?>_Reset() {
			if ($this-><?php echo $strControlId ?> instanceof QJqCheckBox) {
				$this-><?php echo $strControlId ?>->Checked = null;
			} else if ($this-><?php echo $strControlId ?> instanceof QRadioButtonList) {
				$this-><?php echo $strControlId ?>->SelectedValue = null;
			}
			return $this-><?php echo $strControlId ?>;
		}

		/**
		 * Make search query for QRadioButtonList <?php echo $strControlId  ?> to be used in a search query.
		 * @return QQCondition
		 */
		public function <?php echo $strControlId  ?>_MakeSearchQuery() {
			if (null !== $this-><?php echo $strControlId  ?>->SelectedValue) {
				return QQ::Equal(QQN::<?php echo $objTable->ClassName  ?>()-><?php echo $objColumn->PropertyName  ?>, $this-><?php echo $strControlId  ?>->SelectedValue);
			}
			return null;
		}

		/**
		 * Update QJqCheckBox <?php echo $strControlId ?>

		 * @return QJqCheckBox
		 */
		public function <?php echo $strControlId ?>_Update() {
			if ($this-><?php echo $strControlId ?> instanceof QJqCheckBox) {
				$this-><?php echo $strObjectName ?>-><?php echo $objColumn->PropertyName ?> = $this-><?php echo $strControlId ?>->Checked;
			} else if ($this-><?php echo $strControlId ?> instanceof QRadioButtonList) {
				$this-><?php echo $strObjectName ?>-><?php echo $objColumn->PropertyName ?> = $this-><?php echo $strControlId ?>->SelectedValue;
			}
			return $this-><?php echo $strControlId ?>;
		}
		
		/**
		 * Create and setup QLabel <?php echo $strLabelId  ?>

		 * @param string $strControlId optional ControlId to use
		 * @return QLabel
		 */
		public function <?php echo $strLabelId  ?>_Create($strControlId = null) {
			$this-><?php echo $strLabelId  ?> = new QLabel($this->objParentObject, $strControlId);
			$this-><?php echo $strLabelId  ?>->Name = QApplication::Translate('<?php echo QCodeGen::MetaControlLabelNameFromColumn($objColumn)  ?>');
			$this-><?php echo $strLabelId  ?>->Text = ($this-><?php echo $strObjectName  ?>-><?php echo $objColumn->PropertyName  ?>) ? QApplication::Translate('Yes') : QApplication::Translate('No');
			return $this-><?php echo $strLabelId  ?>;
		}