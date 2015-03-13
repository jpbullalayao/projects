<?php 
	include("includes/functions.php");
	session_start();

	if (isset($_POST['submit'])) {
		$errors = array();
		$from = $_POST['email'];
		$gname = $_POST['gname'];
		$gyear = $_POST['gyear'];
		$dname = $_POST['dname'];
		$publisher = $_POST['publisher'];
		$othergenrefield = $_POST['othergenrefield'];
		$otherplatformfield = $_POST['otherplatformfield'];
		$notes = $_POST['notes'];

		// Mail variables 
		$to = "air.jourdan21@gmail.com";
		$subject = "A user has submitted a new video game entry";
		$message = "From: $from\n";
		$message .= "Game: $gname\n";
		$message .= "Year: $gyear\n";
		$message .= "Developer: $dname\n";
		$message .= "Publisher: $publisher\n";
		$message .= "Genre: ".print_genres()."\n";
		$message .= "Platforms: ".print_platforms()."\n";
		$message .= "Notes: $notes\n";

		if (!$from) {
			$errors['email'] = "Please input your email.";
		}

		if (!$gname) {
			$errors['gname'] = "Please input the name of the video game.";
		}

		if (!is_numeric($gyear) || (int) $gyear < 0) {
			$errors['gyear'] = "Please specify a valid year.";
		}

		if (!check_platform_field()) {
			$errors['platform'] = "Please specify a platform.";
		}

		if (empty($errors)) {
			email($to, $from, $subject, $message);

			$to = "psvillanueva@dons.usfca.edu";
			email($to, $from, $subject, $message);

			$to = "wteh@dons.usfca.edu";
			email($to, $from, $subject, $message);
			redirect_to("redirect.php?action=game");
		}

	} else {
		$email = "";
		$gname = "";
		$gyear = "";
		$dname = "";
		$publisher = "";
		$othergenrefield = "";
		$otherplatformfield = "";
		$notes = "";
	}
?>

<!DOCTYPE html>
<head>
	<title>VGDB - Add a Video Game</title>
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/main.css">
	<link rel="icon" type="image/ico" href="images/favicon.ico">
</head>

<body>
	<div id="wrapper">
		<header>
			<nav class="white">
				<ul>
					<li><a href="index.php">Home</a></li>
					<li><a href="games.php">Games</a></li>
					<li><a href="developers.php">Developers</a></li>
					<li><a href="platforms.php">Platforms</a></li>
					<li><a href="members.php">Members</a></li>
				</ul>
			</nav>
			
			<div id="banner">
				<h1>VGDB</h1>

				<p id="description">
					The online Video Game Database.
				</p>
			</div>
		</header>

		<div class="content_padding white">
			<h2 class="center_text">Submit a New Video Game</h2>
			<p>Are you a developer trying to get your game out there? Are we missing any games? Submit new video game entries here!</p><br>
			<p><i>Note: Video game submissions will be reviewed and confirmed by admins. Users who submit new video games may be e-mailed
				for further confirmation.</i></p><br>

			<hr>

			<form action="addGame.php" method="post">
				<?php 
					if (!empty($errors)) {
		                fix_errors();
		            }
				?>
				<label>
					<?php 
						check_errors('email');
					?>
					E-mail: </label><input type="text" name="email" value="<?php echo $from ?>"><br><br>
				<label>
					<?php
						check_errors('gname');
					?>
					Video Game: </label><input type="text" name="gname" value="<?php echo $gname ?>"><br><br>
				<label>
					<?php
						check_errors('gyear');
					?>
					Year: </label><input type="text" name="gyear" value="<?php echo $gyear ?>"><br><br>
				<label>Developer: </label><input type="text" name="dname" value="<?php echo $dname ?>"><br><br>
				<label>Publisher: </label><input type="text" name="publisher" value="<?php echo $publisher ?>"><br><br>
				<label>Genre: </label><br>

					<div class="float_left first_genre_col">
						<input type="checkbox" name="rpg" value="RPG" <?php check_rpg() ?>>Role-Playing<br>
						<input type="checkbox" name="action" value="Action" <?php check_action() ?>>Action<br>
						<input type="checkbox" name="adventure" value="Adventure" <?php check_adventure() ?>>Adventure<br>
					</div>

					<div class="float_left second_genre_col">
						<input type="checkbox" name="shooter" value="Shooter" <?php check_shooter() ?>>Shooter<br>
						<input type="checkbox" name="simulation" value="Simulation" <?php check_simulation() ?>>Simulation<br>
						<input type="checkbox" name="strategy" value="Strategy" <?php check_strategy() ?>>Strategy<br>
					</div>

					<input type="checkbox" name="othergenre" value="Othergenre" <?php check_othergenre() ?>>Other (please specify):
					<input type="text" name="othergenrefield" value="<?php echo $othergenrefield ?>">
					<div class="clear_floats"></div> <!-- Only used to clear floats -->

				<br>	
				<label>
					<?php 
						check_errors('platform');
					?> Platforms: </label><br>

					<div class="float_left first_platform_col">
						<input type="checkbox" name="pc" value="PC" <?php check_pc() ?>>PC<br>
						<input type="checkbox" name="xbox" value="Xbox" <?php check_xbox() ?>>Xbox<br>
						<input type="checkbox" name="x360" value="Xbox 360" <?php check_x360() ?>>Xbox 360<br>
						<input type="checkbox" name="x1" value="Xbox One" <?php check_x1() ?>>Xbox One<br>
					</div> 

					<div class="float_left second_platform_col">
						<input type="checkbox" name="ps" value="PS" <?php check_ps() ?>>PlayStation<br>
						<input type="checkbox" name="ps2" value="PS2" <?php check_ps2() ?>>PlayStation 2<br>
						<input type="checkbox" name="ps3" value="PS3" <?php check_ps3() ?>>PlayStation 3<br>
						<input type="checkbox" name="ps4" value="PS4" <?php check_ps4() ?>>PlayStation 4<br>
					</div>

					<div class="float_left third_platform_col">
						<input type="checkbox" name="wii" value="Wii" <?php check_wii() ?>>Nintendo Wii<br>
						<input type="checkbox" name="wiiu" value="Wii U" <?php check_wiiu() ?>>Nintendo Wii U<br>
						<input type="checkbox" name="ds" value="DS" <?php check_ds() ?>>Nintendo DS<br>
						<input type="checkbox" name="3ds" value="3DS" <?php check_3ds() ?>>Nintendo 3DS<br>
					</div>

					<input type="checkbox" name="otherplatform" value="otherplatform" <?php check_otherplatform() ?>> Other (please specify):
					<input type="text" name="otherplatformfield" value="<?php echo $otherplatformfield ?>">
					<div class="clear_floats"></div> <!-- Only used to clear floats -->

				<br>
				Notes:<br>
				<textarea class="notes" name="notes"><?php echo $notes ?></textarea><br>

				<input type="submit" name="submit" value="Submit">

			</form>
		</div>

		<?php 
			include("includes/layout/footer.php");
		?>
		
	</div>
</body>
</html>