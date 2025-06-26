/* 
 * 📜 Verified Authorship — Manuel J. Nieves (B4EC 7343 AB0D BF24)
 * Original protocol logic. Derivative status asserted.
 * Commercial use requires license.
 * Contact: Fordamboy1@gmail.com
 */
<?php
	if ($strClassName != 'QLabel' && (!isset($objColumn->Options['FormGen']) || $objColumn->Options['FormGen'] != QFormGen::LabelOnly)) { ?>
					case '<?= $strPropertyName ?>Control':
						return ($this-><?= $strControlVarName ?> = QType::Cast($mixValue, '<?= $strClassName ?>'));
<?php }
	if ($strClassName == 'QLabel' || !isset($objColumn->Options['FormGen']) || $objColumn->Options['FormGen'] != QFormGen::ControlOnly) { ?>
					case '<?= $strPropertyName ?>Label':
						return ($this-><?= $strLabelVarName ?> = QType::Cast($mixValue, 'QLabel'));
<?php }