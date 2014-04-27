<?php 
    require_once("includes/functions.php");
    include("includes/layout/header.php");
    head("Sitemap");
    include("includes/search.php");
    
    session_start();
    if (logged_in()) { // Checks to see if user is logged in
        echo "<div id =\"welcome\">";
        //echo "<div id =\"accounts\">";
        //echo "<img src=\"#\" width=15 height=15>";
        echo "<span class=\"text\" style=\"padding-right:0px\">" . $_SESSION["username"] . "</span>";
        //echo "<div id=\"triangle\">";
        //echo "  <a href=\"#\"><img src=\"images/triangle.png\" id = \"triangle\" width=13 height=7></a>";
        //echo "  <li class=\"dropdown\">hello</li>";
        //echo "</div>";
        echo "<a href=\"user/dashboard.php\"><span class=\"text\">Dashboard</span></a>";
        //echo "<a href=\"logout.php\" class=\"text\">Logout</a>";
        echo "</div>";
    } else { 
        include("includes/easy_login.php");
    }
    
    include("includes/logo.php");
    include("includes/navigation.php");
?>
            
            <div id="sitemap"> <!-- Sitemap -->
                <b>Sitemap</b>
                <ul>
                    <li><a href="/">Home</a>
                    <li><a href="forums.php">Forum</a></li>
                    <li><a href="guides.php">Guides</a></li>
                    <li><a href="overview.php">Reviews</a></li>
                        <ul>
                            <li><a href="criteria.php">Criteria</a></li>
                            <li><a href="reviews.php">Archives</a></li>
                        </ul>
                    <li><a href="stream.php">Stream</a></li>
                    <li><a href="chat.php">Chat</a></li>
                </ul>
            </div> <!-- Sitemap -->
            
            
<?php
    include("includes/layout/footer.php");
?>