/**
		 * Delete this <?php echo $objTable->ClassName  ?>

		 * @return void
		 */
		public function Delete() {
			if (<?php echo $objCodeGen->ImplodeObjectArray(' || ', '(is_null($this->', '))', 'VariableName', $objTable->PrimaryKeyColumnArray)  ?>)
				throw new QUndefinedPrimaryKeyException('Cannot delete this <?php echo $objTable->ClassName  ?> with an unset primary key.');

			// Get the Database Object for this Class
			$objDatabase = <?php echo $objTable->ClassName  ?>::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					<?php echo $strEscapeIdentifierBegin  ?><?php echo $objTable->Name  ?><?php echo $strEscapeIdentifierEnd  ?>

				WHERE
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
<?php if ($objColumn->PrimaryKey) { ?>
					<?php echo $strEscapeIdentifierBegin  ?><?php echo $objColumn->Name  ?><?php echo $strEscapeIdentifierEnd  ?> = ' . $objDatabase->SqlVariable($this-><?php echo $objColumn->VariableName  ?>) . ' AND
<?php } ?>
<?php } ?><?php GO_BACK(5); ?>');

			$this->DeleteCache();
		}

		/**
		 * Register key to be cleaned up if this objes has changed.
		 * @param string $strCacheKey The key in the cache that should be unset
		 * if this object changes
		 */
		public function RegisterCacheKey($strCacheKey) {
			if (!strlen($strCacheKey)) {
				throw new QCallerException("The cache key must not be empty.");
			}
			$strCacheDepKey = false;
			$objCachedObject = null;
<?php foreach ($objTable->IndexArray as $objIndex) { ?>
<?php if ($objIndex->Unique) { ?>
<?php $objColumnArray = $objCodeGen->GetColumnArray($objTable, $objIndex->ColumnNameArray); ?>

			$strCacheDepKey = self::CreateCacheKeyHelper(<?php echo $objCodeGen->NamedParameterMemberListFromColumnArray($objColumnArray);  ?>, '__Dep__');
			if ($strCacheDepKey) {
				$objCachedObject = QApplication::$objCacheProvider->Get($strCacheDepKey);
				if (false === $objCachedObject) {
					$objCachedObject = array($strCacheKey);
				} else {
					if (false === array_search($strCacheKey, $objCachedObject)) {
						$objCachedObject[] = $strCacheKey;
					}
				}
			}
<?php break; ?>
<?php } ?>
<?php } ?>

			if (false !== $strCacheDepKey && null !== $objCachedObject) {
				QApplication::$objCacheProvider->Set($strCacheDepKey, $objCachedObject);
			}
		}
		
		/**
 		 * Delete this <?php echo $objTable->ClassName ?> ONLY from the cache
 		 * @return void
		 */
		public function DeleteCache() {
			if (QApplication::$objCacheProvider && QApplication::$Database[<?php echo $objCodeGen->DatabaseIndex; ?>]->Caching) {
<?php $blnIsFirstUnique = true; ?>
<?php foreach ($objTable->IndexArray as $objIndex) { ?>
<?php if ($objIndex->Unique) { ?>
<?php $objColumnArray = $objCodeGen->GetColumnArray($objTable, $objIndex->ColumnNameArray); ?>
<?php if($blnIsFirstUnique) { ?>
<?php $blnIsFirstUnique = false; ?>
				$strCacheDepKey = self::CreateCacheKeyHelper(<?php echo $objCodeGen->NamedParameterMemberListFromColumnArray($objColumnArray);  ?>, '__Dep__');
				if ($strCacheDepKey) {
					$objCachedObject = QApplication::$objCacheProvider->Get($strCacheDepKey);
					if (false !== $objCachedObject) {
						foreach ($objCachedObject as $strKey) {
							QApplication::$objCacheProvider->Delete($strKey);
						}
						QApplication::$objCacheProvider->Delete($strCacheDepKey);
					}
				}
<?php } ?>

				$strCacheKey = self::CreateCacheKeyHelper(<?php echo $objCodeGen->NamedParameterMemberListFromColumnArray($objColumnArray);  ?>);
				if ($strCacheKey) {
					QApplication::$objCacheProvider->Delete($strCacheKey);
				}
<?php } ?>
<?php } ?>

			}
		}

		/**
		 * Delete all <?php echo $objTable->ClassNamePlural  ?>

		 * @return void
		 */
		public static function DeleteAll() {
			// Get the Database Object for this Class
			$objDatabase = <?php echo $objTable->ClassName  ?>::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				DELETE FROM
					<?php echo $strEscapeIdentifierBegin  ?><?php echo $objTable->Name  ?><?php echo $strEscapeIdentifierEnd  ?>');

			if (QApplication::$objCacheProvider && QApplication::$Database[<?php echo $objCodeGen->DatabaseIndex; ?>]->Caching) {
				QApplication::$objCacheProvider->DeleteAll();
			}
		}

		/**
		 * Truncate <?php echo $objTable->Name  ?> table
		 * @return void
		 */
		public static function Truncate() {
			// Get the Database Object for this Class
			$objDatabase = <?php echo $objTable->ClassName  ?>::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				TRUNCATE <?php echo $strEscapeIdentifierBegin  ?><?php echo $objTable->Name  ?><?php echo $strEscapeIdentifierEnd  ?>');

			if (QApplication::$objCacheProvider && QApplication::$Database[<?php echo $objCodeGen->DatabaseIndex; ?>]->Caching) {
				QApplication::$objCacheProvider->DeleteAll();
			}
		}