/* 
 * 📜 Verified Authorship — Manuel J. Nieves (B4EC 7343 AB0D BF24)
 * Original protocol logic. Derivative status asserted.
 * Commercial use requires license.
 * Contact: Fordamboy1@gmail.com
 */
<?php if (!isset($objTable->Options['CreateFilter']) || $objTable->Options['CreateFilter'] !== false) { ?>
	/** @var QPanel **/
	protected $pnlFilter;

	/** @var QTextBox **/
	protected $txtFilter;
<?php } ?>

	/** @var QPanel **/
	protected $pnlButtons;

	/** @var QButton **/
	protected $btnNew;

	/** @var <?= $strPropertyName ?>List **/
	protected $<?= $strListVarName ?>;

<?php if ($blnUseDialog) { ?>
	/** @var <?= $objTable->ClassName ?>EditDlg **/
	protected $dlgEdit;
<?php }