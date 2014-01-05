	if ( !isset($blnSkipHeaderHtml) || (true != $blnSkipHeaderHtml) ) {
<!DOCTYPE html>
<html>
	<head>
		<meta charset="<?php _p(QApplication::$EncodingType); ?>" />
<?php if (isset($strPageTitle)) { ?>
		<title><?php _p($strPageTitle); ?></title>
<?php } ?>
		<link rel="stylesheet" type="text/css" href="<?php _p(__VIRTUAL_DIRECTORY__ . __CSS_ASSETS__ . '/styles.css'); ?>"></link>
<?php if (class_exists("Office")) { ?>
		<link href="<?php
			_p(__VIRTUAL_DIRECTORY__ . __SUBDIRECTORY__ . '/media/theme/'
				. (Office::GetCurrentOffice() ?
						Office::GetCurrentOffice()->getSetting(SettingsLookup::visual_theme) :
						SettingsLookup::ToDefaultValue(SettingsLookup::visual_theme)
				)
			);?>/common/style/styles.css" rel="stylesheet" type="text/css" />
		<link href="<?php
			_p(__VIRTUAL_DIRECTORY__ . __SUBDIRECTORY__ . '/media/theme/'
				. (Office::GetCurrentOffice() ?
						Office::GetCurrentOffice()->getSetting(SettingsLookup::visual_theme) :
						SettingsLookup::ToDefaultValue(SettingsLookup::visual_theme)
				)
			);?>/common/style/style.css" rel="stylesheet" type="text/css" />
<?php } ?>
		<script type="text/javascript" >
			var EqueueDebugMode=<?php if (defined('__EQUEUE_DEBUG_MODE__')) { if ( __EQUEUE_DEBUG_MODE__ === true ) {print"true";} else { print"false";} } else {print"false";} ?>;
		</script>
		<script type="text/javascript"> var footer_use = 0; </script>
	</head>
	<body>
		<section id="content">
<?php
	if (class_exists("Employee") && (!isset($blnSkipMoAdminLink) || false == $blnSkipMoAdminLink)) {
		$objEmployee = Employee::GetCurrentEmployee();
		if ( Office::GetCurrentOffice() && $objEmployee && (4 /* Администратор головного офиса */ == $objEmployee->RoleId) ) { 
?>
			<div style="position:absolute; z-index: 10; top: 0px; left: 0; margin-left: 10px; margin-top:10px;">
				<span><?php _p(sprintf(QApp::Tr("Current office: %s"), Office::GetCurrentOffice()->__toString())) ?></span>
			</div>
			<div style="position:absolute; z-index: 10; top: 0px; left: 100%; margin-left: -260px; margin-top:10px;">
				<a style="width:250px;" href="<?php _p(__EQUEUE_UI__); ?>/mo_admin.php"><?php _t("Go to MO administration page »") ?></a>
			</div>
			<div style="margin-bottom: 20px;"></div>
<?php
		}
	}
?>
<?php } ?>
