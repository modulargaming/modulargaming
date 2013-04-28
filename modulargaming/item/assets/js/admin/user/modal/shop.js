var shopModal = $.extend({}, Modal, {
	_title: 'User shop',
	_setup: function(paths, modal){
		this.initTabs($('#shop-tabs'));

		//load the shop's content
		$.get(paths.load, function(d,s,x){
			$('#input-title').val(d.fields.name);
			$('#input-description').val(d.fields.description);
			$('#input-size').val(d.fields.size);

			//bind the update button
			$('#modal-shop-update').click(function(e){
				e.preventDefault();

				var form = $('#modal-shop-form').serialize();

				$.post(paths.update, form, function(d,s,x){
					if(d.status == 'success')
					{
						modal.modal.one('hidden', function(){
							$('#crud-notify').notify({message: 'The shop was updated successfully', type: 'success'}).show();
						});
						modal.modal.modal('hide');
					}
					else
					{
						var errors = '<span class="error" id="modal-shop-save-errors">'+ d.errors.join(', ')+'</span>';
						$('#shop-info').append(errors);
						setTimeout(function(){
							$('#modal-shop-save-errors').fadeOut(function(){
								$('#modal-shop-save-errors').remove();
							});
						}, 15000);
					}
				});
			});
		});

		//bind the stock table
		var dataTable = $('#table-shop').dataTable(this.table(paths.stock));

		//bind the option button
		$('#table-shop').on('click', '.btn-reset', {modal: this}, function(e){
			e.preventDefault();
			var id = $(this).data('id');
			var row_id = dataTable.fnGetPosition($(this).closest('tr')[0]);

			$.post(paths.reset, {id: id}, function(d,s,x){
				if(d.status == 'success')
				{
					//reset the table's price to zero
					dataTable.fnUpdate('0', row_id, 4);
				}
				else
				{
					$('#shop-stock').append('<span class="error" id="modal-shop-error">'+ d.error+'</span>');
					setTimeout(function(){
						$('#modal-shop-error').fadeOut();
					}, 8000);
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
						return '<button data-id="' + data + '" class="btn btn-primary btn-reset">Reset price</button>';
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
	handlers.shop = shopModal;
});