<?php

	function email($to, $from, $subject, $message, $user = NULL) {
        $from = "From: $from";
        if ($user) {
            $message = "From username: $user\n\n" . $message;
        }  
        mail($to, $subject, $message, $from);
    }

    function redirect_to($page) {
        header("Location: {$page}");
        exit();
    }

    function connect_to_db() {
    	$hostname = "cs333.db.10386188.hostedresource.com";
    	$username = "cs333";
        $dbname   = "cs333";
        $password = "Jourdanb21!";

        mysql_connect($hostname, $username, $password) OR DIE ("Unable to 
        connect to VGDB database! Please try again later.");
        mysql_select_db($dbname);
    }

    function check_errors($key) {
        global $errors;
        
        if (isset($errors[$key])) {
            echo "<span class=\"error\">*</span>";
        }
    }

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

    /* Called in results.php */
    function find_game($keyword) {
        $table = "VideoGames";

        $query = "SELECT * FROM $table WHERE `gname` LIKE '%$keyword%'";
        $result = mysql_query($query);

        if ($result) {
            echo "<div class=\"games center_game\">";
            echo "<table>";
            echo "<th><b>Games</b></th>";
            echo "<th class=\"year\"><b>Year</b></th>";
            while ($row = mysql_fetch_array($result)) {
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

    /* Called in platform.php */
    function get_num_games($pid) {
        $table = "PublishedOn";

        $query = "SELECT COUNT(pid) as count FROM $table WHERE pid=$pid";
        $results = mysql_query($query);

        if ($results) {
            $row = mysql_fetch_array($results);
            return $row['count'];
        }
    }

    /* Called in game.php */
    function get_game_info($gid) {
        $table = "VideoGames";

        $query = "SELECT * FROM $table WHERE gid=$gid";
        $result = mysql_query($query);

        if ($result) {
            $row = mysql_fetch_array($result);
            return $row;
        }
    }

    /* Called in game.php */
    function check_if_user_rated($gid, $userid) {
        $table = "RatedBy";

        $query = "SELECT * FROM $table WHERE gid=$gid AND userid=$userid";
        $results = mysql_query($query);

        if (mysql_num_rows($results) > 0) {
            return false; // User has rated already, not allowed to rate again
        } else {
            return true; // User is allowed to rate game
        }
    }

    /* Called in game.php */
    function insert_rating($gid, $userid, $urating) {
        $table = "RatedBy";

        $query = "INSERT INTO $table (gid, userid, urating) VALUES ($gid, $userid, $urating)";
        mysql_query($query);

        $query = "SELECT AVG(urating) as avg FROM $table WHERE gid=$gid";
        $results = mysql_query($query);

        if ($results) {
            $row = mysql_fetch_array($results);
            $avg = $row['avg'];
            $table = "VideoGames";

            /* Update grating for game */
            $query = "UPDATE $table SET grating=$avg WHERE gid=$gid";
            mysql_query($query);
        }
    }

    function check_if_user_favorited($gid, $userid) {
        $table = "FavoritedBy";

        $query = "SELECT * FROM $table WHERE gid=$gid AND userid=$userid";
        $results = mysql_query($query);

        if (mysql_num_rows($results) > 0) {
            return false; // User has rated already, not allowed to rate again
        } else {
            return true; // User is allowed to rate game
        }
    }

    /* Called in game.php */
    function insert_favorite($gid, $userid) {
        $table = "FavoritedBy";

        $query = "INSERT INTO $table (gid, userid) VALUES ($gid, $userid)";
        mysql_query($query);
    }

    /* Called in platforms.php */
    function sort_by_pname () {
    	$table = "Platforms";
    	$query = "SELECT * FROM $table ORDER BY pname ASC";
    	$result = mysql_query($query);

    	if ($result) {
        	echo "<div class=\"platforms center_platform\">";
        	echo "<table>";
        	echo "<th><b>Platform</b></th>";
        	echo "<th class=\"year\"><b>Year</b></th>";
            while ($row = mysql_fetch_array($result)) {
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

    /* Called in platforms.php */
    function sort_by_pyear () {
    	$table  = "Platforms";
    	$query  = "SELECT * FROM $table ORDER BY pyear ASC";
    	$result = mysql_query($query);

    	if ($result) {
        	echo "<div class=\"platforms center_platform\">";
        	echo "<table>";
        	echo "<th><b>Platform</b></th>";
        	echo "<th class=\"year\"><b>Year</b></th>";
            while ($row = mysql_fetch_array($result)) {
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

    /* Called in developers.php */
    function get_developers_table() {
        $table = "Developers";

        $query = "SELECT * FROM $table";
        $results = mysql_query($query);

        if ($results) {
            while ($row = mysql_fetch_array($results)) {
                $dname   = $row["dname"];
                $founded = $row["founded"];
                echo "<tr class=\"developer\">";
                echo "<td><a href=\"developer.php?did=".get_did($dname)."\" class=\"red\">$dname</a></td>";
                echo "<td class=\"year\">$founded</td>";
                echo "</tr>";
            }
        }
    }

    /* Helper function for get_developers_table() */
    function get_did($dname) {
        $table = "Developers";

        $query = "SELECT did FROM $table WHERE dname=\"$dname\"";
        $results = mysql_query($query);

        if ($results) {
            $row = mysql_fetch_array($results); 
            return $row["did"];
        }
    }

    /* Called in developer.php */
    function get_developer_info($did) {
        $table = "Developers";

        $query = "SELECT * FROM $table WHERE did=$did";
        $results = mysql_query($query);

        if ($results) {
            $row = mysql_fetch_array($results); 
            return $row;
        }
    }

    /* Called in developer.php */
    function get_developed_games($did) {
        $table = "DevelopedBy";

        $query = "SELECT gid FROM $table WHERE did=$did";
        $results = mysql_query($query);

        if ($results) {
            while ($row = mysql_fetch_array($results)) {
                $gid = $row['gid'];
                get_games_by_gid($gid);
            }
        }
    }

    /* Called in developer.php */
    function get_num_games_developed($did) {
        $table = "DevelopedBy";

        $query = "SELECT COUNT(gid) as count FROM $table WHERE did=$did";
        $results = mysql_query($query);

        if ($results) {
            $row = mysql_fetch_array($results);
            return $row['count'];
        }
    }

    /* Called in platforms.php */
    function get_platforms_table() {
    	$table  = "Platforms";

	    $query  = "SELECT * FROM $table";
        $result = mysql_query($query);

        if ($result) {
        	echo "<div class=\"platforms center_platform\">";
        	echo "<table>";
        	echo "<th><b>Platform</b></th>";
        	echo "<th class=\"year\"><b>Year</b></th>";
            while ($row = mysql_fetch_array($result)) {
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
	
    /* Called in platform.php */
    function get_gid_by_pid($pid) {
        $table = "PublishedOn";

        $query = "SELECT * FROM $table WHERE pid=$pid";
        $results = mysql_query($query);

        if ($results) {
            echo "<div class=\"games center_game\">";
            echo "<table>";
            echo "<th><b>Games</b></th>";
            echo "<th class=\"year\"><b>Year</b></th>";
            while ($row = mysql_fetch_array($results)) {
                $gid = $row['gid'];
                get_games_by_gid($gid);
            }
            echo "</table>";
            echo "</div>";
        }
    }

    /* Helper function for get_gid_by_pid(), and get_developed_games() */
    function get_games_by_gid($gid) {
        $table = "VideoGames";

        $query = "SELECT * FROM $table WHERE gid=$gid";
        $results = mysql_query($query);

        if ($results) {
            $row = mysql_fetch_array($results);
            $gname = $row["gname"];
            $gyear = $row["gyear"];
            echo "<tr class=\"game\">";
            echo "<td><a href=\"game.php?gid=$gid\" class=\"red\">$gname</a></td>";
            echo "<td class=\"year\">$gyear</td>";
            echo "</tr>";
        }
    }

    /* Called from members.php */
    function get_users_table() {
        $table = "Users";

        $query  = "SELECT * FROM $table";
        $result = mysql_query($query);

        if ($result) {
            echo "<div class=\"users center_user\">";
            echo "<table>";
            echo "<th><b>Username</b></th>";
            echo "<th class=\"email\"><b>E-mail</b></th>";
            while ($row = mysql_fetch_array($result)) {
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

    /* Called in profile.php */
    function get_username($userid) {
        $table = "Users";

        $query = "SELECT username FROM $table WHERE userid=$userid";
        $results = mysql_query($query);

        if ($results) {
            $row = mysql_fetch_array($results);
            return $row['username'];
        }
    }

    /* Called in profile.php */
    function get_email($userid) {
        $table = "Users";

        $query = "SELECT email FROM $table WHERE userid=$userid";
        $results = mysql_query($query);

        if ($results) {
            $row = mysql_fetch_array($results);
            return $row['email'];
        }
    }

    /* Called in profile.php */
    function get_games_favorited($userid) {
        $table = "FavoritedBy";

        $query = "SELECT * FROM $table WHERE userid=$userid";
        $results = mysql_query($query);

        if ($results) {
            echo "<ul>";
            while ($row = mysql_fetch_array($results)) {
                $gid   = $row['gid'];
                $gname = get_game_name($gid);
                echo "<li><a href=\"game.php?gid=$gid\" class=\"red\">$gname</a></li>";
            }
            echo "</ul>";
        }
    }

    /* Helper function for get_games_favorited() and get_games_rated*/
    function get_game_name($gid) {
        $table = "VideoGames";

        $query = "SELECT gname FROM $table WHERE gid=$gid";
        $results = mysql_query($query);

        if ($results) {
            $row = mysql_fetch_array($results);
            return $row['gname'];
        }
    }

    /* Called in profile.php */
    function get_num_games_favorited($userid) {
        $table = "FavoritedBy";

        $query = "SELECT COUNT(gid) as count FROM $table WHERE userid=$userid";
        $results = mysql_query($query);

        if ($results) {
            $row = mysql_fetch_array($results);
            return $row['count'];
        }
    }

    /* Called in profile.php */
    function get_games_rated($userid) {
        $table = "RatedBy";

        $query = "SELECT * FROM $table WHERE userid=$userid";
        $results = mysql_query($query);

        if ($results) {
            echo "<ul>";
            while ($row = mysql_fetch_array($results)) {
                $gid     = $row['gid'];
                $urating = $row['urating'];
                $gname   = get_game_name($gid);
                echo "<li><a href=\"game.php?gid=$gid\" class=\"red\">$gname ($urating)</a></li>";
            }
            echo "</ul>";
        }
    }

    /* Called in profile.php */
    function get_num_games_rated($userid) {
        $table = "RatedBy";

        $query = "SELECT COUNT(gid) as count FROM $table WHERE userid=$userid";
        $results = mysql_query($query);

        if ($results) {
            $row = mysql_fetch_array($results);
            return $row['count'];
        }
    }

    /* Called in games.php */
    function get_games_table() {
        $table = "VideoGames";

        $query  = "SELECT * FROM $table";
        $result = mysql_query($query);

        if ($result) {
            echo "<div class=\"games center_game\">";
            echo "<table>";
            echo "<th><b>Games</b></th>";
            echo "<th class=\"year\"><b>Year</b></th>";
            while ($row = mysql_fetch_array($result)) {
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

    /* Helper function for find_game() */
    function get_gid($gname) {
        $table = "VideoGames";

        $query = "SELECT gid FROM $table WHERE gname='$gname'";
        $result = mysql_query($query);

        if ($result) {
            $row = mysql_fetch_array($result);
            return $row['gid'];
        }
    }

	function get_pid($pname) {
        $table = "Platforms";

        $query = "SELECT pid FROM $table WHERE pname='$pname'";
        $result = mysql_query($query);

        if ($result) {
            $row = mysql_fetch_array($result);
            return $row['pid'];
        }
    }

    function get_platform_info($pid) {
        $table = "Platforms";

        $query = "SELECT * FROM $table WHERE pid=$pid";
        $result = mysql_query($query);

        if ($result) {
            $row = mysql_fetch_array($result);
            return $row;
        } 
    }

    function check_internet($row) {
        if ($row['internet'] === 'Y') {
            return "Yes";
        } else {
            return "No";
        }
    }

	function check_pc() {
		if (isset($_POST['pc'])) {
			echo "checked";
		}
	}
	
	function check_ps() {
		if (isset($_POST['ps'])) {
			echo "checked";
		}
	}
	
	function check_ps2() {
		if (isset($_POST['ps2'])) {
			echo "checked";
		}
	}
	
	function check_ps3() {
		if (isset($_POST['ps3'])) {
			echo "checked";
		}
	}
	
	function check_ps4() {
		if (isset($_POST['ps'])) {
			echo "checked";
		}
	}
	
	function check_xbox() {
		if (isset($_POST['xbox'])) {
			echo "checked";
		}
	}
	
	function check_x360() {
		if (isset($_POST['x360'])) {
			echo "checked";
		}
	}
	
	function check_x1() {
		if (isset($_POST['x1'])) {
			echo "checked";
		}
	}
	
	function check_wii() {
		if (isset($_POST['wii'])) {
			echo "checked";
		}
	}
	
	function check_wiiu() {
		if (isset($_POST['wiiu'])) {
			echo "checked";
		}
	}

    function check_ds() {
        if (isset($_POST['ds'])) {
            echo "checked";
        }
    }

    function check_3ds() {
        if (isset($_POST['3ds'])) {
            echo "checked";
        }
    }

    function check_otherplatform() {
        if (isset($_POST['otherplatform'])) {
            echo "checked";
        }
    }

    /* Called in addGame.php */
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

    /* Called in addGame.php */
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


    function check_rpg() {
        if (isset($_POST['rpg'])) {
            echo "checked";
        }
    }

    function check_action() {
        if (isset($_POST['action'])) {
            echo "checked";
        }
    }

    function check_adventure() {
        if (isset($_POST['adventure'])) {
            echo "checked";
        }
    }

    function check_shooter() {
        if (isset($_POST['shooter'])) {
            echo "checked";
        }
    }

    function check_simulation() {
        if (isset($_POST['simulation'])) {
            echo "checked";
        }
    }

    function check_strategy() {
        if (isset($_POST['strategy'])) {
            echo "checked";
        }
    }

    function check_othergenre() {
        if (isset($_POST['othergenre'])) {
            echo "checked";
        }
    }

    /* Called in addGame.php */
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


    /* Called in register.php */
    function check_username($username) {
        global $errors;

        if (!$username) {
            $errors['username'] = "Please specify a username.";
        } else {

            if (strlen($username) < 4 || strlen($username) > 30) {
                $errors['username'] = "Username must be between 4 and 30 characters.";
            }

            /* Query to check if username is already in use */
            $table = "Users";
            $query = "SELECT * FROM $table WHERE username='$username'";
            $result = mysql_query($query);

            /* There's a matching username */
            if (mysql_num_rows($result) > 0) {
                $errors['username'] = "Username is already in use.";
            }
        }
    }

    /* Called in register.php */
    function check_email($email) {
        global $errors;
        
        if (!$email) {
            $errors['email'] = "Please specify an e-mail.";
        } else {

            /* Query to check if email is already in use */
            $table = "Users";
            $query = "SELECT * FROM $table WHERE email='$email'";
            $result = mysql_query($query);

            /* There's a matching username */
            if (mysql_num_rows($result) > 0) {
                $errors['email'] = "E-mail is already in use.";
            }
        }
    }

    /* Called in register.php */
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

    /* Called in register.php */
    function register_user($username, $email, $password) {
        $table = "Users";

        $query  = "INSERT INTO $table (username, password, email) ";
        $query .= "VALUES ('$username', '$password', '$email')";
        mysql_query($query);
    }

    function get_userid($username) {
        $table = "Users";

        $query = "SELECT userid FROM $table WHERE username='$username'";
        $results = mysql_query($query);

        if ($results) {
            $row = mysql_fetch_array($results);
            return $row['userid'];
        }
    }

    /* Called in login.php */
    function try_login($username, $password) {
        global $errors;
        $table = "Users";

        $query     = "SELECT * FROM $table WHERE username='$username'";
        $results   = mysql_query($query);
        $row       = mysql_fetch_assoc($results);
        $stored_pw = $row['password'];

        if (password_check($password, $stored_pw)) {
            return true;
        } else {
            $errors['login'] = "Incorrect username and/or password";
            return false;
        }
    }

    /* Helper function for try_login() */
    function password_check($password, $existing_hash) {
        // existing hash contains format and salt at start
        $hash = crypt($password, $existing_hash);

        if ($hash === $existing_hash) {
            return true;
        } else {
            return false;
        }
    }

    function logged_in() {
        return isset($_SESSION['username']);
    }

    function logout() {
        $_SESSION['userid'] = null;
        $_SESSION['username'] = null;
        redirect_to('redirect.php?action=logout');
    }

    function snes_insert() {
        $table = "VideoGames";

        $query = "SELECT * FROM $table WHERE platform='SNES'";
        $results = mysql_query($query);

        if ($results) {
            
            $table2 = "PublishedOn";
            
            while ($row = mysql_fetch_array($results)) {        
                $gid = $row['gid'];
                $pubyear = $row['gyear'];

                $query2 = "INSERT INTO $table2 (gid, pid, pubyear) VALUES ($gid, 2, $pubyear)";
                $results2 = mysql_query($query2);
            }
        } else {
            DIE("SEARCH FAILED");
        }
    }    


    function nes_insert() {
        $table = "VideoGames";

        $query = "SELECT * FROM $table WHERE platform='NES'";
        $results = mysql_query($query);

        if ($results) {
            
            $table2 = "PublishedOn";
            
            while ($row = mysql_fetch_array($results)) {        
                $gid = $row['gid'];
                $pubyear = $row['gyear'];

                $query2 = "INSERT INTO $table2 (gid, pid, pubyear) VALUES ($gid, 3, $pubyear)";
                $results2 = mysql_query($query2);
            }
        } else {
            DIE("SEARCH FAILED");
        }
    }
    
    function gameboy_insert() {
        $table = "VideoGames";

        $query = "SELECT * FROM $table WHERE platform='Game Boy'";
        $results = mysql_query($query);

        if ($results) {
            
            $table2 = "PublishedOn";
            
            while ($row = mysql_fetch_array($results)) {        
                $gid = $row['gid'];
                $pubyear = $row['gyear'];

                $query2 = "INSERT INTO $table2 (gid, pid, pubyear) VALUES ($gid, 8, $pubyear)";
                $results2 = mysql_query($query2);
            }
        } else {
            DIE("SEARCH FAILED");
        }
    }

    function ps1_insert() {
        $table = "VideoGames";

        $query = "SELECT * FROM $table WHERE platform='PS1'";
        $results = mysql_query($query);

        if ($results) {
            
            $table2 = "PublishedOn";
            
            while ($row = mysql_fetch_array($results)) {        
                $gid = $row['gid'];
                $pubyear = $row['gyear'];

                $query2 = "INSERT INTO $table2 (gid, pid, pubyear) VALUES ($gid, 13, $pubyear)";
                $results2 = mysql_query($query2);
            }
        } else {
            DIE("SEARCH FAILED");
        }
    }


    function dreamcast_insert() {
        $table = "VideoGames";

        $query = "SELECT * FROM $table WHERE platform='Dreamcast'";
        $results = mysql_query($query);

        if ($results) {
            
            $table2 = "PublishedOn";
            
            while ($row = mysql_fetch_array($results)) {        
                $gid = $row['gid'];
                $pubyear = $row['gyear'];

                $query2 = "INSERT INTO $table2 (gid, pid, pubyear) VALUES ($gid, 21, $pubyear)";
                $results2 = mysql_query($query2);
            }
        } else {
            DIE("SEARCH FAILED");
        }
    }

    /* ONE-TIME FUNCTION: Used in order to get arcade games and insert into PublishedOn Table */
    function arcade_insert() {
        $table = "VideoGames";

        $query = "SELECT * FROM $table WHERE platform='Arcade'";
        $results = mysql_query($query);

        if ($results) {
            
            $table2 = "PublishedOn";
            
            while ($row = mysql_fetch_array($results)) {        
                $gid = $row['gid'];
                $pubyear = $row['gyear'];

                $query2 = "INSERT INTO $table2 (gid, pid, pubyear) VALUES ($gid, 28, $pubyear)";
                $results2 = mysql_query($query2);
            }
        } else {
            DIE("SEARCH FAILED");
        }
    }

    function windows_insert() {
        $table = "VideoGames";

        $query = "SELECT * FROM $table WHERE platform='Windows'";
        $results = mysql_query($query);

        if ($results) {
            
            $table2 = "PublishedOn";
            
            while ($row = mysql_fetch_array($results)) {        
                $gid = $row['gid'];
                $pubyear = $row['gyear'];

                $query2 = "INSERT INTO $table2 (gid, pid, pubyear) VALUES ($gid, 29, $pubyear)";
                $results2 = mysql_query($query2);
            }
        } else {
            DIE("SEARCH FAILED");
        }
    }
?>