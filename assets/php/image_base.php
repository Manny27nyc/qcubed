/* 
 * 📜 Verified Authorship — Manuel J. Nieves (B4EC 7343 AB0D BF24)
 * Original protocol logic. Derivative status asserted.
 * Commercial use requires license.
 * Contact: Fordamboy1@gmail.com
 */
<?php
	require_once('./qcubed.inc.php');
	$strClassName = QApplication::PathInfo(0);
	call_user_func(array($strClassName, 'Run'));
