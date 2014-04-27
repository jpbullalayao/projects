<?php
    session_start();
    require_once("database/login.php");
    require_once("includes/functions.php");
    require_once("includes/validations.php");
    include("includes/layout/header.php");
    head("Login");
    include("includes/search.php");
    include("includes/easy_login.php");
    include("includes/logo.php");
    include("includes/navigation.php");
            
    if (isset($_POST['submit'])) {

        $username = sanitize_input($_POST["username"]);
        $password = $_POST["password"];
         
         if (!$username) { // localtesting purposes 
         //if (!check_if_username_exists($username, "real")) { // Username doesn't exist
            $error = true;
         } else { // Username does exist
         
            
            /*$query  = "SELECT password FROM real_users ";
            $query .= "WHERE username = '$username'";*/
            
            /* local testing purposes */
            $query = "SELECT password FROM testusers ";
            $query .= "WHERE username = '$username'";
            
            //$query = "SELECT password FROM "
                   
            $result = mysqli_query($connection, $query);
            confirm_query($result);
            $row = mysqli_fetch_assoc($result);
            $stored_pw = $row['password'];

            if ($password === $stored_pw) { // local testing purposes
            //if (password_check($password, $stored_pw)) { // Passwords are correct
                $_SESSION["username"] = $username;
                redirect_to("index.php");
            } else {
                $error = true;
            }            
        }     
    } else if (isset($_GET["user"])){
        $username = $_GET["user"];
        $password = "";
        $error = false;
    } else {
        $username = "";
        $password = "";
        $error = false;
    }     
?>


<form action="login.php" method="post"> <!-- Login Form -->
    <div align="center">
	    <fieldset id="login">
		   <legend><h2>Login</h2></legend>
		   
		   <?php 
		       if ($error == true) {
		           echo "<span class=\"error\">*</span> Incorrect username and/or password.<br><br>";
		       }
               
               if (isset($_GET["logout"])) {
                   if ($_GET["logout"] == true) { // Logout message
                       echo "You have been successfully logged out.<br><br>";
                   }
               }
               
               if (isset($_GET["user"])) {
                   echo "Your account has been activated. Please login.<br><br>";
               }
		   ?>
		   
		   <label>Username:</label><input type="text" name="username" size="15" value="<?php echo $username?>"><br>
		   <label>Password:</label><input type="password" name="password" size="15" value=""><br>
		   <input type="submit" class="button" name="submit" id="submit" value="login"><br>
		   
		   <div id="register" align="left">
		       <a href="register.php" class="text">Register</a>
		   </div> <!-- Register -->
		   
		   <div id="forgot" align="right">
		       <a href="recover.php" class="text">Forgot Password?</a><br>
		   </div> <!-- Forgot Password -->
	    </fieldset>
	</div> <!-- Center -->
</form> <!-- Login Form -->
			
			
<?php
    include("includes/layout/footer.php");
?>