<?php
    require_once("../includes/functions.php");
    require_once("../database/login.php");
    include("../includes/layout/user_header.php");
    head("Activate Account");
    include("../includes/search.php");
    
    session_start();
    if (logged_in()) { // Checks to see if user is logged in
        echo "<div id =\"welcome\">";
        //echo "<div id =\"accounts\">";
        echo "<span class=\"text\">Hello, " . $_SESSION["username"] . "</span>";
        echo "<a href=\"logout.php\" class=\"text\">Logout</a>";
        echo "</div>";
    } else { 
        include("../includes/easy_login.php");
    }
    
    include("../includes/logo.php");
    include("../includes/navigation.php");
        
    if (isset($_GET["user"]) && isset($_GET["code"])) { // User clicked on link from confirmation email 
        $username = $_GET["user"];
        $code     = $_GET["code"];
        activate_user($username, $code);
    } else if (isset($_GET["error"])){ // Error when activating account
        echo "<article>Unable to activate account. This may be a result of not activating your account within 30 days upon registration. ";
        echo "Please register again <a href=\"../register.php\" style=\"color:#33CCFF\">here</a>, or contact our support team at ";
        echo "<span style=\"color:#33CCFF\">support@hyakkiscorner.com</span> to activate your account for you if you think this isn't the case.</article>";
    } else { // User not clicking activate link from e-mail
        redirect_to("../");
    }
    
    include("../includes/layout/user_footer.php");
?>