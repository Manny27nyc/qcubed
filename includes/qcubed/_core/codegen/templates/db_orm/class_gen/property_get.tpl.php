
		/**
		 * Constructs an unique for this object key string to be used for cache querying.
		 * @return string The key to be used for cache querying
		 */
		public function CreateInSessionCacheKey() {
<?php foreach ($objTable->IndexArray as $objIndex) { ?>
<?php if ($objIndex->Unique) { ?>
<?php $objColumnArray = $objCodeGen->GetColumnArray($objTable, $objIndex->ColumnNameArray); ?>

			$strCacheKey = self::CreateInSessionCacheKeyHelper(<?php echo $objCodeGen->NamedParameterMemberListFromColumnArray($objColumnArray);  ?>);
			return $strCacheKey;
<?php break; ?>
<?php } ?>
<?php } ?>

		}

		/**
		 * Constructs an unique for this object key string to be used for cache querying.
		 * @return string The key to be used for cache querying
		 */
		public function CreateCacheKey() {
<?php foreach ($objTable->IndexArray as $objIndex) { ?>
<?php if ($objIndex->Unique) { ?>
<?php $objColumnArray = $objCodeGen->GetColumnArray($objTable, $objIndex->ColumnNameArray); ?>

			$strCacheKey = self::CreateCacheKeyHelper(<?php echo $objCodeGen->NamedParameterMemberListFromColumnArray($objColumnArray);  ?>);
			return $strCacheKey;
<?php break; ?>
<?php } ?>
<?php } ?>

		}

		/**
		 * Constructs an unique for this object key string to be used for cache
		 * stored in a _SESSION variable querying.
		 * @return string The key to be used for cache querying
		 */
		protected static function CreateInSessionCacheKeyHelper(/* ... */) {
			$strClassName = '<?php
				$strClassName = $objTable->ClassName;
				$strIris = 'Iris';
				$blnIrisFound = (FALSE !== strpos($strClassName, $strIris));
				if ($blnIrisFound) {
					$strClassName = substr($strClassName, strlen($strIris));
				}
				echo $strClassName;
			?>';
			// @hack for php version < 5.4
			$objArgsArray = array();
			$arg_list = func_get_args();
			$numargs = func_num_args();
			for ($i = 0; $i < $numargs; $i++) {
				$objArgsArray[] = $arg_list[$i];
			}
			//$objArgsArray = func_get_args();

			$strCacheKey = QAbstractCacheProvider::MakeKey(
				QApplication::$Database[<?php echo $objCodeGen->DatabaseIndex; ?>]->Database
				, $strClassName
				, $objArgsArray
			);
			return $strCacheKey;
		}
		
		/**
		 * Constructs an unique for this object key string to be used for cache querying.
		 * Accepts a list of variables produced by $objCodeGen->NamedParameterListFromColumnArray function
		 * That has a form of "'Id', $intId1, 'Id2', $intId2"
		 * @return string The key to be used for cache querying
		 */
		protected static function CreateCacheKeyHelper(/* ... */) {
			$strCacheKey = false;
			if (QApplication::$objCacheProvider && QApplication::$Database[<?php echo $objCodeGen->DatabaseIndex; ?>]->Caching) {
				$strClassName = '<?php
					$strClassName = $objTable->ClassName;
					$strIris = 'Iris';
					$blnIrisFound = (FALSE !== strpos($strClassName, $strIris));
					if ($blnIrisFound) {
						$strClassName = substr($strClassName, strlen($strIris));
					}
					echo $strClassName;
				?>';
				// @hack for php version < 5.4
				$objArgsArray = array();
				$arg_list = func_get_args();
				$numargs = func_num_args();
				for ($i = 0; $i < $numargs; $i++) {
					$objArgsArray[] = $arg_list[$i];
				}
				//$objArgsArray = func_get_args();
				
				$strCacheKey = QApplication::$objCacheProvider->CreateKey(
					QApplication::$Database[<?php echo $objCodeGen->DatabaseIndex; ?>]->Database
					, $strClassName
					, $objArgsArray
				);
			}
			return $strCacheKey;
		}

		/**
		 * Override method to perform a property "Get"
		 * This will get the value of $strName
		 *
		 * @param string $strName Name of the property to get
		 * @return mixed
		 */
		public function __get($strName) {
			switch ($strName) {
				///////////////////
				// Member Variables
				///////////////////
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
				case '<?php echo $objColumn->PropertyName  ?>':
					/**
					 * Gets the value for <?php echo $objColumn->VariableName  ?> <?php if ($objColumn->Identity) print '(Read-Only PK)'; else if ($objColumn->PrimaryKey) print '(PK)'; else if ($objColumn->Timestamp) print '(Read-Only Timestamp)'; else if ($objColumn->Unique) print '(Unique)'; else if ($objColumn->NotNull) print '(Not Null)'; ?>

					 * @return <?php echo $objColumn->VariableType  ?>

					 */
					return $this-><?php echo $objColumn->VariableName  ?>;

<?php } ?>

				///////////////////
				// Member Objects
				///////////////////
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
<?php if (($objColumn->Reference) && (!$objColumn->Reference->IsType)) { ?>
				case '<?php echo $objColumn->Reference->PropertyName  ?>':
					/**
					 * Gets the value for the <?php echo $objColumn->Reference->VariableType  ?> object referenced by <?php echo $objColumn->VariableName  ?> <?php if ($objColumn->Identity) print '(Read-Only PK)'; else if ($objColumn->PrimaryKey) print '(PK)'; else if ($objColumn->Unique) print '(Unique)'; else if ($objColumn->NotNull) print '(Not Null)'; ?>

					 * @return <?php echo $objColumn->Reference->VariableType  ?>

					 */
					try {
						return <?php echo $objColumn->Reference->VariableType  ?>::Load($this-><?php echo $objColumn->VariableName  ?>);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

<?php } ?>
<?php } ?>
<?php foreach ($objTable->ReverseReferenceArray as $objReverseReference) { ?>
<?php if ($objReverseReference->Unique) { ?>
<?php $objReverseReferenceTable = $objCodeGen->TableArray[strtolower($objReverseReference->Table)]; ?>
<?php $objReverseReferenceColumn = $objReverseReferenceTable->ColumnArray[strtolower($objReverseReference->Column)]; ?>
				case '<?php echo $objReverseReference->ObjectPropertyName  ?>':
					/**
					 * Gets the value for the <?php echo $objReverseReference->VariableType  ?> object that uniquely references this <?php echo $objTable->ClassName  ?>

					 * by <?php echo $objReverseReference->ObjectMemberVariable  ?> (Unique)
					 * @return <?php echo $objReverseReference->VariableType  ?>

					 */
					try {
						return <?php echo $objReverseReference->VariableType  ?>::LoadBy<?php echo $objReverseReferenceColumn->PropertyName  ?>(<?php echo $objCodeGen->ImplodeObjectArray(', ', '$this->', '', 'VariableName', $objTable->PrimaryKeyColumnArray)  ?>);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

<?php } ?>
<?php } ?>

				////////////////////////////
				// Virtual Object References (Many to Many and Reverse References)
				// (If restored via a "Many-to" expansion)
				////////////////////////////

<?php foreach ($objTable->ManyToManyReferenceArray as $objReference) { ?>
<?php 
	$objAssociatedTable = $objCodeGen->GetTable($objReference->AssociatedTable);
	$varPrefix = (is_a($objAssociatedTable, 'QTypeTable') ? '_int' : '_obj');
	$varType = (is_a($objAssociatedTable, 'QTypeTable') ? 'integer' : $objReference->VariableType);
?>
				case '_<?php echo $objReference->ObjectDescription ?>':
					/**
					 * Gets the value for the private <?php echo $varPrefix . $objReference->ObjectDescription ?> (Read-Only)
					 * if set due to an expansion on the <?php echo $objReference->Table ?> association table
					 * @return <?php echo $varType  ?>

					 */
					return $this-><?php echo $varPrefix . $objReference->ObjectDescription ?>;

				case '_<?php echo $objReference->ObjectDescription ?>Array':
					/**
					 * Gets the value for the private <?php echo $varPrefix . $objReference->ObjectDescription ?>Array (Read-Only)
					 * if set due to an ExpandAsArray on the <?php echo $objReference->Table ?> association table
					 * @return <?php echo $varType ?>[]
					 */
					return $this-><?php echo $varPrefix . $objReference->ObjectDescription ?>Array;


<?php } ?><?php foreach ($objTable->ReverseReferenceArray as $objReference) { ?><?php if (!$objReference->Unique) { ?>
				case '_<?php echo $objReference->ObjectDescription ?>':
					/**
					 * Gets the value for the private _obj<?php echo $objReference->ObjectDescription ?> (Read-Only)
					 * if set due to an expansion on the <?php echo $objReference->Table ?>.<?php echo $objReference->Column ?> reverse relationship
					 * @return <?php echo $objReference->VariableType  ?>

					 */
					return $this->_obj<?php echo $objReference->ObjectDescription ?>;

				case '_<?php echo $objReference->ObjectDescription ?>Array':
					/**
					 * Gets the value for the private _obj<?php echo $objReference->ObjectDescription ?>Array (Read-Only)
					 * if set due to an ExpandAsArray on the <?php echo $objReference->Table ?>.<?php echo $objReference->Column ?> reverse relationship
					 * @return <?php echo $objReference->VariableType  ?>[]
					 */
					return $this->_obj<?php echo $objReference->ObjectDescription ?>Array;

<?php } ?><?php } ?>

				case '__Restored':
					return $this->__blnRestored;

				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}