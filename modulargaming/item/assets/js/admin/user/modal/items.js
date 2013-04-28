var itemsModal = $.extend({}, Modal, {
	_title: 'User items',
	_setup: function(paths, modal){
		this.initTabs($('#items-tabs'));

		//bind the item table
		var dataTable = $('#table-items').dataTable(this.table(paths.load));

		var last_added = null;

		$('#input-item').typeahead({
			source:function (query, process) {
				return $.get(paths.search, { title:query }, function (data) {
					return process(data);
				});
			},
			updater: function(item){
				if(last_added != item) {
					$('#input-item-list').append('<li><checkbox name="items[]" value="'+item+'" class="hide" checked />'+item+'</li>');
					last_added = item;
					setTimeout(function(){last_added=null;}, 100);
				}
			}
		});

		$('#filter-location').change(function(){
			dataTable.fnFilter($(this).val(), 2);
		});

		//register the modify button
		$('#table-items').on('click', '.btn-modify', function(ev){
			ev.preventDefault();
			var id = $(this).data('id');

			$('body').modalmanager('loading');

			$.get(paths.modify, {id: id}, function(d,s,x){
				$('#input-items-modify-id').text(d.id);
				$('#item-id').val(d.id);
				$('.modal-items-modify-name').text(d.name);
				$('#input-items-modify-location').text(d.loc);
				$('#input-items-modify-amount').val(d.amount);

				//open the modal
				modal.openModalStack($('#modal-items-modify'));

				$('#modal-items-modify-complete').unbind('click');
				$('#modal-items-modify-complete').click(function(e){
					e.preventDefault();
					var values = $('#modal-items-modify-form').serialize();

					$('body').modalmanager('loading');

					$.post(paths.save, values, function(d,s,x){
						if(d.status == 'success')
						{
							//show notification when all modals are closed
							modal.modal.one('hidden', function(){
								$('#crud-notify').notify({message: 'Item(s) were given successfully', type: 'success'}).show();
							});

							$('body').modalmanager('removeLoading');

							//close any open modals
							$('#modal-items-modify').modal('hide');
							modal.modal('hide');
						}
						else
						{
							var errors = '<span class="error">'+ d.errors.join(', ')+'</span>';
							$('#items-give').append(errors);
						}
					});
				});
			});
		});


		//give the items to the user
		$('#modal-items-give').click(function(e){
			e.preventDefault();
			var values = $('#modal-items-form').serialize();

			$('body').modalmanager('loading');

			$.post(paths.give, values, function(d,s,x){
				if(d.status == 'success')
				{
					//show notification when all modals are closed
					modal.modal.one('hidden', function(){
						$('#crud-notify').notify({message: 'Item(s) were given successfully', type: 'success'}).show();
					});

					$('body').modalmanager('removeLoading');

					//close any open modals
					modal.modal('hide');
				}
				else
				{
					var errors = '<span class="error">'+ d.errors.join(', ')+'</span>';
					$('#items-give').append(errors);
				}
			});
		});
	},
	table : function(url) {
		return {
			"bProcessing":true,
			"bServerSide":true,
			"sAjaxSource": url,
			"sDom":"<'row-fluid'<'span4 select-input'><'span8'f>><'row-fluid muted pagination-centered'r>"+
				"<'row-fluid't>"+
				"<'row-fluid'<'span5 muted'i><'span6 offset1 pull-right'p>>",
			"fnInitComplete": function(oSettings, json) {
				$('.select-input').html($('#filter-select').html());
				$('#filter-select').remove();
			},
			"aoColumnDefs":[
				{
					"bSortable":false,
					"aTargets":[ -1 ],
					"mRender":function (data, type, full) {
						return '<button data-id="' + data + '" class="btn btn-warning btn-modify">Modify</button>';
					}
				},
				{
					"bSortable":false,
					"aTargets":[ 0 ],
					"mRender":function (data, type, full) {
						return '<img src="' + data + '" />';
					}
				}
			]
		}
	}
});

$(document).ready(function () {
	handlers.items = itemsModal;
});