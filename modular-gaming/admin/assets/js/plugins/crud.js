(function( $ ){
	var form = {};
	opts = {};
	
	var methods = {
		init : function( options ) {
			opts = $.extend({}, $.fn.mgForm.defaults, options);
		
			//get the table containing all the data
			var tableEl = $(this);
			
			//get a map of the form fields
            form = $('#modal-crud-form');
            
          //bind events to create buttons
           	$('.btn-create').click(function(e){
           		e.preventDefault();
           		methods.show.apply(form, [0, {}]);
            });
           	
           	$(this).bind('crud.FormOpen', {f: form}, function(e, param){
           		methods.show.apply(e.data.f, [0, param]);
           	});
            
            //bind events to edit and delete buttons
            tableEl.find('tbody > tr').each(function(){
            	var tr = $(this);
            	var id = tr.attr('id');
            	id = id.replace('container-','');
            	 
            	tr.find('.btn-edit').click(function(e){
            		e.preventDefault();
            		methods.show.apply(form, [id, {id: id}]);
            	});
            	tr.find('.btn-delete').click(function(e){
            		e.preventDefault();
            		methods.showDelete.apply(form, [id, $('#container-'+opts.identifier+'-'+id).text()]);
            	});
            });
            return this;
		},
		show : function(id, param) {
			$('#modal-crud-form')[0].reset();
			
			//@todo add clean callback
			
			$('.modal-options').addClass('hide');
			
			//reset errors
        	$('#modal-crud-form :input[type!=button]').each(function(){
        		$('#form-error-'+$(this).attr('name')).addClass('hide').attr('title', '');
        	});
        	
        	//if no id is specified we're creating
        	if(id == 0 || typeof param === 'undefined') {
        		$('h3#modal-header').html('Creating');
        		$('#input-id').val('0');
        		$('#modal-crud').modal();
        	}
        	else {
        		var req_data = opts.retrieve;
        		
        		$.get(req_data, param, function (data) {
        			$('h3#modal-header').html('Editing "'+data[opts.identifier]+'"');
        			$('.modal-options').removeClass('hide');
        			
        			//register option buttons
        			$('#crud-container').trigger('crud.options', [data.id, data]);
        			
        			$('#option-delete').click(function(e){
        				e.preventDefault();
        				$('#modal-crud').modal('hide');
        				$('#modal-delete-keep').click(function(e){
        					e.preventDefault();
        					$('#modal-delete').modal('hide');
        					$('#modal-crud').modal('show');
        				});
        				methods.showDelete.call(form, [data.id, data[opts.identifier]]);
        			});
        			//set the field values
        			$.each(data, function(key,val){
        				$('#input-'+key).val(val);
        			});
        			
        			//show the modal
        			$('#modal-crud').modal();
        		});
        	}
        	
        	var form = this;
        	//bind the save button
            $('#modal-crud-save').one('click', function(e){
            	e.preventDefault();
            	methods.save.apply(form);
            });
        },
        save : function() { 
        	var values = $('#modal-crud-form').serialize();
        	var id = $('#input-id').val();
        	
        	$.post(opts.save, values, function(data) {
        		if(data.action == 'saved') {
        			$('.bottom-right').notify({
        			    message: { text: $('#input-'+opts.identifier).val()+' has been saved successfully!' }
        			  }).show();
        			
        			$('#modal-crud').modal('hide');
        			
        			//update the item list table
        			if(id != 0 && $("#container-"+id).length != 0 && opts.data_in_table.length > 0) {
        				$.each(opts.data_in_table, function(key, val) {
        					var c = $('#container-'+val+'-'+id);
        					c.text($('#input-'+val).val());
        				});
        			}
        			
        			//@todo implement save callback
        			//opts.save.success.apply(this, [data]);
        		}
        		else {
        			//mark the errors on the form
        			$.each(data.errors, function(key, val){
        				var e = $('#modal-crud-error-'+val.field);
        				e.removeClass('hide');
        				e.attr('title', val.msg.join('<br />'));
        			});
        			$('[rel=tooltip]').tooltip();
        		}
        	});
        },
        showDelete : function(id, name) {
        	$('#modal-delete-name').text(name);
        	$('#modal-delete-type').text($($('.btn-create')[0]).text().replace('Create ', ''));
        	$('#modal-delete').modal();
        
        	//bind the delete button
            $('#btn-remove').click(function(e){
            	e.preventDefault();
            	methods.doDelete.apply(this, [id, name]);
            });
        },
        doDelete : function(id, name) { 
        	$.post(opts.remove, {id: id}, function(data) {
        		if(data.action == 'deleted') {
        			$('.bottom-right').notify({
        				message: { text: name+' has been deleted successfully!' }
            		}).show();
            		$('#modal-delete').modal('hide');
            				
            		//remove the item from the interface
            		if($('#container-'+id).length != 0)
            			$('#container-'+id).addClass('warning');
            		}
            		else {
            			//error deleting
            			$('.bottom-right').notify({
            				type: 'error',
            				message: { text: name+' could not be deleted!' }
            			}).show();
            			$('#modal-delete').modal('hide');
            		}
            	}
            );
        },
	};

	$.fn.mgForm = function( method ) {
    
		if ( methods[method] ) {
			return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
		} else if ( typeof method === 'object' || ! method ) {
			return methods.init.apply( this, arguments );
		} else {
			$.error( 'Method ' +  method + ' does not exist on jQuery.mgForm' );
		}    
	};
  
  	$.fn.mgForm.defaults = {
        retrieve: '', //url to retrieve a data entiry from
        save: '', //url to post the form values to
        data_in_table: {},
        remove: '', // url to send a delete request to
        identifier: 'name' //the property used as an identifier
	};

})( jQuery );