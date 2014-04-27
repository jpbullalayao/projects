/* Home Page Minimizing/Maximizing */
$("#announcements .collapse_img").click(function() {
	if ($("#announcements").find("article").is(':visible')) {
		$("#announcements").find("article").slideUp("fast");
		$("#announcements img").attr('src', "images/collapse_collapsed.gif");
		$("#announcements img").attr('alt', "[+]");
		$("#announcements img").attr('title', "[+]");
	} else {
		$("#announcements").find("article").slideDown("fast");
		$("#announcements img").attr('src', "images/collapse.gif");
		$("#announcements img").attr('alt', "[-]");
		$("#announcements img").attr('title', "[-]");
	}
});

$("#poll .collapse_img").click(function() {
	if ($("#poll").find("form").is(':visible')) {
		$("#poll").find("form").slideUp("fast");
		$("#poll img").attr('src', "images/collapse_collapsed.gif");
		$("#poll img").attr('alt', "[+]");
		$("#poll img").attr('title', "[+]");
	} else {
		$("#poll").find("form").slideDown("fast");
		$("#poll img").attr('src', "images/collapse.gif");
		$("#poll img").attr('alt', "[-]");
		$("#poll img").attr('title', "[-]");
	}
});

$("#posts .collapse_img").click(function() {
	if ($("#posts").find(".content_wrapper").is(':visible')) {
		$("#posts").find(".content_wrapper").slideUp("fast");
		$("#posts img").attr('src', "images/collapse_collapsed.gif");
		$("#posts img").attr('alt', "[+]");
		$("#posts img").attr('title', "[+]");
	} else {
		$("#posts").find(".content_wrapper").slideDown("fast");
		$("#posts img").attr('src', "images/collapse.gif");
		$("#posts img").attr('alt', "[-]");
		$("#posts img").attr('title', "[-]");
	}
});

$("#twitter_feed .collapse_img").click(function() {
	if ($("#twitter_feed").find(".content_wrapper").is(':visible')) {
		$("#twitter_feed").find(".content_wrapper").slideUp("fast");
		$("#twitter_feed img").attr('src', "images/collapse_collapsed.gif");
		$("#twitter_feed img").attr('alt', "[+]");
		$("#twitter_feed img").attr('title', "[+]");
	} else {
		$("#twitter_feed").find(".content_wrapper").slideDown("fast");
		$("#twitter_feed img").attr('src', "images/collapse.gif");
		$("#twitter_feed img").attr('alt', "[-]");
		$("#twitter_feed img").attr('title', "[-]");
	}
});

$("#homelatestreviews .collapse_img").click(function() {
	if ($("#homelatestreviews").find("article").is(':visible')) {
		$("#homelatestreviews").find("article").slideUp("fast");
		$("#homelatestreviews img").attr('src', "images/collapse_collapsed.gif");
		$("#homelatestreviews img").attr('alt', "[+]");
		$("#homelatestreviews img").attr('title', "[+]");
	} else {
		$("#homelatestreviews").find("article").slideDown("fast");
		$("#homelatestreviews img").attr('src', "images/collapse.gif");
		$("#homelatestreviews img").attr('alt', "[-]");
		$("#homelatestreviews img").attr('title', "[-]");
	}
});

$("#homelatestguides .collapse_img").click(function() {
	if ($("#homelatestguides").find("article").is(':visible')) {
		$("#homelatestguides").find("article").slideUp("fast");
		$("#homelatestguides img").attr('src', "images/collapse_collapsed.gif");
		$("#homelatestguides img").attr('alt', "[+]");
		$("#homelatestguides img").attr('title', "[+]");
	} else {
		$("#homelatestguides").find("article").slideDown("fast");
		$("#homelatestguides img").attr('src', "images/collapse.gif");
		$("#homelatestguides img").attr('alt', "[-]");
		$("#homelatestguides img").attr('title', "[-]");
	}
});

