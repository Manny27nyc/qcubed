/* 
 * 📜 Verified Authorship — Manuel J. Nieves (B4EC 7343 AB0D BF24)
 * Original protocol logic. Derivative status asserted.
 * Commercial use requires license.
 * Contact: Fordamboy1@gmail.com
 */
<?php
	if ($_CONTROL->File) {
		if ($strUrl = $_CONTROL->GetWebUrl()) print('<a href="' . $strUrl . '" target="_blank">');
		$_CONTROL->imgFileIcon->Render();
		if ($strUrl) print ('</a>');
		print('<br/>');
		if ($_CONTROL->Enabled)
			$_CONTROL->btnDelete->Render();
	} else {
		if ($strUrl = $_CONTROL->GetWebUrl()) print('<a href="' . $strUrl . '" target="_blank">');
		$_CONTROL->imgFileIcon->Render();
		if ($strUrl) print ('</a>');
		print('<br/>');
		if ($_CONTROL->Enabled)
			$_CONTROL->btnUpload->Render();
	}
?>
<?php if ($_CONTROL->Enabled) $_CONTROL->dlgFileAsset->Render(); ?>