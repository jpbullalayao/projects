<?php
    # Load Twitter classes
    require_once('php/TwitterOAuth.php');
    require_once('php/TwitterAPIExchange.php');

    # Define constants
    define('TWEET_LIMIT', 10);
    //define('TWITTER_USERNAME', 'jay_raiii');
    define('CONSUMER_KEY', 'IvHtgHZEEGSS4MR9stLU3A');
    define('CONSUMER_SECRET', 'PkVzmnx5wrcgzV34nyd4WPP3E7mdBWoPxArLM72U');
    define('ACCESS_TOKEN', '106922518-SAcF2xZ6cMAuWo6zsrp0x0LqZdTAvNkIBsLc1IZm');
    define('ACCESS_TOKEN_SECRET', '6g9kXZhXHddsDpLRAL74KwVtiwEzGonNqMdwdkT9NyOji');

    function getTweetsByUsername($username) {
        # Create the connection
        $twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);

        # Migrate over to SSL/TLS
        $twitter->ssl_verifypeer = true;

        # Load the Tweets
        $tweets = $twitter->get('statuses/user_timeline', array('screen_name' => $username, 'exclude_replies' => 'true', 'include_rts' => 'false', 'count' => TWEET_LIMIT));

        //$tweets = $twitter->get('statuses/user_timeline', array('screen_name' => TWITTER_USERNAME, 'exclude_replies' => 'true', 'include_rts' => 'false', 'count' => TWEET_LIMIT));

        # Example output
        //if(!empty($tweets)) {
            file_put_contents("tweets.json", json_encode($tweets));
            /*foreach($tweets as $tweet) {

                # Access as an object
                $tweetText = $tweet->text;

                # Make links active
               // $tweetText = preg_replace("/(http://|(www.))(([^s<]{4,68})[^s<]*)/", '<a href="http://$2$3" target="_blank">$1$2$4</a>', $tweetText);

                # Linkify user mentions
                //$tweetText = preg_replace("/@(w+)/", '<a href="http://www.twitter.com/$1" target="_blank">@$1</a>', $tweetText);

                # Linkify tags
                $tweetText = preg_replace("/#(\w+)/", "<a href=\"http://twitter.com/search?q=%23\\1&src=hash\" target=\"_blank\">\\1</a>", $tweetText);
                //$tweetText = preg_replace("/#(\w+)/", "<a href=\"http://search.twitter.com/search?q=\\1\" target=\"_blank\">#\\1</a>", $rtweetText);
                //$tweetText = preg_replace("/#(w+)/", '<a href="http://search.twitter.com/search?q=$1" target="_blank">#$1</a>', $tweetText);

                echo "<a href=\"http://www.twitter.com/{$tweet->user->screen_name}\">".$tweet->user->screen_name."</a>: ".$tweetText."<br><br>";
            }*/
        /*} else {
            echo "No tweets";
        }*/
    }

    function getTweetsByFilter() {

        // Have an array of filters set by user

        // for each filter, get tweets, write them to a JSON file
       
        // hardcode for all filter

        // hardcode for main filter
        getTweetsByUsername("jay_raiii");

        // hardcode for employee filter

        // hardcode for customer filter
    }


    function getTweetsByHashtag($hashtag) {

        $settings = array(
                    'oauth_access_token' => ACCESS_TOKEN,
                    'oauth_access_token_secret' => ACCESS_TOKEN_SECRET,
                    'consumer_key' => CONSUMER_KEY,
                    'consumer_secret' => CONSUMER_SECRET);
        // Your specific requirements
        $url = 'https://api.twitter.com/1.1/search/tweets.json';
        $requestMethod = 'GET';
        $getfield = '?q=#'.$hashtag.'&result_type=recent&count=10'; /* 10 for debugging purposes */

        // Perform the request
        $twitter = new TwitterAPIExchange($settings);
        $twitter->setGetfield($getfield)
                     ->buildOauth($url, $requestMethod)
                     ->performRequest();

        $json = $twitter->setGetfield($getfield)
                     ->buildOauth($url, $requestMethod)
                     ->performRequest();

        $response = json_decode($json);

        //if (!empty($response)) {
            //foreach($response['statuses'] as $tweet) {
            foreach($response->statuses as $tweet) {

                $tweetText = $tweet->text;

                # Linkify user mentions (NOT WORKING)
                $tweetText = preg_replace("/@(w+)/", '<a href="http://www.twitter.com/\\1" target="_blank">@\\1</a>', $tweetText);

                # Linkify tags (FIX SO THAT HASHTAGS ARE EXACTLY HOW THEY TYPED IT)
                $tweetText = preg_replace("/#(\w+)/", "<a href=\"http://twitter.com/search?q=%23\\1&src=hash\" target=\"_blank\">".$hashtag."</a>", $tweetText);


                echo "<div class=\"tweet\">";

                /* Image */
                echo "<div class=\"profPic left\"><img src=\"".$tweet->user->profile_image_url."\"></div>";

                echo "<div class=\"bio left\">";
                echo "<span class=\"bioName\">".$tweet->user->name."</span><br>";
                echo "<span class=\"bioName\"><b>@".$tweet->user->screen_name."</b></span><br>";
                echo "</div>"; /* Close "bio" div */

                echo "<div class=\"clear\"></div>";
                echo "<div class=\"tweetText\">".$tweetText."</div>";
                //<a href=\"http://www.twitter.com/{$tweet->user->screen_name}\">".$tweet->user->screen_name."</a>: ".$tweetText."<br>";
                //echo "<a href=\"http://www.twitter.com/{$tweet->user->screen_name}\">".$tweet->user->screen_name."</a>: ".$tweetText."<br>";

                echo "<div class=\"tweetOptions right\">";
                /* Reply */
                echo "<img src=\"images/twitter/reply.png\"><a class=\"tweetSmall\" href=\"http://www.twitter.com/intent/tweet?in_reply_to=".$tweet->id."\">Reply</a> ";

                /* Retweet */
                echo "<img src=\"images/twitter/retweet.png\"><a class=\"tweetSmall\" href=\"http://www.twitter.com/intent/retweet?tweet_id=".$tweet->id."\">Retweet</a> "; 

                /* Favorite */
                echo "<img src=\"images/twitter/favorite.png\"><a class=\"tweetSmall\" href=\"http://www.twitter.com/intent/favorite?tweet_id=".$tweet->id."\">Favorite</a> ";

                /* Follow */
                echo "<img src=\"images/twitter/follow.png\"><a class=\"tweetSmall\" href=\"http://www.twitter.com/intent/user?screen_name=".$tweet->user->screen_name."\">Follow</a>"; 
            
                echo "</div>"; /* Close "tweetOptions" div */
                echo "</div>"; /* Close "tweet" div */
                echo "<div class=\"clear\"></div>";
                echo "<hr>";

            }
        //}

        /* DEBUGGING PURPOSES */
        /*echo "<pre>";
        print_r($response);
        echo "</pre>";*/
    }
?>