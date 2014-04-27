<?php
    require_once("functions.php");
    include("../includes/layout/header.php");
    head("Login");
    include("../includes/search.php");
    include("../includes/easy_login.php");
    include("../includes/logo.php");
    include("../includes/navigation.php");
        
    if (isset($_GET['error'])) { // Will come here if login is wrong from another page
        $error = $_GET['error'];
    } else {
        $error = false; // Initialize if we try to log in from login.php
    }
    
    if (isset($_POST['submit'])) {
        $username = $_POST["username"];
        $password = $_POST["password"];
        
        if ($username == "jourdan" && $password == "pw") {
            redirect_to("index.html");
        } else {
            $error = true;
        }
    } else {
        
        if (isset($_GET['username'])) {
            $username = $_GET['username']; // Will save username if login is wrong and user tried to log in from another page
        } else {
            $username = "";
            $password = "";
        }
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
					   ?>
					   
					   <label>Username:</label><input type="text" name="username" id="username" size="15" value="<?php echo $username?>"><br>
					   <label>Password:</label><input type="password" name="password" id="password" size="15" value=""><br>
					   <input type="submit" name="submit" id="submit" value="Submit"><br>
					   
					   <div id="register" align="left">
					       <a href="#" class="text">Register</a>
					   </div>
					   
					   <div id="forgot" align="right">
					       <a href="#" class="text">Forgot Password?</a><br>
					   </div>
				    </fieldset>
				</div>
			</form> <!-- Login Form -->
			
			
<?php
    include("../includes/layout/footer.php");
?>