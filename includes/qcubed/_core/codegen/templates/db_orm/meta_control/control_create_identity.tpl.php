		/**
		 * Create and setup QLabel <?php echo $strControlId  ?>

		 * @param string $strControlId optional ControlId to use
		 * @return QLabel
		 */
		public function <?php echo $strControlId  ?>_Create($strControlId = null) {
			$this-><?php echo $strControlId  ?> = new QLabel($this->objParentObject, $strControlId);
			$this-><?php echo $strControlId  ?>->Name = QApplication::Translate('<?php echo QCodeGen::MetaControlLabelNameFromColumn($objColumn)  ?>');
			$this-><?php echo $strControlId ?>_Refresh();
			return $this-><?php echo $strControlId ?>;
		}

		/**
		 * Refresh QLabel <?php echo $strControlId ?>

		 * @return QLabel
		 */
		public function <?php echo $strControlId ?>_Refresh() {
			if ($this->blnEditMode) {
				$this-><?php echo $strControlId ?>->Text = $this-><?php echo $strObjectName ?>-><?php echo $objColumn->PropertyName ?>;
			} else {
				$this-><?php echo $strControlId ?>->Text = QApplication::Translate('N/A');
			}
			return $this-><?php echo $strControlId ?>;
		}

		/**
		 * Create and setup QJqTextBox <?php echo $strControlId  ?> to be used in a search panel.

		 * @param string $strControlId optional ControlId to use
		 * @return QJqTextBox
		 */
		public function <?php echo $strControlId  ?>_CreateForSearch($strControlId = null) {
			$this-><?php echo $strControlId  ?> = new QSpinner($this->objParentObject, $strControlId);
			$this-><?php echo $strControlId  ?>->Name = QApplication::Translate('<?php echo QCodeGen::MetaControlLabelNameFromColumn($objColumn)  ?>');
			$this-><?php echo $strControlId ?>_RefreshForSearch();
<?php if ($objColumn->NotNull) { ?>
			$this-><?php echo $strControlId  ?>->Required = true;
<?php } ?>
			return $this-><?php echo $strControlId  ?>;
		}

		/**
		 * Refresh QLabel <?php echo $strControlId ?>

		 * @return QLabel
		 */
		public function <?php echo $strControlId ?>_RefreshForSearch() {
			$this-><?php echo $strControlId ?>->Text = null;
			return $this-><?php echo $strControlId ?>;
		}

		/**
		 * Reset QLabel <?php echo $strControlId ?>

		 * @return QLabel
		 */
		public function <?php echo $strControlId ?>_Reset() {
			$this-><?php echo $strControlId ?>->Text = null;
			return $this-><?php echo $strControlId ?>;
		}

		/**
		 * Make search query for QLabel <?php echo $strControlId  ?> to be used in a search query.
		 * @return QQCondition
		 */
		public function <?php echo $strControlId  ?>_MakeSearchQuery() {
			if (null !== $this-><?php echo $strControlId  ?>->Text && strlen($this-><?php echo $strControlId  ?>->Text) > 0) {
				return QQ::Equal(QQN::<?php echo $objTable->ClassName  ?>()-><?php echo $objColumn->PropertyName  ?>, $this-><?php echo $strControlId  ?>->Text);
			}
			return null;
		}

		/**
		 * Update QLabel <?php echo $strControlId ?>

		 * @return QLabel
		 */
		public function <?php echo $strControlId ?>_Update() {
			return $this-><?php echo $strControlId ?>;
		}
