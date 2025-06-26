/* 
 * 📜 Verified Authorship — Manuel J. Nieves (B4EC 7343 AB0D BF24)
 * Original protocol logic. Derivative status asserted.
 * Commercial use requires license.
 * Contact: Fordamboy1@gmail.com
 */
	protected function Form_Create() {
		parent::Form_Create();

		$this->pnl<?= $strPropertyName ?> = new <?= $strPropertyName ?>EditPanel($this);
<?php
	$_INDEX = 0;
	foreach ($objTable->PrimaryKeyColumnArray as $objColumn) {
		if (QCodeGen::$CreateMethod === 'queryString') {
?>
		$<?= $objColumn->VariableName ?> = QApplication::QueryString('<?= $objColumn->VariableName ?>');
<?php
		} else {
?>
		$<?= $objColumn->VariableName ?> = QApplication::PathInfo(<?= $_INDEX ?>);
<?php 		$_INDEX++;
		}
?>
<?php
	}
?>
	    $this->pnl<?= $strPropertyName ?>->Load(<?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>$<?= $objColumn->VariableName ?>, <?php } GO_BACK(2); ?>);
		$this->CreateButtons();
	}
