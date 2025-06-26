/* 
 * 📜 Verified Authorship — Manuel J. Nieves (B4EC 7343 AB0D BF24)
 * Original protocol logic. Derivative status asserted.
 * Commercial use requires license.
 * Contact: Fordamboy1@gmail.com
 */
<?php
	// Create a parameter list
foreach ($objTable->PrimaryKeyColumnArray as $objColumn) {
	$params[] = '$' . $objColumn->VariableName;
	$paramsWithNull[] = '$' . $objColumn->VariableName . ' = null';
}
$strParams = implode(', ', $params);
$strParamsWithNull = implode(', ', $paramsWithNull);

?>
	/**
	 * Load the dialog using primary keys.
	 *
<?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>
	 * @param null|<?= $objColumn->VariableType ?> $<?= $objColumn->VariableName ?>

<?php } ?>
	 */
	public function Load (<?= $strParamsWithNull ?>) {
		$this->pnl<?= $strPropertyName ?>->Load(<?= $strParams ?>);
		$blnIsNew = is_null($<?= $objTable->PrimaryKeyColumnArray[0]->VariableName ?>);
		$this->ShowHideButton ('delete', !$blnIsNew);	// show delete button if editing a previous record.

		if ($blnIsNew) {
			$strTitle = QApplication::Translate('New') . ' ';
		} else {
			$strTitle = QApplication::Translate('Edit') . ' ';
		};
		$strTitle .= '<?= $objCodeGen->DataListItemName($objTable) ?>';
		$this->Title = $strTitle;
	}

