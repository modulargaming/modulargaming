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
            
            $('#modal-delete-type').text($($('.btn-create')[0]).text().replace('Create ', ''));
            
          //bind events to create buttons
           	$('.btn-create').click(function(e){
           		e.preventDefault();
           		methods.show.apply(form, [0]);
            });
           	
           	$(this).bind('crud.FormOpen', {f: form}, function(e, param){
           		methods.show.apply(e.data.f, [0, param]);
           	});
            
          //bind events to edit and delete buttons
           	$('#crud-container > tbody > tr').on('click', '.btn-edit', function(e){
        		e.preventDefault();
        		var id = $(this).parents('tr').attr('id').replace('container-', '');
        		methods.show.apply(form, [id, {id: id}]);
        	});
           	$('#crud-container > tbody > tr').on('click', '.btn-delete', function(e){
        		e.preventDefault();
        		var id = $(this).parents('tr').attr('id').replace('container-', '');
        		var name = $(this).parents('tr').find('td[data-prop="'+opts.identifier+'"]').text();
        		methods.showDelete.apply(form, [id, name]);
        	});

            return this;
		},
		show : function(id, param) {
			$('#modal-crud-form')[0].reset();
			
			// clean callback
			$('#crud-container').trigger('crud.clean');
			
			$('.modal-options').addClass('hide');
			
			//reset errors
        	$('#modal-crud-form .btn-mini').each(function(){
        		$(this).addClass('hide').attr('title', '');
        	});
        	
        	methods.bindSave.call(this);
        	
        	//if no id is specified we're creating
        	if(id == 0 && typeof param === 'undefined') {
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
        			$('#crud-container').trigger('crud.load', [data]);
        			
        			$('#option-delete').click(function(e){
        				e.preventDefault();
        				$('#modal-crud').modal('hide');
        				$('#modal-delete-keep').one('click', function(e){
        					e.preventDefault();
        					$('#modal-delete').modal('hide');
        					$('#modal-crud').modal('show');
        				});
        				methods.showDelete.call(form, data.id, data[opts.identifier]);
        			});
        			//set the field values
        			$.each(data, function(key,val){
        				$('#input-'+key).val(val);
        			});
        			
        			//show the modal
        			$('#modal-crud').modal();
        		});
        	}
        },
        save : function() { 
        	$('#crud-container').trigger('crud.preSave');
        	
        	var values = $('#modal-crud-form').serialize();
        	var id = $('#input-id').val();
        	
        	$.post(opts.save, values, function(data) {
        		if(data.action == 'saved') {
        			$('.bottom-right').notify({
        			    message: { text: $('#input-'+opts.identifier).val()+' has been saved successfully!' }
        			  }).show();
        			
        			$('#crud-container').trigger('crud.save', [values, data]);
        			
        			$('#modal-crud').modal('hide');
        			
        			//update the item list table
        			var container = $("#container-"+id);
        			if(id != 0 && container.length != 0 && opts.data_in_table.length > 0) {
        				$.each(opts.data_in_table, function(key, val) {
        					var c = container.find('[data-prop="'+val+'"]');
        					var input = $('#input-'+val);
        					if(c.length > 0){
        						if(input.is('select')) {
        							c.text(input.find(':selected').text());
        						}
        						else if(!input.is('checkbox') && !input.is('radio'))
        							c.text(input.val());
        					}
        						
        				});
        			}
        			else {
        				//we're dealing with a new table row
        				var max_results = $('#crud-container').data('pagination');
        				var rows = $('#crud-container > tbody > tr').length;
        				
        				if(max_results.length == 0 || (rows != 1 && max_results > (rows - 1))) {
        					
        					var tpl = $('#crud-container > tbody > tr:first').clone();
        					tpl.attr('id', 'container-'+data.row.id);
        					
        					//add new row
        					var td = 1;
        					$.each(data.row, function(key, val){
        						if(key != 'id') {
        							tpl.find('td:nth-child('+td+')').html(val);
        							td++;
        						}
        					});
        					
        					tpl.insertBefore('#crud-container-bottom');
        					
        					//bind the delete and edit buttons
        					methods.bindTblEdit.call(this, tpl.find('.btn-edit'), data.row.id);
        					methods.bindTblDelete.call(this, tpl.find('.btn-delete'));
        				}
        				else if(rows == 1) {
        					alert('This is your first time adding a row, reload your page please.');
        				}
        			}
        		}
        		else {
        			//rebind the save button
        			methods.bindSave.call(this);
        			
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
        	$('#btn-remove').prop('disabled', false);
        	$('#modal-delete').modal();
        
        	//bind the delete button
            $('#btn-remove').one('click', function(e){
            	e.preventDefault();
            	$(this).prop('disabled', true);
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
            			//disable all links
            			$('#container-'+id+' td:last').find('button').each(function(){
            				console.log('disable');
            				$(this).addClass('disabled');
            			});
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
        bindSave: function () {
        	$('#modal-crud-save').unbind('click');
        	//bind the save button
            $('#modal-crud-save').one('click', function(e){
            	e.preventDefault();
            	methods.save.apply(this);
            });
        },
        bindTblEdit: function(element, id){
			element.click(function(e){
				e.preventDefault();
        		methods.show.apply(form, [id, {id: id}]);
			});
		},
		bindTblDelete: function(element){
			$(element).click(function(e){
				e.preventDefault();
				var id = element.parents('tr').attr('id').replace('container-', '');
        		var name = element.parents('tr').find('td[data-prop="'+opts.identifier+'"]').text();
        		methods.showDelete.apply(form, [id, name]);
			});
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