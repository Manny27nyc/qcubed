		/**
		 * Create and setup QJqSelectMenu <?php echo $strControlId  ?>

		 * @param string $strControlId optional ControlId to use
		 * @return QJqSelectMenu
		 */
		public function <?php echo $strControlId  ?>_Create($strControlId = null) {
			$this-><?php echo $strControlId  ?> = new QJqSelectMenu($this->objParentObject, $strControlId);
			$this-><?php echo $strControlId  ?>->Name = QApplication::Translate('<?php echo QConvertNotation::WordsFromCamelCase($objColumn->Reference->PropertyName)  ?>');
<?php if ($objColumn->NotNull) { ?>
			$this-><?php echo $strControlId  ?>->Required = true;
<?php } ?><?php if (!$objColumn->NotNull) { ?>
			$this-><?php echo $strControlId  ?>->AddItem(QApplication::Translate('- Select One -'), null);
<?php } ?>
			foreach (<?php echo $objColumn->Reference->VariableType  ?>::$NameArray as $intId => $strValue)
				$this-><?php echo $strControlId  ?>->AddItem(new QListItem($strValue, $intId, $this-><?php echo $strObjectName  ?>-><?php echo $objColumn->PropertyName  ?> == $intId));
			return $this-><?php echo $strControlId  ?>;
		}

		/**
		 * Create and setup QLabel <?php echo $strLabelId  ?>

		 * @param string $strControlId optional ControlId to use
		 * @return QLabel
		 */
		public function <?php echo $strLabelId  ?>_Create($strControlId = null) {
			$this-><?php echo $strLabelId  ?> = new QLabel($this->objParentObject, $strControlId);
			$this-><?php echo $strLabelId  ?>->Name = QApplication::Translate('<?php echo QConvertNotation::WordsFromCamelCase($objColumn->Reference->PropertyName)  ?>');
			$this-><?php echo $strLabelId  ?>->Text = ($this-><?php echo $strObjectName  ?>-><?php echo $objColumn->PropertyName  ?>) ? <?php echo $objColumn->Reference->VariableType  ?>::$NameArray[$this-><?php echo $strObjectName  ?>-><?php echo $objColumn->PropertyName  ?>] : null;
<?php if ($objColumn->NotNull) { ?>
			$this-><?php echo $strLabelId  ?>->Required = true;
<?php } ?>
			return $this-><?php echo $strLabelId  ?>;
		}

		/**
		 * Reset QJqSelectMenu <?php echo $strControlId ?>

		 * @return QJqSelectMenu
		 */
		public function <?php echo $strControlId ?>_Reset() {
			$this-><?php echo $strControlId ?>->SelectedValue = null;
			return $this-><?php echo $strControlId ?>;
		}

		/**
		 * Make search query for QJqSelectMenu <?php echo $strControlId  ?> to be used in a search query.
		 * @return QQCondition
		 */
		public function <?php echo $strControlId  ?>_MakeSearchQuery() {
			if (null !== $this-><?php echo $strControlId  ?>->SelectedValue) {
				return QQ::Equal(QQN::<?php echo $objTable->ClassName  ?>()-><?php echo $objColumn->PropertyName  ?>, $this-><?php echo $strControlId  ?>->SelectedValue);
			}
			return null;
		}
