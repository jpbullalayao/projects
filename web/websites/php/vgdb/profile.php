<?php 
	include("includes/functions.php");
	connect_to_db();
	session_start();

	if (isset($_GET["userid"])) {
		$userid   = $_GET["userid"];
		$username = get_username($userid);
		$email    = get_email($userid);
	} else {
		redirect_to('./');
	}
?>

<!DOCTYPE html>
<head>
	<title>VGDB - <?php echo $username ?>'s Profile</title>
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
			<h2 class="center_text"><?php echo $username?>'s Profile</h2>

			<b>E-mail:</b> <?php echo $email ?><br><br>

			<b><?php echo get_num_games_favorited($userid); ?> Games Favorited:</b><br>
			
			<?php
				// call function to get all favorited games
				get_games_favorited($userid);
			?>

			<br>

			<b><?php echo get_num_games_rated($userid); ?> Games Rated:</b><br>
			<?php
				// call function to get all games rated + urating
				get_games_rated($userid);
			?>

		</div>

		<?php 
			include("includes/layout/footer.php");
		?>
		
	</div>
</body>
</html>