<footer>
	<ul>
		<li><a href="about.php">About</a></li> |
		<li><a href="staff.php">Staff</a></li> |
		<li><a href="feedback.php">Feedback</a></li> |
		<li><a href="contact.php">Contact Us</a></li> |
		
		<?php 
			if (logged_in()) {
				$userid = $_SESSION['userid'];
				echo "<li><a href=\"profile.php?userid=$userid\">My Account</a></li>";
			} else {
				echo "<li><a href=\"register.php\">Register</a></li> | ";
				echo "<li><a href=\"login.php\">Login</a></li>";
			}
		?>
		
	</ul>

	<article id="copyright">Copyright &copy; 2013</article>
</footer>