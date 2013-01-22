/**
 * Modular Gaming Inventory UI handler.
 */

$(document).ready(function() {
	//delegate a click event to the table containing actions
	$('#item_actions').on('click', 'li > a', function(e){
		e.preventDefault();
		console.log($(this).parent('li').data('action'));
	});
	
	$('#item-action-close').click(function(e){
		e.preventDefault();
		$('#item-consume').addClass('hide');
	});
	
	$('ul.thumbnails > li > a').click(function(e){
		e.preventDefault();
		
		//empty out table
		$('#item_actions').find('li').remove();
		
		//do request
		$.getJSON(this.href, function(data) {
			if(data.status == 'success') {
				$.each(data.actions, function(key, val){
					$('#item-actions').append('<li data-action="'+key+'"><a href="#">'+val+'</a></li>');
				});
				$('#item-consume > h5').text("'"+data.name+"'");
				$('#item-consume').removeClass('hide');
				//highlight the border of the selected item in green
			}
			else {
				$.each(data.errors, function(k, v) {
					$('#item-container').before('<div class="alert">'+
							  +'<button type="button" class="close" data-dismiss="alert">&times;</button>' 
							  +'<strong>Error: </strong> '+ v
							+'</div>');
				});
			}
		}).fail(function(jqXHR, textStatus, errorThrown) {
			console.log(errorThrown);
		});
	});
});