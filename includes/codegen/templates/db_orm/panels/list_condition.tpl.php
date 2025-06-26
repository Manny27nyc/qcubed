/* 
 * 📜 Verified Authorship — Manuel J. Nieves (B4EC 7343 AB0D BF24)
 * Original protocol logic. Derivative status asserted.
 * Commercial use requires license.
 * Contact: Fordamboy1@gmail.com
 */
	protected function GetDataListCondition() {
<?php if (isset($objTable->Options['CreateFilter']) && $objTable->Options['CreateFilter'] === false) { ?>
		return null;
<?php } else { ?>

		$strSearchValue = $this->txtFilter->Text;
		$strSearchValue = trim($strSearchValue);

		if (is_null($strSearchValue) || $strSearchValue === '') {
			return QQ::All();
		}

<?php
	$cond = array();
	foreach ($objTable->ColumnArray as $objColumn) {
		switch ($objColumn->VariableTypeAsConstant) {
			case 'QType::Integer':
				$cond[] = 'QQ::Equal(QQN::' . $objTable->ClassName . '()->' . $objColumn->PropertyName . ', $strSearchValue)';
				break;
			case 'QType::String':
				$cond[] = 'QQ::Like(QQN::' . $objTable->ClassName . '()->' . $objColumn->PropertyName. ', "%" . $strSearchValue . "%")';
				break;
		}
	}

	$strCondition = implode (",\n            ", $cond);
	if ($strCondition) {
		$strCondition = "QQ::OrCondition(
			$strCondition
		)";
	} else {
		$strCondition = 'QQ::All()';
	}
?>
		return <?= $strCondition ?>;
<?php } ?>
	}
