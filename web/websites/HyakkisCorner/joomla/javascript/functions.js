var MyBB = {
	
	popupWindow: function(url, name, width, height)
	{
		settings = "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes";

		if(width)
		{
			settings = settings+",width="+width;
		}

		if(height)
		{
			settings = settings+",height="+height;
		}
		window.open(url, name, settings);
	},
	
	quickLogin: function()
	{		
		if($("quick_login"))
		{
			var form = new Element("form", { method: "post", action: "member.php" });
			//var form = new Element("form", { method: "post", action: "www.hyakkiscorner.com/forum/member.php" });
			form.insert({ bottom: new Element("input",
				{
					name: "action",
					type: "hidden",
					value: "do_login"
				})
			});

            //if(!document.location.href)
			if(document.location.href)
			{
				form.insert({ bottom: new Element("input",
					{
						name: "url",
						type: "hidden",
						value: this.HTMLchars(document.location.href)
					})
				});
			}

			form.insert({ bottom: new Element("input",
				{
					name: "quick_login",
					type: "hidden",
					value: "1"
				})
			});

			form.insert({ bottom: new Element("input",
				{
					name: "quick_username",
					id: "quick_login_username",
					type: "text",
					value: lang.username,
					"class": "textbox",
					onfocus: "if(this.value == '"+lang.username+"') { this.value=''; }",
					onblur: "if(this.value == '') { this.value='"+lang.username+"'; }"
				})
			}).insert({ bottom: "&nbsp;" });

			form.insert({ bottom: new Element("input",
				{
					name: "quick_password",
					id: "quick_login_password",
					type: "password",
					value: lang.password,
					"class": "textbox",
					onfocus: "if(this.value == '"+lang.password+"') { this.value=''; }",
					onblur: "if(this.value == '') { this.value='"+lang.password+"'; }"
				})
			}).insert({ bottom: "&nbsp;" });

			form.insert({ bottom: new Element("input",
				{
					name: "submit",
					type: "submit",
					value: lang.login,
					"class": "button"
				})
			});

			var span = new Element("span", { "class": "remember_me" }).insert({ bottom: new Element("input",
				{
					name: "quick_remember",
					id: "quick_login_remember",
					type: "checkbox",
					value: "yes",
					"class": "checkbox"
				})
			});

			span.innerHTML += "<label for=\"quick_login_remember\"> "+lang.remember_me+"</label>";
			form.insert({ bottom: span });

			form.innerHTML += lang.lost_password+lang.register_url;
	
			$("quick_login").innerHTML = "";
			$("quick_login").insert({ before: form });

			$("quick_login_remember").setAttribute("checked", "checked");
			$('quick_login_username').focus();
		}

		return false;
	}
}


/* Minimizing/Maximizing */
$("#announcements .collapse_img").click(function() {
	if ($("#announcements").find(".content_section").is(':visible')) {
		$("#announcements").find(".content_section").slideUp("fast");
		$("#announcements img:first").attr('src', "http://www.hyakkiscorner.com/images/collapse_collapsed.gif");
		$("#announcements img:first").attr('alt', "[+]");
		$("#announcements img:first").attr('title', "[+]");
	} else {
		$("#announcements").find(".content_section").slideDown("fast");
		$("#announcements img:first").attr('src', "http://www.hyakkiscorner.com/images/collapse.gif");
		$("#announcements img:first").attr('alt', "[-]");
		$("#announcements img:first").attr('title', "[-]");
	}
});

$("#hyakkis_poll .collapse_img").click(function() {
	if ($("#hyakkis_poll").find(".content_section").is(':visible')) {
		$("#hyakkis_poll").find(".content_section").slideUp("fast");
		$("#hyakkis_poll img:first").attr('src', "http://www.hyakkiscorner.com/images/collapse_collapsed.gif");
		$("#hyakkis_poll img:first").attr('alt', "[+]");
		$("#hyakkis_poll img:first").attr('title', "[+]");
	} else {
		$("#hyakkis_poll").find(".content_section").slideDown("fast");
		$("#hyakkis_poll img:first").attr('src', "http://www.hyakkiscorner.com/images/collapse.gif");
		$("#hyakkis_poll img:first").attr('alt', "[-]");
		$("#hyakkis_poll img:first").attr('title', "[-]");
	}
});

