/**
 * Item admin list.
 */

$(document).ready(function () {
	$('#data-tabs a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});

	$('#backup-start').click(function(e){
		e.preventDefault();

		var requests = $('#table-requests').data();

		var queue = $.qjax({
			ajaxSettings: {
				type: 'GET',
				dataType: 'json'
			},
			onStart: function() {
				$('#crud-notify').notify({message: 'Backup process Started', type: 'warning'}).show();
			},
			onStop: function() {
				$('#crud-notify').notify({message: 'Backup process was completed', type: 'success'}).show();
			}
		});

		$.each(requests, function(id,url){
			//find the responding LI
			var el = $('#backup-'+id);
			var tr = el.parents('tr');

			//if we have records stored we'll want to queue a backup request
			if(el.find('.stored_records').text() > 0) {
				tr.data('complete', 0);

				var amount = tr.find('td:nth-child(3)').find('ul>li').length;
				tr.data('amount', amount);

				queue.Queue({
					url: url,
					beforeSend: function(){
						tr.addClass('info');
						el.addClass('info');
					},
					success: function(){
						el.removeClass('info').addClass('success');
						tr.data('complete', tr.data('complete')+1);

						if(tr.data('complete') == tr.data('amount')) {
							tr.removeClass('info').addClass('success');
							el.closest('div').find('a').addClass('btn-success');
						}
					}
				});
			}
			else {
				//no records
				tr.addClass('warning');
			}
		});
	});
});