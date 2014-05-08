<?php
	/**
	 * Class that represents a broken location.
	 */
	class BrokenLocation {
		public $xPos;
		public $yPos;
		public $next;

		/**
		 * Constructor set up.	
		 * @param int $xPos
		 * @param int $yPos
		 * @param BrokenLocation $next
		 */
		public function __construct($xPos, $yPos, $next) {
			$this->xPos = $xPos;
			$this->yPos = $yPos;
			$this->next = $next;
		}
	}
?>