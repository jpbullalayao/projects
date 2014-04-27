<?php
    require_once("includes/functions.php");
    include("includes/layout/header.php");
    
    if (isset($_GET['action'])) {
        
        $action = $_GET['action'];
        
        if ($action === "feedback") {
            head("Feedback");
            $message = "Thank you for your feedback!";
        }
        
        if ($action === "contact") {
            head("Contact Us");
            $message = "Thank you for your inquiry!";
        }
        
        if ($action === "accounts") {
            head("Account Support");
            $message = "Thank you for informing us about any problems regarding your account. Please allow 24-48 hours for one of our
                        administrators to contact you via e-mail and private message. We are sorry for any inconveniences regarding
                        your account.";
        }
        
		if ($action === "comment_error") {
			head("Error");
			$message = "There was an error when posting your comment. Please contact one of the admins about this problem so we can fix this
			           as soon as possible. We are very sorry for the inconvenience.";
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
            <div id="redirect">
                <article>
                    <?php echo $message?> You will now be directed to the home page... <br><br><br>
                    If you are not redirected, click <a href="./" style="color:#33CCFF">here</a>.
                </article>
            </div> <!-- Redirect -->
        </div> <!-- Wrapper -->
    </body>
</html>