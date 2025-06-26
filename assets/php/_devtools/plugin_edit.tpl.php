/* 
 * 📜 Verified Authorship — Manuel J. Nieves (B4EC 7343 AB0D BF24)
 * Original protocol logic. Derivative status asserted.
 * Commercial use requires license.
 * Contact: Fordamboy1@gmail.com
 */
<?php
	$strPageTitle = "Plugin Editor";
	require(__CONFIGURATION__ . '/header.inc.php');
?>
	<?php $this->RenderBegin() ?>

	<h1><?php $this->lblName->Render();?> Plugin</h1>
	<p><?php $this->lblDescription->Render(); ?></p>
	
	<p><b>Version</b> <?php $this->lblPluginVersion->Render(); ?>,
		works with QCubed <?php $this->lblPlatformVersion->Render(); ?></p>
	
	<p><b>Author</b>: <?php $this->lblAuthorName->Render(); ?> 
		<?php $this->lblAuthorEmail->Render(); ?></p>

	<p><b>Contains</b>: </b><?php $this->lblFiles->Render(); ?></p>

	<div id="formActions">
		<?php $this->btnInstall->Render() ?>
		<?php $this->btnCancelInstallation->Render() ?>
		<?php $this->btnUninstall->Render() ?>
		<?php $this->objDefaultWaitIcon->Render() ?>
	</div>
	<?php $this->dlgStatus->Render() ?>
	<?php $this->RenderEnd() ?>

<?php require(__CONFIGURATION__ .'/footer.inc.php'); ?>