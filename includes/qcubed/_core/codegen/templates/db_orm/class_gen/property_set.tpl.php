		/**
		 * Override method to perform a property "Set"
		 * This will set the property $strName to be $mixValue
		 *
		 * @param string $strName Name of the property to set
		 * @param string $mixValue New value of the property
		 * @return mixed
		 */
		public function __set($strName, $mixValue) {
			switch ($strName) {
				///////////////////
				// Member Variables
				///////////////////
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
<?php if ((!$objColumn->Identity) && (!$objColumn->Timestamp)) { ?>
				case '<?php echo $objColumn->PropertyName  ?>':
					/**
					 * Sets the value for <?php echo $objColumn->VariableName  ?> <?php if ($objColumn->PrimaryKey) print '(PK)'; else if ($objColumn->Unique) print '(Unique)'; else if ($objColumn->NotNull) print '(Not Null)'; ?>

					 * @param <?php echo $objColumn->VariableType  ?> $mixValue
					 * @return <?php echo $objColumn->VariableType  ?>

					 */
					try {
						if ($mixValue instanceof QDbSpecific) {
							return ($this-><?php echo $objColumn->VariableName  ?> = $mixValue);
						}
						return ($this-><?php echo $objColumn->VariableName  ?> = QType::Cast($mixValue, <?php echo $objColumn->VariableTypeAsConstant  ?>));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

<?php } ?>
<?php } ?>

				///////////////////
				// Member Objects
				///////////////////
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
<?php if (($objColumn->Reference) && (!$objColumn->Reference->IsType)) { ?>
				case '<?php echo $objColumn->Reference->PropertyName  ?>':
					/**
					 * Sets the value for the <?php echo $objColumn->Reference->VariableType  ?> object referenced by <?php echo $objColumn->VariableName  ?> <?php if ($objColumn->Identity) print '(Read-Only PK)'; else if ($objColumn->PrimaryKey) print '(PK)'; else if ($objColumn->Unique) print '(Unique)'; else if ($objColumn->NotNull) print '(Not Null)'; ?>

					 * @param <?php echo $objColumn->Reference->VariableType  ?> $mixValue
					 * @return <?php echo $objColumn->Reference->VariableType  ?>

					 */
					if (is_null($mixValue)) {
						$this-><?php echo $objColumn->VariableName  ?> = null;
						return null;
					} else {
						// Make sure $mixValue actually is a <?php echo $objColumn->Reference->VariableType  ?> object
						try {
							$mixValue = QType::Cast($mixValue, '<?php echo $objColumn->Reference->VariableType  ?>');
						} catch (QInvalidCastException $objExc) {
							$objExc->IncrementOffset();
							throw $objExc;
						}

						// Make sure $mixValue is a SAVED <?php echo $objColumn->Reference->VariableType  ?> object
						if (is_null($mixValue-><?php echo $objCodeGen->TableArray[strtolower($objColumn->Reference->Table)]->ColumnArray[strtolower($objColumn->Reference->Column)]->PropertyName  ?>))
							throw new QCallerException('Unable to set an unsaved <?php echo $objColumn->Reference->PropertyName  ?> for this <?php echo $objTable->ClassName  ?>');

						// Update Local Member Variables
						$this-><?php echo $objColumn->VariableName  ?> = $mixValue-><?php echo $objCodeGen->TableArray[strtolower($objColumn->Reference->Table)]->ColumnArray[strtolower($objColumn->Reference->Column)]->PropertyName  ?>;

						// Return $mixValue
						return $mixValue;
					}
					break;

<?php } ?>
<?php } ?>
				default:
					try {
						return parent::__set($strName, $mixValue);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}