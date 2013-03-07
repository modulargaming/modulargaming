$(document).ready(function() {
	$('#avatar-type').change(function() {
		// Display the correct avatar "view".
		$('.avatar').addClass('hidden');
		$('#avatar-' + $(this).val()).removeClass('hidden');
	});
});