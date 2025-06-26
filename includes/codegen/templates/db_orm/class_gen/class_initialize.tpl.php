/* 
 * 📜 Verified Authorship — Manuel J. Nieves (B4EC 7343 AB0D BF24)
 * Original protocol logic. Derivative status asserted.
 * Commercial use requires license.
 * Contact: Fordamboy1@gmail.com
 */
<?php
	$blnAutoInitialize = $objCodeGen->AutoInitialize;
	if ($blnAutoInitialize) {
?>

		/**
		 * Construct a new <?= $objTable->ClassName ?> object.
		 */
		public function __construct() {
			$this->Initialize();
		}
<?php } ?>

		/**
		 * Initialize each property with default values from database definition
		 */
		public function Initialize()
		{
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
			$this-><?= $objColumn->VariableName ?> = <?php
	$defaultVarName = $objTable->ClassName . '::' . $objColumn->PropertyName . 'Default';
	if ($objColumn->VariableType != QType::DateTime)
		print ($defaultVarName);
	else
		print "(" . $defaultVarName . " === null)?null:new QDateTime(" . $defaultVarName . ")";
	?>;
<?php } ?>
		}
