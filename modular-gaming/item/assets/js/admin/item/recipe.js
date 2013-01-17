/**
 * Modular Gaming js file.
 */

$(document).ready(function() {
	$('#search').typeahead({
	    source: function (query, process) {
	        return $.get('./search/', { type: 'recipe', item: query }, function (data) {
	            return process(data);
	        });
	    },
	    updater: function(obj){
	    	$('#crud-container').trigger('crud.FormOpen', {name: obj});
	    }
	});
	
	$('#input-crafted_item').typeahead({
	    source: function (query, process) {
	        return $.get('./search/', { type: 'item', item: query }, function (data) {
	            return process(data);
	        });
	    },
	});
	
	var ingrEl = 0;
	var ingrCount = 0;
	
	$('#crud-container').on('crud.clean', function(){
		$('#ingredient-body > tr').each(function(){$(this).remove();});
		ingrCount = 0;
		ingrEl = 0;
	});
	
	$('#crud-container').on('crud.load', function(e, data){
		if(data.materials.length > 0){
			$.each(data.materials, function(key, val){
				$('#ingredient-body').append('<tr><td><input type="text" class="input-small disabled" name="materials['+val.id+'][name]" value="'+val.name+'" /></td><td><input type="text" class="input-mini" name="materials['+val.id+'][amount]" value="'+val.amount+'" /></td><td><a href="#" class="btn btn-mini btn-danger ingredient-remove">x</a></td></tr>');
				
			});
		}
	});
	
	$('#crud-container').on('crud.save', function(e, values, data){
		$('#container-'+$('#input-id').val()).find('[data-prop="ingredients"]').text($('#ingredient-body > tr').length);
	});
	
	$('#btn-add-ingredient').click(function(e){
		e.preventDefault();
		$('#ingredient-body').append('<tr><td><input type="text" class="input-small" name="materials[key'+ingrEl+'][name]"/></td><td><input type="text" class="input-mini" name="materials[key'+ingrEl+'][amount]" value="1" /></td><td><a href="#" class="btn btn-mini btn-danger ingredient-remove">x</a></td></tr>');
		
		$('input[name="materials[key'+ingrEl+'][name]"]').typeahead({
		    source: function (query, process) {
		        return $.get('./search/', { type: 'item', item: query }, function (data) {
		            return process(data);
		        });
		    },
		});
		ingrCount++;
		ingrEl++;
	});
	$('#ingredient-body').on('click', 'tr > td.ingredient-remove', function(e){
		e.preventDefault();
		$(this).parents('tr').remove();
		ingrCount--;
	});
	
	$('#crud-container').mgForm({
		retrieve: './recipes/retrieve/',
		save: './recipes/save/',
		data_in_table: ['name'],
		remove: './recipes/delete/',
		identifier: 'name'
	});
});