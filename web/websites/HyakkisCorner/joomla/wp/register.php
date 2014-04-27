<?php
    require_once("database/login.php"); 
    require_once("includes/functions.php");
    require_once("includes/validations.php");
    include("includes/layout/header.php");
    head("Register");
    include("includes/search.php");
    include("includes/easy_login.php");
    include("includes/logo.php");
    include("includes/navigation.php");
    
    if (isset($_POST['submit'])) {
        $errors = array();
        
        $username   = sanitize_input($_POST["username"]);
        $password   = $_POST["password"];
        $confirm_pw = $_POST["confirm_pw"];
        $email      = sanitize_input($_POST["email"]);
        
        // Validate username
        validate_username($username);
        check_if_username_exists($username, "temp");
        //check_if_username_exists($username, "real");

        // Confirm that passwords are the same
        compare_pws($password, $confirm_pw);        
        check_if_pw_empty($password);
        
        // Validate email
        validate_email($email); // DOUBLE CHECK REGEX
        check_if_email_empty($email);
        check_if_email_exists($email, "temp");
        //check_if_email_exists($email, "real");
       
        // Confirm that checkbox was checked
        $agree = check_confirmation();
        
        if (empty($errors)) {
            $password = password_encrypt($password);

            // $query = "INSERT INTO testusers (username, password, email, admin, date) ";
            // $query .= "VALUES('$username', '$password', '$email', 0, 0)";            
            // $result = mysqli_query($connection, $query);
            // confirm_query($result);
            
            // send verification email
            send_verification_email($username, $password, $email);
        }
        
    } else {
        $username = "";
        $email    = "";
        $agree    = "";
    }
?>


<form action="register.php" method="post">
    <div align="center">
        <fieldset id="register_form">
            <legend><h2>Register</h2></legend>
                
            <span class="text">With your own account on Hyakki's Corner, you have complete access to all of the site's features, such as:<br>
                <ol>
                    <li>Posting threads and comments on the forums</li>
                    <li>Rating and commenting on guides and reviews</li>
                    <li>Voting in the Daily Poll</li>
                    <li>Interaction with other users</li>
                    <li>Chatting with other users in the public and streaming chat room</li>
                </ol>
               
                You must be at least 13 years of age to register for an account.<br><br>
            </span>
            
            <?php
                if (!empty($errors)) {
                    fix_errors();
                }
            ?>
            <label>
                <?php
                    check_errors('username');
                ?>
                Username:</label><input type="text" name="username" id="username" size="15" value="<?php echo $username?>"><br>
            <label>
                <?php
                    check_errors('email');
                ?>
                E-mail:</label><input type="text" name="email" id="email" size="15" value="<?php echo $email?>"><br>
            

            <label>            
                <?php // Checks for password error
                    check_errors('password');
                ?>
                Password:</label><input type="password" name="password" id="password" size="15" value=""><br>
            <label>
                <?php // Checks for password error
                    check_errors('password');
                ?>
                Confirm Password:</label><input type="password" name="confirm_pw" id="confirm_pw" size="15" value=""><br>
            
            <?php
                check_errors('agree');
            ?>
            <input type="checkbox" name="agree" id="agree" <?php if ($agree === true) echo "checked=\"checked\""?>>
            I am at least 13 years of age and have read and agree with the <br><a href="terms.php" class="links">Terms of Use</a> 
            and <a href="privacy.php" class="links">Privacy Policy</a>.<br><br>

            <div align="center">
                <input type="submit" class="button" name="submit" id="submit" value="register"><br>
            </div> <!-- Center -->
        </fieldset>
    </div> <!-- Center -->     
</form>

<?php
    include("includes/layout/footer.php");
    disconnect($connection);
?>