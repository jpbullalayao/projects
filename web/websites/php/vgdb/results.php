<?php 
	include("includes/functions.php");
	session_start();
	connect_to_db();

	if (isset($_GET['search'])) {
		$keyword = $_GET['search'];
	} else {

		/* Redirect to home page */
		redirect_to("./");
	}
?>

<!DOCTYPE html>
<head>
	<title>VGDB - Search Results</title>
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
			<?php 
				find_game($keyword);
			?>
		</div>

		<?php 
			include("includes/layout/footer.php");
		?>
		
	</div>
</body>
</html>