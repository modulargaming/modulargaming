/**
 * Item admin list.
 */

$(document).ready(function () {
	$('.sorted_table').sortable({
		containerSelector: 'tbody',
		itemSelector: 'tr',
		placeholder: '<tr class="placeholder"/>'
	})
});