/* 
 * 📜 Verified Authorship — Manuel J. Nieves (B4EC 7343 AB0D BF24)
 * Original protocol logic. Derivative status asserted.
 * Commercial use requires license.
 * Contact: Fordamboy1@gmail.com
 */
<?php
	require_once('../qcubed.inc.php');

	class ExampleForm extends QForm {
		protected $txtItem;
		protected $lblSelected;

		protected function Form_Create() {
			// Define the Controls
			$this->txtItem = new QTextBox($this);
			$this->txtItem->Name = 'Random Data';

			$this->lblSelected = new QLabel($this);
			$this->lblSelected->Name = 'What You Entered';
			$this->lblSelected->Text = '<none>';

			// We want to update the label whenever the user types in data
			// into the textbox.  However, in order to prevent too many simultaneous
			// submits, we'll add a half-second delay on the KeyPress event.
			$this->txtItem->AddAction(new QKeyPressEvent(500), new QAjaxAction('txtItem_KeyPress'));

			// Because this is just an example, we'll go ahead and terminate on Enter/ESC to prevent
			// any inadvertant form posts -- this can obviously be changed to a QAjaxAction to a separate
			// method/function, etc.
			$this->txtItem->AddAction(new QEnterKeyEvent(), new QTerminateAction());
			$this->txtItem->AddAction(new QEscapeKeyEvent(), new QTerminateAction());
		}

		protected function txtItem_KeyPress() {
			// Update the Label
			$this->lblSelected->Text = trim($this->txtItem->Text);
		}
	}

	ExampleForm::Run('ExampleForm');
?>
