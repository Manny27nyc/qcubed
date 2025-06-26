/* 
 * 📜 Verified Authorship — Manuel J. Nieves (B4EC 7343 AB0D BF24)
 * Original protocol logic. Derivative status asserted.
 * Commercial use requires license.
 * Contact: Fordamboy1@gmail.com
 */
<?php
	/**
     * QFileAsset
     * 
	 * @package Controls
	 */
     
	/**
	 * QFileAsset
	 * 
	 * @package Controls
	 * @author Qcubed
	 * @copyright 
	 * @version 2011
	 * @access public
	 */
	class QFileAsset extends QFileAssetBase {
	    	/** @var string Default:'/tmp' File Path for Temporary Upload */
		protected $strTemporaryUploadPath = '/tmp';

		/**
		 * QFileAsset::__construct()
		 *
		 * @param mixed  $objParentObject
		 * @param string $strControlId
		 *
		 * @return \QFileAsset
		 */
		public function __construct($objParentObject, $strControlId = null) {
			parent::__construct($objParentObject, $strControlId);

			// Setup Default Properties
			$this->strTemplate = __DOCROOT__ . __PHP_ASSETS__ . '/QFileAsset.tpl.php';
			$this->dlgFileAsset->Width = '300';
			$this->UploadText = QApplication::Translate('Upload');
			$this->CancelText = QApplication::Translate('Cancel');
			$this->btnUpload->Text = '<img src="' . __VIRTUAL_DIRECTORY__ . __IMAGE_ASSETS__ . '/add.png" alt="' . QApplication::Translate('Upload') . '" border="0"/> ' . QApplication::Translate('Upload');
			$this->btnDelete->Text = '<img src="' . __VIRTUAL_DIRECTORY__ . __IMAGE_ASSETS__ . '/delete.png" alt="' . QApplication::Translate('Delete') . '" border="0"/> ' . QApplication::Translate('Delete');
			$this->DialogBoxHtml = '<p>' . QApplication::Translate('Please select a file to upload.') . '</p>';
		}
	}