/**
 * Modular Gaming js file.
 */

$(document).ready(function () {
	//attach the auto complete
	$('#user-search').typeahead({
		source:function (query, process) {
			return $.get('./user/search', { username:query }, function (data) {
				return process(data);
			});
		}
	});
});