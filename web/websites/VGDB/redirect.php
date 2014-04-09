<?php
	include("includes/functions.php");
	session_start();
	
    if (isset($_GET['action'])) {
        
        $action = $_GET['action'];
        
        if ($action === "feedback") {
            $message = "Thank you for your feedback!";
        }
        
        else if ($action === "contact") {
            $message = "Thank you for your inquiry!";
        }
        
        else if ($action === "game") {
        	$message = "Thanks for submitting a new game entry! We will look over your submission and determine whether or not we will add your entry to the database. ";
        	$message .= "We will send you an e-mail following our decision or if we need more information.";
        }

        else if ($action === "developer") {
        	$message = "Thanks for submitting a new developer entry! We will look over your submission and determine whether or not we will add your entry to the database. ";
        	$message .= "We will send you an e-mail following our decision or if we need more information.";
        }

        else if ($action === "register") {
        	$message = "Thank you for registering on VGDB!";
        }

        else if ($action === "logout") {
        	$message = "You have been logged out.";
        }

    } else {
        redirect_to("./");
    }
?>

<script> // Redirects to home page after 5 seconds
    setTimeout(function() {
      window.location.replace("./");  
    }, 5000); 
</script>


<!DOCTYPE html>
<head>
	<title>VGDB - Thanks for your submission!</title>
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/main.css">
</head>

<body>
	<div id="wrapper">
		<!--<header>
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
		</header>-->

		<div class="content_padding white redirect">
			<p><?php echo $message?> You will now be directed to the home page... <br><br><br>
                If you are not redirected, click <a href="./" style="color:#FF0000">here</a>.
            </p>
		</div>

		<footer>
			<!--<ul>
				<li><a href="about.php">About</a></li> |
				<li><a href="staff.php">Staff</a></li> |
				<li><a href="feedback.php">Feedback</a></li> |
				<li><a href="contact.php">Contact Us</a></li> |
				<li><a href="register.php">Register</a></li> |
				<li><a href="login.php">Login</a></li> 
			</ul>-->

			<article id="copyright">Copyright &copy; 2013</article>
		</footer>
	</div>
</body>
</html>