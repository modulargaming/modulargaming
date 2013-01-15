/**
 * Modular Gaming js file.
 */

$(document).ready(function() {
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
	
	$('#crud-container').on('crud.options', function (e, id, data){
		$('#option-gift').click(function(e){
			e.preventDefault();
			$('#modal-gift-username').typeahead({
			    source: function (query, process) {
			        return $.get('./item/search', { type: 'user', item: query }, function (data) {
			            return process(data);
			        });
			    }
			});
			$('#modal-crud').modal('hide');
			$('#modal-gift').modal('show');
			$('#modal-gift').on('hidden', function(){
				$('#modal-crud').modal('show');
			});
			
			$("#modal-gift-username").val();
			$('#modal-gift-amount').val('1');
			
			$('#modal-gift-send').click(function(e){
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
	
	$('#crud-container').mgForm({
		retrieve: './item/retrieve/',
		save: './item/save/',
		data_in_table: ['name', 'type_id'],
		remove: './item/delete/'
	});
	
});