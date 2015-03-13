<?php 
	include("includes/functions.php");
	session_start();
	connect_to_db();

	/*snes_insert();
	nes_insert();
	ps1_insert();
	gameboy_insert();
	dreamcast_insert();
	windows_insert();*/
	//arcade_insert();
?>

<!DOCTYPE html>
<head>
	<title>VGDB - Users</title>
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
			<h2 class="center_text">Registered Users</h2>
			<?php 
				get_users_table();
			?>
		</div>

		<?php 
			include("includes/layout/footer.php");
		?>

	</div>
</body>
</html>