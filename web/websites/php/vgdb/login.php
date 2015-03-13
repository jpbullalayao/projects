<?php 
	include("includes/functions.php");
	session_start();
	connect_to_db();

	if (isset($_POST['submit'])) {
		$errors   = array();
		$username = $_POST['username'];
		$password = $_POST['password'];

		$success = try_login($username, $password);

		if ($success) {
			$_SESSION['username'] = $username;
			$_SESSION['userid'] = get_userid($username);
			redirect_to('./');
		} 
	} else {
		$username = "";
	}
?>

<!DOCTYPE html>
<head>
	<title>VGDB - Login</title>
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
			<fieldset id="login" class="content_padding">
				<legend class="center_text"><h2>Login</h2></legend>
				
				<?php 
					if (!empty($errors)) {
		                fix_errors();
		            }
				?>

				<form action="login.php" method="post">
					<label>Username: </label><input type="text" name="username" value="<?php echo $username?>"><br><br>
					<label>Password: </label><input type="password" name="password"><br><br>
					<input type="submit" name="submit" value="Login">
				</form>
			</fieldset>			
		</div>

		<?php 
			include("includes/layout/footer.php");
		?>
		
	</div>
</body>
</html>