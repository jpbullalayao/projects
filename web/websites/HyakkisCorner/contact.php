<?php 
    require_once("includes/functions.php");
    require_once("includes/validations.php");
    include("includes/layout/header.php");
    head("Contact Us");
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
        $message = $_POST['contact_inquiry'];
        $to = "info@hyakkiscorner.com";
        $subject = "A user has sent us an inquiry on Hyakki's Corner";        
                
        if (!$from) { // User didn't specify an e-mail
            $errors['email'] = "Please input your e-mail.";
        } else { 
            email($to, $from, $subject, $message);
            redirect_to("redirect.php?action=contact");
        }
    } else {
        $message = "";
    }
?>


<div id="contact">
    <b class="title">Contact Us</b>
    <article>
        Anything you'd like to inquire about? Want information about Hyakki's Corner? Please contact us!
    </article>
    
    <form action="contact.php" method="post">
        <?php
            if (!empty($errors)) {
                fix_errors();
            }
        ?>
        
        <label>
            <?php
                check_errors('email');
            ?> Your E-mail: </label><input type="text" class="textbox" name="email"></input><br><br>
        
        <label id="contact_label">Inquiry: </label>
            <textarea id="contact_inquiry" class="textbox" name="contact_inquiry" cols="35" rows="15"><?php echo $message?></textarea>
            
        <div align="center" style="clear:both;">
            <input type="submit" class="button" name="submit" value="submit">
        </div>    
    </form>
</div>


<?php
    include("includes/layout/footer.php");
?>