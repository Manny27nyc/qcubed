<?php

	include_once __EQUEUE_UI_ROOT__ . "/protected.inc.php";

	if ( !isset($blnSkipHeaderHtml) || (true != $blnSkipHeaderHtml) ) {
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="<?php _p(QApplication::$EncodingType); ?>" />
<?php if (isset($strPageTitle)) { ?>
		<title><?php _p($strPageTitle); ?></title>
<?php } ?>
		<link href="<?php
			_p(__VIRTUAL_DIRECTORY__ . __SUBDIRECTORY__ . '/media/theme/'
				. (Office::GetCurrentOffice() ?
						Office::GetCurrentOffice()->getSetting(SettingsLookup::visual_theme) :
						SettingsLookup::ToDefaultValue(SettingsLookup::visual_theme)
				)
			);?>/common/style/styles.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" >
			var EqueueDebugMode=<?php if (defined('__EQUEUE_DEBUG_MODE__')) { if ( __EQUEUE_DEBUG_MODE__ === true ) {print"true";} else { print"false";} } else {print"false";} ?>;
		</script>
		<script type="text/javascript"> var footer_use = 0; </script>
	</head>
	<body>
		<section id="content">
					<?php
						$objEmployee = Employee::GetCurrentEmployee();
						if ( $objEmployee && (4 /* Администратор головного офиса */ == $objEmployee->RoleId) ) { 
					?>
					<div style="font-size: 14px; font-weight: bold; line-height: 24px;"><a href="<?php _p(__EQUEUE_UI__); ?>/mo_admin.php" style="color:white;">Перейти к панели администрирования офисов »</a></div>
					<?php } ?>
<?php } ?>