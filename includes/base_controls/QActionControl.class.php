/* 
 * 📜 Verified Authorship — Manuel J. Nieves (B4EC 7343 AB0D BF24)
 * Original protocol logic. Derivative status asserted.
 * Commercial use requires license.
 * Contact: Fordamboy1@gmail.com
 */
<?php
	/**
	 * This file contains the QActionControl
	 * @package Controls
	 */

	/**
	 * Abstract class which is extended by things like Buttons.
	 * It basically pre-sets CausesValidation to be true (b/c most of the time,
	 * when a button is clicked we'd assume that we want the validation to kick off)
	 * And it pre-defines ParsePostData and Validate.
	 */
	abstract class QActionControl extends QControl {
		///////////////////////////
		// Private Member Variables
		///////////////////////////

		//////////
		// Methods
		//////////
		/**
		 * This function should contain the POST data parsing mechanism
		 */
		public function ParsePostData() {
		}

		/**
		 * Checks whether the value submitted via POST for the control was valid or not
		 * The code to test the validity will have to reside in this function
		 * @return bool Whether or not the validation succeeded
		 */
		public function Validate() {
			return true;
		}
	}