<?php 
    require_once("includes/functions.php");
    include("includes/layout/header.php");
    head("Hyakki's Corner");
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
?>

<?php
    include("includes/navigation.php");
?>


<div id="left_wrapper">
    <div id="announcements"> <!-- Announcements -->
        <b class="title"><div class="collapse_img"><img src="images/collapse.gif" alt="[-]" title="[-]"></div>Announcements</b>
        <article>
           Hello! Thank you for visiting Hyakki's Corner. The site is currently open to the public, however we are still
           working on the content and development of the website. We are working hard hoping that
           the website will be finished (at least, as far as content and user usability goes) by the end of August. This site
           will be dedicated to in-depth video game reviews, video game guides, video streaming by the admins, and user
           interactivity through forums, private messaging and public chat. Please come back soon!
       </article>
       
       <!--<article>
           (<i>Note:</i> The registration form works, but if you register an account between now and the official launch of the website,
           you would have to register your account again when the website is officially launched. Please don't register an account now.)
       </article>-->
       
       <article style="text-align: right"><i>Posted by <a class="text" href="http://www.hyakkiscorner.com/forum/member.php?action=profile&uid=4">Professor Ragna</a> (6/24/2013)</i></article>
    </div> <!-- Announcements --> 
    
    <div id="homelatestreviews">
        <b class="title"><div class="collapse_img"><img src="images/collapse.gif" alt="[-]" title="[-]"></div>Latest Reviews</b>
        <article>List of ~3 of the latest reviews submitted will go here.</article>
    </div>

    <div id="homelatestguides">
        <b class="title"><div class="collapse_img"><img src="images/collapse.gif" alt="[-]" title="[-]"></div>Latest Guides</b>
        <article>List of ~3 of the latest guides submitted will go here.</article>
    </div>

    <div id="updates"> <!-- Updates -->
        <b class="title"><div class="collapse_img"><img src="images/collapse.gif" alt="[-]" title="[-]"></div>Latest Revisions, Updates</b>
        <article>The following modifications have been made to the website in the past 30 days:
            <ol>
                <li>Wrote short bio for Professor Ragna under "Team" section.</li>
                <li>Modified features, images and styles for forum. <i>(7/11/2013)</i></li>
                <li>Modified styles and features for forum. <i>(7/10/2013)</i></li>
                <li>Modified console list in "Reviews" and "Guides" sections. <i>(7/2/2013)</i></li>
                <li>Modified color scheme. <i>(6/26/2013)</i></li>
                <li>Modified font and color gradient for main navigation bar. <i>(6/26/2013)</i></li>
                <li>Modified "Reviews" dropdown menu. <i>(6/26/2013)</i></li>
                <li>Modified "Guides" section. <i>(6/26/2013)</i></li>
                <li>Modified "Reviews" sections. <i>(6/26/2013)</i></li>
                <li>User registration and login forms completed on localhost. <i>(6/25/2013)</i></li>
                <li>Added "Chat" section on navigation bar <i>(6/24/2013)</i></li>         
            </ol>              
        </article>                 
    </div> <!-- Updates -->    
</div> <!-- Left Wrapper -->

<div id="right_wrapper">
    <div id="poll"> <!-- Poll -->
        <b class="title"><div class="collapse_img"><img src="images/collapse.gif" alt="[-]" title="[-]"></div>Daily Poll</b>
        <form id="formpoll" name="formpoll">
            <article>Which console are you looking forward to the most?</article>
            <input type="radio" id="favconsole" name="favxboxone" value="xboxone">Xbox One<br>
            <input type="radio" id="favconsole" name="favps4" value="ps4">PS4<br>
            <input type="submit" value="Submit">
        </form>
    </div> <!-- Poll --> 
    
    <div id="posts"> <!-- Posts -->
        <b class="title"><div class="collapse_img"><img src="images/collapse.gif" alt="[-]" title="[-]"></div>Latest Forum Posts</b>
        
        <div class="content_wrapper">
        <?php

            define("IN_MYBB", 1);
            require_once("/forum/global.php"); // Change this if needed
            $tlimit = 5; // How many titles you want
                    
            $query = $db->query("SELECT * FROM ".TABLE_PREFIX."threads ORDER BY `tid` DESC LIMIT $tlimit");
            
                $list = '';
                while($fetch = $db->fetch_array($query))
                {
                    $list .= "<li><a class=\"forum_post\" href=\"forum/showthread.php?tid={$fetch['tid']}\" target=\"_blank\">".htmlspecialchars_uni($fetch['subject'])."</a><br>";
                    $list .= "<span class=\"author\">By: <a href=\"http://www.hyakkiscorner.com/forum/member.php?action=profile&uid={$fetch['uid']}\">{$fetch['username']}</a></span></li>";
                }
                echo $list;
            ?>
        </div> <!-- Content -->        
    </div> <!-- Posts -->   
        
    <div id="twitter_feed"><!-- Twitter -->
        <b class="title"><div class="collapse_img"><img src="images/collapse.gif" alt="[-]" title="[-]"></div>Twitter Feed</b>
        <div class="content_wrapper">
            <div style="padding:0 10px 0 10px">
                <a class="twitter-timeline" href="https://twitter.com/HyakkisCorner" data-link-color="#FFFFFF" data-chrome="transparent" data-widget-id="358025612075601921"  data-tweet-limit="10">Tweets by @HyakkisCorner</a>           
                <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
            </div>
        </div>
    </div> <!-- Twitter --> 
</div> <!-- Right Wrapper -->
                                 
        
            
<?php
    include("includes/layout/footer.php");
?>
