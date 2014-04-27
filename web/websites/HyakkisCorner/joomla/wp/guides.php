<?php 
    require_once("includes/functions.php");
    include("includes/layout/header.php");
    head("Guides");
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

        
<div id="guides">
    <b class="title">Available Guides</b>
    <div class="gameList">
        <ul>
            <li class="game"><a href="#">Game Guide #1</a></li>
            <li class="game"><a href="#">Game Guide #2</a></li>
            <li class="game"><a href="#">Game Guide #3</a></li>
            <li class="game"><a href="#">Game Guide #4</a></li>
            <li class="game"><a href="#">Game Guide #5</a></li>
        </ul>
    </div>
    
    <!--<ul>
        <!--<h4><li class="console">PC <span class="gameText">(<span class="count"></span> guides)</span></li></h4>
            <div class="gameList">
                <ul>
                    <li class="game"><a href="#">Guide #1</a></li>
                    <li class="game"><a href="#">Guide #2</a></li>
                    <li class="game"><a href="#">Guide #3</a></li>
                    <li class="game"><a href="#">Guide #4</a></li>
                </ul>
            </div>
        
        <h4><li class="console">Xbox One <span class="gameText">(<span class="count"></span> guides)</span></li></h4>
            <div class="gameList">
                <ul>
                    <li class="game"><a href="#">Guide #1</a></li>
                    <li class="game"><a href="#">Guide #2</a></li>
                    <li class="game"><a href="#">Guide #3</a></li>
                    <li class="game"><a href="#">Guide #4</a></li>
                </ul>
            </div>            
        <h4><li class="console">Xbox 360 <span class="gameText">(<span class="count"></span> guides)</span></li></h4>
            <div class="gameList">
                <ul>
                    <li class="game"><a href="#">Guide #1</a></li>
                    <li class="game"><a href="#">Guide #2</a></li>
                    <li class="game"><a href="#">Guide #3</a></li>
                    <li class="game"><a href="#">Guide #4</a></li>
                </ul>
            </div>            
        <h4><li class="console">PS4 <span class="gameText">(<span class="count"></span> guides)</span></li></h4>  
            <div class="gameList">
                <ul>
                    <li class="game"><a href="#">Guide #1</a></li>
                    <li class="game"><a href="#">Guide #2</a></li>
                    <li class="game"><a href="#">Guide #3</a></li>
                    <li class="game"><a href="#">Guide #4</a></li>
                </ul>
            </div>                 
        <h4><li class="console">PS3 <span class="gameText">(<span class="count"></span> guides)</span></li></h4>
            <div class="gameList">
                <ul>
                    <li class="game"><a href="#">Guide #1</a></li>
                    <li class="game"><a href="#">Guide #2</a></li>
                    <li class="game"><a href="#">Guide #3</a></li>
                    <li class="game"><a href="#">Guide #4</a></li>
                </ul>
            </div>             
        <h4><li class="console">Wii U <span class="gameText">(<span class="count"></span> guides)</span></li></h4>
            <div class="gameList">
                <ul>
                    <li class="game"><a href="#">Guide #1</a></li>
                    <li class="game"><a href="#">Guide #2</a></li>
                    <li class="game"><a href="#">Guide #3</a></li>
                    <li class="game"><a href="#">Guide #4</a></li>
                </ul>
            </div>  
        <h4><li class="console">Wii <span class="gameText">(<span class="count"></span> guides)</span></li></h4>
            <div class="gameList">
                <ul>
                    <li class="game"><a href="#">Guide #1</a></li>
                    <li class="game"><a href="#">Guide #2</a></li>
                    <li class="game"><a href="#">Guide #3</a></li>
                    <li class="game"><a href="#">Guide #4</a></li>
                </ul>
            </div>             
        <h4><li class="console">3DS <span class="gameText">(<span class="count"></span> guides)</span></li></h4>
            <div class="gameList">
                <ul>
                    <li class="game"><a href="#">Guide #1</a></li>
                    <li class="game"><a href="#">Guide #2</a></li>
                    <li class="game"><a href="#">Guide #3</a></li>
                    <li class="game"><a href="#">Guide #4</a></li>
                </ul>
            </div>             
        <h4><li class="console">DS <span class="gameText">(<span class="count"></span> guides)</span></li></h4>
            <div class="gameList">
                <ul>
                    <li class="game"><a href="#">Guide #1</a></li>
                    <li class="game"><a href="#">Guide #2</a></li>
                    <li class="game"><a href="#">Guide #3</a></li>
                    <li class="game"><a href="#">Guide #4</a></li>
                </ul>
            </div>             
        <h4><li class="console">Vita <span class="gameText">(<span class="count"></span> guides)</span></li></h4>   
            <div class="gameList">
                <ul>
                    <li class="game"><a href="#">Guide #1</a></li>
                    <li class="game"><a href="#">Guide #2</a></li>
                    <li class="game"><a href="#">Guide #3</a></li>
                    <li class="game"><a href="#">Guide #4</a></li>
                </ul>
            </div>                  
        <h4><li class="console">PSP <span class="gameText">(<span class="count"></span> guides)</span></li></h4>
            <div class="gameList">
                <ul>
                    <li class="game"><a href="#">Guide #1</a></li>
                    <li class="game"><a href="#">Guide #2</a></li>
                    <li class="game"><a href="#">Guide #3</a></li>
                    <li class="game"><a href="#">Guide #4</a></li>
                </ul>
            </div> -->            
    <!--</ul>-->
</div>

<div id="topguides">
    <b class="title">Top Guides</b>
    <article>List of top guides will go here.</article>
</div>

<div id="latestguides">
    <b class="title">Latest Guide Submissions</b>
    <article>
        Latest guide submissions will go here.
    </article>
</div>  
        
<?php
    include("includes/layout/footer.php");
?>