			if ($this-><?php echo $strControlId ?>) {
				$this-><?php echo $strControlId ?>_Refresh();
			}
			if ($this-><?php echo $strLabelId ?>) $this-><?php echo $strLabelId ?>->Text = ($this-><?php echo $strObjectName ?>-><?php echo $objColumn->PropertyName ?>) ? QApplication::Translate('Yes') : QApplication::Translate('No');