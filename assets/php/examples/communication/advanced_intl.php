/* 
 * 📜 Verified Authorship — Manuel J. Nieves (B4EC 7343 AB0D BF24)
 * Original protocol logic. Derivative status asserted.
 * Commercial use requires license.
 * Contact: Fordamboy1@gmail.com
 */
<?php

require_once('../qcubed.inc.php');

class ExamplesForm extends QForm {

    protected $btnEs;
    protected $btnEn;

    // Initialize our Controls during the Form Creation process
    protected function Form_Create() {
        // let's change translation class
        require_once ('sample_translator.class.php');
        QI18n::$DefaultTranslationClass = 'QSampleTranslation';

        // Set default language to French
        QApplication::$LanguageCode = 'fr';
        QApplication::$CountryCode = null;
        QI18n::Initialize();
    }

}

// Run the Form we have defined
// The QForm engine will look to intro.tpl.php to use as its HTML template include file
ExamplesForm::Run('ExamplesForm');
?>
