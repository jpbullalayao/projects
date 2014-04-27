<?php
    function redirect_to($page) {
        header("Location: {$page}");
        exit();
    }
    
	function connect_to_joomla_db() {
		 $hostname = "hya1316812541645.db.10386188.hostedresource.com";
         $username = "hya1316812541645";
         $dbname = "hya1316812541645";
         $password = "Hyakkidb21!";
         
         //Connecting to your database
         mysql_connect($hostname, $username, $password) OR DIE ("Unable to 
         connect to Hyakki's Corner database! Please try again later.");
         mysql_select_db($dbname);
	}
	
	function display_comment_box($MyBBI, $alias) {
		if ($MyBBI->isLoggedIn()) {      
	        echo "<form method=\"post\" action=\"$alias\">";
	        echo "<div id=\"user\">";
	        echo "<label for=\"username\">Username:</label>";
	        echo "<input type=\"text\" name=\"username\">";
	        echo "</div>";  
	        echo "<textarea class=\"comment_box\" name=\"comment_box\" rows=\"5\">Leave a comment... we watch for spam!</textarea>";         
	        echo "<div align=\"center\">";
	        echo "<input class=\"button\" type=\"submit\" name=\"submit\" value=\"submit\">";
	        echo "</div>";
	        echo "</form>";
	    }		 
	}
	
	function get_comments($db, $fid) {
		$query = "SELECT * FROM jos_comments WHERE fid='$fid' ORDER BY date DESC";
    	$db->setQuery($query);
    	$comments = $db->loadObjectList();
		
		if (!empty($comments)) { // List comments
  
	        foreach($comments as $comment) {
	            $date = date("n/j/y g:i A", strtotime($comment->date));
	            $uid = $comment->id;
	            $uid = $comment->uid;
	            $username = $comment->username;
	            $avatar = $comment->avatar;
	            $text = nl2br($comment->comment);         
	            
	            echo "<div class=\"comment\">"; 
	            echo "<table>";
	            echo "<tr>";
	            echo "<td class=\"commenter\"><a class=\"link\" href=\"forum/member.php?action=profile&uid={$uid}\">{$username}</a></td>";
	            echo "<td class=\"comment_text\" rowspan=\"3\">{$text}</td>";  
	            echo "</tr>";
	            echo "<tr>";
	            echo "<td class=\"commenter small\">{$date}</td>";
	            echo "</tr>";
				
				if (!empty($avatar)) {
		            echo "<tr>";
		            echo "<td class=\"commenter\"><img src=\"forum/{$avatar}\"></td>";
		            echo "</tr>";
				}
	            echo "</table>";
	            echo "</div>";
				echo "<hr class=\"orange_line\">"; 
	        }
	    } else { // No comments
	        echo "<p align=\"center\">No comments have been posted yet. <a class=\"link\" href=\"http://www.hyakkiscorner.com/forum/member.php?action=register\">
	        	Register</a> now to post a comment!</p>";
	    }
	}
	
	function post_comment($MyBBI, $db, $comment) {
	    $user = $MyBBI->getUser();
        $uid = $user['uid'];
        $username = $user['username'];
        $avatar = $user['avatar'];
        $fid = JRequest::getVar('id');  
  
        $alias = get_alias($db, $fid);
        $comment = mysql_real_escape_string(htmlentities(strip_tags($comment)));

        if ($comment) {
            $query = "INSERT INTO jos_comments (fid, alias, uid, username, avatar, comment, date) VALUES ($fid, '$alias', $uid, '$username', '$avatar', '$comment', NOW());";
            $db->setQuery($query);
            $result = $db->query();
  
            header("location: $alias");
		} else {
			/*redirect_to("redirect.php?action=comment_error");*/
		}
	}
	
	function get_alias($db, $fid) {		
		$query = "SELECT alias FROM jos_content WHERE id='$fid'";
        $db->setQuery($query);
        $result = $db->query();
        $alias = $db->loadResult();
		return $alias;
	}
	
	function get_latest_reviews() {
	    $db = JFactory::getDbo();
      
      	$query = 'SELECT * FROM `jos_content` WHERE catid = \'78\' AND state = \'1\' ORDER BY id DESC LIMIT 10;';
	    $db->setQuery($query);
	    $results = $db->loadObjectList();
	   
	    if (empty($results)) {
	        echo "<p class=\"margin_10\">No reviews have been published recently.</p>";
	    } else {
	        echo "<ul>";
	        foreach($results as $result) {
	            echo "<li><a href=\"http://hyakkiscorner.com/{$result->alias}\">{$result->title}</a></li>";
	        }
	        echo "</ul>";
	    }	
	}
	
	function get_all_reviews() {
		$db = JFactory::getDbo();
      
        // Display all published reviews in alphabetical order.
        $query = 'SELECT * FROM `jos_content` WHERE catid = \'78\' AND state = \'1\' ORDER BY title;'; 
        $db->setQuery($query);
        $results = $db->loadObjectList();
   
        if (empty($results)) {
            echo "<p style=\"margin:10px;\">No reviews have been published.</p>";
        } else {
            echo "<ul>";
            foreach($results as $result) {
                echo "<li><a href=\"http://hyakkiscorner.com/{$result->alias}\">{$result->title}</a></li>";
            }
            echo "</ul>";
        } 
	}
	
	function get_featured_reviews() {
		$db = JFactory::getDbo();
      
        // Display featured reviews in alphabetical order.
        $query = 'SELECT * FROM `jos_content` WHERE catid = \'78\' AND state = \'1\' AND featured = \'1\' ORDER BY title;';
        $db->setQuery($query);
        $results = $db->loadObjectList();
   
        if (empty($results)) {
            echo "<p style=\"margin:10px;\">No reviews have been featured.</p>";
        } else {
            echo "<ul>";
            foreach($results as $result) {
                echo "<li><a href=\"http://hyakkiscorner.com/{$result->alias}\">{$result->title}</a></li>";
            }
            echo "</ul>";
        } 
	}
	
	function get_latest_guides() {
		$db = JFactory::getDbo();
	      
	    $query = 'SELECT * FROM `jos_content` WHERE catid = \'79\' AND state = \'1\' ORDER BY id DESC LIMIT 10;';
	    $db->setQuery($query);
	    $results = $db->loadObjectList();
	   
	    if (empty($results)) {
	        echo "<p class=\"margin_10\">No guides have been published recently.</p>";
	    } else {
	        echo "<ul>";
	        foreach($results as $result) {
	            echo "<li><a href=\"http://hyakkiscorner.com/{$result->alias}\">{$result->title}</a></li>";
	        }  
	        echo "</ul>";
	    } 
	}
	
	function get_all_guides() {
		$db = JFactory::getDbo();
      
        // Display all published guides in alphabetical order.
        $query = 'SELECT * FROM `jos_content` WHERE catid = \'79\' AND state = \'1\' ORDER BY title;'; 
        $db->setQuery($query);
        $results = $db->loadObjectList();
   
        if (empty($results)) {
            echo "<p class=\"margin_10\">No guides have been published.</p>";
        } else {
            echo "<ul>";
            foreach($results as $result) {
                echo "<li><a href=\"http://hyakkiscorner.com/{$result->alias}\">{$result->title}</a></li>";
            }
        	echo "</ul>";
  		}
	}
	
	function get_featured_guides() {
		$db = JFactory::getDbo();
      
        // Display featured guides in alphabetical order.
        $query = 'SELECT * FROM `jos_content` WHERE featured = \'1\' AND catid = \'79\' AND state = \'1\' ORDER BY title'; 
        $db->setQuery($query);
        $results = $db->loadObjectList();
   
        if (empty($results)) {
            echo "<p class=\"margin_10\">No guides have been featured.</p>";
        } else {
            echo "<ul>";
            foreach($results as $result) {
                echo "<li><a href=\"http://hyakkiscorner.com/{$result->alias}\">{$result->title}</a></li>";
            }
        	echo "</ul>";
      	} 
	}
	
	function sitemap_get_reviews() {
		$db = JFactory::getDbo();
      
        // Display all published reviews in alphabetical order.
        $query = 'SELECT * FROM `jos_content` WHERE catid = \'78\' AND state = \'1\' ORDER BY title;'; 
        $db->setQuery($query);
        $results = $db->loadObjectList();
   
        if (!empty($results)) {
			echo "<h2>Video Game Reviews</h2>";
            echo "<ul>";
            foreach($results as $result) {
                echo "<li><a href=\"http://hyakkiscorner.com/{$result->alias}\">{$result->title}</a></li>";
            }
            echo "</ul>";
        }		
	}
	
	function sitemap_get_guides() {
		$db = JFactory::getDbo();
      
        // Display all published guides in alphabetical order.
        $query = 'SELECT * FROM `jos_content` WHERE catid = \'79\' AND state = \'1\' ORDER BY title;'; 
        $db->setQuery($query);
        $results = $db->loadObjectList();
   
        if (!empty($results)) {
        	echo "<h2>Video Game Guides</h2>";
            echo "<ul>";
            foreach($results as $result) {
                echo "<li><a href=\"http://hyakkiscorner.com/{$result->alias}\">{$result->title}</a></li>";
            }
        	echo "</ul>";
        }		
	}
	
	function get_latest_forum_posts() {
		define('IN_MYBB', NULL);
		global $mybb, $lang, $query, $db, $cache, $plugins, $displaygroupfields;
		require_once('forum/global.php');
		require_once('MyBBIntegrator.php');
		$MyBBI = new MyBBIntegrator($mybb, $db, $cache, $plugins, $lang, $config);       
		
		
		$fields = 't.`tid`, t.`fid`, t.`subject`, t.`uid`, t.`username`, t.`dateline`, t.`views`, t.`replies`, t.`numratings`, t.`totalratings`';
		$latest_threads = $MyBBI->getLatestThreads(0, $fields, 5, true, true, false);
		foreach ($latest_threads as $latest_thread)
		{
		    $line = "<li><a class=\"forum_post\" href=\"forum/showthread.php?tid=";
		    $line .= $latest_thread['tid'];
		    $line .= "\">";
		  
		    $thread = $latest_thread['subject'];
		        if (strlen($thread) > 37) {
		            $thread = substr($thread, 0, 36)."...";
		        }
		    $line .= htmlspecialchars_uni($thread);    
		    $line .= "</a><br>";
		    $line .= "<div><span class=\"post_stats\">Views: {$latest_thread['views']} Replies: {$latest_thread['replies']}</span>";
		    $line .= "<span class=\"author\">By: <a href=\"http://www.hyakkiscorner.com/forum/member.php?action=profile&uid={$latest_thread['uid']}\">{$latest_thread['username']}</a></span><div class=\"content_clear_floats\"></div></div></li>";
		
		    echo $line;
		}
	}

	function get_categories() {
		define('IN_MYBB', NULL);
		global $mybb, $lang, $query, $db, $cache, $plugins, $displaygroupfields;
		require_once('forum/global.php');
		require_once('MyBBIntegrator.php');
		$MyBBI = new MyBBIntegrator($mybb, $db, $cache, $plugins, $lang, $config); 
		
		$query = $MyBBI->db->query('SELECT * FROM `mybb_forums` WHERE type = \'c\';');
		
		// Gets categories from database
		while ($category = $MyBBI->db->fetch_array($query))
        {
            $categories[] = $category;
        }

		if (!empty($categories)) {
			echo "<br>";
			echo "<ul>";
			foreach($categories as $category) {
				echo "<li><h2><a href=\"http://www.hyakkiscorner.com/forum/forumdisplay.php?fid={$category['fid']}\">{$category['name']}</a></h2></li>";
				get_forums($category['fid'], $MyBBI);	
	
			}		
			echo "</ul>";
			echo "<br>";
		}
	}
	
	function get_forums($pid, $MyBBI) {
		$query = $MyBBI->db->query('SELECT * FROM `mybb_forums` WHERE type = \'f\' AND pid = \''.$pid.'\';');
		
		// Gets forums under the associated category from database
		while ($forum = $MyBBI->db->fetch_array($query))
		{
			$forums[] = $forum;
		}
		
		if (!empty($forums)) {
			echo "<br>";
			echo "<ul>";
			foreach($forums as $forum) {
				echo "<li><h3><a href=\"http://www.hyakkiscorner.com/forum/forumdisplay.php?fid={$forum['fid']}\">{$forum['name']}</a></h3></li>";
				get_threads($forum['fid'], $MyBBI);
			}		
			echo "</ul>";
			echo "<br>";
		}
	}
	
	function get_threads($fid, $MyBBI) {
		$query = $MyBBI->db->query('SELECT * FROM `mybb_threads` WHERE fid = \''.$fid.'\' ORDER BY tid DESC;');
		
		// Gets threads under associated forum from database
		while ($thread = $MyBBI->db->fetch_array($query)) {
			$threads[] = $thread;
		}
		
		//echo sizeof($threads);
		if (!empty($threads)) {
			echo "<br>";
			echo "<ul>";
			foreach($threads as $thread) {
				echo "<li><a href=\"http://www.hyakkiscorner.com/forum/showthread.php?tid={$thread['tid']}\">{$thread['subject']}</a></li>";
			}
			echo "</ul>";
			echo "<br>";
		}
	}
	
	function check_errors($key) {
        global $errors;
        
		
        if (isset($errors[$key])) {
            echo "<span class=\"error_star\">*</span>";
        }
    }
	
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