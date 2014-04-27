<?php
    
    function fix_errors() {
        global $errors;
        
        if (!empty($errors)) {
            echo "<span class=\"error\">Please fix the following errors:</span> ";
            echo "<ul>";
            foreach ($errors as $key => $error) {
                echo "<li>$error</li>";
            }
            echo "</ul>";    
        }
    }
    
    function validate_username($input) {
        global $errors;
        $file = fopen("user/dirtywords.txt", "r");

        // Will check bad words in username
        while (!feof($file)) {
            $word = trim(fgets($file));
            $input_array = explode("_", $input); // Checks for underscores in username and deletes them to 
            $new_word = "";
            
            for ($i = 0; $i < count($input_array); $i++) { // Concatenates word array together into one word to compare to dirty words
                $new_word .= $input_array[$i];
            }
            
            if (strlen(stristr($new_word, $word)) > 0) {
                $errors["username"] = "Inappropriate username specified.";
                return;
            }
        }
        
        if (preg_match("[\W]", $input) || empty($input) || strlen($input) < 4 || strlen($input) > 20)  {
            $errors["username"] = "Username must be 4-20 characters long, and contain only letters, numbers and underscores.";
        }


    }
    
    function check_if_username_exists($username, $table) {
        global $errors, $connection;
        
        if ($table === "temp") { // look in temp_users table
            $query = "SELECT username FROM temp_users "; 
        } else { // look in real_users table
            $query = "SELECT username FROM real_users ";
        }
        //$query = "SELECT username FROM testusers ";
        $query .= "WHERE username = '$username'";
        $result = mysqli_query($connection, $query);
        $user = mysqli_fetch_assoc($result);
        
        if ($user) { // Username exists already
            $errors["username"] = "Username already exists.";
            return true;
        } else {
            return false;
        }
    }
    
    function check_if_email_empty($email) {
        global $errors;
        
        if (empty($email)) {
            $errors["email"] = "Specify your e-mail address.";
        }
    }
    
    function check_if_email_exists($email, $table) {
        global $errors, $connection;
        
        if ($table === "temp") {
            $query = "SELECT email FROM temp_users ";
        } else {
            $query = "SELECT email FROM read_users ";
        }
        //$query = "SELECT email FROM testusers ";
        $query .= "WHERE email = '$email'";
        $result = mysqli_query($connection, $query);
        $email = mysqli_fetch_assoc($result);
        
        if ($email) { // e-mail exists already
            $errors["email"] = "E-mail address already in use.";
            return true;
        } else {
            return false;
        }
    }
    
    function validate_email($email) {
        global $errors;
        
        // Only checks to see if @ character is in email
        if (!preg_match("[@]", $email) || !preg_match("/\./", $email)) { 
        //if (!preg_match("/[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[a-zA-Z0-9]+/", $email)) {
            $errors["email"] = "Invalid e-mail address.";
        }
    }
    
    function validate_dob($month, $day, $year) {
        global $errors;
        
        if (preg_match("[-]", $month) || preg_match("[-]", $day) || preg_match("[-]", $year)) {
            $errors["empty_dob"] = "Specify your date of birth.";
        }    
    }
    
    function validate_gender($gender) {
        global $errors;
        
        if (preg_match("[-]", $gender)) {
            $errors["empty_gender"] = "Specify your gender.";
        }
    }
    
    function compare_pws($password1, $password2) {
        global $errors;
        
        if ($password1 !== $password2) {
            $errors['password'] = "Passwords don't match.";
        }
    }    
    
    function check_if_pw_empty($password) {
        global $errors;
        
        if (empty($password) && !is_numeric($password) && !is_null($password) && !is_bool($password)) {
            $errors['password'] = "Password field is empty.";
        }    
    }

    function password_encrypt($password) {
            $hash_format = "$2y$10$";
            $salt_length = 22;
            $salt = generate_salt($salt_length);
            $format_and_salt = $hash_format . $salt;
            $hash = crypt($password, $format_and_salt);
            return $hash;
    }    
    
    function generate_salt($length) {
        // not 100% unique/random, but good enough for a salt
        // MD5 returns 32 characters
        $unique_random_string = md5(uniqid(mt_rand(), true));
        
        // Valid characters for a salt are [a-zA-Z0-9./]
        $base64_string = base64_encode($unique_random_string);

        // But not '+' which is valid in base64 encoding
        $modified_base64_string = str_replace('+', '.', $base64_string);

        // Truncate string to the correct length
        $salt = substr($modified_base64_string, 0, $length);
        return $salt;
    }   
    
    
    function password_check($password, $existing_hash) {
        // existing hash contains format and salt at start
        $hash = crypt($password, $existing_hash);
       
        if ($hash === $existing_hash) {
            return true;
        } else {
            return false;
        }
    }
    
    
    function check_confirmation() {
        global $errors;
        
        if (!isset($_POST["agree"])) {
            $errors["agree"] = "Please confirm that you are over 13 and have read the Terms of Use and Privacy Policy.";
            return false; // Wasn't checked
        } else {
            return true; // Was checked
        }
    }
    
    function check_errors($key) {
        global $errors;
        
        if (isset($errors[$key])) {
            echo "<span class=\"error_star\">*</span>";
        }
    }
    
    
    
?>