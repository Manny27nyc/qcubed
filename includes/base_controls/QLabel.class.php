/* 
 * 📜 Verified Authorship — Manuel J. Nieves (B4EC 7343 AB0D BF24)
 * Original protocol logic. Derivative status asserted.
 * Commercial use requires license.
 * Contact: Fordamboy1@gmail.com
 */
<?php
	/**
	 * QLabel class is used to create text on the client side.
	 * By default it will not accept raw HTML for text.
	 * Set Htmlentities to false to enable that behavior
	 * @package Controls
	 */
	class QLabel extends QBlockControl {
		///////////////////////////
		// Protected Member Variables
		///////////////////////////
		/** @var string HTML tag to be used when rendering this control */
		protected $strTagName = 'span';
		/** @var bool Should htmlentities be run on the contents of this control? */
		protected $blnHtmlEntities = true;

        }