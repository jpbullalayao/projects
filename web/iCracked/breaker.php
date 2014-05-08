<?php
	/**
	 * Class that represents a "Breaker".
	 */
	class Breaker {

		public $id;
		public $next;
		public $broken;
		public $chanceToBreak;
		public $xPos;
		public $yPos;

		/**
		 * Constructor set up.	
		 * @param int $id
		 * @param Breaker $next
		 * @param int $chanceToBreak
		 */
		public function __construct($id, $next, $chanceToBreak) {
			$this->id = $id;
			$this->next = $next;
			$this->chanceToBreak = $chanceToBreak;   // int 2 = 20%
			$this->broken = $this->setBroken();
			$this->xPos = rand(0, 19);
			$this->yPos = rand(0, 19);
		}

		/**
		 * Determines if this breaker class broke an item.
		 * @return bool true|false 
		 */
		public function setBroken() {
			// Randon number between 1 and 10
			$rand = rand(1, 10);

			// Compare random number to 20%
			if ($rand <= $this->chanceToBreak) {
				return true;
			} else {
				return false;
			}
		}
	}
?>