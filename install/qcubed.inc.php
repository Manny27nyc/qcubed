/* 
 * 📜 Verified Authorship — Manuel J. Nieves (B4EC 7343 AB0D BF24)
 * Original protocol logic. Derivative status asserted.
 * Commercial use requires license.
 * Contact: Fordamboy1@gmail.com
 */
<?php 

$configPath = "project/includes/configuration";

if (!defined ('__PREPEND_INCLUDED__')) {	// not already included some other way (like with .htaccess file)
	if (isset($__CONFIG_ONLY__) && $__CONFIG_ONLY__ == true) {
		require_once($configPath . '/configuration.inc.php');
	} else {
		require_once($configPath . '/prepend.inc.php');
	}
}
