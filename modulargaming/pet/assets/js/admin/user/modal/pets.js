var petsModal = $.extend({}, Modal, {
	_title: 'Pets',
	_setup: function(paths, modal){
		this.initTabs($('#pets-tabs'));

		$('.tab-content').on('click', '.pet-save', function(e){
			e.preventDefault();

			var id = $(this).data('id');
			data = $('#modal-pets-form-'+id).serialize();

			$.post(paths.save.replace(0, id), data, function(d,s,x){
				if(d.status == 'success')
				{
					modal.modal.one('hidden', function(){
						$('#crud-notify').notify({message: 'Pet was edited successfully', type: 'success'}).show();
					});
					modal.modal.modal('hide');
				}
				else
				{
					var errors = '<span class="error">'+ d.errors.join(', ')+'</span>';
					$('#pet-info-'+id).append(errors);
				}
			});
		});
	}
});

$(document).ready(function () {
	handlers.pets = petsModal;
});