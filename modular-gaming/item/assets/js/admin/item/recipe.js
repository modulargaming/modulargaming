/**
 * Modular Gaming js file.
 */

$(document).ready(function() {
	$('#search').typeahead({
	    source: function (query, process) {
	        return $.get('../search/', { type: 'recipe', item: query }, function (data) {
	            return process(data);
	        });
	    },
	    updater: function(obj){
	    	$('#crud-container').trigger('crud.FormOpen', {name: obj});
	    }
	});
	
	$('#crud-container').mgForm({
		retrieve: '../recipes/retrieve/',
		save: '../recipes/save/',
		data_in_table: ['name'],
		remove: '../recipes/delete/'
	});
});