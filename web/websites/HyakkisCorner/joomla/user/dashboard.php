<?php
    require_once("../includes/functions.php");
    require_once("../database/login.php");
    include("../includes/layout/user_header.php");
    head("My Dashboard");
    include("../includes/search.php");
    
    session_start(); 
    if (logged_in()) {
        echo "<div id =\"welcome\">";
        echo "<span class=\"text\" style=\"padding-right:0px\">" . $_SESSION["username"] . "</span>";
        echo "<a href=\"user/dashboard.php\"><span class=\"text\">Dashboard</span></a>";
        echo "</div>";        
    } else { // User not logged in, shouldn't be seeing this page.
        redirect_to("../");
    }    
    
    include("../includes/logo.php");
    include("../includes/user_navigation.php");  
?>

<div id="dashboard_wrapper">
    <div align="center"><h1><?php echo $_SESSION["username"]?>'s Dashboard</h1></div>
    <fieldset id="profile">
        <legend><h3>Profile</h3></legend>
        <div id="avatar">
            Avatar
        </div>
        
        <div id="dashboard_col1">
            Join date: <br>
            # of posts<br><br>
            <a href="#">Logout</a><br>
        </div>
        
        <div id="dashboard_col2">
            <a href="#">Inbox</a><br>
            <a href="#">Profile Settings</a><br>
            <a href="#">Forum Settings</a><br>
            <a href="#">Contacts</a><br>
        </div>
        
        <div id="dashboard_col3">
            <a href="#">Blog</a><br>
            <a href="#">Favorite Threads</a><br>
        </div>
    </fieldset>
    
    <fieldset id="info">
        <legend><h3>Personal Info</h3></legend>
        <label class="dashboard_list">Name</label><input type="text" class="personal_info"><br>
        <label class="dashboard_list">Location</label><input type="text" class="personal_info" id="location" value="Where are you from?"><br>
        <label class="dashboard_list">Gender</label>
            <select size="1" name="gender" id="gender">
                <option>------</option>
                <option>Male</option>
                <option>Female</option>
            </select><br>
        <label class="dashboard_list">Age</label>
            <select size="1" name="age" id="age">
                <?php print_age(); ?>
            </select></input><br>
        <label class="dashboard_list">E-mail</label><input type="text" class="personal_info"><br>
        <div align="center">
            <input type="submit" class="button" name="info_submit" id="info_submit" value="done">
        </div>
    </fieldset>
    
    <fieldset id="gaming">
        <legend><h3>Gaming</h3></legend>
        <label class="dashboard_list">Xbox One</label><input type="text" id="xone_gamertag" value="Enter gamertag..."><br>
        <label class="dashboard_list">Xbox 360</label><input type="text" id="x360_gamertag" value="Enter gamertag..."><br>
        <label class="dashboard_list">PS4</label><input type="text" id="ps4_psn" value="Enter PSN..."><br>
        <label class="dashboard_list">PS3</label><input type="text" id="ps3_psn" value="Enter PSN..."><br>
        <label class="dashboard_list">Steam</label><input type="text" id="steam_user" value="Enter Steam name..."><br>
        <label class="dashboard_list">3DS</label><input type="text" id="_3ds_part1" value="####" size="1"><input type="text" id="_3ds_part2" value="####" size="1"><input type="text" id="_3ds_part3" value="####" size="1"><br>
        <label class="dashboard_list">Wii</label><input type="text" id="wii_part1" value="####" size="1"><input type="text" id="wii_part2" value="####" size="1"><input type="text" id="wii_part3" value="####" size="1"><input type="text" id="wii_part4" value="####" size="1"><br>
        <label class="dashboard_list">Wii U</label><input type="text" id="wiiu_user" value="Enter Nintendo Network I.D..."><br>

        <div align="center">
            <input type="submit" class="button" name="game_submit" id="game_submit" value="done">
        </div>
    </fieldset>

    <fieldset id="media_wrapper">
        <legend><h3>Media</h3></legend>
        <div id="media_col1">
            <label class="dashboard_list">Facebook</label><input type="text" id="facebook" value="Enter your Facebook URL..."><br>
            <label class="dashboard_list">YouTube</label><input type="text" id="youtube" value="Enter your YouTube URL..."><br>
            <label class="dashboard_list">Twitter</label><input type="text" id="twitter" value="@"><br>
            <label class="dashboard_list">Instagram</label><input type="text" id="instagram" value="@"><br>
            <label class="dashboard_list">Vine</label><input type="text" id="vine" value="Enter your Vine username..."><br>
        </div>
        
        <div id="media_col2">
            <label class="dashboard_list">Google Talk</label><input type="text" id="google_talk" value="Enter your screen name..."></input><br>
            <label class="dashboard_list">AIM</label><input type="text" id="aim" value="Enter your screen name..."></input><br>
            <label class="dashboard_list">Skype</label><input type="text" id="skype" value="Enter your screen name..."></input><br>
        </div>    
        
        <div align="center" style="clear: both">
            <input type="submit" class="button" name="media_submit" id="media_submit" value="done">
        </div>
    </fieldset> 
    
    <fieldset id="bio">
        <legend><h3>Bio</h3></legend>
        <textarea name="bio" cols="82" rows="10"></textarea>
        <div align="center">
            <input type="submit" class="button" name="bio_submit" id="bio_submit" value="done">
        </div>
    </fieldset>
    
    <fieldset id="signature">
        <legend><h3>Forum Signature</h3></legend>
        
        <textarea name="signature" cols="82" rows="10">hello</textarea>
        <div align="center">
            <input type="submit" class="button" name="sig_submit" id="sig_submit" value="done">
        </div>
    </fieldset>
   
    
    
</div>

<?php
    include("../includes/layout/user_footer.php");
?>