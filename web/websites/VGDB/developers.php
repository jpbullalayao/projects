<?php 
	include("includes/functions.php");
	connect_to_db();
	session_start();
?>

<!DOCTYPE html>
<head>
	<title>VGDB - Developers</title>
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
			<h2 class="center_text">Indie Developers</h2> 
			<p class="center_text">Are you an independent developer? Submit your information <a href="addDeveloper.php">here</a>!</p>

			<div class="developers center_developer">
				<table>
					<th><b>Developer</b></th>
					<th class="year"><b>Year</b></th>
					<?php 
						get_developers_table();
					?>
				</table>
			</div> <!-- Developers -->
		</div> <!-- Content -->

		<?php 
			include("includes/layout/footer.php");
		?>
		
	</div>
</body>
</html>