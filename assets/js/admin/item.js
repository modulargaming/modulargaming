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
			$('#input00').val(data.status);
			$('#input01').val(data.name);
			$('#input02').val(data.description);
			$('#input03').val(data.image);
			$('#input04').val(data.unique);
			$('#input05').val(data.transferable);
			$('#input06').val(data.type_id);
			
			//set the commands
			
            $('#item-modal').modal();
        });
	}	
}

function saveForm(){
	var values = $('#item-form').serialize();
	
	$.post("./item/save/", values,
	function(data) {
		if(data.action == 'saved') {
			$('.bottom-right').notify({
			    message: { text: $('#input01').val()+' has been saved successfully!' }
			  }).show();
			$('#item-modal').modal('hide');
		}
		else {
			//mark the errors on the form
			alert('error!!!!');
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
	$.post("./item/delete/", {id: $('#item-modal-delete').data('item-id')},
		function(data) {
			if(data.action == 'deleted') {
				$('.bottom-right').notify({
					message: { text: $('#item-modal-delete').data('item-name')+' has been deleted successfully!' }
				}).show();
				$('#item-modal-delete').modal('hide');
			}
			else {
				//mark the errors on the form
				alert('error!!!!');
			}
		}
	);
}