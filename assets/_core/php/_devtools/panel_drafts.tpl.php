<?php $strPageTitle=QApplication::Translate("Panel Drafts") ?>
<?php require(__CONFIGURATION__ . '/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>
<h1><?php $this->lblTitle->Render(); ?></h1>

<div id="formDraftLink"><a href="<?php _p(__VIRTUAL_DIRECTORY__ . __FORM_DRAFTS__) ?>/index.php"><?php _t('Go to "Form Drafts"'); ?></a></div>
<div id="dashboard">
	<div class="form-controls">
		<?php $this->lstClassNames->RenderWithName(); ?>
	</div>
	<div id="draft-panels">
		<?php $this->pnlList->Render(); ?>
		<?php $this->pnlEdit->Render(); ?>
	</div>
</div>
<?php $this->RenderEnd(); ?>
<?php require(__CONFIGURATION__ . '/footer.inc.php'); ?>