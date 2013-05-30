<?php
	require('../../../../includes/configuration/prepend.inc.php');

// Define the Qform with all our Qcontrols
class ExamplesForm extends QForm {

	// Local declarations of our Qcontrols
	protected $lblMessage;
	/**
	 * @var QJqSelectMenu A listbox of Persons
	 */
	protected $lstPersons;
	/**
	 * @var QJqSelectMenu A listbox of Persons
	 */
	protected $lstPersons2;
	/** @var QTabs */
	protected $Tabs;

	// Initialize our Controls during the Form Creation process
	protected function Form_Create() {
		// Define our Label
		$this->lblMessage = new QLabel($this);
		$this->lblMessage->Text = '<None>';
		
		// Tabs
		$this->Tabs = new QTabs($this);
		$tab1 = new QPanel($this->Tabs);
		$tab1->AutoRenderChildren = true;
		$tab1->Text = 'First tab is active by default';
		$tab2 = new QPanel($this->Tabs);
		$tab2->AutoRenderChildren = true;
		$tab2->Text = 'Tab 2';
		$this->Tabs->Headers = array('One', 'Two');
		
		// Define the ListBox, and create the first listitem as 'Select One'
		$this->lstPersons2 = new QJqSelectMenu($tab2);
		$this->lstPersons2->AddItem(QApplication::Translate('- Select One -'), null);

		// Add the items for the listbox, pulling in from the Person table
		$objPersons = Person::LoadAll(QQ::Clause(QQ::OrderBy(QQN::Person()->LastName, QQN::Person()->FirstName)));
		if ($objPersons){
			foreach ($objPersons as $objPerson) {
				// We want to display the listitem as Last Name, First Name
				// and the VALUE of the listitem should be the person object itself
				$this->lstPersons2->AddItem($objPerson->LastName . ', ' . $objPerson->FirstName, $objPerson);
			}
		}
		// Declare a QChangeEvent to call a server action: the lstPersons_Change PHP method
		$this->lstPersons2->AddAction(new QChangeEvent(), new QServerAction('lstPersons2_Change'));
		
		$this->Tabs->AddAction(new QTabs_ActivateEvent(), new QJavaScriptAction($this->lstPersons2->GetRefreshUiJavaScript()));

		// Define the ListBox, and create the first listitem as 'Select One'
		$this->lstPersons = new QJqSelectMenu($tab1);
		$this->lstPersons->AddItem(QApplication::Translate('- Select One -'), null);

		// Add the items for the listbox, pulling in from the Person table
		$objPersons = Person::LoadAll(QQ::Clause(QQ::OrderBy(QQN::Person()->LastName, QQN::Person()->FirstName)));
		if ($objPersons){
			foreach ($objPersons as $objPerson) {
				// We want to display the listitem as Last Name, First Name
				// and the VALUE of the listitem should be the person object itself
				$this->lstPersons->AddItem($objPerson->LastName . ', ' . $objPerson->FirstName, $objPerson);
			}
		}
		// Declare a QChangeEvent to call a server action: the lstPersons_Change PHP method
		$this->lstPersons->AddAction(new QChangeEvent(), new QServerAction('lstPersons_Change'));
	}

	// Handle the changing of the listbox
	protected function lstPersons_Change($strFormId, $strControlId, $strParameter) {
		// See if there is something selected
		// Note that in the HTML that gets rendered, the <option> values are arbitrary
		// index numbers.  However, we put in the whole Person object as the QListItem
		// value.  So the SelectedValue property of the QListControl will
		// do a proper lookup of the QListItem that was selected, and will return
		// to us the Person OBJECT (or NULL if they selected "- Select One -").
		$objPerson = $this->lstPersons->SelectedValue;

		if ($objPerson) {
			$this->lblMessage->Text = sprintf('%s %s, Person ID of %s', $objPerson->FirstName, $objPerson->LastName, $objPerson->Id);
		} else {
			// No one was selected
			$this->lblMessage->Text = '<None>';
		}
	}
	// Handle the changing of the listbox
	protected function lstPersons2_Change($strFormId, $strControlId, $strParameter) {
		// See if there is something selected
		// Note that in the HTML that gets rendered, the <option> values are arbitrary
		// index numbers.  However, we put in the whole Person object as the QListItem
		// value.  So the SelectedValue property of the QListControl will
		// do a proper lookup of the QListItem that was selected, and will return
		// to us the Person OBJECT (or NULL if they selected "- Select One -").
		$objPerson = $this->lstPersons2->SelectedValue;

		if ($objPerson) {
			$this->lblMessage->Text = sprintf('%s %s, Person ID of %s', $objPerson->FirstName, $objPerson->LastName, $objPerson->Id);
		} else {
			// No one was selected
			$this->lblMessage->Text = '<None>';
		}
	}
	
}

// Run the Form we have defined
// The QForm engine will look to intro.tpl.php to use as its HTML template include file
ExamplesForm::Run('ExamplesForm');
?>