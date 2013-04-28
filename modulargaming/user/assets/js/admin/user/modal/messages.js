var messageModal = $.extend({}, Modal, {
	_title: 'Messages',
	_setup: function(paths, modal){
		this.initTabs($('#msg-tabs'));

		//bind the received msgs
		$('#table-msg-received').dataTable(this.table(paths.received));

		//bind the sent msgs
		$('#table-msg-sent').dataTable(this.table(paths.sent));

		//bind the option buttons
		$('#table-msg-received, #table-msg-sent').on('click', '.btn-delete', function(e){
			e.preventDefault();
			var id = $(this).data('id');

			$('.modal-msg-delete-id').text(id);
			modal.openModalStack($('#modal-msg-delete'));

		});

		$('#table-msg-received, #table-msg-sent').on('click', '.btn-read', function(ev){
			ev.preventDefault();
			var id = $(this).data('id');

			$('body').modalmanager('loading');

			$.get(paths.read, {id: id}, function(d,s,x){
				$('.modal-msg-read-id').text(d.fields.id);
				$('#modal-msg-read-sender').text(d.fields.sender);
				$('#modal-msg-read-receiver').text(d.fields.receiver);
				$('#modal-msg-read-subject').text(d.fields.subject);
				$('#modal-msg-read-message').text(d.fields.message);

				$('#modal-msg-read-delete').on('click', function(e){
					$('modal-msg-delete-id').text(d.fields.id);
					modal.openModalStack($('#modal-msg-delete'));
				});

				//open the modal
				modal.openModalStack($('#modal-msg-read'));
			});

		});

		//bind the send msg button
		$('#modal-msg-send').click(function(e){
			e.preventDefault();

			var values = $('#modal-msg-form').serialize();

			$('body').modalmanager('loading');

			$.post(paths.send, values, function(d,s,x){
				if(d.status == 'success')
				{
					modal.modal.one('hidden', function(){
						$('#crud-notify').notify({message: 'Message was sent successfully', type: 'success'}).show();
					});

					//close any open modals
					$('body').modalmanager('removeLoading');
					modal.modal('hide');
				}
				else
				{
					var errors = '<span class="error">'+ d.errors.join(', ')+'</span>';
					$('#modal-msg-body').append(errors);
				}
			});
		});

		//bind the delete button
		$('#modal-msg-delete-complete').click(function(e){
			$('body').modalmanager('loading');
			var id = $('.modal-msg-delete-id')[0].text();

			$.post(paths.delete, {id: id}, function(d,s,x){

				$('#modal-msg-delete').one('hidden', function(){
					$('#crud-notify').notify({message: 'Message #'+id+' was deleted successfully', type: 'success'}).show();
				});

				//close any open modals
				$('body').modalmanager('removeLoading');
				$('#modal-msg-delete').modal('hide');
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
						return '<button data-id="' + data + '" class="btn btn-primary btn-read">Read</button> <button data-id="' + data + '" class="btn btn-danger btn-delete">Delete</button>';
					}
				}
			]
		}
	}
});

$(document).ready(function () {
	handlers.messages = messageModal;
});