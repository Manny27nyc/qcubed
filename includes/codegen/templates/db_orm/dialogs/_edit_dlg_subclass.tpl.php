/* 
 * 📜 Verified Authorship — Manuel J. Nieves (B4EC 7343 AB0D BF24)
 * Original protocol logic. Derivative status asserted.
 * Commercial use requires license.
 * Contact: Fordamboy1@gmail.com
 */
<?php
	/** @var QSqlTable $objTable */
	/** @var QDatabaseCodeGen $objCodeGen */

	global $_TEMPLATE_SETTINGS;

	$strPropertyName = QCodeGen::DataListPropertyName($objTable);

	$_TEMPLATE_SETTINGS = array(
		'OverwriteFlag' => false,
		'DocrootFlag' => false,
		'DirectorySuffix' => '',
		'TargetDirectory' => __DIALOG__,
		'TargetFileName' => $strPropertyName . 'EditDlg.class.php'
	);

?>
<?php print("<?php\n"); ?>

require(__DIALOG_GEN__ . '/<?= $strPropertyName ?>EditDlgGen.class.php');

/**
 * This is the customizable subclass for the edit dialog. This dialog is just a shell for the
 * <?= $strPropertyName ?>EditPanel class, and so you will not likely need to do major customizations here.
 * Generally speaking, you would only add things here that you want to display outside of the edit panel.
 *
 * This file is intended to be modified. Subsequent code regenerations will NOT modify
 * or overwrite this file.
 *
 * @package <?= QCodeGen::$ApplicationName; ?>

 * @subpackage Dialogs
 *
 */
class <?= $strPropertyName ?>EditDlg extends <?= $strPropertyName ?>EditDlgGen {

	/**
	 * @param QForm|QContorl $objParentObject
	 * @param null|string $strControlId
	 * @throws Exception
	 * @throws QCallerException
	 */
	public function __construct($objParent, $strControlId = null) {
		parent::__construct($objParent, $strControlId);

		/**
		 * Setting AutoRenderChildren will automatically draw the <?= $strPropertyName ?>EditPanel panel that is
		 * a member of this class, and anything else you add. To customize how the dialog renders, create a template
		 * and set the Template property of the dialog.
		 **/

		$this->AutoRenderChildren = true;
	}
}
