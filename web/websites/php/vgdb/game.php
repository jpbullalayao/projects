<?php 
	include("includes/functions.php");
	session_start();
	connect_to_db();

	if (isset($_GET['gid'])) {
		$gid = $_GET['gid'];
		$userid = $_SESSION['userid'];

		/* Checks to see if user already rated and/or favorited the game already */

		if (logged_in()) {
			$permission_to_rate = check_if_user_rated($gid, $userid);
			$permission_to_favorite = check_if_user_favorited($gid, $userid);

			/* User tried to rate game */
			if (isset($_POST['submit_rating'])) {
				$urating = $_POST['rating'];

				if ($permission_to_rate) {
					insert_rating($gid, $userid, $urating);
				}
			}

			/* User tried to favorite game */
			if (isset($_POST['submit_favorite'])) {

				if ($permission_to_favorite) {
					insert_favorite($gid, $userid);
				}
			}
		}

		$row = get_game_info($gid);

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
				if (isset($_GET['gid'])) {
					$gid       = $_GET['gid'];
					//$row = get_platform_info($pid);
					$gname     = $row['gname'];
					$gyear     = $row['gyear'];
					$genre     = $row['genre'];
					$publisher = $row['publisher'];
					$image 	   = $row['image'];
					$grating   = $row['grating'];
					$platform  = $row['platform'];

					echo "<h2 class=\"center_text\">$gname</h2>";
					//echo "<b>Platform: </b>$pname<br><br>";
					echo "<div class=\"game_info\">";
					echo "<b>Year: </b>$gyear<br><br>";
					echo "<b>Platform: </b>$platform<br><br>";
					echo "<b>Genre(s): </b>$genre<br><br>";
					echo "<b>Publisher: </b>$publisher<br><br>";
					echo "<b>Rating: </b>$grating<br><br>";

					if (logged_in()) {

						/* Check again if user is allowed to rate */
						$permission_to_rate = check_if_user_rated($gid, $userid);
						$permission_to_favorite = check_if_user_favorited($gid, $userid);

						/* User already rated game */
						if (!$permission_to_rate) {
							echo "<b>You have already rated this game.</b><br><br>";
						}

						/* User already favorited game */
						if (!$permission_to_favorite) {
							echo "<b>You have already favorited this game.</b><br><br>";
						}
					}
					
					echo "</div>";

					if ($image != null) {
						echo "<div class=\"game_image\">";
						echo "<img src=\"$image\" alt=\"$gname\">";
						echo "</div>";
					}
				} else {
					echo "";
				}
			?>

			<div class="clear_floats"></div>

			<?php
				if (logged_in()) {
					if ($permission_to_rate) {
						echo "Rate this game!<br>";
						echo "<form action=\"game.php?gid=$gid\" method=\"post\">";
						echo "<input type=\"radio\" name=\"rating\" value=\"1\">1";
						echo "<input type=\"radio\" name=\"rating\" value=\"2\">2";
						echo "<input type=\"radio\" name=\"rating\" value=\"3\">3";
						echo "<input type=\"radio\" name=\"rating\" value=\"4\">4";
						echo "<input type=\"radio\" name=\"rating\" value=\"5\">5";
						echo "<input type=\"radio\" name=\"rating\" value=\"6\">6";
						echo "<input type=\"radio\" name=\"rating\" value=\"7\">7";
						echo "<input type=\"radio\" name=\"rating\" value=\"8\">8";
						echo "<input type=\"radio\" name=\"rating\" value=\"9\">9";
						echo "<input type=\"radio\" name=\"rating\" value=\"10\">10";
						echo "<input type=\"submit\" name=\"submit_rating\" value=\"Go!\"><br><br>";
					} 

					if ($permission_to_favorite) {
						echo "Favorite this game?<br>";
						echo "<form action=\"game.php?gid=$gid\" method=\"post\">";
						echo "<input type=\"radio\" name=\"favorite\" value=\"yes\">Yes";
						echo "<input type=\"submit\" name=\"submit_favorite\" value=\"Go!\"><br><br>";
					}
				}

			?>

		</div>

		<?php 
			include("includes/layout/footer.php");
		?>
		
	</div>
</body>
</html>