$("#posts .collapse_img").click(function() {
	if ($("#posts").find("#posts_wrapper").is(':visible')) {
		$("#posts").find("#posts_wrapper").slideUp("fast");
		$("#posts img:first").attr('src', "http://www.hyakkiscorner.com/images/collapse_collapsed.gif");
		$("#posts img:first").attr('alt', "[+]");
		$("#posts img:first").attr('title', "[+]");
	} else {
		$("#posts").find("#posts_wrapper").slideDown("fast");
		$("#posts img:first").attr('src', "http://www.hyakkiscorner.com/images/collapse.gif");
		$("#posts img:first").attr('alt', "[-]");
		$("#posts img:first").attr('title', "[-]");
	}
});

$("#tweets .collapse_img").click(function() {
	if ($("#tweets").find("div").is(':visible')) {
		$("#tweets").find("div").slideUp("fast");
		$("#tweets img:first").attr('src', "http://www.hyakkiscorner.com/images/collapse_collapsed.gif");
		$("#tweets img:first").attr('alt', "[+]");
		$("#tweets img:first").attr('title', "[+]");
	} else {
		$("#tweets").find("div").slideDown("fast");
		$("#tweets img:first").attr('src', "http://www.hyakkiscorner.com/images/collapse.gif");
		$("#tweets img:first").attr('alt', "[-]");
		$("#tweets img:first").attr('title', "[-]");
	}
});

$("#homelatestreviews .collapse_img").click(function() {
	if ($("#homelatestreviews").find(".review_list").is(':visible')) {
		$("#homelatestreviews").find(".review_list").slideUp("fast");
		$("#homelatestreviews img:first").attr('src', "http://www.hyakkiscorner.com/images/collapse_collapsed.gif");
		$("#homelatestreviews img:first").attr('alt', "[+]");
		$("#homelatestreviews img:first").attr('title', "[+]");
	} else {
		$("#homelatestreviews").find(".review_list").slideDown("fast");
		$("#homelatestreviews img:first").attr('src', "http://www.hyakkiscorner.com/images/collapse.gif");
		$("#homelatestreviews img:first").attr('alt', "[-]");
		$("#homelatestreviews img:first").attr('title', "[-]");
	}
});

$("#homelatestguides .collapse_img").click(function() {
	if ($("#homelatestguides").find(".guide_list").is(':visible')) {
		$("#homelatestguides").find(".guide_list").slideUp("fast");
		$("#homelatestguides img:first").attr('src', "http://www.hyakkiscorner.com/images/collapse_collapsed.gif");
		$("#homelatestguides img:first").attr('alt', "[+]");
		$("#homelatestguides img:first").attr('title', "[+]");
	} else {
		$("#homelatestguides").find(".guide_list").slideDown("fast");
		$("#homelatestguides img:first").attr('src', "http://www.hyakkiscorner.com/images/collapse.gif");
		$("#homelatestguides img:first").attr('alt', "[-]");
		$("#homelatestguides img:first").attr('title', "[-]");
	}
});

$("#updates .collapse_img").click(function() {
	if ($("#updates").find(".content_section").is(':visible')) {
		$("#updates").find(".content_section").slideUp("fast");
		$("#updates img:first").attr('src', "http://www.hyakkiscorner.com/images/collapse_collapsed.gif");
		$("#updates img:first").attr('alt', "[+]");
		$("#updates img:first").attr('title', "[+]");
	} else {
		$("#updates").find(".content_section").slideDown("fast");
		$("#updates img:first").attr('src', "http://www.hyakkiscorner.com/images/collapse.gif");
		$("#updates img:first").attr('alt', "[-]");
		$("#updates img:first").attr('title', "[-]");	
	}
});

$("#guides .collapse_img").click(function() {
	if ($("#guides").find(".content_section").is(':visible')) {
		$("#guides").find(".content_section").slideUp("fast");
		$("#guides img:first").attr('src', "http://www.hyakkiscorner.com/images/collapse_collapsed.gif");
		$("#guides img:first").attr('alt', "[+]");
		$("#guides img:first").attr('title', "[+]");
	} else {
		$("#guides").find(".content_section").slideDown("fast");
		$("#guides img:first").attr('src', "http://www.hyakkiscorner.com/images/collapse.gif");
		$("#guides img:first").attr('alt', "[-]");
		$("#guides img:first").attr('title', "[-]");	
	}
});

