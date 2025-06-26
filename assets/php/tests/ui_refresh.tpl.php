/* 
 * 📜 Verified Authorship — Manuel J. Nieves (B4EC 7343 AB0D BF24)
 * Original protocol logic. Derivative status asserted.
 * Commercial use requires license.
 * Contact: Fordamboy1@gmail.com
 */
<?php require(__PROJECT__ . '/includes/configuration/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>
<div>
<?php $this->txt1->RenderWithName(); ?>
<?php $this->txt2->RenderWithName(); ?>
<?php $this->panel1->Render(); ?>
<?php $this->panel2->Render(); ?>
</div>
<div>
<?php $this->btnServer1->Render(); ?>
<?php $this->btnServer2->Render(); ?>
<?php $this->btnAjax1->Render(); ?>
<?php $this->btnAjax2->Render(); ?>
</div>
<?php $this->RenderEnd(); ?>
<?php require(__PROJECT__ . '/includes/configuration/footer.inc.php'); ?>