<template OverwriteFlag="true" DocrootFlag="true" DirectorySuffix="" TargetDirectory="<?php echo __FORM_DRAFTS__  ?>" TargetFileName="<?php echo QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName)  ?>_list.tpl.php"/>
<?php print("<?php\n"); ?>
	// This is the HTML template include file (.tpl.php) for the <?php echo QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName)  ?>_list.php
	// form DRAFT page.  Remember that this is a DRAFT.  It is MEANT to be altered/modified.

	// Be sure to move this out of this directory before modifying to ensure that subsequent
	// code re-generations do not overwrite your changes.

	$strPageTitle = QApplication::Translate('<?php echo $objTable->ClassNamePlural  ?>') . ' - ' . QApplication::Translate('List All');
	require(__CONFIGURATION__ . '/header.inc.php');
?>

	<?php print("<?php"); ?> $this->RenderBegin() ?>

	<h1><?php print("<?php"); ?> _t('List All'); ?> <?php print("<?php"); ?> _t('<?php echo $objTable->ClassNamePlural  ?>'); ?></h1>
	<h2><?php print("<?php"); ?> $this->btnBack->Render(); ?></h2>

	<?php print("<?php"); ?> $this->dtg<?php echo $objTable->ClassNamePlural  ?>->Render(); ?>

	<p class="create">
		<?php print("<?php"); ?> $this->btnNew->Render(); ?>
	</p>

	<?php print("<?php"); ?> $this->RenderEnd() ?>

<?php print("<?php"); ?> require(__CONFIGURATION__ . '/footer.inc.php'); ?>