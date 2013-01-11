/**
 * Modular Gaming js file.
 */

$(document).ready(function() {
	$('#item_tab_list a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	})
	$('#item_search').typeahead({
	    source: function (query, process) {
	        return $.get('./item/search', { item: query }, function (data) {
	            return process(data);
	        });
	    },
	    updater: function(obj){
	    	openForm(obj, 'name');
	    }
	});
});

function openForm(id, checker) {
	var param;
	
	//reset form values
	$('#item-form')[0].reset();
	
	//reset form errors
	$('#form-error-name').addClass('hide').attr('title', '');
	$('#form-error-description').addClass('hide').attr('title', '');
	$('#form-error-image').addClass('hide').attr('title', '');
	
	//reset commands
	
	if(typeof id === 'undefined') {
		$('h3#item-modal-header').html('Creating');
		$('#input-id').val('0');
		$('#item-modal').modal();
	}
	else {
		if(typeof checker === 'undefined')
			param = { id: id };
		else if(checker == 'name')
			param = { name: id };
		
		$.get('./item/retrieve/', param, function (data) {
			$('h3#item-modal-header').html('Editing "'+data.name+'"');
			$('#input-id').val(data.id);
			$('#form-item-status').val(data.status);
			$('#form-item-name').val(data.name);
			$('#form-item-description').val(data.description);
			$('#form-item-image').val(data.image);
			$('#form-item-unique').val(data.unique);
			$('#form-item-transferable').val(data.transferable);
			$('#form-item-type').val(data.type_id);
			
			//set the commands
			
            $('#item-modal').modal();
        });
	}	
}

function saveForm(){
	var values = $('#item-form').serialize();
	var item_id = $('#input-id').val();
	$.post("./item/save/", values,
	function(data) {
		if(data.action == 'saved') {
			$('.bottom-right').notify({
			    message: { text: $('#form-item-name').val()+' has been saved successfully!' }
			  }).show();
			$('#item-modal').modal('hide');
			
			//update the item list table
			if(item_id != 0 && $("#item-container-"+item_id).length != 0) {
				$('#item-container-name-'+item_id).text($('#form-item-name').val());
				$('#item-container-type-'+item_id).text($('#form-item-type').val());
			}
		}
		else {
			//mark the errors on the form
			$.each(data.errors, function(){
				var e = $('#form-error-'+this.field);
				e.removeClass('hide');
				e.attr('title', this.msg.join('<br />'));
			});
			$('[rel=tooltip]').tooltip();
		}
	});
}

function deleteItem(id, name) {
	$('#item-delete-name').text(name);
	$('#item-modal-delete').data('item-id', id);
	$('#item-modal-delete').data('item-name', name);
	$('#item-modal-delete').modal();
}

function doDelete(){
	var item_id = $('#item-modal-delete').data('item-id');
	
	$.post("./item/delete/", {id: item_id},
		function(data) {
			if(data.action == 'deleted') {
				$('.bottom-right').notify({
					message: { text: $('#item-modal-delete').data('item-name')+' has been deleted successfully!' }
				}).show();
				$('#item-modal-delete').modal('hide');
				
				//remove the item from the interface
				if($('item-container-'+item_id).length != 0)
					$('item-container-'+item_id).slideUp();
			}
			else {
				//error deleting
				$('.bottom-right').notify({
					type: 'error',
					message: { text: $('#item-modal-delete').data('item-name')+' could not be deleted!' }
				}).show();
				$('#item-modal-delete').modal('hide');
			}
		}
	);
}