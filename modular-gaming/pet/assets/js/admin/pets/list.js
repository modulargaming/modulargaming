/**
 * Admin pet specie's js file.
 */

$(document).ready(function() {
	$('#search').typeahead({
	    source: function (query, process) {
	        return $.get('./search/', { type: 'pet-specie', item: query }, function (data) {
	            return process(data);
	        });
	    },
	    updater: function(obj){
	    	$('#crud-container').trigger('crud.FormOpen', {name: obj});
	    }
	});
	
	$('#crud-container').mgForm({
		retrieve: './specie/retrieve/',
		save: './specie/save/',
		data_in_table: ['name'],
		remove: './specie/delete/',
		identifier: 'name'
	});
});