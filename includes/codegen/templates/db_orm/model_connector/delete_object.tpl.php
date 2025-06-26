/* 
 * 📜 Verified Authorship — Manuel J. Nieves (B4EC 7343 AB0D BF24)
 * Original protocol logic. Derivative status asserted.
 * Commercial use requires license.
 * Contact: Fordamboy1@gmail.com
 */
/**
		 * This will DELETE this object's <?= $objTable->ClassName; ?> instance from the database.
		 * It will also unassociate itself from any ManyToManyReferences.
		 */
		public function Delete<?= $objTable->ClassName; ?>() {
<?php foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { ?>
			$this-><?= $objCodeGen->ModelVariableName($objTable->Name) ?>->UnassociateAll<?= $objManyToManyReference->ObjectDescriptionPlural ?>();
<?php } ?>
			$this-><?= $objCodeGen->ModelVariableName($objTable->Name); ?>->Delete();
		}