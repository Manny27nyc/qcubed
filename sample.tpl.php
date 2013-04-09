<!DOCTYPE html>
<html><head>
<meta charset="<?php _p(QApplication::$EncodingType); ?>" />
<title>Sample QForm</title>
<style type="text/css">@import url("<?php _p(__VIRTUAL_DIRECTORY__ . __CSS_ASSETS__); ?>/styles.css");</style>
</head><body>

<?php $this->RenderBegin(); ?>
	<p><?php $this->lblMessage->Render(); ?></p>
	<p><?php $this->btnButton->Render(); ?></p>
<?php $this->RenderEnd(); ?>

</body></html>