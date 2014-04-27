<?php
    require_once("includes/functions.php");
    require_once("includes/validations.php");
    include("includes/layout/header.php");
    head("Account Support");
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
    
    if (isset($_POST['submit'])) {
        $errors  = array();
        $from   = $_POST['email'];
        $user = $_POST['username']; 
        $message = $_POST['account_inquiry'];
        $to = "support@hyakkiscorner.com";
        $subject = "A user has sent us an account inquiry on Hyakki's Corner";    
           
                
        if (!$from || !$user) { // User didn't specify an e-mail or a username
            if (!$from) {
                $errors['email'] = "Please input your e-mail.";
            }            
            if (!$user) {
                $errors['username'] = "Please input the username you registered with this site.";
            }
        } else {
            email($to, $from, $subject, $message, $user);
            redirect_to("redirect.php?action=accounts");
        }
    } else {
        $from = "";
        $user = "";
        $message = "";   
    }
?>

<div id="account_support">
    <b class="title">Account Support</b>
    <article>
        As a website who values its users, we strongly encourage you to fill out this ticket if you are experiencing any problems
        with your account. We would like nothing more than to accomodate users with the desired satisfaction that they 
        expect out of this website during their stay. Any problems with your account? Please tell us!
    </article>
    
    <form action="accounts.php" method="post">
        <?php
            if (!empty($errors)) {
                fix_errors();
            }
        ?>
        
        <label>
            <?php
                check_errors('email');
            ?> Your E-Mail: 
        </label><input type="text" class="textbox" name="email" value="<?php echo $from?>"><br><br>
        
        <label>
            <?php
                check_errors('username'); 
            ?> Your Username: </label><input type="text" class="textbox" name="username" value="<?php echo $user?>"><br><br>
        
        <label id="account_label">Account Inquiry: </label>
        <textarea id="account_inquiry" class="textbox" name="account_inquiry" cols="35" rows="15"><?php echo $message?></textarea>
            
        <div align="center" style="clear:both;">
            <input type="submit" class="button" name="submit" value="submit">
        </div>  
        
    </form>
</div>

<?php
    include("includes/layout/footer.php");
?>