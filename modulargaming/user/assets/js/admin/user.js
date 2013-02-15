// Testing
$(document).ready(function() {
	$('.user-edit').click(function(e) {
		e.preventDefault();
		var href = $(this).attr('href');

		$.getJSON(href, function(data) {
			var edit_output = Mustache.render(TEMPLATES['Edit'], data);

			var edit_variables = {
				'header': 'Edit',
				'action': 'Save',
				'body': edit_output
			};

			var output = Mustache.render(TEMPLATES['Modal'], edit_variables);
			$('#dump').html(output);
			$('#dump .modal').modal('toggle');
		}).fail(function(jqXHR, textStatus, errorThrown) {
			alert(errorThrown);
		});
	});
});