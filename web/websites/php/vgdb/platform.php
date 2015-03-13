<?php 
	include("includes/functions.php");
	session_start();
	connect_to_db();

	if (isset($_GET['pid'])) {
		$pid = $_GET['pid'];
		$row = get_platform_info($pid);
	} else {
		$pid = "";
		$row = array();
	}

?>

<!DOCTYPE html>
<head>
	<title>VGDB - Platform</title>
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
				if (isset($_GET['pid'])) {
					$pid = $_GET['pid'];
					$row = get_platform_info($pid);
					$pname = $row['pname'];
					$pyear = $row['pyear'];

					echo "<h2 class=\"center_text\">$pname</h2>";
					echo "<b>Platform: </b>$pname<br><br>";
					echo "<b>Year: </b>$pyear<br><br>";
					echo "<b>Internet: </b>".check_internet($row)."<br><br>";

					echo "<h3 class=\"center_text\">".get_num_games($pid)." Games published on $pname:</h3>";

					/* Print all games on this platform */
					if ($pid == 2) {
						include("nes.php");
					}

					else if ($pid == 3) {
						include("snes.php");
					}

					else if ($pid == 8) {
						include("gameboy.php");
					}

					else if ($pid == 13) {
						include("ps1.php");
					}

					else if ($pid == 28) {
						include("arcade.php");
					}

					else {
						get_gid_by_pid($pid);
					}

				} else {
					echo "";
				}
			?>
		</div>

		<?php 
			include("includes/layout/footer.php");
		?>
		
	</div>
</body>
</html>