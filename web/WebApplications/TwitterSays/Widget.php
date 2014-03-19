<?php
	include("php/functions.php");

	/* Immediately get tweets and write to json file */
	getTweetsByFilter();
?>




<!DOCTYPE html>
<html>
	<head>
	    <title>Twitter Says...</title>
	    <link rel="stylesheet" href="./css/ui-lib.min.css"></link>
	    <link rel="stylesheet" href="./css/main.css"></link>

	    <script>
		/*function getTweetsByFilter() {

			//var value = $("filter_dropdown right").attr("value");

			//if (value === "main") {
			$.getJSON('http://www.cs.usfca.edu/~jpbullalayao/WixApps/TwitterSays/development/tweets.json', function (data) {
				var output = '<div class="tweet">';
				output += '<p>Hello!</p>';
				output += '</div>';

				$('.feed').html(output);
			});
			//}
		}
		</script>
	</head>


	<style wix-style>
	    body {
	        background-color: {{style.colorWOpacity color}}; /* style parameter */
	    }
	    h1 {
        	/*{{Title}} /* font from the template */
        	/*{{style.fontFamily}};*/ /* only works once */
    	}
	</style>

	<body>
		<div class="widgetWrapper"> <!-- USED ONLY FOR DEVELOPING. REMOVE WHEN FINISHED -->
		    <!-- Container for twitter feed -->
		    <div class="twitterContainer">
		    	<h1 class="title">Twitter Says...</h1>

		    	<!-- Filters -->
		    	<div class="filters">
		    		<div class="filter_text left">Tweets from:</div> 
			    	<div class="filter_dropdown right" wix-model="filter" wix-ctrl="Dropdown">
					    <div value="all">All</div>
					    <div value="main">Main</div>
					    <div value="employees">Employees</div>
					    <div value="customers">Customers</div>
					</div>
					<div class="clear"></div>
				</div>

		    	<!-- Twitter Feed -->
		    	<div class="feed" wix-scroll="{height:400}">
		    		<p style="background:rgba(255,255,255,0.1)">
				    <!--<p style="height:250px;background:rgba(255,255,255,0.1)">-->
				    	

				    	<?php
				    	 	getTweetsByHashtag("#XboxOne");
				    	?> 
				    	<!--<?php getTweetsByHashtag("#WixHackathonSF") ?> <!-- HASHTAG CURRENTLY HARDCODED. CHANGE LATER.-->
				    </p>

				    <!-- If Employees tab pressed, get employees tweets -->
				</div>  

				<!-- Input -->
				<div class="tweetInput">
					<div class="tweetBox">
						<a href="https://twitter.com/intent/tweet?hashtags=WixHackathonSF">
							<input type="text" class="input" placeholder="#XboxOne" disabled="disabled">
						</a>
					</div>
				</div>
		    </div>
		</div>

		<!-- Debugging -->
		<?php //getTweetsByHashtag("#WixHackathonSF") ?>

		<!-- Necessary Javascript -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
        <script src="http://sslstatic.wix.com/services/js-sdk/1.26.0/js/Wix.js"></script>
        <script src="js/ui-lib.min.js"></script>
        <script type="text/javascript" src="//platform.twitter.com/widgets.js"></script> <!-- Twitter -->
        <script src="js/functions.js"></script>

        <script type="text/javascript">

        	/* Need this in order to display everything */
		    $(document).ready(function () {

		        Wix.UI.initialize({
		        	filter: 'all'
		            /*numOfImages: 100,
		            isIconShown: true,
		            imageVisibility: 'show',
		            imagesToSync: 0,
		            imageLink: false*/
		        });

		        Wix.getStyleParams(function(styleParams){
    				// styleParams is a map with all style values {colors:{}, numbers:{}, booleans:{}, fonts:{}}
				});

		        /* Set up AJAX */
		        connect();
		    });

		    Wix.UI.onChange('filter', function(value, key){
	        	value = Wix.UI.get('filter');

	        	/*if (value === 'all') {
	        		$('.feed').html("All selected");
	        	}*/

	        	if (value === 'main') {
	        		$('.feed').html("Main selected");
	        	}

	        	else if (value === 'employees') {
	        		$('.feed').html("Employees selected");
	        	}

	        	else if (value === 'customers') {
	        		$('.feed').html("Customers selected");
	        	}
			});
		</script>
	</body>
</html>