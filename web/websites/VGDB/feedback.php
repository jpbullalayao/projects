<?php
	include("includes/functions.php");
	session_start();

	if (isset($_POST['submit'])) {
        $errors  = array();
        $from   = $_POST['email'];
        $message = $_POST['feedback'];
        $to = "air.jourdan21@gmail.com";
        $subject = "A user has sent us feedback on VGDB";        
                
        if (!$from) { // User didn't specify an e-mail
            $errors['email'] = "Please input your e-mail.";
        } else { 
            email($to, $from, $subject, $message);

            $to = "psvillanueva@dons.usfca.edu";
            email($to, $from, $subject, $message);

            $to = "wteh@dons.usfca.edu";
            email($to, $from, $subject, $message);
            redirect_to("redirect.php?action=feedback");
        }
    } else {
        $message = "";
    }
?>

<!DOCTYPE html>
<head>
	<title>VGDB - Feedback</title>
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
			<fieldset id="feedback" class="content_padding">
				<legend class="center_text"><h2>Feedback</h2></legend>
				<p>VGDB appreciates any and all feedback regarding our website. If you have any suggestions or feedback, let us know!</p><br>
				<?php
		            if (!empty($errors)) {
		                fix_errors();
		            }
		        ?>
				<form action="feedback.php" method="post">
					<label>
						<?php
                			check_errors('email');
            			?> E-mail: </label><input type="text" name="email"><br><br>
					<label>Feedback: </label><br>
						<textarea class="notes" name="feedback"><?php echo $message?></textarea><br>

					<input type="submit" name="submit" value="Submit">
				</form>
			</fieldset>
		</div>

		<?php 
			include("includes/layout/footer.php");
		?>
		
	</div>
</body>
</html>