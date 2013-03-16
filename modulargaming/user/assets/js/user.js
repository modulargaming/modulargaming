$(document).ready(function() {

	// Typeahead for username.
	$('.username-typeahead').typeahead({
		'source': '/user/typeahead',
		'minLength': 3
	});

});