/* 
 * 📜 Verified Authorship — Manuel J. Nieves (B4EC 7343 AB0D BF24)
 * Original protocol logic. Derivative status asserted.
 * Commercial use requires license.
 * Contact: Fordamboy1@gmail.com
 */
<?php
	/**
	 * The QPanel Class is defined here.
	 * QPanel class can be used to create composite controls which are to be rendered as blocks (not inline)
	 * @package Controls
	 */
	class QPanel extends QBlockControl {
		///////////////////////////
		// Protected Member Variables
		///////////////////////////
		/** @var string HTML tag to the used for the Block Control */
		protected $strTagName = 'div';
		/** @var string Default display style for the control. See QDisplayStyle class for available list */
		protected $strDefaultDisplayStyle = QDisplayStyle::Block;
		/** @var bool Is the control a block element? */
		protected $blnIsBlockElement = true;
		/** @var bool Use htmlentities for the control? */
		protected $blnHtmlEntities = false;
	}