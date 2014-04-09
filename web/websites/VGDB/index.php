<?php
	include("includes/functions.php");
	session_start();
	connect_to_db();

	if (isset($_POST["submit"])) {
		$keyword = $_POST['search'];
		redirect_to("results.php?search=$keyword");
		//arcade_insert();
	}
?>

<!DOCTYPE html>
<head>
	<title>VGDB - The Online Video Game Database</title>
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

		<div>
			<form id="search" action="index.php" method="post">
				<input id="searchbar" type="text" name="search" placeholder="Search for a video game...">
				<div id="centersearchbutton">
					<input id="searchbutton" type="submit" name="submit" value="Go!">
				</div>
			</form>
		</div>

		<?php 
			include("includes/layout/footer.php");
		?>
		
	</div>
</body>
</html>