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
	}
}


/* Minimizing/Maximizing */
$("#announcements .collapse_img").click(function() {
	if ($("#announcements").find(".content_section").is(':visible')) {
		$("#announcements").find(".content_section").slideUp("fast");
		$("#announcements").css("box-shadow", "0px 0px 0px #828282")
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

$("#posts .collapse_img").click(function() {
	if ($("#posts").find("#posts_wrapper").is(':visible')) {
		$("#posts").find("#posts_wrapper").slideUp("fast");
		$("#posts").css("box-shadow", "0px 0px 0px #828282")
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
		$("#tweets").css("box-shadow", "0px 0px 0px #828282")
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
		$("#homelatestreviews").css("box-shadow", "0px 0px 0px #828282")
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
		$("#homelatestguides").css("box-shadow", "0px 0px 0px #828282")
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

$("#guides .collapse_img").click(function() {
	if ($("#guides").find(".content_section").is(':visible')) {
		$("#guides").find(".content_section").slideUp("fast");
		$("#guides").css("box-shadow", "0px 0px 0px #828282")
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
		$("#featured_guides").css("box-shadow", "0px 0px 0px #828282")
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
		$("#latest_guides").css("box-shadow", "0px 0px 0px #828282")
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

$("#reviews .collapse_img").click(function() {
	if ($("#reviews").find(".content_section").is(':visible')) {
		$("#reviews").find(".content_section").slideUp("fast");
		$("#reviews").css("box-shadow", "0px 0px 0px #828282")
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
		$("#featured_reviews").css("box-shadow", "0px 0px 0px #828282")
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
		$("#latest_reviews").css("box-shadow", "0px 0px 0px #828282")
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

/* Comment Box */
$(".comment_box").blur(function() {
	$(this).css({"color": "#999999"});
	if (this.value == '') {
		this.value = 'Leave a comment... we watch for spam!';
	}
});

$(".comment_box").focus(function() {
	$(this).css({"color": "#000000"});
	if (this.value == 'Leave a comment... we watch for spam!') {
		this.value = '';
	}
});

/* Sets initial values */
$(function() {
	/* Feedback Text Area Resizing */
	var maxw = $("#feedback_comments").width();
	var minw = maxw;
	var minh = $("#feedback_comments").height();		
});