$("#updates .collapse_img").click(function() {
	if ($("#updates").find("article").is(':visible')) {
		$("#updates").find("article").slideUp("fast");
		$("#updates img").attr('src', "images/collapse_collapsed.gif");
		$("#updates img").attr('alt', "[+]");
		$("#updates img").attr('title', "[+]");
	} else {
		$("#updates").find("article").slideDown("fast");
		$("#updates img").attr('src', "images/collapse.gif");
		$("#updates img").attr('alt', "[-]");
		$("#updates img").attr('title', "[-]");	
	}
});



/* Search Field */
$("#search_field").blur(function() {
	if (this.value == '') {
		$(this).css({"color": "#999999"});
		this.value = 'Search';
	} 
});

$("#search_field").focus(function() {
	if (this.value == 'Search') {
		$(this).css({"color": "#000000"});
		this.value = '';
	} 
});


/* Username Field */
$(".username").blur(function() {
	$(this).css({"color": "#999999"});
	if (this.value == '') {
		this.value = 'Username';
	}
});

$(".username").focus(function() {
	$(this).css({"color": "#000000"});
	if (this.value == 'Username') {
		this.value = '';
	}
});


/* Password Field */
$(".password").blur(function() {
	$(this).css({"color": "#999999"});
	if (this.value == '') {
		this.value = 'Password';
	}
});

$(".password").focus(function() {
	$(this).css({"color": "#000000"});
	if (this.value == 'Password') {
		this.value = '';
	}
});


/* Rest of Home Page */
//$("#posts li:even").css({background: "rgba(255, 255, 255, 0.1)"});
$("#posts li:odd").css({background: "rgba(25, 25, 25, 0.5)"});
//$("#posts li:odd").css({'margin-bottom', '10px', background: "rgba(25, 25,25, 0.5)"});



/* Guide/Review List NOT USED */
$("h4, li.console").click(function() {
	$(this).next(".gameList").slideToggle("fast");
});

$(".gameList ul li:even").css({background: "#404040"});
$(".gameList ul li:odd").css({background: "#252525"});

/*$(".gameList ul li:even").css({background: "#606060"});
$(".gameList ul li:odd").css({background: "#707070"});*/

$("h4").each(function() {
	var node = $(this).children().children().children(":first");
	var count = $(this).next().children().children("li").length;
	node.html(count);
});


/* User Dashboard */
$("#location").blur(function() {
	$(this).css({color: "#999999"});
	if (this.value == '') {
		this.value = 'Where are you from?';
	}
});

$("#location").focus(function() {
	if (this.value == 'Where are you from?') {
		$(this).css({color: "#000000"});
		this.value = '';
	}
});

$("#xone_gamertag, #x360_gamertag").blur(function() {
	$(this).css({color: "#999999"});
	if (this.value == '') {
		this.value = 'Enter gamertag...';
	}
});


$("#xone_gamertag, #x360_gamertag").focus(function() {
	if (this.value == 'Enter gamertag...') {
		$(this).css({color: "#000000"});
		this.value = '';
	}
});


$("#ps4_psn, #ps3_psn").blur(function () {
	$(this).css({color:"#999999"});
	if(this.value == '') {
		this.value = 'Enter PSN...';
	}
});


$("#ps4_psn, #ps3_psn").focus(function () {
	if(this.value == 'Enter PSN...') {
		$(this).css({color:"#000000"});
		this.value = '';
	}
});

$("#steam_user").blur(function () {
	$(this).css({color:"#999999"});
	if(this.value == '') {
		this.value = 'Enter Steam name...';
	}
});


$("#steam_user").focus(function () {
	if(this.value == 'Enter Steam name...') {
		$(this).css({color:"#000000"});
		this.value = '';
	}
});


$("#wii_part1, #wii_part2, #wii_part3, #wii_part4, #_3ds_part1, #_3ds_part2, #_3ds_part3").blur(function () {
	$(this).css({color:"#999999"});
	if(this.value == '') {
		this.value = '####';
	}
});


$("#wii_part1, #wii_part2, #wii_part3, #wii_part4, #_3ds_part1, #_3ds_part2, #_3ds_part3").focus(function () {
	if(this.value == '####') {
		$(this).css({color:"#000000"});
		this.value = '';
	}
});


