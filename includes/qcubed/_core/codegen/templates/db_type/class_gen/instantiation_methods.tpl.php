
		///////////////////////////////
		// INSTANTIATION-RELATED METHODS
		///////////////////////////////

		/**
		 * Instantiate a <?php echo $objTypeTable->ClassName ?> value-name pair from a Database Row.
		 * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
		 * is calling this <?php echo $objTypeTable->ClassName ?>::InstantiateDbRow in order to perform
		 * early binding on referenced objects.
		 * @param DatabaseRowBase $objDbRow
		 * @param string $strAliasPrefix
		 * @param string $strExpandAsArrayNodes
		 * @param QBaseClass $arrPreviousItem
		 * @param string[] $strColumnAliasArray
		 * @return <?php echo $objTypeTable->ClassName ?>

		*/
		public static function InstantiateDbRow($objDbRow, $strAliasPrefix = null, $strExpandAsArrayNodes = null, $arrPreviousItems = null, $strColumnAliasArray = array()) {
			// If blank row, return null
			if (!$objDbRow) {
				return null;
			}
<?php 
	$aryColumns = $objTypeTable->ColumnArray;
	$objFirstColumn = reset($aryColumns);
	$objSecondColumn = next($aryColumns);
 ?>
			$strPk = array_key_exists($strAliasPrefix . '<?php echo $objFirstColumn->Name ?>', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . '<?php echo $objFirstColumn->Name ?>'] : $strAliasPrefix . '<?php echo $objFirstColumn->Name ?>';
			$strValue = array_key_exists($strAliasPrefix . '<?php echo $objSecondColumn->Name ?>', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . '<?php echo $objSecondColumn->Name ?>'] : $strAliasPrefix . '<?php echo $objSecondColumn->Name ?>';
			return array(
				$objDbRow->GetColumn($strPk, '<?php echo $objFirstColumn->DbType ?>') =>
				$objDbRow->GetColumn($strValue, '<?php echo $objSecondColumn->DbType ?>'));
		}

		/**
		 * Instantiate an array of <?php echo $objTypeTable->ClassName ?> value-pair from a Database Result
		 * @param DatabaseResultBase $objDbResult
		 * @param string $strExpandAsArrayNodes
		 * @param string[] $strColumnAliasArray
		 * @return <?php echo $objTypeTable->ClassName ?>[]
		 */
		public static function InstantiateDbResult(QDatabaseResultBase $objDbResult, $strExpandAsArrayNodes = null, $strColumnAliasArray = null) {
			$objToReturn = array();
			
			if (!$strColumnAliasArray)
				$strColumnAliasArray = array();

			// If blank resultset, then return empty array
			if (!$objDbResult)
				return $objToReturn;

			// Load up the return array with each row
			$objToReturn = array();
			while ($objDbRow = $objDbResult->GetNextRow()) {
				$objItem = <?php echo $objTypeTable->ClassName ?>::InstantiateDbRow($objDbRow, null, $strExpandAsArrayNodes, $objToReturn, $strColumnAliasArray);
				if ($objItem) {
					$objToReturn = $objToReturn + $objItem;
				}
			}
			return $objToReturn;
		}