$("#featured_guides .collapse_img").click(function() {
	if ($("#featured_guides").find(".content_section").is(':visible')) {
		$("#featured_guides").find(".content_section").slideUp("fast");
		$("#featured_guides img:first").attr('src', "http://www.hyakkiscorner.com/images/collapse_collapsed.gif");
		$("#featured_guides img:first").attr('alt', "[+]");
		$("#featured_guides img:first").attr('title', "[+]");
	} else {
		$("#featured_guides").find(".content_section").slideDown("fast");
		$("#featured_guides img:first").attr('src', "http://www.hyakkiscorner.com/images/collapse.gif");
		$("#featured_guides img:first").attr('alt', "[-]");
		$("#featured_guides img:first").attr('title', "[-]");	
	}
});

$("#latest_guides .collapse_img").click(function() {
	if ($("#latest_guides").find(".content_section").is(':visible')) {
		$("#latest_guides").find(".content_section").slideUp("fast");
		$("#latest_guides img:first").attr('src', "http://www.hyakkiscorner.com/images/collapse_collapsed.gif");
		$("#latest_guides img:first").attr('alt', "[+]");
		$("#latest_guides img:first").attr('title', "[+]");
	} else {
		$("#latest_guides").find(".content_section").slideDown("fast");
		$("#latest_guides img:first").attr('src', "http://www.hyakkiscorner.com/images/collapse.gif");
		$("#latest_guides img:first").attr('alt', "[-]");
		$("#latest_guides img:first").attr('title', "[-]");	
	}
});

$("#overview .collapse_img").click(function() {
	if ($("#overview").find(".content_section").is(':visible')) {
		$("#overview").find(".content_section").slideUp("fast");
		$("#overview img:first").attr('src', "http://www.hyakkiscorner.com/images/collapse_collapsed.gif");
		$("#overview img:first").attr('alt', "[+]");
		$("#overview img:first").attr('title', "[+]");
	} else {
		$("#overview").find(".content_section").slideDown("fast");
		$("#overview img:first").attr('src', "http://www.hyakkiscorner.com/images/collapse.gif");
		$("#overview img:first").attr('alt', "[-]");
		$("#overview img:first").attr('title', "[-]");	
	}
});

$("#criteria .collapse_img").click(function() {
	if ($("#criteria").find(".content_section").is(':visible')) {
		$("#criteria").find(".content_section").slideUp("fast");
		$("#criteria img:first").attr('src', "http://www.hyakkiscorner.com/images/collapse_collapsed.gif");
		$("#criteria img:first").attr('alt', "[+]");
		$("#criteria img:first").attr('title', "[+]");
	} else {
		$("#criteria").find(".content_section").slideDown("fast");
		$("#criteria img:first").attr('src', "http://www.hyakkiscorner.com/images/collapse.gif");
		$("#criteria img:first").attr('alt', "[-]");
		$("#criteria img:first").attr('title', "[-]");	
	}
});

$("#reviews .collapse_img").click(function() {
	if ($("#reviews").find(".content_section").is(':visible')) {
		$("#reviews").find(".content_section").slideUp("fast");
		$("#reviews img:first").attr('src', "http://www.hyakkiscorner.com/images/collapse_collapsed.gif");
		$("#reviews img:first").attr('alt', "[+]");
		$("#reviews img:first").attr('title', "[+]");
	} else {
		$("#reviews").find(".content_section").slideDown("fast");
		$("#reviews img:first").attr('src', "http://www.hyakkiscorner.com/images/collapse.gif");
		$("#reviews img:first").attr('alt', "[-]");
		$("#reviews img:first").attr('title', "[-]");	
	}
});

