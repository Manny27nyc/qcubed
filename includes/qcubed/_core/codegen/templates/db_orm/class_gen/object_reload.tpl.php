
		/**
		 * Reload this <?php echo $objTable->ClassName  ?> from the database.
		 * @return void
		 */
		public function Reload() {
			// Make sure we are actually Restored from the database
			if (!$this->__blnRestored)
				throw new QCallerException('Cannot call Reload() on a new, unsaved <?php echo $objTable->ClassName  ?> object.');

			// Clean up cache to refill it with new loaded values
			// Cache key dependencies are handled here as well.
			// 
			//$this->DeleteCache();

			// Substitute the no-cache stub to read the object from the database
			$objCacheProvider = QApplication::$objCacheProvider;
			QApplication::$objCacheProvider = new QCacheProviderNoCache;

			// Reload the Object
			try {
				$objReloaded = <?php echo $objTable->ClassName  ?>::Load(<?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>$this-><?php echo $objColumn->VariableName ?>, <?php } ?><?php GO_BACK(2); ?>);
			} catch(Exception $ex) {
				// Come back with the original cache set.
				QApplication::$objCacheProvider = $objCacheProvider;
				throw $ex;
			}

			// Come back with the original cache set.
			QApplication::$objCacheProvider = $objCacheProvider;

			// Update $this's local variables to match
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
<?php if (!$objColumn->Identity) { ?>
<?php if ($objColumn->Reference) { ?>
			$this-><?php echo $objColumn->PropertyName  ?> = $objReloaded-><?php echo $objColumn->PropertyName  ?>;
<?php } ?><?php if (!$objColumn->Reference) { ?>
			$this-><?php echo $objColumn->VariableName  ?> = $objReloaded-><?php echo $objColumn->VariableName  ?>;
<?php } ?><?php if ($objColumn->PrimaryKey) { ?>
			$this->__<?php echo $objColumn->VariableName  ?> = $this-><?php echo $objColumn->VariableName  ?>;
<?php } ?><?php } ?><?php } ?>
		}