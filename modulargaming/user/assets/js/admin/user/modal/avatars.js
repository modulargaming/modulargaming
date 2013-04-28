var avatarsModal = $.extend({}, Modal, {
	_title: 'Avatars',
	_setup: function(paths, modal){
		this.initTabs($('#avatars-tabs'));

		//bind the avatar table
		var dataTable = $('#table-avatars').dataTable(this.table(paths.load));

		//bind the option buttons
		$('#table-avatars').on('click', '.btn-remove', function(e){
			e.preventDefault();
			var id = $(this).data('id');

			$('.modal-avatars-delete-id').text(id);
			modal.openModalStack($('#modal-avatars-delete'));

		});

		//attach the auto complete
		$('#input-title').typeahead({
			source:function (query, process) {
				return $.get(paths.search, { title:query }, function (data) {
					return process(data);
				});
			}
		});

		//bind the give avatar button
		$('#modal-avatars-give').click(function(e){
			e.preventDefault();

			var values = $('#modal-avatars-form').serialize();

			$.post(paths.give, values, function(d,s,x){
				if(d.status == 'success')
				{
					modal.modal.one('hidden', function(){
						$('#crud-notify').notify({message: 'Avatar '+ d.name +' was given successfully', type: 'success'}).show();
					});

					modal.modal('hide');
				}
				else
				{
					var errors = '<span class="error">'+ d.errors.join(', ')+'</span>';
					$('#avatars-give').append(errors);
				}
			});
		});


		//bind the delete button
		$('#modal-avatars-delete-complete').click(function(e){
			e.preventDefault();
			$('body').modalmanager('loading');

			var id = $('.modal-avatars-delete-id')[0].text();
			var rowIndex = dataTable.fnGetPosition($('table-avatars .btn-remove[data-id="'+id+'"]').closest('tr')[0]);

			$.post(paths.remove, {id: id}, function(d,s,x){
				if(d.status == 'success')
				{
					$('body').modalmanager('removeLoading');
					$('#modal-avatars-delete').modal('hide');
					dataTable.fnDeleteRow(rowIndex, null, true);
				}

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
						return '<button data-id="' + data + '" class="btn btn-danger btn-remove">Remove</button>';
					}
				}
			]
		}
	}
});

$(document).ready(function () {
	handlers.avatars = avatarsModal;
});