$("#featured_reviews .collapse_img").click(function() {
	if ($("#featured_reviews").find(".content_section").is(':visible')) {
		$("#featured_reviews").find(".content_section").slideUp("fast");
		$("#featured_reviews img:first").attr('src', "http://www.hyakkiscorner.com/images/collapse_collapsed.gif");
		$("#featured_reviews img:first").attr('alt', "[+]");
		$("#featured_reviews img:first").attr('title', "[+]");
	} else {
		$("#featured_reviews").find(".content_section").slideDown("fast");
		$("#featured_reviews img:first").attr('src', "http://www.hyakkiscorner.com/images/collapse.gif");
		$("#featured_reviews img:first").attr('alt', "[-]");
		$("#featured_reviews img:first").attr('title', "[-]");	
	}
});

$("#latest_reviews .collapse_img").click(function() {
	if ($("#latest_reviews").find(".content_section").is(':visible')) {
		$("#latest_reviews").find(".content_section").slideUp("fast");
		$("#latest_reviews img:first").attr('src', "http://www.hyakkiscorner.com/images/collapse_collapsed.gif");
		$("#latest_reviews img:first").attr('alt', "[+]");
		$("#latest_reviews img:first").attr('title', "[+]");
	} else {
		$("#latest_reviews").find(".content_section").slideDown("fast");
		$("#latest_reviews img:first").attr('src', "http://www.hyakkiscorner.com/images/collapse.gif");
		$("#latest_reviews img:first").attr('alt', "[-]");
		$("#latest_reviews img:first").attr('title', "[-]");	
	}
});

$("#about .collapse_img").click(function() {
	if ($("#about").find(".content_section").is(':visible')) {
		$("#about").find(".content_section").slideUp("fast");
		$("#about img:first").attr('src', "http://www.hyakkiscorner.com/images/collapse_collapsed.gif");
		$("#about img:first").attr('alt', "[+]");
		$("#about img:first").attr('title', "[+]");
	} else {
		$("#about").find(".content_section").slideDown("fast");
		$("#about img:first").attr('src', "http://www.hyakkiscorner.com/images/collapse.gif");
		$("#about img:first").attr('alt', "[-]");
		$("#about img:first").attr('title', "[-]");	
	}
});

$("#team .collapse_img").click(function() {
	if ($("#team").find(".content_section").is(':visible')) {
		$("#team").find(".content_section").slideUp("fast");
		$("#team img:first").attr('src', "http://www.hyakkiscorner.com/images/collapse_collapsed.gif");
		$("#team img:first").attr('alt', "[+]");
		$("#team img:first").attr('title', "[+]");
	} else {
		$("#team").find(".content_section").slideDown("fast");
		$("#team img:first").attr('src', "http://www.hyakkiscorner.com/images/collapse.gif");
		$("#team img:first").attr('alt', "[-]");
		$("#team img:first").attr('title', "[-]");	
	}
});

$("#terms .collapse_img").click(function() {
	if ($("#terms").find(".content_section").is(':visible')) {
		$("#terms").find(".content_section").slideUp("fast");
		$("#terms img:first").attr('src', "http://www.hyakkiscorner.com/images/collapse_collapsed.gif");
		$("#terms img:first").attr('alt', "[+]");
		$("#terms img:first").attr('title', "[+]");
	} else {
		$("#terms").find(".content_section").slideDown("fast");
		$("#terms img:first").attr('src', "http://www.hyakkiscorner.com/images/collapse.gif");
		$("#terms img:first").attr('alt', "[-]");
		$("#terms img:first").attr('title', "[-]");	
	}
});

$("#privacy_policy .collapse_img").click(function() {
	if ($("#privacy_policy").find(".content_section").is(':visible')) {
		$("#privacy_policy").find(".content_section").slideUp("fast");
		$("#privacy_policy img:first").attr('src', "http://www.hyakkiscorner.com/images/collapse_collapsed.gif");
		$("#privacy_policy img:first").attr('alt', "[+]");
		$("#privacy_policy img:first").attr('title', "[+]");
	} else {
		$("#privacy_policy").find(".content_section").slideDown("fast");
		$("#privacy_policy img:first").attr('src', "http://www.hyakkiscorner.com/images/collapse.gif");
		$("#privacy_policy img:first").attr('alt', "[-]");
		$("#privacy_policy img:first").attr('title', "[-]");	
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
//$("#posts li:odd").css({background: "rgba(25, 25, 25, 0.5)"});
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
