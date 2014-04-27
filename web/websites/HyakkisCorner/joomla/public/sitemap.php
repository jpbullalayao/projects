<?php 
    include("../includes/layout/header.php");
    head("Sitemap");
    include("../includes/search.php");
    include("../includes/easy_login.php");
    include("../includes/logo.php");
    include("../includes/navigation.php");
?>
            
            <div id="sitemap"> <!-- Sitemap -->
                <b>Sitemap</b>
                <ul>
                    <li><a href="index.html">Home</a>
                    <li><a href="forums.html">Forum</a></li>
                    <li><a href="guides.html">Guides</a></li>
                    <li><a href="archives.html">Reviews</a></li>
                        <ul>
                            <li><a href="criteria.html">Criteria</a></li>
                            <li><a href="archives.html">Archives</a></li>
                        </ul>
                    <li><a href="stream.html">Stream</a></li>
                </ul>
            </div> <!-- Sitemap -->
            
            
<?php
    include("../includes/layout/footer.php");
?>