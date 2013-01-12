/**
 * Modular Gaming js file.
 */

$(document).ready(function() {
	$('#item_search').typeahead({
	    source: function (query, process) {
	        return $.get('./item/search', { type: 'item_type', item: query }, function (data) {
	            return process(data);
	        });
	    },
	    updater: function(obj){
	    	$('#container-type').trigger('adminFormOpen', {name: obj});
	    }
	});
	var type = $('#container-type').mgForm({
		retrieve: './retrieve_type/',
		save: {url: './save_type/', in_table: ['name']},
		remove: './delete_type/'
	});
});