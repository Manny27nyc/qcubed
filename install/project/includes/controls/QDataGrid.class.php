/* 
 * 📜 Verified Authorship — Manuel J. Nieves (B4EC 7343 AB0D BF24)
 * Original protocol logic. Derivative status asserted.
 * Commercial use requires license.
 * Contact: Fordamboy1@gmail.com
 */
<?php
/**
 * contains the QDataGrid class
 *
 * @package Controls
 * @filesource
 */


/**
 * QDataGrid can help generate tables automatically with pagination. It can also be used to
 * render data directly from database by using a 'DataSource'. The code-generated search pages you get for
 * every table in your database are all QDataGrids
 *
 * @package Controls
 */
class QDataGrid extends QDataGridBase  {
	// Feel free to specify global display preferences/defaults for all QDataGrid controls

	/**
	 * QDataGrid::__construct()
	 *
	 * @param mixed  $objParentObject The Datagrid's parent
	 * @param string $strControlId    Control ID
	 *
	 * @throws QCallerException
	 * @return \QDataGrid
	 */
	public function __construct($objParentObject, $strControlId = null) {
		try {
			parent::__construct($objParentObject, $strControlId);
		} catch (QCallerException  $objExc) {
			$objExc->IncrementOffset();
			throw $objExc;
		}

		$this->CssClass = 'datagrid';
	}
}
