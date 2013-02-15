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
	
	//colour buttons
	$('#crud-container > tbody > tr').on('click', '.btn-colour', function(e){
		e.preventDefault();
		var id = $(this).parents('tr').attr('id').replace('container-', '');
		$('#specie-id').val(id);
		var btn = $(this);
		btn.text('loading');
		
		$.get('./specie/col/load', {id: id}, function (data) {
			var options = [];
			var table = [];
			$('#add-colour').data('specie-id', id);
			
			$.each(pet_colours, function(key, value){
				if(data.colours.indexOf(key) >= 0) {
					table.push('<tr><td>'+value.name+'</td><td><button class="btn btn-danger btn-colour-remove" data-id="'+key+'">Remove colour</button></td></tr>');
				}
				else {
					options.push('<option value="'+key+'">'+value.name+'</option>');
				}
			});
			
			//add the options and table rows
			$('#colour-list-options').find('option').remove();
			$('#colour-list-options').html(options.join(''));
			$('#colour-list > tbody').find('tr').remove();
			$('#colour-list > tbody').append(table.join(''));
			btn.text('Colours');
			$('#modal-colour').modal('show');
		});
	});
	
	//remove a colour
	$('#colour-list tbody').on('click', '.btn-colour-remove', function(e){
		e.preventDefault();
		var colour = $(this).data('id');
		var specie = $('#add-colour').data('specie-id');
		
		$.get('./specie/col/delete', {specie_id: specie, colour_id: colour}, function (data) {
			//remove the table row
			$('#colour-list > tbody').find('button[data-id="'+colour+'"]').parents('tr').remove();
			//add back the option
			$('#colour-list-options').append('<option value="'+colour+'">'+pet_colours[colour].name+'</option>');
		});
	});
	
	//add selected colours
	$('#add-colour').click(function(e) {
		e.preventDefault();
		var form = $('#colour-form').serialize();
		
		//do the request
		$.post('./specie/col/update', form, function(data){
			var table = [];
			
			$.each(data, function(key, id){
				table.push('<tr><td>'+pet_colours[id].name+'</td><td><button class="btn btn-danger btn-colour-remove" data-id="'+id+'">Remove colour</button></td></tr>');
			});
			
			$('#colour-list-options option:selected').remove();
			$('#colour-list > tbody').append(table.join(''));
		});
	});
	
	$('#crud-container').mgForm({
		retrieve: './specie/retrieve/',
		save: './specie/save/',
		data_in_table: ['name'],
		remove: './specie/delete/',
		identifier: 'name'
	});
});