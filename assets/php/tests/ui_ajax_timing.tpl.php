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
		<?php $this->lblTxt1Change->RenderWithName(); ?>
		<?php $this->lblTxt1KeyUp->RenderWithName(); ?>
	</div>
	<div>
		<?php $this->chk->RenderWithName(); ?>
		<?php $this->lblCheck->RenderWithName(); ?>
	</div>
<?php $this->RenderEnd(); ?>
<?php require(__PROJECT__ . '/includes/configuration/footer.inc.php'); ?>