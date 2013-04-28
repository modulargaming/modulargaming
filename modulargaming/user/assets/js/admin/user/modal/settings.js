var settingsModal = $.extend({}, Modal, {
	_title: 'Settings',
	_setup: function(paths, modal){
		//bind the local settings table
		var dataTable = $('#table-settings').dataTable(this.table(paths.load));

		//bind the remove button
		$('#table-settings').on('click', '.btn-remove', function(e){
			e.preventDefault();
			var id = $(this).data('id');
			var rowIndex = dataTable.fnGetPosition($(this).closest('tr')[0]);
			var name = dataTable.fnGetData(rowIndex, 0);
			$('.modal-settings-delete-name').text(name);

			modal.openModalStack($('#modal-settings-delete'));
		});

		//bind the edit button
		$('#table-settings').on('click', '.btn-edit', function(ev){
			ev.preventDefault();
			var id = $(this).data('id');

			$('body').modalmanager('loading');

			$.get(paths.read, {id: id}, function(d,s,x){
				$('#modal-settings-read-id').text(d.id);
				$('#input-settings-id').val(d.id);
				var name = d.name;
				$('.modal-settings-read-name').text(name);
				$('.modal-settings-delete-name').text(name);
				$('#input-settings-content').text(d.content);

				//Bind the delete button
				$('#modal-settings-read-delete').unbind('click');
				$('#modal-settings-read-delete').on('click', function(e){
					modal.openModalStack($('#modal-settings-delete'));
				});

				//open the modal
				modal.openModalStack($('#modal-settings-read'));
			});
		});

		//bind the save setting button
		$('#modal-settings-read-save').on('click', function(e){
			e.preventDefault();

			var values = $('#modal-settings-form').serialize();

			$('body').modalmanager('loading');

			$.post(paths.save, values, function(d,s,x){
				if(d.status == 'success')
				{
					//show notification when all modals are closed
					modal.modal.one('hidden', function(){
						$('#crud-notify').notify({message: 'Setting #'+ d.name+' was edited successfully', type: 'success'}).show();
					});

					$('body').modalmanager('removeLoading');

					//close any open modals
					$('#modal-settings-read').modal('hide');
					modal.modal('hide');
				}
				else
				{
					var errors = '<span class="error">'+ d.errors.join(', ')+'</span>';
					$('#modal-settings-body').append(errors);
				}
			});
		});

		$('#modal-settings-delete-complete').on('click', function(e){
			e.preventDefault();

			$('body').modalmanager('loading');
			$.get(path.remove, {name: $('.modal-settings-delete-name')[0].text()}, function(d,s,x){

				//show notification when hidden
				modal.modal.one('hidden', function(){
					$('#crud-notify').notify({message: 'Setting #'+data.name+' was deleted successfully', type: 'success'}).show();
				});

				//hide any open modals
				$('body').modalmanager('removeLoading');
				$('#modal-settings-delete').modal('hide');
				modal.modal.modal('hide');
			});
		});
	},
	table : function(url) {
		return {
			"bProcessing":true,
			"bServerSide":true,
			"sAjaxSource": url,
			"sDom":"<'row-fluid'<'span8 offset3'f>><'row-fluid muted pagination-centered'r>"+
				"<'row-fluid't>"+
				"<'row-fluid'<'span5 muted'i><'span6 offset1 pull-right'p>>",
			"aoColumnDefs":[
				{
					"bSortable":false,
					"aTargets":[ -1 ],
					"mRender":function (data, type, full) {
						return '<button data-id="' + data + '" class="btn btn-warning btn-edit">Edit</button> <button data-id="' + data + '" class="btn btn-danger btn-remove">Remove</button>';
					}
				},
				{
					"bSortable":false,
					"aTargets":[ -2 ],
					"mRender":function (data, type, full) {
						if(data.length > 22)
						{
							return data.substr(0, 20)+'...';
						}
						else
							return data;

					}
				}
			]
		}
	}
});

$(document).ready(function () {
	handlers.settings = settingsModal;
});