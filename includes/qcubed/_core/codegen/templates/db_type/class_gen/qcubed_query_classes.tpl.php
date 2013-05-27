
	/////////////////////////////////////
	// ADDITIONAL CLASSES for QCubed QUERY
	/////////////////////////////////////

<?php foreach ($objTypeTable->ManyToManyReferenceArray as $objReference) { ?>
	/**
	 * @uses QQAssociationNode
	 *
	 * @property-read QQNode $<?php echo $objReference->OppositePropertyName ?>

	 * @property-read QQNode<?php echo $objReference->VariableType ?> $<?php echo $objReference->VariableType ?>

	 * @property-read QQNode<?php echo $objReference->VariableType ?> $_ChildTableNode
	 **/
	class QQNode<?php echo $objTypeTable->ClassName ?><?php echo $objReference->ObjectDescription ?> extends QQAssociationNode {
		protected $strType = 'association';
		protected $strName = '<?php echo strtolower($objReference->ObjectDescription); ?>';

		protected $strTableName = '<?php echo $objReference->Table ?>';
		protected $strPrimaryKey = '<?php echo $objReference->Column ?>';
		protected $strClassName = '<?php echo $objReference->VariableType ?>';

		public function __get($strName) {
			switch ($strName) {
				case '<?php echo $objReference->OppositePropertyName ?>':
					return new QQNode('<?php echo $objReference->OppositeColumn ?>', '<?php echo $objReference->OppositePropertyName ?>', '<?php echo $objReference->OppositeVariableType ?>', $this);
				case '<?php echo $objReference->VariableType ?>':
					return new QQNode<?php echo $objReference->VariableType ?>('<?php echo $objReference->OppositeColumn ?>', '<?php echo $objReference->OppositePropertyName ?>', '<?php echo $objReference->OppositeVariableType ?>', $this);
				case '_ChildTableNode':
					return new QQNode<?php echo $objReference->VariableType ?>('<?php echo $objReference->OppositeColumn ?>', '<?php echo $objReference->OppositePropertyName ?>', '<?php echo $objReference->OppositeVariableType ?>', $this);
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
	}

<?php } ?>

	/**
	 * @uses QQNode
	 *
<?php foreach ($objTypeTable->ColumnArray as $objColumn) { ?>
	 * @property-read QQNode $<?php echo $objColumn->PropertyName ?>

<?php if ($objColumn->Reference) { ?>
	 * @property-read QQNode<?php echo $objColumn->Reference->VariableType; ?> $<?php echo $objColumn->Reference->PropertyName ?>

<?php } ?>
<?php } ?>
	 *
<?php foreach ($objTypeTable->ManyToManyReferenceArray as $objReference) { ?>
	 * @property-read QQNode<?php echo $objTypeTable->ClassName ?><?php echo $objReference->ObjectDescription ?> $<?php echo $objReference->ObjectDescription ?>

<?php } ?>
	 *
<?php foreach ($objTypeTable->ReverseReferenceArray as $objReference) { ?>
	 * @property-read QQReverseReferenceNode<?php echo $objReference->VariableType ?> $<?php echo $objReference->ObjectDescription ?>

<?php } ?>
<?php $objPkColumn = $objTypeTable->PrimaryKeyColumnArray[0]; ?>
	 * @property-read QQNode<?php if ($objPkColumn->Reference) return $objPkColumn->Reference->VariableType; ?> $_PrimaryKeyNode
	 **/
	class QQNode<?php echo $objTypeTable->ClassName ?> extends QQNode {
		protected $strTableName = '<?php echo $objTypeTable->Name ?>';
		protected $strPrimaryKey = '<?php echo $objTypeTable->PrimaryKeyColumnArray[0]->Name ?>';
		protected $strClassName = '<?php echo $objTypeTable->ClassName ?>';
		public function __get($strName) {
			switch ($strName) {
<?php foreach ($objTypeTable->ColumnArray as $objColumn) { ?>

				case '<?php echo $objColumn->PropertyName ?>':
					return new QQNode('<?php echo $objColumn->Name ?>', '<?php echo $objColumn->PropertyName ?>', '<?php echo $objColumn->DbType ?>', $this);
	<?php if (($objColumn->Reference)) { ?>

				case '<?php echo $objColumn->Reference->PropertyName ?>':
					return new QQNode<?php echo $objColumn->Reference->VariableType; ?>('<?php echo $objColumn->Name ?>', '<?php echo $objColumn->Reference->PropertyName ?>', '<?php echo $objColumn->DbType ?>', $this);
	<?php } ?>
<?php } ?>
<?php foreach ($objTypeTable->ManyToManyReferenceArray as $objReference) { ?>

				case '<?php echo $objReference->ObjectDescription ?>':
					return new QQNode<?php echo $objTypeTable->ClassName ?><?php echo $objReference->ObjectDescription ?>($this);
<?php } ?><?php foreach ($objTypeTable->ReverseReferenceArray as $objReference) { ?>

				case '<?php echo $objReference->ObjectDescription ?>':
					return new QQReverseReferenceNode<?php echo $objReference->VariableType ?>($this, '<?php echo strtolower($objReference->ObjectDescription); ?>', 'reverse_reference', '<?php echo $objReference->Column ?>'<?php echo ($objReference->Unique) ? ", '" . $objReference->ObjectDescription . "'" : null; ?>);
<?php } ?><?php $objPkColumn = $objTypeTable->PrimaryKeyColumnArray[0]; ?>

				case '_PrimaryKeyNode':
					return new QQNode<?php if ($objPkColumn->Reference) return $objPkColumn->Reference->VariableType; ?>('<?php echo $objPkColumn->Name ?>', '<?php echo $objPkColumn->PropertyName ?>', '<?php echo $objPkColumn->DbType ?>', $this);
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
	}

	/**
<?php foreach ($objTypeTable->ColumnArray as $objColumn) { ?>
	 * @property-read QQNode $<?php echo $objColumn->PropertyName ?>

<?php if ($objColumn->Reference) { ?>
	 * @property-read QQNode<?php echo $objColumn->Reference->VariableType; ?> $<?php echo $objColumn->Reference->PropertyName ?>

<?php } ?>
<?php } ?>
	 *
<?php foreach ($objTypeTable->ManyToManyReferenceArray as $objReference) { ?>
	 * @property-read QQNode<?php echo $objTypeTable->ClassName ?><?php echo $objReference->ObjectDescription ?> $<?php echo $objReference->ObjectDescription ?>

<?php } ?>
	 *
<?php foreach ($objTypeTable->ReverseReferenceArray as $objReference) { ?>
	 * @property-read QQReverseReferenceNode<?php echo $objReference->VariableType ?> $<?php echo $objReference->ObjectDescription ?>

<?php } ?>
<?php $objPkColumn = $objTypeTable->PrimaryKeyColumnArray[0]; ?>
	 * @property-read QQNode<?php if ($objPkColumn->Reference) return $objPkColumn->Reference->VariableType; ?> $_PrimaryKeyNode
	 **/
	class QQReverseReferenceNode<?php echo $objTypeTable->ClassName ?> extends QQReverseReferenceNode {
		protected $strTableName = '<?php echo $objTypeTable->Name ?>';
		protected $strPrimaryKey = '<?php echo $objTypeTable->PrimaryKeyColumnArray[0]->Name ?>';
		protected $strClassName = '<?php echo $objTypeTable->ClassName ?>';
		public function __get($strName) {
			switch ($strName) {
<?php foreach ($objTypeTable->ColumnArray as $objColumn) { ?>

				case '<?php echo $objColumn->PropertyName ?>':
					return new QQNode('<?php echo $objColumn->Name ?>', '<?php echo $objColumn->PropertyName ?>', '<?php echo $objColumn->VariableType ?>', $this);
	<?php if ($objColumn->Reference) { ?>

				case '<?php echo $objColumn->Reference->PropertyName ?>':
					return new QQNode<?php echo $objColumn->Reference->VariableType; ?>('<?php echo $objColumn->Name ?>', '<?php echo $objColumn->Reference->PropertyName ?>', '<?php echo $objColumn->VariableType ?>', $this);
	<?php } ?>
<?php } ?>
<?php foreach ($objTypeTable->ManyToManyReferenceArray as $objReference) { ?>

				case '<?php echo $objReference->ObjectDescription ?>':
					return new QQNode<?php echo $objTypeTable->ClassName ?><?php echo $objReference->ObjectDescription ?>($this);
<?php } ?><?php foreach ($objTypeTable->ReverseReferenceArray as $objReference) { ?>

				case '<?php echo $objReference->ObjectDescription ?>':
					return new QQReverseReferenceNode<?php echo $objReference->VariableType ?>($this, '<?php echo strtolower($objReference->ObjectDescription); ?>', 'reverse_reference', '<?php echo $objReference->Column ?>'<?php echo ($objReference->Unique) ? ", '" . $objReference->ObjectDescription . "'" : null; ?>);
<?php } ?><?php $objPkColumn = $objTypeTable->PrimaryKeyColumnArray[0]; ?>

				case '_PrimaryKeyNode':
					return new QQNode<?php if ($objPkColumn->Reference) return $objPkColumn->Reference->VariableType; ?>('<?php echo $objPkColumn->Name ?>', '<?php echo $objPkColumn->PropertyName ?>', '<?php echo $objPkColumn->VariableType ?>', $this);
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
	}