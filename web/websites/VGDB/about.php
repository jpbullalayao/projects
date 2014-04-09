<?php 
	include("includes/functions.php");
	session_start();
?>

<!DOCTYPE html>
<head>
	<title>VGDB - About Us</title>
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
			<h2 class="center_text">About VGDB</h2>
			<p>VGDB is an organization consisting of three gamers aiming to establish a means for independent video game
			developers to get their names and video games out there to the public domain. By doing so, we allow for
			video game developers and games to be discovered through a reference database that includes the significant
			details of not just their own video games, but also most video games that have ever been published.</p>
		</div>

		<?php 
			include("includes/layout/footer.php");
		?>
		
	</div>
</body>
</html>