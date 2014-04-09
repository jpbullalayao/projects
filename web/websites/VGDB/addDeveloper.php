<?php 
	include("includes/functions.php");
	session_start();

	if (isset($_POST['submit'])) {
		$errors  = array();
		$from    = $_POST['email'];
		$dname   = $_POST['dname'];
		$website = $_POST['website'];
		$founded = $_POST['founded'];
		$city    = $_POST['city'];
		$country = $_POST['country'];
		$founder = $_POST['founder'];
		$notes   = $_POST['notes'];

		// Mail variables 
		$to = "air.jourdan21@gmail.com";
		$subject = "A user has submitted a new developer entry";
		$message = "From: $from\n";
		$message .= "Developer: $dname\n";
		$message .= "Website: $website\n";
		$message .= "Founded: $founded\n";
		$message .= "City: $city\n";
		$message .= "Country: $country\n";
		$message .= "Founder: $founder\n";
		$message .= "Notes: $notes\n";
		$message .= "Query: \n\nINSERT INTO Developers(dname, website, email, founded, city, country, founder) ";
		$message .= "VALUES(\"$dname\", \"$website\", \"$from\", $founded, \"$city\", \"$country\", \"$founder\");";

		if (!$from) {
			$errors['email'] = "Please input your email.";
		}

		if (!$dname) {
			$errors['dname'] = "Please input the developer name.";
		}

		if (!is_numeric($founded) || (int)$founded < 0 || (int)$founded > 2013) {
			$errors['founded'] = "Please input a valid year.";
		}

		// User submitted an email and developer name
		if ($from and $dname) {
			email($to, $from, $subject, $message);

			$to = "psvillanueva@dons.usfca.edu";
			email($to, $from, $subject, $message);

			$to = "wteh@dons.usfca.edu";
			email($to, $from, $subject, $message);
			redirect_to("redirect.php?action=developer");
		}

	} else {
		$email   = "";
		$dname   = "";
		$website = "";
		$founded = "";
		$city    = "";
		$country = "";
		$founder = "";
		$notes   = "";
	}
?>

<!DOCTYPE html>
<head>
	<title>VGDB - Add a Developer</title>
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
			<h2 class="center_text">Add a Developer</h2>
			<p>Are you an independent developer who is looking to get your name out there? Do you have your own group of developers that is developing 
			   games together? Submit your information here! Upon acceptance, you will be added to our database.</p><br>
			<p><i>Note: Developer submissions will be reviewed and confirmed by admins. Users who submit developer entries may be e-mailed
				for further confirmation.</i></p><br> 

			<hr>

			<form action="addDeveloper.php" method="post">
				<?php 
					if (!empty($errors)) {
		                fix_errors();
		            }
				?>

				<label>
					<?php 
						check_errors('email');
					?> E-mail: </label><input type="text" name="email" value="<?php echo $from ?>"><br><br>
				<label>
					<?php
						check_errors('dname');
					?> Developer Name: </label><input type="text" name="dname" value="<?php echo $dname ?>"><br><br>
				<label>Website: </label><input type="text" name="website" value="<?php echo $website ?>"><br><br>
				<label>
					<?php
						check_errors('founded');
					?>
					Year Founded: </label><input type="text" name="founded" value="<?php echo $founded ?>"><br><br>
				<label>City: </label><input type="text" name="city" value="<?php echo $city ?>"><br><br>
				<label>Country: </label><input type="text" name="country" value="<?php echo $country ?>"><br><br>
				<label>Founder: </label><input type="text" name="founder" value="<?php echo $founder ?>"><br><br>
				

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