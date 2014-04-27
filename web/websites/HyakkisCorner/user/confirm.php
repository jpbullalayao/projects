<?php
    require_once("../includes/functions.php");
    include("../includes/layout/user_header.php");
    head("Register");
    include("../includes/logo.php");
    include("../includes/user_navigation.php");    
    
    if (isset($_GET["email"])) { // User just registered
        $email = $_GET["email"];
    } else { // Not accessed from registration page
        redirect_to("../"); // Redirect to home page
    }
?>

<article>
    A confirmation e-mail was just sent to <?php echo "<span style=\"color:#33CCFF\">$email</span>" ?>.<br><br> 
    Please log in to your e-mail and follow the instructions to activate
    your account. Your activation code will expire after 30 days. If you don't see an e-mail in your inbox, be sure to check your spam folder!<br><br>
    
    If you did not receive an e-mail, please contact our support team, specifying your username, at <span style="color:#33CCFF">support@hyakkiscorner.com.</span><br><br>
    
    We hope you enjoy your stay! 
</article>

<?php
    include("../includes/layout/user_footer.php")
?>