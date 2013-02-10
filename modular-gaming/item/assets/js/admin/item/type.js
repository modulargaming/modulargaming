/**
 * Modular Gaming - Item type admin js file.
 */

$(document).ready(function() {
	
	$('#data-table').CRUD({
		base_url: './types/',
		identifier: {data: 'name', table: 1},
		table: {
			span: 4,
			offset: 4
		}
	});
});