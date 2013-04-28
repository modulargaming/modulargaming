var tradeModal = $.extend({}, Modal, {
	_title: 'Trades',
	_setup: function(paths, modal){
		this.initTabs($('#trades-tabs'));

		//bind the received trades
		var lots = this.table(paths.lots);
		lots.aoColumnDefs[0].mRender = function(data, type, full) {
			return '<button data-id="' + data + '" class="btn btn-warning btn-modify">Edit</button> <button data-id="' + data + '" data-type="lot" class="btn btn-danger btn-cancel">cancel</button>';
		}

		var dtLots = $('#table-trades-lots').dataTable(lots);

		//bind the sent trades
		var dtBids = $('#table-trades-bids').dataTable(this.table(paths.bids));

		//bind the option buttons
		$('#table-trades-lots, #table-trades-bids').on('click', '.btn-cancel', function(e){
			e.preventDefault();
			var id = $(this).data('id');
			var type = $(this).data('type');

			$('.modal-trades-cancel-id').text(id);
			$('#cancel-id').val(id);
			$('.modal-trades-cancel-type').text(type);
			$('#cancel-type').val(type);
			modal.openModalStack($('#modal-trades-cancel'));

		});

		$('#table-trades-lots').on('click', '.btn-modify', function(ev){
			ev.preventDefault();
			var id = $(this).data('id');

			$('body').modalmanager('loading');

			$.get(paths.load, {id: id}, function(d,s,x){
				$('.modal-trades-modify-lot').text(d.fields.id);
				$('#lot-id').text(d.fields.id);
				$('#input-trades-modify-description').text(d.fields.description);

				//open the modal
				modal.openModalStack($('#modal-trades-modify'));
			});

		});

		//bind the modify trades button
		$('#modal-trades-modify-complete').click(function(e){
			e.preventDefault();

			var values = $('#modal-trades-modify-form').serialize();

			$('body').modalmanager('loading');

			$.post(paths.edit, values, function(d,s,x){
				if(d.status == 'success')
				{
					modal.modal.one('hidden', function(){
						$('#crud-notify').notify({message: 'Trade was edited successfully', type: 'success'}).show();
					});

					//close any open modals
					$('body').modalmanager('removeLoading');
					$('#modal-trades-read').modal('hide');
					modal.modal('hide');
				}
				else
				{
					var errors = '<span class="error">'+ d.errors.join(', ')+'</span>';
					$('#modal-trade-body').append(errors);
				}
			});
		});

		//bind the delete button
		$('#modal-trades-cancel-complete').click(function(e){
			$('body').modalmanager('loading');

			var values = $('#modal-trades-cancel-form').serialize();

			$.post(paths.cancel, values, function(d,s,x){

				$('#modal-trades-cancel').one('hidden', function(){
					$('#crud-notify').notify({message: type+' #'+id+' was cancelled successfully', type: 'success'}).show();
				});

				//close any open modals
				$('body').modalmanager('removeLoading');
				$('#modal-trades-cancel').modal('hide');
				modal.modal('hide');
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
						return '<button data-id="' + data + '" data-type="bid" class="btn btn-danger btn-cancel">Cancel</button>';
					}
				}
			]
		}
	}
});

$(document).ready(function () {
	handlers.trades = tradeModal;
});