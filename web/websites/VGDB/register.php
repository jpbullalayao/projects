<?php 
	include("includes/functions.php");
	connect_to_db();
	session_start();

	if (isset($_POST['submit'])) {
		$errors = array();
		$username = $_POST['username'];
		$email 	  = $_POST['email'];
		$password = $_POST['password'];
		$confirm  = $_POST['confirm'];

		/* Validations */
		check_username($username);
		check_email($email);
		check_password($password, $confirm);

		if (empty($errors)) {
			$password = password_encrypt($password);
			register_user($username, $email, $password);
			$_SESSION['username'] = $username;
			$_SESSION['userid'] = get_userid($username);
			redirect_to("redirect.php?action=register");
		}

	} else {
		$username = "";
		$email = "";
	}
?>

<!DOCTYPE html>
<head>
	<title>VGDB - Register</title>
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
			<fieldset id="register" class="content_padding">
				<legend class="center_text"><h2>Register</h2></legend>
				<p>Register now for an account at VGDB!</p><br>
				<p>With an account at VGDB, you gain access to the following: <p>
					<ol>
						<li>Contributing to the Database</li>
						<li>Commenting on and rating video games</li>
						<li>Showcasing your taste in video games</li>
						<li>Being discovered as an indie developer</li>
					</ol><br>

				<?php 
					if (!empty($errors)) {
		                fix_errors();
		            }
				?>
					
				<form action="register.php" method="post">
					<label>
						<?php 
							check_errors('username');
						?>
						Username: </label><input type="text" name="username" value="<?php echo $username?>"><br><br>
					<label>
						<?php
							check_errors('email');
						?>
						E-mail: </label><input type="text" name="email" value="<?php echo $email?>"><br><br>
					<label>
						<?php 
							check_errors('password');
						?>
						Password: </label><input type="password" name="password"><br><br>
					<label>Confirm Password: </label><input type="password" name="confirm"><br><br>
					<input type="submit" name="submit" value="Register">
				</form>
			</fieldset>			
		</div>

		<?php 
			include("includes/layout/footer.php");
		?>
		
	</div>
</body>
</html>