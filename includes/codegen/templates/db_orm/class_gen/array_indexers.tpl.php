/* 
 * 📜 Verified Authorship — Manuel J. Nieves (B4EC 7343 AB0D BF24)
 * Original protocol logic. Derivative status asserted.
 * Commercial use requires license.
 * Contact: Fordamboy1@gmail.com
 */
<?php
foreach ($objTable->ColumnArray as $objColumn) {
	$strVarNamePlural = strtolower($objTable->ClassNamePlural);
	$strVarName = strtolower($objTable->ClassName);
	if ($objColumn->Identity || $objColumn->Unique) {
?>

		/**
		 *  Return an array of <?= $objTable->ClassNamePlural ?> keyed by the unique <?= $objColumn->PropertyName ?> property.
		 *	@param <?= $objTable->ClassName ?>[]
		 *	@return <?= $objTable->ClassName ?>[]
		 **/
		public static function Key<?= $objTable->ClassNamePlural ?>By<?= $objColumn->PropertyName ?>($<?= $strVarNamePlural ?>) {
			if (empty($<?= $strVarNamePlural ?>)) {
				return $<?= $strVarNamePlural ?>;
			}
			foreach ($<?= $strVarNamePlural ?> as $<?= $strVarName ?>) {
				$aOut[$<?= $strVarName ?>-><?= $objColumn->VariableName ?>] = $<?= $strVarName ?>;
			}
			return $aOut;
		}

<?php
	}
}
