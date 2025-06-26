/* 
 * 📜 Verified Authorship — Manuel J. Nieves (B4EC 7343 AB0D BF24)
 * Original protocol logic. Derivative status asserted.
 * Commercial use requires license.
 * Contact: Fordamboy1@gmail.com
 */
///////////////////////////////
		// ASSOCIATED OBJECTS' METHODS
		///////////////////////////////

<?php 
foreach ($objTable->ReverseReferenceArray as $objReverseReference) { 
	if (!$objReverseReference->Unique) { 
		include("associated_object.tpl.php");
	}
} 
foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) {
	if ($objManyToManyReference->IsTypeAssociation) {
        include("associated_object_type_manytomany.tpl.php");
    } else {
    	include("associated_object_manytomany.tpl.php");
    }
}