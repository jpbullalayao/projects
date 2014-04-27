<?php
    $email_to = "air.jourdan21@gmail.com";
	$email_subject = "Signup";
	$name = $_POST['name'];
	$email = $_POST['email'];
	$email_message = "e-list signup";
	$headers = 'From: '.$email."\r\n";
	$sent = mail($email_to, $email_subject, $email_message, $headers);
	
	if ($sent) {
		print "Sent successfully";
	} else {
		print "not sent";
	}
?>
	<h3>Thanks for signing up! Return to our <a href="index.html"> home</a> page.</h3>.
