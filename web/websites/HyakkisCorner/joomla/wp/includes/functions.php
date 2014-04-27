<?php
    function redirect_to($page) {
        header("Location: {$page}");
        exit();
    }
    
    function disconnect($connection) {
        mysqli_close($connection);
    }
    
    function confirm_query($result_set) {
        // Test if there was a query error
        if (!$result_set) {
            die("Database query failed.");
        } 
    }    
    
    function print_age() {
        echo "<option>---</option>";
        for ($i = 13; $i <= 110; $i++) {
            echo "<option>$i</option>";                
        }
    }
    
    function convert_month($month) {
        if ($month == "January") {
            return '01';
        } else if ($month == "February") {
            return '02';
        } else if ($month == "March") {
            return '03';
        } else if ($month == "April") {
            return '04';
        } else if ($month == "May") {
            return '05';
        } else if ($month == "June") {
            return '06';
        } else if ($month == "July") {
            return '07';
        } else if ($month == "August") {
            return '08';
        } else if ($month == "September") {
            return '09';
        } else if ($month == "October") {
            return '10';
        } else if ($month == "November") {
            return '11';
        } else if ($month == "December") {
            return '12';
        } 
    }
    
    function sanitize_input($input) {
        $input = stripslashes($input);
        $input = htmlentities($input);
        $input = strip_tags($input);
        return $input;
    }
    
    function logged_in() {
        return isset($_SESSION["username"]);
    }
    
    function send_verification_email($username, $password, $email) {
        global $connection;
        
        $header = "From: noreply@hyakkiscorner.com";
        $subject = "Thank you for registering with Hyakki's Corner!";
        $code    = md5(uniqid(rand()));
        $message = "Hello, $username,\n\n";
        $message .= "Thank you for registering with Hyakki's Corner!\n\n";
        $message .= "In order to activate your account, please click on the following link: \n";
        $message .= "http://www.hyakkiscorner.com/user/activate.php?user=$username&code=$code";
        $message .= "\n\nPlease note that this link will expire after 30 days. If you are having trouble activating your account, PLEASE contact our support team at ";
        $message .= "support@hyakkiscorner.com, specifying your username, to have a support team member activate your account for you.";
        $message .= "\n\nWe hope you enjoy your stay at hyakkiscorner.com!";
        
        // store into temp database
        $query  =  "INSERT INTO temp_users (username, password, email, admin, date, code) ";
        $query  .= "VALUES('$username', '$password', '$email', 0, NOW(), '$code')";
        $result = mysqli_query($connection, $query);
        
        if ($result) { // Information successfully inserted into database
            mail($email, $subject, $message, $header);
            $email = urlencode($email);
            redirect_to("user/confirm.php?email=$email");
            
        } else { // Information unsuccessfully inserted into database
            die("Something went wrong on our end. Please try registering later.");
        }
    }
    
    function activate_user($username, $code) {
        global $connection;
        
        $query = "SELECT * FROM temp_users ";
        $query .= "WHERE username = '$username'";
        $result = mysqli_query($connection, $query);
        confirm_query($result);
        
        $db_info = mysqli_fetch_assoc($result);
        $db_code = $db_info["code"];
        $db_password = $db_info["password"]; 
        $db_email = $db_info["email"];
        $db_admin = $db_info["admin"];
        
        echo "code: $db_code<br>";
        echo "pw: $db_password<br>";
        echo "email: $db_email<br>";
        echo "username: $username<br>";
        
        if ($code === $db_code) {
            
            // Store into real user database
            $query  = "INSERT INTO real_users (username, password, email, admin, date) ";
            $query .= "VALUES('$username', '$db_password', '$db_email', $db_admin, NOW())";
            $result = mysqli_query($connection, $query);
            confirm_query($result);
            mysqli_free_result($result);
            
            // Delete from temporary user database
            mysqli_free_result($result);
            $query =  "DELETE FROM temp_users ";
            $query .= "WHERE username = '$username'";
            $result = mysqli_query($connection, $query);
            confirm_query($result);
            mysqli_free_result($result);
            
            redirect_to("../login.php?user=$username");
        } else { // Error
            redirect_to("../user/activate.php?error=true");
        }
    }

    function email($to, $from, $subject, $message, $user = NULL) {
        $from = "From: $from";
        if ($user) {
            $message = "From username: $user\n\n" . $message;
        }  
        mail($to, $subject, $message, $from);
    }

?>