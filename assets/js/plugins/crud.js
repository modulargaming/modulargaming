(function( $ ){
	var form = {};
	var type = '';
	opts = {};
	
	var methods = {
		init : function( options ) {
			opts = $.extend({}, $.fn.mgForm.defaults, options);
		
			//get the table containing all the data
			type = this.attr('id').replace('container-','');
			var tableEl = $(this);
			
			//get a map of the form fields
            form.fields = {};
            form.e = $('#form-'+type);
            
            form.e.find(':input').map(function(index, elm) {
            	var e = $(elm);
            	var id = e.attr('id').replace('input-'+form.type+'-','');
            	
            	//buttons get ignored
            	if(elm.type != 'button')
            		form.fields[id] = {name: elm.name, id: id, element: e, type: elm.type};
            }); 
            
          //bind events to create buttons
           	$('.btn-create').click(function(e){
           		e.preventDefault();
           		methods.show.apply(form, [0, {}]);
            });
           	
           	$(this).bind('adminFormOpen', {f: form}, function(e, param){
           		alert('invocation ' + form.e.attr('id'));
           		methods.show.apply(e.data.f, [0, param]);
           	});
            
            //bind events to edit and delete buttons
            tableEl.find('tbody > tr').each(function(){
            	var tr = $(this);
            	var id = tr.attr('id');
            	id = id.replace('container-'+type+'-','');
            	
            	tr.find('.btn-edit').click(function(e){
            		e.preventDefault();
            		methods.show.apply(form, [id, {id: id}]);
            	});
            	tr.find('.btn-delete').click(function(e){
            		e.preventDefault();
            		methods.showDelete.apply(form, [id, $('#container-'+type+'-'+opts.identifier+'-'+id).text()]);
            	});
            });
            return this;
		},
		show : function(id, param) {
			this.e[0].reset();
			opts.clean.apply(this, [type]);
			

			//reset errors
        	$.each(this.fields, function(key, val){
        		$('#form-'+type+'-error-'+val.name).addClass('hide').attr('title', '');
        	});
        	
        	//if no id is specified we're creating
        	if((typeof id === 'undefined' || id == 0) && typeof param === 'undefined') {
        		$('h3#modal-'+type+'-header').html('Creating');
        		$('#input-'+type+'-id').val('0');
        		$('#modal-'+type).modal();
        	}
        	else {
        		var req_data = opts.retrieve;
        		
        		$.get(req_data, param, function (data) {
        			$('h3#modal-'+type+'-header').html('Editing "'+data[opts.identifier]+'"');
        			
        			//set the field values
        			$.each(data, function(key,val){
        				alert(this.fields[key].type);
        			    $('#input-'+type+'-'+key).val(val);
        			});
        			
        			//do the fill callback
        			opts.fill.call(this, [data, type]);
        			
        			//show the modal
        			$('#modal-'+type).modal();
        		});
        	}
        	
        	var form = this;
        	//bind the save button
            $('#btn-save').click(function(e){
            	e.preventDefault();
            	methods.save.apply(form);
            });
        },
        save : function() { 
        	var values = this.e.serialize();
        	var id = $('#input-'+type+'-id').val();
        	
        	$.post(opts.save.url, values, function(data) {
        		if(data.action == 'saved') {
        			$('.bottom-right').notify({
        			    message: { text: $('#input-'+type+'-'+opts.identifier).text()+' has been saved successfully!' }
        			  }).show();
        			$('#modal-'+type).modal('hide');
        			
        			//update the item list table
        			if(id != 0 && $("#container-"+type+"-"+id).length != 0 && opts.save.in_table.length > 0) {
        				$.each(opts.save.in_table, function(key, val) {
        					var c = $('#container-'+type+'-'+val+'-'+id);
        					c.text($('#input-'+type+'-'+val).val());
        				});
        			}
        			
        			opts.save.success.apply(this, [data]);
        		}
        		else {
        			//mark the errors on the form
        			$.each(data.errors, function(key, val){
        				var e = $('#form-'+type+'-error-'+val.field);
        				e.removeClass('hide');
        				e.attr('title', val.msg.join('<br />'));
        			});
        			$('[rel=tooltip]').tooltip();
        		}
        	});
        },
        showDelete : function(id, name) {
        	$('#modal-delete-name').text(name);
        	$('#modal-delete-type').text(type.replace('-', '').replace('_', ' '));
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
            		if($('container-'+type+'-'+id).length != 0)
            			$('container-'+type+'-'+id).fadeOut();
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
  		clean: function(type) {return true;},
        retrieve: '', //url to retrieve a data entiry from
        fill: function(data, type){return true;}, //callback that's run after retrieving data
        save: {url: '', in_table: {}, success: function(data){return true;}}, //url to post the form values to
        remove: '', // url to send a delete request to
        identifier: 'name' //the property used as an identifier
	};

})( jQuery );