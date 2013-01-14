// Testing
$(document).ready(function() {
	var edit_template = $('#edit').html();
	var modal_template = $('#modal').html();

	$('.user-edit').click(function(e) {
		e.preventDefault();
		var href = $(this).attr('href');

		$.getJSON(href, function(data) {
			var edit_output = Mustache.render(edit_template, data);

			var edit_variables = {
				'header': 'Edit',
				'action': 'Save',
				'body': edit_output
			};

			var output = Mustache.render(modal_template, edit_variables);
			$('#dump').html(output);
			$('#dump .modal').modal('toggle');
		}).fail(function(jqXHR, textStatus, errorThrown) {
			alert(errorThrown);
		});
	});
});