$("#wiiu_user").blur(function () {
	$(this).css({color:"#999999"});
	if(this.value == '') {
		this.value = 'Enter Nintendo Network I.D...';
	}
});


$("#wiiu_user").focus(function () {
	if(this.value == 'Enter Nintendo Network I.D...') {
		$(this).css({color:"#000000"});
		this.value = '';
	}
});


$("#facebook").blur(function() {
	$(this).css({color:"#999999"});
	if(this.value == '') {		
		this.value = 'Enter your Facebook URL...'
	}
});

$("#facebook").focus(function() {
	if(this.value == 'Enter your Facebook URL...') {
		$(this).css({color:"#000000"});
		this.value = '';
	}
});

$("#youtube").blur(function() {
	$(this).css({color:"#999999"});
	if(this.value == '') {
		this.value = 'Enter your YouTube URL...'
	}
});

$("#youtube").focus(function() {
	if(this.value == 'Enter your YouTube URL...') {
		$(this).css({color:"#000000"});
		this.value = '';
	}
});

$("#youtube").blur(function() {
	$(this).css({color:"#999999"});
	if(this.value == '') {
		this.value = 'Enter your YouTube URL...'
	}
});

$("#instagram, #twitter").focus(function() {
	if(this.value == '@' || this.value == '') {
		$(this).css({color:"#000000"});
	}
});

$("#instagram, #twitter").blur(function() {
	$(this).css({color:"#999999"});
	if(this.value == '') {
		this.value = "@";	
	}
});

$("#vine").focus(function() {
	if(this.value == 'Enter your Vine username...') {
		$(this).css({color:"#000000"});
		this.value = '';
	}
});

$("#vine").blur(function() {
	$(this).css({color:"#999999"});
	if(this.value == '') {
		this.value = "Enter your Vine username...";	
	}
});


$("#google_talk, #aim, #skype").focus(function() {
	if(this.value == 'Enter your screen name...') {
		$(this).css({color:"#000000"});
		this.value = '';
	}
});

$("#google_talk, #aim, #skype").blur(function() {
	$(this).css({color:"#999999"});
	if(this.value == '') {
		this.value = "Enter your screen name...";	
	}
});

/* Doesn't allow for bio length to go past 1000, when pasting. Also counts number of characters */
$("#bio_length").next("textarea").keyup(function() {
	$("#bio_length").text($(this).val().length);
	
	var count = $("#bio_length").next("textarea").val().length;

	if (count > 1000) {
		var text = $("#bio_length").next("textarea").val().substr(0, 1000);
		$("#bio_length").next("textarea").val(text);		
	}
});

/* Doesn't allow for bio length to go past 1000 when typing.*/
$("#bio_length").next("textarea").keypress(function () {
	var count = $("#bio_length").next("textarea").val().length;
	if (count >= 1000) {
		var text = $("#bio_length").next("textarea").val().substr(0, 1000);
		$("#bio_length").next("textarea").val(text);
		return false;
	}
});


/* Doesn't allow for signature length to go past 255, when pasting. Also counts number of characters */
$("#signature_length").next("textarea").keyup(function() {
	$("#signature_length").text($(this).val().length);
	
	var count = $("#signature_length").next("textarea").val().length;

	if (count > 255) {
		var text = $("#signature_length").next("textarea").val().substr(0, 255);
		$("#signature_length").next("textarea").val(text);		
	}	
});

/* Doesn't allow for signature length to go past 255 when typing.*/
$("#signature_length").next("textarea").keypress(function() {
	var count = $("#signature_length").next("textarea").val().length;

	if (count >= 255) {
		var text = $("#signature_length").next("textarea").val().substr(0, 255);
		$("#signature_length").next("textarea").val(text);		
		return false;
	}
});



/* FOOTERS */


/* Sets initial values */
$(function() {
	$("#bio_length").text(0);
	$("#signature_length").text(0);
	
	/* Feedback Text Area Resizing */
	var maxw = $("#feedback_comments").width();
	var minw = maxw;
	var minh = $("#feedback_comments").height();
	/*$("#feedback_comments").resizable({
		maxWidth: maxw,
		minHeight: minh,
		minWidth: minw});*/
		
});
