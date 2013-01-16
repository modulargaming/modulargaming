/**
 * Modular Gaming js file.
 */

$(document).ready(function() {
	$('#search').typeahead({
	    source: function (query, process) {
	        return $.get('./search/', { type: 'item_type', item: query }, function (data) {
	            return process(data);
	        });
	    },
	    updater: function(obj){
	    	$('#crud-container').trigger('crud.FormOpen', {name: obj});
	    }
	});
	
	$('#crud-container').mgForm({
		retrieve: './types/retrieve/',
		save: './types/save/',
		data_in_table: ['name'],
		remove: './types/delete/'
	});
});