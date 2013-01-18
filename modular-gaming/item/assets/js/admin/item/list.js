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