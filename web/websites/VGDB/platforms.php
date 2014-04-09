<?php 
	include("includes/functions.php");
	session_start();
	connect_to_db();

	if (isset($_POST["submit"])) {
		$sort_by = $_POST["sort_by"];
	} else {
		$sort_by = '';
	}
?>

<!DOCTYPE html>
<head>
	<title>VGDB - Platforms</title>
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
			<h2 class="center_text">Platforms</h2>

			<div class="center_sort_platform">
				<form action="platforms.php" method="post">
					Sort by...
					<select name="sort_by">
						<option <?php if ($sort_by === "platform") echo "selected" ?> value="platform">Platform</option>
						<option <?php if ($sort_by === "year") echo "selected" ?> value="year">Year</option>
					</select>
					<input type="submit" name="submit" value="Go!"><br>
				</form>
			</div>

			<?php 
				if (isset($_POST["submit"])) {
					if ($sort_by === "platform") {
						sort_by_pname();
					} else {
						sort_by_pyear();
					}
				} else {
					get_platforms_table();
				}
			?>

		</div>

		<?php 
			include("includes/layout/footer.php");
		?>
		
	</div>
</body>
</html>