<template OverwriteFlag="true" DocrootFlag="false" DirectorySuffix="" TargetDirectory="<?php echo __MODEL_GEN__  ?>" TargetFileName="<?php echo $objTypeTable->ClassName  ?>Gen.class.php"/>
<?php print("<?php\n"); ?>
	/**
	 * The <?php echo $objTypeTable->ClassName ?> class defined here contains
	 * code for the <?php echo $objTypeTable->ClassName ?> enumerated type.  It represents
	 * the enumerated values found in the "<?php echo $objTypeTable->Name ?>" table
	 * in the database.
	 *
	 * To use, you should use the <?php echo $objTypeTable->ClassName ?> subclass which
	 * extends this <?php echo $objTypeTable->ClassName ?>Gen class.
	 *
	 * Because subsequent re-code generations will overwrite any changes to this
	 * file, you should leave this file unaltered to prevent yourself from losing
	 * any information or code changes.  All customizations should be done by
	 * overriding existing or implementing new methods, properties and variables
	 * in the <?php echo $objTypeTable->ClassName ?> class.
	 *
	 * @package <?php echo QCodeGen::$ApplicationName;  ?>

	 * @subpackage GeneratedDataObjects
	 */
	abstract class <?php echo $objTypeTable->ClassName ?>Gen extends QBaseClass {
<?php echo ($intKey = 0) == 1; ?><?php foreach ($objTypeTable->TokenArray as $intKey=>$strValue) { ?>
		const <?php echo $strValue ?> = <?php echo $intKey ?>;
<?php } ?>

		const MaxId = <?php echo $intKey ?>;

		public static $NameArray = array(<?php if (count($objTypeTable->NameArray)) { ?>

<?php foreach ($objTypeTable->NameArray as $intKey=>$strValue) { ?>
			<?php echo $intKey ?> => '<?php echo $strValue ?>',
<?php } ?><?php GO_BACK(2); ?><?php }?>);

		public static $NameArrayReversed = array(<?php if (count($objTypeTable->NameArray)) { ?>

<?php foreach ($objTypeTable->NameArray as $intKey=>$strValue) { ?>
			'<?php echo $strValue ?>' => <?php echo $intKey ?>,
<?php } ?><?php GO_BACK(2); ?><?php } ?>);

		public static $TokenArray = array(<?php if (count($objTypeTable->TokenArray)) { ?>

<?php foreach ($objTypeTable->TokenArray as $intKey=>$strValue) { ?>
			<?php echo $intKey ?> => '<?php echo $strValue ?>',
<?php } ?><?php GO_BACK(2); ?><?php }?>);

<?php if (count($objTypeTable->ExtraFieldNamesArray)) { ?>
		public static $ExtraColumnNamesArray = array(
<?php foreach ($objTypeTable->ExtraFieldNamesArray as $strColName) { ?>
			'<?php echo $strColName ?>',
<?php } ?><?php GO_BACK(2); ?>);

		public static $ExtraColumnValuesArray = array(
<?php foreach ($objTypeTable->ExtraPropertyArray as $intKey=>$arrColumns) { ?>
			<?php echo $intKey ?> => array (
<?php foreach ($arrColumns as $strColName=>$strColValue) { ?>
						'<?php echo $strColName ?>' => '<?php echo str_replace("'", "\\'", $strColValue) ?>',
<?php } ?><?php GO_BACK(2); ?>),
<?php } ?><?php GO_BACK(2); ?>);


<?php } ?>
		public static function ToString($int<?php echo $objTypeTable->ClassName ?>Id) {
			if (!isset(self::$NameArray[$int<?php echo $objTypeTable->ClassName ?>Id])) {
				throw new QCallerException(sprintf('Invalid int<?php echo $objTypeTable->ClassName ?>Id: %s', $int<?php echo $objTypeTable->ClassName ?>Id));
			}
			return self::$NameArray[$int<?php echo $objTypeTable->ClassName ?>Id];
		}

		public static function ToToken($int<?php echo $objTypeTable->ClassName ?>Id) {
			if (!isset(self::$TokenArray[$int<?php echo $objTypeTable->ClassName ?>Id])) {
				throw new QCallerException(sprintf('Invalid int<?php echo $objTypeTable->ClassName ?>Id: %s', $int<?php echo $objTypeTable->ClassName ?>Id));
			}
			return self::$TokenArray[$int<?php echo $objTypeTable->ClassName ?>Id];
		}

		/**
		 * Get the Id from name
		 * @param string $str<?php echo $objTypeTable->ClassName ?>Name Mnemonic or name or token of this type value
		 * @return int The Id of the type value, referenced by $str<?php echo $objTypeTable->ClassName ?>Name
		 */
		public static function FromName($str<?php echo $objTypeTable->ClassName ?>Name) {
			if (!isset(self::$TokenArrayReversed[$str<?php echo $objTypeTable->ClassName ?>Name])) {
				throw new QCallerException(sprintf('Invalid str<?php echo $objTypeTable->ClassName ?>Name: %s', $str<?php echo $objTypeTable->ClassName ?>Name));
			}
			return self::$TokenArrayReversed[$str<?php echo $objTypeTable->ClassName ?>Name];
		}

<?php foreach ($objTypeTable->ExtraFieldNamesArray as $strColName) { ?>
		public static function To<?php echo $strColName ?>($int<?php echo $objTypeTable->ClassName ?>Id) {
			if (array_key_exists($int<?php echo $objTypeTable->ClassName ?>Id, <?php echo $objTypeTable->ClassName ?>::$ExtraColumnValuesArray))
				return <?php echo $objTypeTable->ClassName ?>::$ExtraColumnValuesArray[$int<?php echo $objTypeTable->ClassName ?>Id]['<?php echo $strColName ?>'];
			else
				throw new QCallerException(sprintf('Invalid int<?php echo $objTypeTable->ClassName ?>Id: %s', $int<?php echo $objTypeTable->ClassName ?>Id));
		}

<?php } ?>

<?php foreach ($objTypeTable->ExtraFieldNamesArray as $strColName) { ?>
		public static function To<?php echo $strColName ?>ByName($str<?php echo $objTypeTable->ClassName ?>Name) {
			$int<?php echo $objTypeTable->ClassName ?>Id = self::FromName($str<?php echo $objTypeTable->ClassName ?>Name);
			if (array_key_exists($int<?php echo $objTypeTable->ClassName ?>Id, <?php echo $objTypeTable->ClassName ?>::$ExtraColumnValuesArray))
				return <?php echo $objTypeTable->ClassName ?>::$ExtraColumnValuesArray[$int<?php echo $objTypeTable->ClassName ?>Id]['<?php echo $strColName ?>'];
			else
				throw new QCallerException(sprintf('Invalid int<?php echo $objTypeTable->ClassName ?>Id: %s', $int<?php echo $objTypeTable->ClassName ?>Id));
		}

<?php } ?>
	/////////////////////////////// 
	// CLASS-WIDE LOAD AND COUNT METHODS 
	/////////////////////////////// 

	/** 
	* Static method to retrieve the Database object that owns this class. 
	* @return QDatabaseBase reference to the Database object that can query this class 
	*/ 
	public static function GetDatabase() { 
		return QApplication::$Database[<?php echo $objCodeGen->DatabaseIndex; ?>]; 
	} 

	<?php include('qcubed_query_methods.tpl.php'); ?> 
	<?php include('instantiation_methods.tpl.php'); ?> 

	}
	<?php include('qcubed_query_classes.tpl.php'); ?> 
?>