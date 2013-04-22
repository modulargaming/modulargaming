var checkboxes = $('.message-checkbox');
function Checkboxes () {
	for (var i = 0; i < checkboxes.length; i++) {
		var li = $(checkboxes[i]).parent().parent();
		if (checkboxes[i].checked) {
			li.addClass('message-checked');
		}
		else {
			li.removeClass('message-checked');
		}
	}
	if ($('.message-checkbox:checked').length) {
		$('#messages-selected').removeClass('disabled');
	}
	else {
		$('#messages-selected').addClass('disabled');
	}
}
function CheckboxesCheck (check) {
	for (var i = 0; i < checkboxes.length; i++) {
		checkboxes[i].checked = check;
	}
	Checkboxes();
}
checkboxes.change(function () { Checkboxes(); });
Checkboxes();
$('#messages-select').click(function () {
	if ($('.message-checkbox:checked').length == checkboxes.length) {
		CheckboxesCheck(false);
	}
	else {
		CheckboxesCheck(true);
	}
});