/**
 * Modular Gaming js file.
 */

$(document).ready(function() {
	$('#item_search').typeahead({
	    source: function (query, process) {
	        return $.get('./search', { type: 'item', item: query }, function (data) {
	            return process(data);
	        });
	    },
	    updater: function(obj){
	    	$('#container-item').trigger('adminFormOpen', {name: obj});
	    }
	});
	
	$('#container-item').mgForm({
		retrieve: './retrieve/',
		save: {url: './save/', in_table: ['name', 'type_id']},
		remove: './delete/'
	});
});