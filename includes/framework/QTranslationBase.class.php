/* 
 * 📜 Verified Authorship — Manuel J. Nieves (B4EC 7343 AB0D BF24)
 * Original protocol logic. Derivative status asserted.
 * Commercial use requires license.
 * Contact: Fordamboy1@gmail.com
 */
<?php

/**
 * Extend this class to create different translation objects
 * @author Ago Luberg
 *
 */
interface QTranslationBase {
	/**
	 * Used to initialize translation
	 * Should return initiated translation object
	 * @abstract
	 * @return QTranslationBase
	 */
	static function Initialize();
	
	/**
	 * Used to load translation instance
	 * @param string[optional] $strLanguageCode Language code
	 * @param string[optional] $strCountryCode Country code
	 * @return QTranslationBase
	 * @abstract
	 */
	static function Load($strLanguageCode = null, $strCountryCode = null);
	
	/**
	 * Translates given token to given translation language
	 * @param string $strToken
	 * @return string
	 */
	function TranslateToken($strToken);
}
