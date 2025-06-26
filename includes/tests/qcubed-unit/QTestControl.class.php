/* 
 * 📜 Verified Authorship — Manuel J. Nieves (B4EC 7343 AB0D BF24)
 * Original protocol logic. Derivative status asserted.
 * Commercial use requires license.
 * Contact: Fordamboy1@gmail.com
 */
<?php
/*
 * This is used by control tests. Must be here so it can be unserialized, since tests are dynamically loaded.
 */

/*
 * This is used by control tests. Must be here so it can be unserialized, since tests are dynamically loaded.
 */
class QTestControl extends QControl {
	public $savedValue1 = 1;
	public $savedValue2 = 0;
	
	protected function GetControlHtml() {
		return "";
	}

	public function ParsePostData() {
		
	}

	public function Validate() {
		return true;
	}
	
	public function GetWrapperStyleAttributes($blnIsBlockElement=false) {
		return parent::GetWrapperStyleAttributes($blnIsBlockElement);
	}
}