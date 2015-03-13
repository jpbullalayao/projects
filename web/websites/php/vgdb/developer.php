<?php 
	include("includes/functions.php");
	connect_to_db();
	session_start();

	if (isset($_GET['did'])) {
		$did     = $_GET['did'];
		$row     = get_developer_info($did);
		$dname   = $row['dname'];
		$website = $row['website'];
		$email   = $row['email'];
		$founded = $row['founded'];
		$city    = $row['city'];
		$country = $row['country'];
		$founder = $row['founder'];
	} else {
		/* Redirect to home page */
		redirect_to("./");
	}
?>

<!DOCTYPE html>
<head>
	<title>VGDB - <?php echo $dname; ?></title>
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
			<h2 class="center_text"><?php echo $dname; ?></h2> 

			<b>Developer: </b><?php echo $dname; ?><br><br>
			<b>Website: </b><?php echo $website; ?><br><br>
			<b>E-mail: </b><?php echo $email; ?><br><br>
			<b>Founded: </b><?php echo $founded; ?><br><br>
			<b>City: </b><?php echo $city; ?><br><br>
			<b>Country: </b><?php echo $country; ?><br><br>
			<b>Founder: </b><?php echo $founder; ?><br><br>


			<h3 class="center_text"><?php echo get_num_games_developed($did); ?> Games Developed by <?php echo $dname; ?></h3>

			<div class="games center_game">
            	<table>
            		<th><b>Games</b></th>
            		<th class="year"><b>Year</b></th>
						<?php 
							/* Gets list of developed games */
							get_developed_games($did);
						?>
				</table>
			</div>
			
		</div> <!-- Content -->

		<?php 
			include("includes/layout/footer.php");
		?>
		
	</div>
</body>
</html>