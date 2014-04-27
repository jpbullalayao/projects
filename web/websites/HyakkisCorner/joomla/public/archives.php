<?php 
    include("../includes/layout/header.php");
    head("Archives");
    include("../includes/search.php");
    include("../includes/easy_login.php");
    include("../includes/logo.php");
    include("../includes/navigation.php");
?>

<div id="topreviews">
    <b>Top Reviews</b>
    <article>List of some of the top submitted reviews will be posted here.</article>
        <ul>
            <li><a href="#">Review #1</a></li>
            <li><a href="#">Review #2</a></li>
            <li><a href="#">Review #3</a></li>                      
        </ul>
</div>          


<div id="reviews"> <!-- Available Reviews -->
    <b>Available Reviews</b>
    <article>The list of available reviews will be listed here.</article>
        <ul>
            <li><a href="#">Review #1</a></li>
            <li><a href="#">Review #2</a></li>
            <li><a href="#">Review #3</a></li>
        </ul>
    <a href="#" class="upload">Upload a Review</a>
</div> <!-- Available Reviews -->

<div id="latestreviews"> <!-- Latest Reviews -->
    <b>Latest submitted reviews</b>
    <article>The latest submitted reviews will go here.</article>
    
    Test<br><br><br><br><br>
    Test<br><br><br><br><br>
    Test<br><br><br><br><br>
    Test<br><br><br><br><br>
    Test<br><br><br><br><br>
</div> <!-- Latest Reviews -->


<?php
    include("../includes/layout/footer.php");
?>


