/**
 * Item admin list.
 */

$(document).ready(function () {

	$('#link_drive').click(function(e){
		e.preventDefault();
	});
		$('#link_drive').popover({
			html: true,
			placement: 'left',
			content: '<p style="font-size: 12px; font-weight: normal; line-height: 14px;">In order to make use of MG\'s backup functionality you\'ll have to <a href="https://developers.google.com/drive/quickstart-php#step_1_enable_the_drive_api" target="_blank">create</a> an API project in the Google API console.<br /><br /> <strong>Make sure your redirect URL is the same as below</strong></p>'
		});
});