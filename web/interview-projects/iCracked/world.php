<?php 
	/**
	 * Handles much of the necessary components that need to be displayed on the index.php web page.
	 */
	class World {

		private $numFixers;
		private $numBreakers;
		private $headFixer;           // Linked list of Fixers
		private $headBreaker;         // Linked list of Breakers
		private $headBrokenLocation;  // Linked list of Broken Locations
		private $grid;                // 2D Array

		/**
		 * Constructor set up.	
		 * @param int $numFixers
		 * @param int $numBreakers
		 * @param Fixer $headFixer
		 * @param Breaker $headBreaker
		 * @param BrokenLocation $headBrokenLocation
		 * @param array(array()) $grid
		 */
		public function __construct($numFixers, $numBreakers, $headFixer, $headBreaker, $headBrokenLocation, $grid) {
			$this->numFixers = $numFixers;
			$this->numBreakers = $numBreakers;
			$this->headFixer = $headFixer;
			$this->headBreaker = $headBreaker;
			$this->headBrokenLocation = $headBrokenLocation;
			$this->grid = $grid;
		}

		/**
		 * Creates necessary fixers.
		 */
		public function createFixers() {
			for ($i = $this->numFixers; $i > 0; $i--) {
				$fixer = new Fixer($i, $this->headFixer);
				$this->headFixer = $fixer;
			}
		}

		/**
		 * Creates necessary breakers.
		 */
		public function createBreakers() {
			for ($i = $this->numBreakers; $i > 0; $i--) {
				$breaker = new Breaker($i, $this->headBreaker, 2);
				$this->headBreaker = $breaker;

				if ($breaker->broken) {
					$this->createBrokenLocation($breaker);
				}
			}
		}

		/**
		 * Create broken location (index of grid where Breaker broke an item) nodes.
		 */
		public function createBrokenLocation($breaker) {
			$brokenLocation = new BrokenLocation($breaker->xPos, $breaker->yPos, $this->headBrokenLocation);
			$this->headBrokenLocation = $brokenLocation;
		}

		/**
		 * Prints out positions in grid of where Breakers broke items.
		 */
		public function printBrokenLocations() {
			$temp = $this->headBrokenLocation;

			while ($temp != NULL) {
				echo "(".$temp->xPos.", ".$temp->yPos.") ";
				$temp = $temp->next;
			}
		}

		/**
		 * Prints out values for Fixer classes.
		 */
		public function printFixers() {
			$temp = $this->headFixer;

			while ($temp != NULL) {
				echo "Fixer ".$temp->id.": (".$temp->xPos." , ".$temp->yPos.")<br>";
				$temp = $temp->next;
			}
		}

		/**
		 * Prints out values for Breaker classes.
		 */
		public function printBreakers() {
			$temp = $this->headBreaker;

			while ($temp != NULL) {
				echo "Breaker ".$temp->id.": (".$temp->xPos." , ".$temp->yPos.")<br>";
				$temp = $temp->next;
			}
		}

		/**
		 * Creates grid, which is a 2D array.
		 */
		public function createGrid() {
			for ($i = 0; $i < 20; $i++) {
				$innerArray = array();
				for ($j = 0; $j < 20; $j++) {
					$innerArray[$j] = $j;
				}
				$this->grid[$i] = $innerArray;
			}
		}

		/**
		 * Prints grid on web page within a table tag.
		 */
		public function printGrid() {
			echo "<table>";
			for ($i = 0; $i < 20; $i++) {
				echo "<tr>";
				for ($j = 0; $j < 20; $j++) {
					$red = $this->getRed($i, $j);   
					$cyan = $this->getCyan($i, $j);

					$this->setCellColor($red, $cyan);

					echo "(".$i." , ".$this->grid[$i][$j].")";
					echo "</td>";
				}
				echo "</tr>";
			}
			echo "</table>";
		}

		/**
		 * Determines if we need to change background color of current cell to Red.
		 * @param int $i (row)
		 * @param int $j (col)
		 * @return bool $red
		 */
		public function getRed($i, $j) {
			$red = false;
			$temp = $this->headBrokenLocation;
			while ($temp != NULL) {
				if ($temp->xPos == $i && $temp->yPos == $j) {
					$red = true;
					break;
				}
				$temp = $temp->next;
			}
			return $red;
		} 

		/**
		 * Determines if we need to change background color of current cell to Cyan.
		 * @param int $i (row)
		 * @param int $j (col)
		 * @return bool $cyan
		 */
		public function getCyan($i, $j) {
			$cyan = false;
			$temp = $this->headFixer;
			while ($temp != NULL) {
				if ($temp->xPos == $i && $temp->yPos == $j) {
					$cyan = true;
					break;
				}
				$temp = $temp->next;
			}
			return $cyan;
		}

		/**
		 * Sets background color for a cell in the grid.
		 * @param bool $red
		 * @param bool $cyan
		 */
		public function setCellColor($red, $cyan) {
			
			if ($red && !$cyan) {
				echo "<td class=\"cell red\">";
			}
			
			else if ($cyan && !$red) {
				echo "<td class=\"cell cyan\">";
			} 

			else if ($red && $cyan) {
				echo "<td class=\"cell magenta\">";
			}

			else {
				echo "<td class=\"cell\">";
			}
		}

		/**
		 * Stub that will allow each Fixer to find the next broken item (Breaker) to fix.
		 */
		public function findBreaker() {
			$temp = $this->headFixer;

			while ($temp != NULL) {
				// Iterate through Breaker linked list, see if $broken = true. If so, call
				// Fixer.move() in order to move toward the Breaker location.
			}
		}
	}
?>
