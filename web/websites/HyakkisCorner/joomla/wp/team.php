<?php 
    require_once("includes/functions.php");
    include("includes/layout/header.php");
    head("Executive Team");
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

<div id="team">
    <b>Meet the Staff</b>
    
    <div class="team_member">
        <article><i>Jourdan Bul-lalayao (<a href="http://www.hyakkiscorner.com/forum/member.php?action=profile&uid=4">Professor Ragna</a>) - Web Designer and Developer</i> <br><br>
        Jourdan develops and designs the front-end and back-end of the 
        website and has been with the team since the beginning of its production in May 2013. With only one year of web design and development experience, he aims to deliver to users a top-notch web application which would allow
        for them to interact, connect, share, and review content produced by fellow users; while, at the same time, taking strides to further learn how 
        to deliver useful and professional web applications. His interests lie in several fields of computer science, which
        include software development, video game development and web development. His ultimate goal is to develop quality video games and useful
        applications in the future. Aside from programming, he has hobbies in anime, video games, writing, graphic design, sports, and the Japanese 
        language. He is currently a student at the University of San Francisco, majoring in Computer Science.</article>   
    </div> 
  
</div> <!-- Team -->

<?php
    include("includes/layout/footer.php");
?>