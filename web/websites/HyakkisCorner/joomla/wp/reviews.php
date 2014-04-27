<?php
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
define('WP_USE_THEMES', FALSE);

/** Loads the WordPress Environment and Template */
require('./wp-blog-header.php');
?>


<?php 
    require_once("includes/functions.php");
    include("includes/layout/header.php");
    head("Archives");
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
    include("includes/side_navigation.php");
?>

<div id="topreviews">
    <b class="title">Top Reviews</b>
    <article>List of some of the top submitted reviews will be posted here.</article>
        <ul>
            <li><a href="#">Review #1</a></li>
            <li><a href="#">Review #2</a></li>
            <li><a href="#">Review #3</a></li>                      
        </ul>
</div>          


<div id="reviews"> <!-- Available Reviews -->
    <b>Available Reviews</b>
    <div class="gameList">
        <ul>
            <li class="game"><a href="#">Game Review #1</a></li>
            <li class="game"><a href="#">Game Review #2</a></li>
            <li class="game"><a href="#">Game Review #3</a></li>
            <li class="game"><a href="#">Game Review #4</a></li>
            <li class="game"><a href="#">Game Review #5</a></li>
        </ul>
    </div>
    <!--<ul>
        <h4><li class="console">PC <span class="gameText">(<span class="count"></span> reviews)</span></li></h4>
            <div class="gameList">
                <ul>
                    <li class="game"><a href="#">Review #1</a></li>
                    <li class="game"><a href="#">Review #2</a></li>
                    <li class="game"><a href="#">Review #3</a></li>
                    <li class="game"><a href="#">Review #4</a></li>
                </ul>
            </div>
        <h4><li class="console">Xbox One <span class="gameText">(<span class="count"></span> reviews)</span></li></h4>
            <div class="gameList">
                <ul>
                    <li class="game"><a href="#">Review #1</a></li>
                    <li class="game"><a href="#">Review #2</a></li>
                    <li class="game"><a href="#">Review #3</a></li>
                </ul>
            </div>           
        <h4><li class="console">Xbox 360 <span class="gameText">(<span class="count"></span> reviews)</span></li></h4>
            <div class="gameList">
                <ul>
                    <li class="game"><a href="#">Review #1</a></li>
                    <li class="game"><a href="#">Review #2</a></li>
                    <li class="game"><a href="#">Review #3</a></li>
                    <li class="game"><a href="#">Review #4</a></li>
                </ul>
            </div>         
        <h4><li class="console">PS4 <span class="gameText">(<span class="count"></span> reviews)</span></li></h4>  
            <div class="gameList">
                <ul>
                    <li class="game"><a href="#">Review #1</a></li>
                    <li class="game"><a href="#">Review #2</a></li>
                    <li class="game"><a href="#">Review #3</a></li>
                    <li class="game"><a href="#">Review #4</a></li>
                </ul>
            </div>                
        <h4><li class="console">PS3 <span class="gameText">(<span class="count"></span> reviews)</span></li></h4>
          
        <h4><li class="console">Wii U <span class="gameText">(<span class="count"></span> reviews)</span></li></h4>

        <h4><li class="console">Wii <span class="gameText">(<span class="count"></span> reviews)</span></li></h4>
            <div class="gameList">
                <ul>
                    <li class="game"><a href="#">Review #1</a></li>
                    <li class="game"><a href="#">Review #2</a></li>
                    <li class="game"><a href="#">Review #3</a></li>
                    <li class="game"><a href="#">Review #4</a></li>
                </ul>
            </div>          
        <h4><li class="console">3DS <span class="gameText">(<span class="count"></span> reviews)</span></li></h4>
            <div class="gameList">
                <ul>
                    <li class="game"><a href="#">Review #1</a></li>
                    <li class="game"><a href="#">Review #2</a></li>
                    <li class="game"><a href="#">Review #3</a></li>
                    <li class="game"><a href="#">Review #4</a></li>
                </ul>
            </div>           
        <h4><li class="console">DS <span class="gameText">(<span class="count"></span> reviews)</span></li></h4>
            <div class="gameList">
                <ul>
                    <li class="game"><a href="#">Review #1</a></li>
                    <li class="game"><a href="#">Review #2</a></li>
                    <li class="game"><a href="#">Review #3</a></li>
                    <li class="game"><a href="#">Review #4</a></li>
                </ul>
            </div>          
        <h4><li class="console">Vita <span class="gameText">(<span class="count"></span> reviews)</span></li></h4>   
                
        <h4><li class="console">PSP <span class="gameText">(<span class="count"></span> reviews)</span></li></h4>
            <div class="gameList">
                <ul>
                    <li class="game"><a href="#">Review #1</a></li>
                    <li class="game"><a href="#">Review #2</a></li>
                    <li class="game"><a href="#">Review #3</a></li>
                    <li class="game"><a href="#">Review #4</a></li>
                </ul>
            </div>
    </ul>-->
</div> <!-- Available Reviews -->

<div id="latestreviews"> <!-- Latest Reviews -->
    <b class="title">Latest submitted reviews</b>
    <article>The latest submitted reviews will go here.</article>
        <ul>
            <li><a href="#">Review #1</a></li>
            <li><a href="#">Review #2</a></li>
            <li><a href="#">Review #3</a></li>                      
        </ul>    
</div> <!-- Latest Reviews -->


<?php
    include("includes/layout/footer.php");
?>


