/**
 * Item admin list.
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
	
	$('#modal-crud-tab a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});
	
	var default_command = '';
	
	//set up item commands
	$('#crud-container').on('crud.clean', function (e){
		$('#modal-crud-commands tr').remove();
		
		//unbind any events previously set
		$('.modal-crud-cmd-options').unbind('click');
		$('.btn-cmd-close').unbind('click');
		$('#input-type_id').unbind('change');
		default_command = '';
		
		//reset tabs
		$('#modal-crud-tab a:first').tab('show');
		
		//Add command to list
		$('.modal-crud-cmd-options').click(function(e){
			e.preventDefault();
			var cmd = $(this).data('command');
			
			if(cmd_definitions[default_command].pets == 0 && cmd_definitions[cmd].pets == 1) {
				//error, can't add a pet related command to an item that doesn't load the pet list
				$('#modal-notify').notify({
					message: { text: 'You can\'t add command "'+cmd.replace('_', ' ')+'" because it requires pets.'},
					type: 'info',
					fadeOut: { enabled: true, delay: 6000 }
				}).show();
			}
			else if($('#modal-crud-commands').find('[name="'+cmd+'"]').length > 0 && cmd_definitions[cmd].multiple == 0) {
				$('#modal-notify').notify({
					message: { text: 'You can\'t add command "'+cmd.replace('_', ' ')+'" more than once to an item.'},
					type: 'info',
					fadeOut: { enabled: true, delay: 6000 }
				}).show();
			}
			else {
				var tpl = $('#item-command-input-collection').find('[name="'+cmd+'"]').parents('tr').clone(true);
				
				//bind autocomplete search if needed
				if(cmd_definitions[cmd].search != 0) {
					tpl.find('.search').typeahead({
					    source: function (query, process) {
					        return $.get('./item/search', { type: cmd_definitions[cmd].search, item: query }, function (data) {
					            return process(data);
					        });
					    }
					});
				}
				
				//add the action to the row
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
			var cmd = cmd_type_map[$('#input-type_id').val()];
			
			var tpl = $('#item-command-input-collection').find('[name="'+cmd+'"]').parents('tr').clone(true);
			tpl.find('a').addClass('disabled');
			
			//if it's already in the action list, remove existing
			if($('#modal-crud-commands').find('[name="'+cmd+'"]').length > 0) {
				var input = $('#modal-crud-commands').find('[name="'+cmd+'"]');
				
				//bind autocomplete search if needed
				if(cmd_definitions[cmd].search != 0) {
					input.find('.search').typeahead({
					    source: function (query, process) {
					        return $.get('./item/search', { type: cmd_definitions[cmd].search, item: query }, function (data) {
					            return process(data);
					        });
					    }
					});
				}
				//assign the value so we don't lose it
				tpl.find('[name="'+cmd+'"]').val(input.val());
				input.parents('tr').remove();
			}
			default_command = cmd;
			$('#modal-crud-commands').prepend(tpl);
		});
	});
	
	//load in any predefined commands
	$('#crud-container').on('crud.load', function(e, data){
		var first = true;
		
		$.each(data.commands, function(k, v){
			var tpl = $('#item-command-input-collection').find('[name="'+v.name+'"]').parents('tr').clone(true);
			
			if(first == true) {
				tpl.find('a').addClass('disabled');
				default_command = v.name;
				first = false;
			}
			tpl.find('[name="'+v.name+'"]').val(v.param);
			
			$('#modal-crud-commands').append(tpl);
		});
	});
	
	//namespace the command input elements properly before sending
	$('#crud-container').on('crud.preSave', function(e){
		var counters = [];
		$('#modal-crud-commands').find(':input').each(function(){
			if(cmd_definitions[this.name].multiple == true)
			{
				if(typeof counters[this.name] == 'undefined')
					counters[this.name] = 0;
				else
					counters[this.name]++;
				
				this.name = 'commands['+this.name+']['+counter[this.name]+']';
			}
			else
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