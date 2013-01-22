/**
 * Modular Gaming Inventory UI handler.
 */

$(document).ready(function() {
	//delegate a click event to the table containing actions
	$('#item_actions').on('click', 'li > a', function(e){
		e.preventDefault();
		console.log($(this).parent('li').data('action'));
	});
	
	$('ul.thumbnails > li > a').click(function(e){
		e.preventDefault();
		
		//empty out table
		$('#item_actions').find('li').remove();
		
		//do request
		$.getJSON(this.href, function(data) {
			if(data.status == 'success') {
				$.each(data.actions, function(key, val){
					$('#item_actions').append('<li data-action="'+key+'"><a href="#">'+val+'</a></li>');
				});
				$('#item_consume > h5').text("'"+data.name+"'");
				$('#item_consume').removeClass('hide');
			}
		}).fail(function(jqXHR, textStatus, errorThrown) {
			console.log(errorThrown);
		});
	});
});