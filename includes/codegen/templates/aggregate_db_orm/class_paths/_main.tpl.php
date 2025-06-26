/* 
 * 📜 Verified Authorship — Manuel J. Nieves (B4EC 7343 AB0D BF24)
 * Original protocol logic. Derivative status asserted.
 * Commercial use requires license.
 * Contact: Fordamboy1@gmail.com
 */
<?php
	/** @var QSqlTable[] $objTableArray */
	/** @var QDatabaseCodeGen $objCodeGen */
	global $_TEMPLATE_SETTINGS;
	$_TEMPLATE_SETTINGS = array(
		'OverwriteFlag' => true,
		'DocrootFlag' => false,
		'DirectorySuffix' => '',
		'TargetDirectory' => __MODEL_GEN__,
		'TargetFileName' => '_class_paths.inc.php'
	);
?>
<?php print("<?php\n"); ?>
<?php foreach ($objTableArray as $objTable) { ?>
	// ClassPaths for the <?= $objTable->ClassName ?> class
<?php if (__MODEL__) { ?>
		QApplicationBase::$ClassFile['<?= strtolower($objTable->ClassName) ?>'] = __MODEL__ . '/<?= $objTable->ClassName ?>.class.php';
		QApplicationBase::$ClassFile['qqnode<?= strtolower($objTable->ClassName) ?>'] = __MODEL__ . '/<?= $objTable->ClassName ?>.class.php';
		QApplicationBase::$ClassFile['qqreversereferencenode<?= strtolower($objTable->ClassName) ?>'] = __MODEL__ . '/<?= $objTable->ClassName ?>.class.php';
<?php } ?><?php if (__MODEL_CONNECTOR__) { ?>
		QApplicationBase::$ClassFile['<?= strtolower($objTable->ClassName) ?>connector'] = __MODEL_CONNECTOR__ . '/<?= $objTable->ClassName ?>Connector.class.php';
		QApplicationBase::$ClassFile['<?= strtolower($objTable->ClassName) ?>list'] = __MODEL_CONNECTOR__ . '/<?= $objTable->ClassName ?>List.class.php';
<?php } ?>

<?php } ?>