function connect() {

	var xmlhttp;

	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp = new XMLHttpRequest();
	} else {// code for IE6, IE5
	  xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}

}

function getTweetsByFilter() {

	var value = $("filter_dropdown right").attr("value");

	if (value === '1') {
	$.getJSON('http://www.cs.usfca.edu/~jpbullalayao/WixApps/TwitterSays/development/tweets.json', function (data) {
		var output = '<div class="tweet">';
		output += '<p>Hello!</p>';
		output += '</div>';
		//console.log(data);
		//var output = ''

		$('.feed').html(output);
	});
	}
}