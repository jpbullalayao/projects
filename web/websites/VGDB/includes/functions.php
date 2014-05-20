<?php
    require_once("config.php");

    /**
     * Function: email
     * Purpose:  Send an e-mail to the $to address from the $from address.
     * @param    string $to
     * @param    string $from
     * @param    string $subject
     * @param    string $message
     * @param    string $user, NULL if not specified
     */
	function email($to, $from, $subject, $message, $user = NULL) {
        $from = "From: $from";
        if ($user) {
            $message = "From username: $user\n\n" . $message;
        }  
        mail($to, $subject, $message, $from);
    }


    /**
     * Function: redirect_to
     * Purpose:  Redirects a user to another page.
     * @param:   string $page
     */
    function redirect_to($page) {
        header("Location: {$page}");
        exit();
    }


    /**
     * Function: connect_to_db()
     * Purpose:  Connects to the VGDB database. All necessary credentials are
     *           hidden for security purposes.
     */
    function connect_to_db() {
        global $link, $dbname;

        mysqli_select_db($link, $dbname);
    }


    /**
     * Function: check_username
     * Purpose:  Performs username validation when user is registering for
     *           an account.
     * Calls:    Called in register.php
     * @param    string $username
     */
    function check_username($username) {
        global $errors, $link;

        if (!$username) {
            $errors['username'] = "Please specify a username.";
        } else {

            if (strlen($username) < 4 || strlen($username) > 30) {
                $errors['username'] = "Username must be between 4 and 30 characters.";
            }

            /* Query to check if username is already in use */
            $table = "Users";
            $query = "SELECT * FROM $table WHERE username='$username'";
            $result = mysqli_query($link, $query);

            /* There's a matching username */
            if (mysqli_num_rows($result) > 0) {
                $errors['username'] = "Username is already in use.";
            }
        }
    }


    /**
     * Function: check_email
     * Purpose:  Performs email validation when user is registering for
     *           an account.
     * Calls:    Called in register.php
     * @param    string $email
     */
    function check_email($email) {
        global $errors, $link;
        
        if (!$email) {
            $errors['email'] = "Please specify an e-mail.";
        } else {

            /* Query to check if email is already in use */
            $table = "Users";
            $query = "SELECT * FROM $table WHERE email='$email'";
            $result = mysqli_query($link, $query);

            /* There's a matching username */
            if (mysqli_num_rows($result) > 0) {
                $errors['email'] = "E-mail is already in use.";
            }
        }
    }


    /**
     * Function: check_password
     * Purpose:  Performs password validation when user is registering for
     *           an account.
     * Calls:    Called in register.php
     * @param    string $email
     */
    function check_password($password, $confirm) {
        global $errors;

        if (!$password || !$confirm) {
            $errors['password'] = "Please fill in the password fields."; 
        } else {
            if ($password !== $confirm) {
                $errors['password'] = "Passwords don't match.";
            }
        }
    }


    /**
     * Function: password_encrypt
     * Purpose:  Encrypts the password.
     * Calls:    Called in register.php
     * @param    string $password
     * @return   string $hash
     */
    function password_encrypt($password) {
        $hash_format = "$2y$10$";
        $salt_length = 22;
        $salt = generate_salt($salt_length);
        $format_and_salt = $hash_format . $salt;
        $hash = crypt($password, $format_and_salt);
        return $hash;
    }    
    

    /**
     * Function: generate_salt
     * Purpose:  Generates the salt for the password.
     * Calls:    Called in password_encrypt()
     * @param    int $length
     * @return   string $salt
     */
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


    /**
     * Function: register_user
     * Purpose:  Officially registers user after performing validation and
     *           error checking.
     * Calls:    Called in register.php
     * @param    string $username
     * @param    string $email
     * @param    string $password
     */
    function register_user($username, $email, $password) {
        global $link;
        $table = "Users";

        $query  = "INSERT INTO $table (username, password, email) ";
        $query .= "VALUES ('$username', '$password', '$email')";
        mysqli_query($link, $query);
    }


    /**
     * Function: try_login
     * Purpose:  Attempts to log the user in based on account credentials 
     *           that user gave in login.php.
     * Calls:    Called in login.php
     * @param    string $username
     * @param    string $password
     * @return   bool
     */
    function try_login($username, $password) {
        global $errors, $link;
        $table = "Users";

        $query     = "SELECT * FROM $table WHERE username='$username'";
        $results   = mysqli_query($link, $query);
        $row       = mysqli_fetch_assoc($results);
        $stored_pw = $row['password'];

        if (password_check($password, $stored_pw)) {
            return true;
        } else {
            $errors['login'] = "Incorrect username and/or password";
            return false;
        }
    }


    /** 
     * Function: password_check
     * Purpose:  Compares password to database hash.
     * Calls:    Called in try_login()
     * @param    string $password
     * @param    string $existing_hash
     * @return   bool
     */
    function password_check($password, $existing_hash) {
        // existing hash contains format and salt at start
        $hash = crypt($password, $existing_hash);

        if ($hash === $existing_hash) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Function: check_errors()
     * Purpose:  Displays red stars next to wrong input in forms.
     */
    function check_errors($key) {
        global $errors;
        
        if (isset($errors[$key])) {
            echo "<span class=\"error\">*</span>";
        }
    }


    /**
     * Function: fix_errors()
     * Purpose:  Displays any errors for user to fix if he/she had wrong input
     *           in forms.
     */
    function fix_errors() {
        global $errors;
        
        if (!empty($errors)) {
            echo "<span class=\"error\">Please fix the following errors:</span> ";
            echo "<ul>";
            foreach ($errors as $key => $error) {
                echo "<li class=\"error\">$error</li>";
            }
            echo "</ul>";    
            echo "<br>";
        }
    }


    /**
     * Function: find_game
     * Purpose:  Displays games that contain the keyword that the user submitted on
     *           on the home page, when he/she wants to search for a video game.
     * Calls:    Called in results.php
     * @param    string $keyword
     */
    function find_game($keyword) {
        global $link;
        $table = "VideoGames";

        $query = "SELECT * FROM $table WHERE `gname` LIKE '%$keyword%'";
        $result = mysqli_query($link, $query);

        if ($result) {
            echo "<div class=\"games center_game\">";
            echo "<table>";
            echo "<th><b>Games</b></th>";
            echo "<th class=\"year\"><b>Year</b></th>";
            while ($row = mysqli_fetch_array($result)) {
                $gname = $row["gname"];
                $gyear = $row["gyear"];
                echo "<tr class=\"game\">";
                echo "<td><a href=\"game.php?gid=".get_gid($gname)."\" class=\"red\">$gname</a></td>";
                echo "<td class=\"year\">$gyear</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
        } 
    }


    /**
     * Function: get_num_games
     * Purpose:  Displays the number of video games published on the platform
     *           with the associated $pid.
     * Calls:    Called in platform.php
     * @param    int $pid
     * @return   int $row['count']
     */
    function get_num_games($pid) {
        global $link;
        $table = "PublishedOn";

        $query = "SELECT COUNT(pid) as count FROM $table WHERE pid=$pid";
        $results = mysqli_query($link, $query);

        if ($results) {
            $row = mysqli_fetch_array($results);
            return $row['count'];
        }
    }


    /**
     * Function: get_game_info
     * Purpose:  Displays the data of the video game with the associated $gid.
     * Calls:    Called in game.php
     * @param    int $gid
     * @return   array $row
     */
    function get_game_info($gid) {
        global $link;
        $table = "VideoGames";

        $query = "SELECT * FROM $table WHERE gid=$gid";
        $result = mysqli_query($link, $query);

        if ($result) {
            $row = mysqli_fetch_array($result);
            return $row;
        }
    }


    /**
     * Function: check_if_user_rated
     * Purpose:  Checks if the user with the associated $userid is allowed to
     *           rate the game with the associated $gid.
     * Calls:    Called in game.php
     * @param    int $gid
     * @param    int $userid
     * @return   bool
     */
    function check_if_user_rated($gid, $userid) {
        global $link;
        $table = "RatedBy";

        $query = "SELECT * FROM $table WHERE gid=$gid AND userid=$userid";
        $results = mysqli_query($link, $query);

        if (mysqli_num_rows($results) > 0) {
            return false; // User has rated already, not allowed to rate again
        } else {
            return true; // User is allowed to rate game
        }
    }


    /**
     * Function: insert_rating
     * Purpose:  Inserts the rating that the user gave a game into the MySQL table. Then,
     *           it displays the updated average of the game's rating.
     * Calls:    Called in game.php
     * @param    int $gid
     * @param    int $userid
     * @param    int $urating
     */
    function insert_rating($gid, $userid, $urating) {
        global $link;
        $table = "RatedBy";

        $query = "INSERT INTO $table (gid, userid, urating) VALUES ($gid, $userid, $urating)";
        mysqli_query($link, $query);

        $query = "SELECT AVG(urating) as avg FROM $table WHERE gid=$gid";
        $results = mysqli_query($link, $query);

        if ($results) {
            $row = mysqli_fetch_array($results);
            $avg = $row['avg'];
            $table = "VideoGames";

            /* Update grating for game */
            $query = "UPDATE $table SET grating=$avg WHERE gid=$gid";
            mysqli_query($link, $query);
        }
    }


    /**
     * Function: check_if_user_favorited
     * Purpose:  Checks if the user with the associated $userid is allowed to
     *           favorite the game with the associated $gid.
     * Calls:    Called in game.php
     * @param    int $gid
     * @param    int $userid
     * @return   bool
     */
    function check_if_user_favorited($gid, $userid) {
        global $link;
        $table = "FavoritedBy";

        $query = "SELECT * FROM $table WHERE gid=$gid AND userid=$userid";
        $results = mysqli_query($link, $query);

        if (mysqli_num_rows($results) > 0) {
            return false; // User has rated already, not allowed to rate again
        } else {
            return true; // User is allowed to rate game
        }
    }


    /**
     * Function: insert_favorite
     * Purpose:  Inserts the user (associated with $userid) that favorited a 
     *           game (associated with $gid).
     * Calls:    Called in game.php
     * @param    int $gid
     * @param    int $userid
     */
    function insert_favorite($gid, $userid) {
        global $link;
        $table = "FavoritedBy";

        $query = "INSERT INTO $table (gid, userid) VALUES ($gid, $userid)";
        mysqli_query($link, $query);
    }


    /**
     * Function: sort_by_pname
     * Purpose:  Sorts the table on platforms.php by platform name, with A being at the top.
     * Calls:    Called in platforms.php
     */
    function sort_by_pname () {
        global $link;
    	$table = "Platforms";
    	$query = "SELECT * FROM $table ORDER BY pname ASC";
    	$result = mysqli_query($link, $query);

    	if ($result) {
            echo "<div class=\"platforms center_platform\">";
            echo "<table>";
            echo "<th><b>Platform</b></th>";
            echo "<th class=\"year\"><b>Year</b></th>";
            while ($row = mysqli_fetch_array($result)) {
            	$pname = $row["pname"];
            	$pyear = $row["pyear"];
            	echo "<tr class=\"platform\">";
            	echo "<td><a href=\"platform.php?pid=".get_pid($pname)."\" class=\"red\">$pname</a></td>";
            	echo "<td class=\"year\">$pyear</td>";
            	echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
        }
    }


    /**
     * Function: sort_by_pyear
     * Purpose:  Sorts the table on platforms.php by platform release year, with
     *           the earliest console displayed at the top.
     * Calls:    Called in platforms.php
     */
    function sort_by_pyear () {
        global $link;
    	$table  = "Platforms";
    	$query  = "SELECT * FROM $table ORDER BY pyear ASC";
    	$result = mysqli_query($link, $query);

    	if ($result) {
            echo "<div class=\"platforms center_platform\">";
            echo "<table>";
            echo "<th><b>Platform</b></th>";
            echo "<th class=\"year\"><b>Year</b></th>";
            while ($row = mysqli_fetch_array($result)) {
            	$pname = $row["pname"];
            	$pyear = $row["pyear"];
            	echo "<tr class=\"platform\">";
            	echo "<td><a href=\"platform.php?pid=".get_pid($pname)."\" class=\"red\">$pname</a></td>";
            	echo "<td class=\"year\">$pyear</td>";
            	echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
        }
    }

    /** 
     * Function: get_developers_table
     * Purpose:  Displays data from the developers table on developers.php page.
     * Calls:    Called in developers.php
     */
    function get_developers_table() {
        global $link;
        $table = "Developers";

        $query = "SELECT * FROM $table";
        $results = mysqli_query($link, $query);

        if ($results) {
            while ($row = mysqli_fetch_array($results)) {
                $dname   = $row["dname"];
                $founded = $row["founded"];
                echo "<tr class=\"developer\">";
                echo "<td><a href=\"developer.php?did=".get_did($dname)."\" class=\"red\">$dname</a></td>";
                echo "<td class=\"year\">$founded</td>";
                echo "</tr>";
            }
        }
    }

    /** 
     * Function: get_did
     * Purpose:  Helper function that gets the developer id associated with $dname.
     * Calls:    Called by get_developers_table() function.
     * @param    string $dname
     * @return   int $row['did']
     */
    function get_did($dname) {
        global $link;
        $table = "Developers";

        $query = "SELECT did FROM $table WHERE dname=\"$dname\"";
        $results = mysqli_query($link, $query);

        if ($results) {
            $row = mysqli_fetch_array($results); 
            return $row['did'];
        }
    }


    /** 
     * Function: get_developer_info
     * Purpose:  Get the information of the developer with the associated $did.
     * Calls:    Called in developer.php
     * @param    int $did
     * @return   array $row
     */
    function get_developer_info($did) {
        global $link;
        $table = "Developers";

        $query = "SELECT * FROM $table WHERE did=$did";
        $results = mysqli_query($link, $query);

        if ($results) {
            $row = mysqli_fetch_array($results); 
            return $row;
        }
    }


    /** 
     * Function: get_developed_games
     * Purpose:  Get all games developed by developer with the associated $did.
     * Calls:    Called in developer.php
     * @param    int $did
     */
    function get_developed_games($did) {
        global $link;
        $table = "DevelopedBy";

        $query = "SELECT gid FROM $table WHERE did=$did";
        $results = mysqli_query($link, $query);

        if ($results) {
            while ($row = mysqli_fetch_array($results)) {
                $gid = $row['gid'];
                get_games_by_gid($gid);
            }
        }
    }


    /**
     * Function: get_num_games_developed
     * Purpose:  Get the number of games developed by the developer with the
     *           associated $did.
     * Calls:    Called in developer.php
     * @param    int $did
     * @return   int $row['count']
     */
    function get_num_games_developed($did) {
        global $link;
        $table = "DevelopedBy";

        $query = "SELECT COUNT(gid) as count FROM $table WHERE did=$did";
        $results = mysqli_query($link, $query);

        if ($results) {
            $row = mysqli_fetch_array($results);
            return $row['count'];
        }
    }


    /* Called in platforms.php */
    /**
     * Function: get_platforms_table()
     * Purpose:  Displays the platform table on platforms.php page.
     * Calls:    Called in platforms.php
     */
    function get_platforms_table() {
        global $link;
    	$table  = "Platforms";

        $query  = "SELECT * FROM $table";
        $result = mysqli_query($link, $query);

        if ($result) {
        	echo "<div class=\"platforms center_platform\">";
        	echo "<table>";
        	echo "<th><b>Platform</b></th>";
        	echo "<th class=\"year\"><b>Year</b></th>";
            while ($row = mysqli_fetch_array($result)) {
            	$pname = $row['pname'];
            	$pyear = $row['pyear'];
            	echo "<tr class=\"platform\">";
            	echo "<td><a href=\"platform.php?pid=".get_pid($pname)."\" class=\"red\">$pname</a></td>";
            	echo "<td class=\"year\">$pyear</td>";
            	echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
        }
    }
	

    /**
     * Function: get_gid_by_pid
     * Purpose:  Gets the $gid associated with $pid, and displays the information corresponding to 
     *           that $gid.
     * Calls:    Called in platform.php
     * @param    int $pid
     */
    function get_gid_by_pid($pid) {
        global $link;
        $table = "PublishedOn";

        $query = "SELECT * FROM $table WHERE pid=$pid";
        $results = mysqli_query($link, $query);

        if ($results) {
            echo "<div class=\"games center_game\">";
            echo "<table>";
            echo "<th><b>Games</b></th>";
            echo "<th class=\"year\"><b>Year</b></th>";
            while ($row = mysqli_fetch_array($results)) {
                $gid = $row['gid'];
                get_games_by_gid($gid);
            }
            echo "</table>";
            echo "</div>";
        }
    }


    /**
     * Function: get_games_by_gid
     * Purpose:  Displays all the information of the game with the associated $gid
     * Calls:    Called by get_gid_by_pid(), and get_developed_games()
     * @param    int $gid
     */
    function get_games_by_gid($gid) {
        global $link;
        $table = "VideoGames";

        $query = "SELECT * FROM $table WHERE gid=$gid";
        $results = mysqli_query($link, $query);

        if ($results) {
            $row = mysqli_fetch_array($results);
            $gname = $row["gname"];
            $gyear = $row["gyear"];
            echo "<tr class=\"game\">";
            echo "<td><a href=\"game.php?gid=$gid\" class=\"red\">$gname</a></td>";
            echo "<td class=\"year\">$gyear</td>";
            echo "</tr>";
        }
    }


    /**
     * Function: get_users_table()
     * Purpose:  Displays entire users table on members.php page.
     * Calls:    Called in members.php
     */
    function get_users_table() {
        global $link;
        $table = "Users";

        $query  = "SELECT * FROM $table";
        $result = mysqli_query($link, $query);

        if ($result) {
            echo "<div class=\"users center_user\">";
            echo "<table>";
            echo "<th><b>Username</b></th>";
            echo "<th class=\"email\"><b>E-mail</b></th>";
            while ($row = mysqli_fetch_array($result)) {
                $userid   = $row["userid"];
                $username = $row["username"];
                $email    = $row["email"];
                echo "<tr class=\"username\">";
                echo "<td><a href=\"profile.php?userid=$userid\" class=\"red\">$username</a></td>";
                //echo "<td><a href=\"profile.php?userid=".get_user($username)."\" class=\"red\">$username</a></td>";
                echo "<td class=\"email\">$email</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
        }
    }


    /**
     * Function: get_username
     * Purpose:  Gets the username assocaited with $userid.
     * Calls:    Called in profile.php
     * @param    int $userid
     * @return   string $row['username']
     */
    function get_username($userid) {
        global $link;
        $table = "Users";

        $query = "SELECT username FROM $table WHERE userid=$userid";
        $results = mysqli_query($link, $query);

        if ($results) {
            $row = mysqli_fetch_array($results);
            return $row['username'];
        }
    }


    /**
     * Function: get_email
     * Purpose:  Gets the e-mail associated with $userid.
     * Calls:    Called in profile.php
     * @param    int $userid
     * @return   string $row['email']
     */
    function get_email($userid) {
        global $link;
        $table = "Users";

        $query = "SELECT email FROM $table WHERE userid=$userid";
        $results = mysqli_query($link, $query);

        if ($results) {
            $row = mysqli_fetch_array($results);
            return $row['email'];
        }
    }


    /**
     * Function: get_games_favorited
     * Purpose:  Displays the games favorited by the user with the assocaited $userid.
     * Calls:    Called in profile.php
     * @param    int $userid
     */
    function get_games_favorited($userid) {
        global $link;
        $table = "FavoritedBy";

        $query = "SELECT * FROM $table WHERE userid=$userid";
        $results = mysqli_query($link, $query);

        if ($results) {
            echo "<ul>";
            while ($row = mysqli_fetch_array($results)) {
                $gid   = $row['gid'];
                $gname = get_game_name($gid);
                echo "<li><a href=\"game.php?gid=$gid\" class=\"red\">$gname</a></li>";
            }
            echo "</ul>";
        }
    }


    /**
     * Function: get_game_name
     * Purpose:  Gets the game name associated with $gid.
     * Calls:    Called in get_games_favorited() and get_games_rated()
     * @param    int $gid
     * @return   string $row['gname']
     */
    function get_game_name($gid) {
        global $link;
        $table = "VideoGames";

        $query = "SELECT gname FROM $table WHERE gid=$gid";
        $results = mysqli_query($link, $query);

        if ($results) {
            $row = mysqli_fetch_array($results);
            return $row['gname'];
        }
    }


    /**
     * Function: get_num_games_favorited
     * Purpose:  Gets the number of games a user favorited (with the associated $userid).
     * Calls:    Called in profile.php
     * @param    int $userid
     * @return   int $row['count']
     */
    function get_num_games_favorited($userid) {
        global $link;
        $table = "FavoritedBy";

        $query = "SELECT COUNT(gid) as count FROM $table WHERE userid=$userid";
        $results = mysqli_query($link, $query);

        if ($results) {
            $row = mysqli_fetch_array($results);
            return $row['count'];
        }
    }


    /** 
     * Function: get_games_rated
     * Purpose:  Displays the games a specific user rated, along with the rating he/she gave it.
     * Calls:    Called in profile.php
     * @param    int $userid
     */
    function get_games_rated($userid) {
        global $link;
        $table = "RatedBy";

        $query = "SELECT * FROM $table WHERE userid=$userid";
        $results = mysqli_query($link, $query);

        if ($results) {
            echo "<ul>";
            while ($row = mysqli_fetch_array($results)) {
                $gid     = $row['gid'];
                $urating = $row['urating'];
                $gname   = get_game_name($gid);
                echo "<li><a href=\"game.php?gid=$gid\" class=\"red\">$gname ($urating)</a></li>";
            }
            echo "</ul>";
        }
    }


    /** 
     * Function: get_num_games_rated
     * Purpose:  Gets the number of games a specific user rated (with the associated $userid).
     * Calls:    Called in profile.php
     * @param    int $userid
     * @return   int $row['count']
     */
    function get_num_games_rated($userid) {
        global $link;
        $table = "RatedBy";

        $query = "SELECT COUNT(gid) as count FROM $table WHERE userid=$userid";
        $results = mysqli_query($link, $query);

        if ($results) {
            $row = mysqli_fetch_array($results);
            return $row['count'];
        }
    }


    /**
     * Function: get_games_table
     * Purpose:  Displays entire VideoGames table on games.php page.
     * Calls:    Called in games.php
     */
    function get_games_table() {
        global $link;
        $table = "VideoGames";

        $query  = "SELECT * FROM $table";
        $result = mysqli_query($link, $query);

        if ($result) {
            echo "<div class=\"games center_game\">";
            echo "<table>";
            echo "<th><b>Games</b></th>";
            echo "<th class=\"year\"><b>Year</b></th>";
            while ($row = mysqli_fetch_array($result)) {
                $gname = $row["gname"];
                $gyear = $row["gyear"];
                echo "<tr class=\"game\">";
                echo "<td><a href=\"game.php?gid=".get_gid($gname)."\" class=\"red\">$gname</a></td>";
                echo "<td class=\"year\">$pyear</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
        } else {
            DIE ("died");
        }
    }


    /**
     * Function: get_gid
     * Purpose:  Gets the gid associated with $gname.
     * Calls:    Called in find_game()
     * @param    string $gname
     * @return   int $row['gid']
     */
    function get_gid($gname) {
        global $link;
        $table = "VideoGames";

        $query = "SELECT gid FROM $table WHERE gname='$gname'";
        $result = mysqli_query($link, $query);

        if ($result) {
            $row = mysqli_fetch_array($result);
            return $row['gid'];
        }
    }


    /**
     * Function: get_pid
     * Purpose:  Gets the pid associated with $pname.
     * Calls:    Called by sort_by_pname(), sort_by_pyear() and get_platforms_table()
     * @param    string $pname
     * @return   int $row['pid']
     */
	function get_pid($pname) {
        global $link;
        $table = "Platforms";

        $query = "SELECT pid FROM $table WHERE pname='$pname'";
        $result = mysqli_query($link, $query);

        if ($result) {
            $row = mysqli_fetch_array($result);
            return $row['pid'];
        }
    }


    /**
     * Function: get_platform_info
     * Purpose:  Gets the information of the platform associated with $pid.
     * Calls:    Called in platform.php
     * @param    int $pid
     * @return   array $row
     */
    function get_platform_info($pid) {
        global $link;
        $table = "Platforms";

        $query = "SELECT * FROM $table WHERE pid=$pid";
        $result = mysqli_query($link, $query);

        if ($result) {
            $row = mysqli_fetch_array($result);
            return $row;
        } 
    }


    /**
     * Function: check_internet
     * Purpose:  Checks if a specific platform has Internet compatibility.
     * Calls:    Called in platform.php
     * @param    array $row
     * @return   string 
     */
    function check_internet($row) {
        if ($row['internet'] === 'Y') {
            return "Yes";
        } else {
            return "No";
        }
    }


    /**
     * Function: check_pc
     * Purpose:  Checks the PC box in addGame.php.
     * Calls:    Called in addGame.php
     */
	function check_pc() {
        if (isset($_POST['pc'])) {
            echo "checked";
        }
    }
	

    /**
     * Function: check_ps
     * Purpose:  Checks the PlayStation box in addGame.php.
     * Calls:    Called in addGame.php
     */
	function check_ps() {
        if (isset($_POST['ps'])) {
            echo "checked";
        }
    }
	

    /**
     * Function: check_ps2
     * Purpose:  Checks the PlayStation 2 box in addGame.php.
     * Calls:    Called in addGame.php
     */
	function check_ps2() {
        if (isset($_POST['ps2'])) {
            echo "checked";
        }
    }
	

    /**
     * Function: check_ps3
     * Purpose:  Checks the PlayStation 3 box in addGame.php.
     * Calls:    Called in addGame.php
     */
	function check_ps3() {
        if (isset($_POST['ps3'])) {
            echo "checked";
        }
    }
	

    /**
     * Function: check_ps4
     * Purpose:  Checks the PlayStation 4 box in addGame.php.
     * Calls:    Called in addGame.php
     */
	function check_ps4() {
        if (isset($_POST['ps'])) {
            echo "checked";
        }
    }
	

    /**
     * Function: check_xbox
     * Purpose:  Checks the Xbox box in addGame.php.
     * Calls:    Called in addGame.php
     */
	function check_xbox() {
        if (isset($_POST['xbox'])) {
            echo "checked";
        }
    }
	

    /**
     * Function: check_x360
     * Purpose:  Checks the Xbox 360 box in addGame.php.
     * Calls:    Called in addGame.php
     */
	function check_x360() {
        if (isset($_POST['x360'])) {
            echo "checked";
        }
    }
	

    /**
     * Function: check_x1
     * Purpose:  Checks the Xbox One box in addGame.php.
     * Calls:    Called in addGame.php
     */
	function check_x1() {
        if (isset($_POST['x1'])) {
            echo "checked";
        }
    }
	

    /**
     * Function: check_wii
     * Purpose:  Checks the Nintendo Wii box in addGame.php.
     * Calls:    Called in addGame.php
     */
	function check_wii() {
        if (isset($_POST['wii'])) {
            echo "checked";
        }
    }
	

    /**
     * Function: check_wiiu
     * Purpose:  Checks the Nintendo Wii U box in addGame.php.
     * Calls:    Called in addGame.php
     */
	function check_wiiu() {
        if (isset($_POST['wiiu'])) {
            echo "checked";
        }
    }


    /**
     * Function: check_ds
     * Purpose:  Checks the Nintendo DS box in addGame.php.
     * Calls:    Called in addGame.php
     */
    function check_ds() {
        if (isset($_POST['ds'])) {
            echo "checked";
        }
    }


    /**
     * Function: check_3ds
     * Purpose:  Checks the Nintendo 3DS box in addGame.php.
     * Calls:    Called in addGame.php
     */
    function check_3ds() {
        if (isset($_POST['3ds'])) {
            echo "checked";
        }
    }


    /**
     * Function: check_otherplatform
     * Purpose:  Checks the Other box in addGame.php.
     * Calls:    Called in addGame.php
     */
    function check_otherplatform() {
        if (isset($_POST['otherplatform'])) {
            echo "checked";
        }
    }


    /**
     * Function: check_platform_field
     * Purpose:  Checks to see if user selected a platform when adding a game.
     * Calls:    Called in addGame.php
     * @return   bool
     */
    function check_platform_field() {
        $pc                 = $_POST['pc'];
        $ps                 = $_POST['ps'];
        $ps2                = $_POST['ps2'];
        $ps3                = $_POST['ps3'];
        $ps4                = $_POST['ps4'];
        $xbox               = $_POST['xbox'];
        $x360               = $_POST['x360'];
        $x1                 = $_POST['x1'];
        $wii                = $_POST['wii'];
        $wiiu               = $_POST['wiiu'];
        $ds                 = $_POST['ds'];
        $_3ds               = $_POST['3ds'];
        $otherplatform      = $_POST['otherplatform'];
        $otherplatformfield = $_POST['otherplatformfield'];

        /* No platform was selected when attempting to add game */
        if (!$pc and !$ps and !$ps2 and !$ps3 and !$ps4 and !$xbox
            and !$x360 and !$x1 and !$wii and !$wiiu and !$ds and !$_3ds
            and !$ds and !$otherplatform and !$otherplatformfield) {

            return false;
        } else {
            return true;
        }

    }


    /**
     * Function: print_platforms
     * Purpose:  Prints into an email message all platforms selected by user when
     *           adding a game.
     * Calls:    Called in addGame.php
     * @return   string $platforms          
     */
    function print_platforms() {
        $pc                 = $_POST['pc'];
        $ps                 = $_POST['ps'];
        $ps2                = $_POST['ps2'];
        $ps3                = $_POST['ps3'];
        $ps4                = $_POST['ps4'];
        $xbox               = $_POST['xbox'];
        $x360               = $_POST['x360'];
        $x1                 = $_POST['x1'];
        $wii                = $_POST['wii'];
        $wiiu               = $_POST['wiiu'];
        $ds                 = $_POST['ds'];
        $_3ds               = $_POST['3ds'];
        $otherplatform      = $_POST['otherplatform'];
        $otherplatformfield = $_POST['otherplatformfield'];

        $platforms = "";

        if (isset($pc)) {
            $platforms .= "$pc ";
        }

        if (isset($ps)) {
            $platforms .= "$ps ";
        }

        if (isset($ps2)) {
            $platforms .= "$ps2 ";
        }

        if (isset($ps3)) {
            $platforms .= "$ps3 ";
        }

        if (isset($ps4)) {
            $platforms .= "$ps4 ";
        }

        if (isset($xbox)) {
            $platforms .= "$xbox ";
        }

        if (isset($x360)) {
            $platforms .= "$x360 ";
        }

        if (isset($x1)) {
            $platforms .= "$x1 ";
        }

        if (isset($wii)) {
            $platforms .= "$wii ";
        }

        if (isset($wiiu)) {
            $platforms .= "$wiiu ";
        }

        if (isset($ds)) {
            $platforms .= "$ds ";
        }

        if (isset($_3ds)) {
            $platforms .= "$_3ds ";
        }

        if (isset($otherplatform)) {
            $platforms .= "$otherplatformfield ";
        }

        return $platforms;
    }


    /**
     * Function: check_rpg
     * Purpose:  Checks the RPG box in addGame.php.
     * Calls:    Called in addGame.php
     */
    function check_rpg() {
        if (isset($_POST['rpg'])) {
            echo "checked";
        }
    }


    /**
     * Function: check_action
     * Purpose:  Checks the Action box in addGame.php.
     * Calls:    Called in addGame.php
     */
    function check_action() {
        if (isset($_POST['action'])) {
            echo "checked";
        }
    }


    /**
     * Function: check_adventure
     * Purpose:  Checks the Adventure box in addGame.php.
     * Calls:    Called in addGame.php
     */
    function check_adventure() {
        if (isset($_POST['adventure'])) {
            echo "checked";
        }
    }


    /**
     * Function: check_shooter
     * Purpose:  Checks the Shooter box in addGame.php.
     * Calls:    Called in addGame.php
     */
    function check_shooter() {
        if (isset($_POST['shooter'])) {
            echo "checked";
        }
    }


    /**
     * Function: check_simulation
     * Purpose:  Checks the Simulation box in addGame.php.
     * Calls:    Called in addGame.php
     */
    function check_simulation() {
        if (isset($_POST['simulation'])) {
            echo "checked";
        }
    }


    /**
     * Function: check_strategy
     * Purpose:  Checks the Strategy box in addGame.php.
     * Calls:    Called in addGame.php
     */
    function check_strategy() {
        if (isset($_POST['strategy'])) {
            echo "checked";
        }
    }


    /**
     * Function: check_strategy
     * Purpose:  Checks the Other box in addGame.php.
     * Calls:    Called in addGame.php
     */
    function check_othergenre() {
        if (isset($_POST['othergenre'])) {
            echo "checked";
        }
    }


    /**
     * Function: print_genres
     * Purpose:  Prints into an email message all genres selected by user when
     *           adding a game.
     * Calls:    Called in addGame.php
     * @return   string $genres        
     */
    function print_genres() {
        $rpg             = $_POST['rpg'];
        $action          = $_POST['action'];
        $adventure       = $_POST['adventure'];
        $shooter         = $_POST['shooter'];
        $simulation      = $_POST['simulation'];
        $strategy        = $_POST['strategy'];
        $othergenre      = $_POST['othergenre'];
        $othergenrefield = $_POST['othergenrefield'];

        $genres = "";

        if (isset($rpg)) {
            $genres .= "$rpg ";
        }

        if (isset($action)) {
            $genres .= "$action ";
        }

        if (isset($adventure)) {
            $genres .= "$adventure ";
        }

        if (isset($shooter)) {
            $genres .= "$shooter "; 
        }

        if (isset($simulation)) {
            $genres .= "$simulation ";
        }

        if (isset($strategy)) {
            $genres .= "$strategy ";
        }

        if (isset($othergenre)) {
            $genres .= "$othergenrefield ";
        }

        return $genres;
    }


    /**
     * Function: get_userid
     * Purpose:  Gets the userid associated with $username.
     * Calls:
     * @param    string $username
     * @return   int $row['userid']
     */
    function get_userid($username) {
        global $link;
        $table = "Users";

        $query = "SELECT userid FROM $table WHERE username='$username'";
        $results = mysqli_query($link, $query);

        if ($results) {
            $row = mysqli_fetch_array($results);
            return $row['userid'];
        }
    }


    /**
     * Function: logged_in
     * Purpose:  Determines if user is logged in.
     * Calls:    Called on every page.
     * @return   bool
     */
    function logged_in() {
        return isset($_SESSION['username']);
    }


    /**
     * Function: logout
     * Purpose:  Logs the user out and redirects him/her to redirect.php page.
     * Calls:    
     */
    function logout() {
        $_SESSION['userid'] = null;
        $_SESSION['username'] = null;
        redirect_to('redirect.php?action=logout');
    }


    /**
     * Function: snes_insert
     * Purpose:  ONE-TIME FUNCTION. Used in order to get snes games and insert into PublishedOn Table.
     * Calls:    Called in members.php, if line isn't commented out.
     */
    function snes_insert() {
        global $link;
        $table = "VideoGames";

        $query = "SELECT * FROM $table WHERE platform='SNES'";
        $results = mysqli_query($link, $query);

        if ($results) {
            
            $table2 = "PublishedOn";
            
            while ($row = mysqli_fetch_array($results)) {        
                $gid = $row['gid'];
                $pubyear = $row['gyear'];

                $query2 = "INSERT INTO $table2 (gid, pid, pubyear) VALUES ($gid, 2, $pubyear)";
                $results2 = mysqli_query($link, $query2);
            }
        } else {
            DIE("SEARCH FAILED");
        }
    }    


    /**
     * Function: nes_insert
     * Purpose:  ONE-TIME FUNCTION. Used in order to get nes games and insert into PublishedOn Table.
     * Calls:    Called in members.php, if line isn't commented out.
     */
    function nes_insert() {
        global $link;
        $table = "VideoGames";

        $query = "SELECT * FROM $table WHERE platform='NES'";
        $results = mysqli_query($link, $query);

        if ($results) {
            
            $table2 = "PublishedOn";
            
            while ($row = mysqli_fetch_array($results)) {        
                $gid = $row['gid'];
                $pubyear = $row['gyear'];

                $query2 = "INSERT INTO $table2 (gid, pid, pubyear) VALUES ($gid, 3, $pubyear)";
                $results2 = mysqli_query($link, $query2);
            }
        } else {
            DIE("SEARCH FAILED");
        }
    }
    

    /**
     * Function: gameboy_insert
     * Purpose:  ONE-TIME FUNCTION. Used in order to get gameboy games and insert into PublishedOn Table.
     * Calls:    Called in members.php, if line isn't commented out.
     */
    function gameboy_insert() {
        global $link;
        $table = "VideoGames";

        $query = "SELECT * FROM $table WHERE platform='Game Boy'";
        $results = mysqli_query($link, $query);

        if ($results) {
            
            $table2 = "PublishedOn";
            
            while ($row = mysqli_fetch_array($results)) {        
                $gid = $row['gid'];
                $pubyear = $row['gyear'];

                $query2 = "INSERT INTO $table2 (gid, pid, pubyear) VALUES ($gid, 8, $pubyear)";
                $results2 = mysqli_query($link, $query2);
            }
        } else {
            DIE("SEARCH FAILED");
        }
    }


    /**
     * Function: ps1_insert
     * Purpose:  ONE-TIME FUNCTION. Used in order to get ps1 games and insert into PublishedOn Table.
     * Calls:    Called in members.php, if line isn't commented out.
     */
    function ps1_insert() {
        global $link;
        $table = "VideoGames";

        $query = "SELECT * FROM $table WHERE platform='PS1'";
        $results = mysqli_query($link, $query);

        if ($results) {
            
            $table2 = "PublishedOn";
            
            while ($row = mysqli_fetch_array($results)) {        
                $gid = $row['gid'];
                $pubyear = $row['gyear'];

                $query2 = "INSERT INTO $table2 (gid, pid, pubyear) VALUES ($gid, 13, $pubyear)";
                $results2 = mysqli_query($link, $query2);
            }
        } else {
            DIE("SEARCH FAILED");
        }
    }


    /**
     * Function: dreamcast_insert
     * Purpose:  ONE-TIME FUNCTION. Used in order to get dreamcast games and insert into PublishedOn Table.
     * Calls:    Called in members.php, if line isn't commented out.
     */
    function dreamcast_insert() {
        global $link;
        $table = "VideoGames";

        $query = "SELECT * FROM $table WHERE platform='Dreamcast'";
        $results = mysqli_query($link, $query);

        if ($results) {
            
            $table2 = "PublishedOn";
            
            while ($row = mysqli_fetch_array($results)) {        
                $gid = $row['gid'];
                $pubyear = $row['gyear'];

                $query2 = "INSERT INTO $table2 (gid, pid, pubyear) VALUES ($gid, 21, $pubyear)";
                $results2 = mysqli_query($link, $query2);
            }
        } else {
            DIE("SEARCH FAILED");
        }
    }

    
    /**
     * Function: arcade_insert
     * Purpose:  ONE-TIME FUNCTION. Used in order to get arcade games and insert into PublishedOn Table.
     * Calls:    Called in members.php, if line isn't commented out.
     */
    function arcade_insert() {
        global $link;
        $table = "VideoGames";

        $query = "SELECT * FROM $table WHERE platform='Arcade'";
        $results = mysqli_query($link, $query);

        if ($results) {
            
            $table2 = "PublishedOn";
            
            while ($row = mysqli_fetch_array($results)) {        
                $gid = $row['gid'];
                $pubyear = $row['gyear'];

                $query2 = "INSERT INTO $table2 (gid, pid, pubyear) VALUES ($gid, 28, $pubyear)";
                $results2 = mysqli_query($link, $query2);
            }
        } else {
            DIE("SEARCH FAILED");
        }
    }


    /**
     * Function: windows_insert
     * Purpose:  ONE-TIME FUNCTION. Used in order to get windows games and insert into PublishedOn Table.
     * Calls:    Called in members.php, if line isn't commented out.
     */
    function windows_insert() {
        global $link;
        $table = "VideoGames";

        $query = "SELECT * FROM $table WHERE platform='Windows'";
        $results = mysqli_query($link, $query);

        if ($results) {
            
            $table2 = "PublishedOn";
            
            while ($row = mysqli_fetch_array($results)) {        
                $gid = $row['gid'];
                $pubyear = $row['gyear'];

                $query2 = "INSERT INTO $table2 (gid, pid, pubyear) VALUES ($gid, 29, $pubyear)";
                $results2 = mysqli_query($link, $query2);
            }
        } else {
            DIE("SEARCH FAILED");
        }
    }
?>