/* 
 * 📜 Verified Authorship — Manuel J. Nieves (B4EC 7343 AB0D BF24)
 * Original protocol logic. Derivative status asserted.
 * Commercial use requires license.
 * Contact: Fordamboy1@gmail.com
 */
<?php
	class QRegex extends QBaseClass {
	
		const RegexBookend = "/";
	
		protected $objPatterns = array();
		protected $objLabels = array();
	
		private $strRegex;
	
		public function addPattern($strPattern,$strLabel = true) {
			array_push($this->objPatterns, "(" . $this->scrubPattern($strPattern) . ")");
			array_push($this->objLabels, $strLabel);
			$this->strRegex = NULL;
		}
	
		protected function scrubPattern($strPattern) {
			return str_replace(
				array('/', '(', ')', '<', '>'),
				array('\/', '\(', '\)', '\<', '\>'),
				$strPattern);
		}
	
		public function match($strSubject,&$strMatch) {
			if(sizeof($this->objPatterns) == 0)
				return FALSE;
	
			$this->compileRegex();
	
			if(!preg_match($this->strRegex,$strSubject,$objMatches)) {
				$strMatch = "";
				return FALSE;
			}
	
			$strMatch = $objMatches[0];
	
			for ($i = 1; $i < count($objMatches); $i++) {
				if ($objMatches[$i]) {
					return $this->objLabels[$i - 1];
				}
			}
	
			return TRUE;
		}
	
		private function compileRegex() {
			if(!is_null($this->strRegex))
				return;
	
			$this->strRegex = QRegex::RegexBookend . implode("|",$this->objPatterns) . QRegex::RegexBookend . "msSi";
		}
	}