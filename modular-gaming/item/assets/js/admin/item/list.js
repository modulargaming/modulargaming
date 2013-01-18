/**
 * Modular Gaming js file.
 */

$(document).ready(function() {
	//page autocomplete search
	$('#item_search').typeahead({
	    source: function (query, process) {
	        return $.get('./item/search', { type: 'item', item: query }, function (data) {
	            return process(data);
	        });
	    },
	    updater: function(obj){
	    	$('#crud-container').trigger('crud.FormOpen', {name: obj});
	    }
	});
	
	//set up item command searches
	//find a way to bind these to the elements, currently does not work.
	$('.pet-color-search').typeahead({
	    source: function (query, process) {
	        return $.get('./item/search', { type: 'pet-color', item: query }, function (data) {
	            return process(data);
	        });
	    }
	});
	
	$('.pet-specie-search').typeahead({
	    source: function (query, process) {
	        return $.get('./item/search', { type: 'pet-specie', item: query }, function (data) {
	            return process(data);
	        });
	    }
	});
	
	$('.recipe-search').typeahead({
	    source: function (query, process) {
	        return $.get('./item/search', { type: 'recipe', item: query }, function (data) {
	            return process(data);
	        });
	    }
	});
	
	$('.item-search').typeahead({
	    source: function (query, process) {
	        return $.get('./item/search', { type: 'item', item: query }, function (data) {
	            return process(data);
	        });
	    }
	});
	
	//set up item commands
	$('#crud-container').on('crud.clean', function (e){
		$('#modal-crud-commands tr').remove();
		
		//Add command to list
		$('#modal-crud-cmd-options > li > a').click(function(e){
			e.preventDefault();
			var cmd = $(this).data('command');
			if($('#modal-crud-commands').find('[name="'+cmd+'"]').length == 0) {
				var tpl = $('#item-command-input-collection').find('[name="'+cmd+'"]').parents('tr').clone(true);
				$('#modal-crud-commands').append(tpl);
			}
		});
		
		
		//command row close
		$('.btn-cmd-close').click(function(e){
			e.preventDefault();
			$(this).parents('tr').remove();
		});
		
		//load the command required by the item type
		$('#input-type_id').change(function(e){
			//remove the first tr
			$('#modal-crud-commands tr:first').remove();
			var cmd = cmd_map[$('#input-type_id').val()];
			
			var tpl = $('#item-command-input-collection').find('[name="'+cmd+'"]').parents('tr').clone(true);
			tpl.find('a').addClass('disabled');
			
			//if it's already in the action list, remove existing
			if($('#modal-crud-commands').find('[name="'+cmd+'"]').length > 0) {
				var input = $('#modal-crud-commands').find('[name="'+cmd+'"]');
				//assign the value so we don't lose it
				tpl.find('[name="'+cmd+'"]').val(input.val());
				input.parents('tr').remove();
			}
			
			$('#modal-crud-commands').prepend(tpl);
		});
	});
	
	$('#crud-container').on('crud.load', function(e, data){
		var first = true;
		
		$.each(data.commands, function(k, v){
			var tpl = $('#item-command-input-collection').find('[name="'+v.name+'"]').parents('tr').clone(true);
			
			if(first == true) {
				tpl.find('a').addClass('disabled');
				first = false;
			}
			tpl.find('[name="'+v.name+'"]').val(v.param);
			
			$('#modal-crud-commands').append(tpl);
		});
	});
	
	$('#crud-container').on('crud.preSave', function(e){
		$('#modal-crud-commands').find('input').each(function(){
			this.name = 'commands['+this.name+']';
		});
	});
	
	//Define extra option buttons that show when editing an item
	$('#crud-container').on('crud.options', function (e, id, data){
		$('#modal-gift').on('hidden', function(){
			$('#modal-crud').modal('show');
		});
		$('#option-gift').click(function(e){
			e.preventDefault();
			$('#modal-gift-username').typeahead({
			    source: function (query, process) {
			        return $.get('./item/search', { type: 'user', item: query }, function (search_data) {
			            return process(search_data);
			        });
			    }
			});
			$('#modal-crud').modal('hide');
			$('#modal-gift').modal('show');
			
			$("#modal-gift-username").val('');
			$('#modal-gift-amount').val('1');
			
			$('#modal-gift-send').one('click', function(e){
				e.preventDefault();
				var f = {
					id : id,
					username : $('#modal-gift-username').val(),
					amount : $('#modal-gift-amount').val()
				};
				
				$.post('./item/gift/', f, function(data) {
	        		if(data.action == 'success') {
	        			$('.bottom-right').notify({
	        			    message: { text: f.amount+' copies of '+data.name+' have been sent to '+f.username+' successfully!' }
	        			  }).show();
	        			
	        			$('#modal-gift').modal('hide');
	        		}
	        		else {
	        			//mark the errors on the form
	        			$.each(data.errors, function(key, val){
	        				var e = $('#modal-gift-error-'+val.field);
	        				e.removeClass('hide');
	        				e.attr('title', val.msg.join('<br />'));
	        			});
	        			$('#modal-gift [rel=tooltip]').tooltip();
	        		}
	        	});
			});
		});
	});
	
	//Activate crud UI behaviour
	$('#crud-container').mgForm({
		retrieve: './item/retrieve/',
		save: './item/save/',
		data_in_table: ['name', 'type_id'],
		remove: './item/delete/',
		identifier: 'name'
	});
	
});