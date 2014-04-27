<div class="content_clear_floats"></div> <!-- Needed to expand Content Wrapper, since sections inside are floated -->


</div> <!-- Content Container -->

            <footer>
                <div id="footnav"> <!-- Navigation -->
                    <b><li>Navigation</li></b>
                    <li><a href="./">Home</a></li>
                    <li><a href="forums.php">Forum</a></li>
                    <li><a href="guides.php">Guides</a></li>
                    <li><a href="overview.php">Reviews</a></li>
                    <li><a href="stream.php">Stream</a></li>
                    <li><a href="chat.php">Chat</a></li>
                </div> <!-- Navigation -->
                
                <div id="footacc"> <!-- Navigation -->
                    <b><li>Accounts</li></b>
                    
                    <?php
                        if (logged_in()) {
                            echo "<li><a href=\"dashboard.php\">Dashboard</a></li>";
                            echo "<li><a href=\"../logout.php\">Logout</a></li>";
                           
                        } else {
                            echo "<li><a href=\"register.php\">Register</a></li>";
                            echo "<li><a href=\"login.php\">Sign In</a></li>";
                            echo "<li><a href=\"recover.php\">Recover Password</a></li>";
                        }
                    ?>        
                </div> <!-- Navigation -->
                
                <div id="footsup"> <!-- Support -->
                    <b><li>Support</li></b>
                    <li><a href="contact.php">Contact Us</a></li>
                    <li><a href="feedback.php">Feedback</a></li>
                    <li><a href="accounts.php">Account Support</a></li>
                </div> <!-- Support -->

                <div id="footmed"> <!-- Media -->
                    <b><li>Media</li></b>
                    <li><img src="images/fb.gif" height=15 width=15>&nbsp;<a href="#">Facebook</a></li>
                    <li><img src="images/twitter.png" height=15 width=15>&nbsp;<a href="#">Twitter</a></li>
                    <li><img src="images/youtube.png" height=15 width=15>&nbsp;<a href="#">YouTube</a></li>
                    <li><img src="images/instagram.png" height=15 width=15>&nbsp;<a href="#">Instagram</a></li>                  
                </div> <!-- Media -->
                
                <div id="footabout"> <!-- About -->
                    <b><li>About</li></b>
                    <li><a href="about.php">Hyakki's Corner</a></li>
                    <li><a href="team.php">Team</a></li>
                </div> <!-- About -->

                <div id="footother"> <!-- Other --> <!-- BUG: MOVES UNDER ABOUT DIV WHEN PAGE RESIZES -->
                    <b><li>Other</li></b>
                    <li><a href="sitemap.php">Sitemap</a></li>
                    <li><a href="terms.php">Terms of Use</a></li>
                    <li><a href="privacy.php">Privacy Policy</a></li>                  
                </div> <!-- Other -->               
            
                <div id="copyright" class="text"> <!-- Copyright -->
                    &copy; 2013 Hyakki's Corner. All Rights Reserved.
                </div> <!-- Copyright -->
            </footer>
        </div> <!-- Wrapper -->
    </body>
    <script src="javascript/functions.js"></script>
</html>
