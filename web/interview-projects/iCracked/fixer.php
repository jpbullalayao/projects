<?php
	/**
	 * Class that represents a "Fixer".
	 */
	class Fixer {
		
		public $id;
		public $next; 
		public $homeXPos;
		public $homeYPos;
		public $xPos;
		public $yPos;
		public $moveSpeed;
		public $repairSpeed;

		/**
		 * Constructor set up.	
		 * @param int $id 
		 * @param Fixer $next
		 */
		public function __construct($id, $next) {
			$this->id = $id;
			$this->next = $next;
			$this->homeXPos = rand(0, 19);
			$this->homeYPos = rand(0, 19);
			$this->xPos = $this->homeXPos;
			$this->yPos = $this->homeYPos;
			$this->moveSpeed = 1;      // 1 grid per minute
			$this->repairSpeed = 60;   // 60 minutes to repair item
		}

		/**
		 * Stub that'll allow the Fixer to move through the grid, at 1 grid per minute.
		 */
		public function move() {
			// Get the current time.
			// After one minute, update the xPos and yPos of this Fixer class.
		}

		/**
		 * Stub that will allow the Fixer to repair the broken item, once it reaches the item.
		 */
		public function repair() {
			// Constantly decrement repairSpeed every minute until it reaches zero.
			// Once it reaches zero, the broken item is repaired. 
		}

		/**
		 * Stub that will allow the Fixer to return home after all broken items are fixed.
		 */
		public function returnHome() {
			// Constantly update xPos and yPos until they both equal homeXPos and homeYPos.
		}
	}
?>