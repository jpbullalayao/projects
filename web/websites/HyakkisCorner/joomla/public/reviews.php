<?php 
    include("../includes/layout/header.php");
    head("Reviews");
    include("../includes/search.php");
    include("../includes/easy_login.php");
    include("../includes/logo.php");
    include("../includes/navigation.php");
?>
        
                
<div id="criteria">
<!--<div class="content"> <!-- Criteria -->
    <b>Criteria</b>
    <article>The criteria description will go here.</article>
</div> <!-- Criteria -->


<!--<div id="divider"> 
</div> -->

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
        
        
<!--<div id="submitreview">
    <b>Submit a Review</b>
    <article>Form for submitting a review will be here</article>
    <form> 
        <fieldset>
        <li><label for="name">Video Game:</label><input type="text" name="name" id="name" required="required"></li>
        <li><label for="system">System:</label><select size="1" name="system" id="system">
                    <option value="Xbox One">Xbox One</option>
                    <option value="PS4">PS4</option>
                    <option value="Xbox 360">Xbox 360</option>
                    <option value="PS3">PS3</option>
                    <option value="Wii U">Wii U</option>
                    <option value="3DS">3DS</option>
                    <option value="Vita">Vita</option>
                    <option value="DS">DS</option>
                    <option value="PSP">PSP</option>
                    <option value="PS2">PS2</option>
                    <option value="PC">PC</option>
                    <option value="Other">Other</option>
                </select></li>
        <li><label for="title">Title:</label><input type="text" name="title" id="title" required="required"></li>
        <li><label for="review">Review:</label><input type="text" name="review" id="review" required="required"></li>
        </fieldset>
    </form> 
</div> <!-- Submit Reviews -->
            
            
<?php
    include("../includes/layout/footer.php");
?>