/**
 * Item admin list.
 */

$(document).ready(function () {

	$('#config-form').dform({method: 'post', html: module_form_definition});
	$('#config-form-save').click(function(e){
		e.preventDefault();
		console.log($('#config-form').serialize());
	});
});