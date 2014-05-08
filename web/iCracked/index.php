<?php
	require_once("world.php");
	require_once("fixer.php");
	require_once("breaker.php");
	require_once("broken_location.php");

	/* World object that we use to call all the necessary methods in order to display everything on the web page */
	$world = new World(10, 200, NULL, NULL, NULL, array(array()));
	$world->createFixers();
	$world->createBreakers();
	$world->createGrid();
?>

<!DOCTYPE html>
<html>
	<head>
	<meta charset="utf-8" />
	<link rel="stylesheet" href="main.css">
	<title>Fixers and Breakers</title>
	</head>

	<body>
		<div class="timer"></div>

		<div class="brokenLocations">
			<b><u>Locations of Broken Items</u></b><br>
			<?php $world->printBrokenLocations(); ?>
		</div>

		<!-- Clear floats of timer and brokenLocations -->
		<div class="clear"></div>

		<div class="grid">
			<?php $world->printGrid(); ?>
		</div>

		<div class="fixers">
			<b><u>Position of Fixers</u></b><br>
			<?php $world->printFixers(); ?>
		</div>
		
		<div class="breakers">
			<b><u>Position of Breakers</u></b><br>
			<?php $world->printBreakers(); ?>
		</div>
		
		<!-- Clear floats of fixers and breakers -->
		<div class="clear"></div>
	</body>

	<!-- Script that uses jQuery + AJAX + PHP in order to update everything on front-end -->
	<script type= "text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script type= "text/javascript">
    $(document).ready(function() {
    	/**
    	 * Updates time on front-end.
    	 */
		function updateTime() {
		  	$.ajax({
		   		type: 'POST',
		   		url: 'time.php',
		   		timeout: 1000,
		   		success: function(date) {
		    		$(".timer").html("<b>Date</b>: " + date); 
		      		window.setTimeout(updateTime, 1000);
		   		},
		  	});
		}

		/**
		 * Should update the grid to show state of fixers and breakers, but it's just pseudo code at the moment.
		 */
		function updateGrid() {
			$.ajax({
				type: 'POST',
				url: '???',
				timeout: 1000,
				success: function(grid) {
					// Update Grid with new background colors if necessary.
					window.setTimeout(updateGrid, 1000);
				},
			});
		}
		
		/**
		 * Should update the locations of the Fixers, but it's just pseudo code at the moment.
		 */
		 function updateFixerLocations() {
			$.ajax({
				type: 'POST',
				url: '???',
				timeout: 1000,
				success: function(fixers) {
					// Update HTML fixer locations
					window.setTimeout(updateFixerLocations, 1000);
				},
			});
		}

		/**
		 * Should constantly call PHP function Fixer.move() to allow the Fixer to move. It's just pseudo code at the moment.
		 */
		function moveFixer() {
			$.ajax({
				type: 'POST',
				url: '???',
				timeout: 1000,
				success: function(fixer) {
					window.setTimeout(moveFixer, 1000);
				},
			});
		}

		updateTime();
		updateGrid();
		updateFixerLocations();
		moveFixer();
	});

    </script>
</html>