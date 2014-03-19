<?php
	include("php/functions.php");

	// WRITE FUNCTION TO SCAN THROUGH ALL CREATED FILTERS AND CREATE JSON FILES FOR THEM */

?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="./css/ui-lib.min.css"></link>
        <link rel="stylesheet" href="./css/main.css"></link>
    </head>
    <body wix-ui class="settingsWrapper"> <!-- KEEP WRAPPER WHEN DEVELOPING, TAKE OFF WHEN DONE -->


    	<div wix-scroll="{height:646}">
	        <header class="box">

	            <div class="logo">
	                <img width="86" src="images/wix_icon.png" alt="logo"/>
	            </div>
	            <div class="loggedOut">
	            	<!-- App Description -->
	            	<p>Display tweets from other users based on company hashtags, as well as tweets from site owner and employees! Site users can easily join discussions by replying, favoriting, retweeting, and tweeting
	            	   with company hashtags.</p>
	                <div class="login-panel"><!-- App login panel --></div>
	            </div>
	            <div class="loggedIn hidden">
	                <!-- App Logged in information -->
	                <div class="premium-panel"><!-- Premium features --></div>
	            </div>
	        </header>

	        <!-- Settings -->
	        <div>
				<!-- Accordion for different sections of settings -->
				<div wix-ctrl="Accordion">
				    <!--<div class="acc-pane">
				        <h3>Settings</h3>
				        <div class="acc-content">Accordion Content
				        </div>
				    </div>-->
				    <div class="acc-pane">
				        <h3>Appearance</h3>

				        <div class="acc-content">
				        	<p>Design your twitter feed to match the theme of your site.</p>
				        </div>

				        <div class="acc-content">
				        	<article class="subsection left">Background</article>
				        	<div wix-param="colorWOpacity" wix-ctrl="ColorPickerWithOpacity" wix-options="{startWithColor: 'color-1'}" class="subsectionWidth left"></div>
				        	<div class="clear"></div>
				        </div>

				        <div class="acc-content">
				        	<article class="subsection left">Title</article>
				        	<div class="subsectionSmallWidth left" wix-param="fontStyle" wix-ctrl="FontStylePicker"></div>
				        	<!--<div class="subsectionSmallWidth right" wix-param="bgColor" wix-ctrl="ColorPicker" class="border"></div>-->
				        	<!--<div class="subsectionSmallWidth left" wix-param="bgColor" wix-ctrl="ColorPicker" wix-options="{startWithColor: 'color-5'}"></div>-->
				        	<div class="clear"></div>
				        </div>

				        <div class="acc-content">
				        	<article class="subsection left">Tweets</article>
				        	<div wix-param="fontStyle" wix-ctrl="FontStylePicker" class="subsectionSmallWidth left"></div>
				        	<!--<div wix-param="bgColor" wix-ctrl="ColorPicker" wix-options="{startWithColor: 'color-5'}" class="subsectionSmallWidth"></div>-->
				        	<div class="clear"></div>
				        </div>

				        <div class="acc-content">
				        	<article class="subsection">Premade Templates</article>
				        	<p class="subsectionLeftMargin">Selection of premade templates will go here.</p>
				        </div>

				        <div class="acc-content">
				        	<article class="subsection">Custom Templates</article>
				        	<p class="subsectionLeftMargin">Custom templates that user made will go here.</p>
				        	<button class="uilib-btn btn-small subsectionLeftMargin">Add a Template</button>
				        </div>
				    </div>

				    <div class="acc-pane">
				        <h3>Filters</h3>

				        <div class="acc-content">
				        	<p>Manage your filters in order to organize your discussions.</p>
				        </div>

				        <div class="acc-content">
					        <article class="subsection left">All</article>
					        <article class="filter_subsection right"></article>
					       	<div class="clear"></div>
				        </div>

				        <div class="acc-content">
				        	<article class="subsection left">Main</article>
				        	<article class="filter_subsection right">@jay_raiii</article>
				        	<div class="clear"></div>
				        </div>

				        <div class="acc-content">
				        	<article class="subsection left">Employees</article>
				        	<article class="filter_subsection right">@Reesa2theRescue, @suervello</article>
				        	<div class="clear"></div>
				        </div>

				        <div class="acc-content">
				        	<article class="subsection">Customers</article>
				        	<!--<article class="filter_subsection right">@Reesa2theRescue, @suervello</article>
				        	<div class="clear"></div>-->
				        </div>


			        	<!-- Add filter, add button. After adding, go to $_POST request to pass in string, add it as an acc-content -->
			        	
			        	<div class="acc-content">
				        	<div wix-model="mySmallNumber" wix-ctrl="Input" wix-options="{placeholder: 'Add a filter...'}" class="subsectionMediumWidth left marg_top subsectionLeftMargin"></div>
				        	<button class="uilib-btn btn-small left">Add</button>
				        	<div class="clear"></div>
			        	</div>
				    </div>

				    <div class="acc-pane">
				        <h3>Tweets</h3>
				        <div class="acc-content">
				        	<p>Establish hashtags for discussions and gathering tweets.</p>
				        </div>
				        
				        <div class="acc-content">
				        	<article class="subsection">#WixHackathonSF</article>
				        </div>

				        <div class="acc-content">	
				        	<div wix-model="myHashtags" wix-ctrl="Input" wix-options="{placeholder: '#'}" class="subsectionMediumWidth left marg_top subsectionLeftMargin"></div>
				        	<button class="uilib-btn btn-small left">Add</button>

				        	<div class="clear"></div>
				        	
				        	<p class="subsectionLeftMargin">Tweets in queue:</p>
				        	<div class="box subsectionLeftMargin subsectionRightMargin" wix-scroll="{height:150}">
							    <p style="height:600px;background:rgba(255,255,255,0.7)">
							    	<?php getTweetsByHashtag("#WixHackathonSF") ?> <!-- Hashtag currently hardcoded -->
							    </p>
							</div>  
				        </div>
				    </div>
				</div>
	        </div>
    	</div>

    	<!-- Debugging -->


        <!-- your settings -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
        <script src="http://sslstatic.wix.com/services/js-sdk/1.26.0/js/Wix.js"></script>
        <script src="js/ui-lib.min.js"></script>

        <script type="text/javascript">

        	/* Need this in order to display everything */
		    $(document).ready(function () {

		        Wix.UI.initialize({
		            /*numOfImages: 100,
		            isIconShown: true,
		            imageVisibility: 'show',
		            imagesToSync: 0,
		            imageLink: false*/
		        });
		    });
		</script>

    </body>
</html>