var logsModal = $.extend({}, Modal, {
	_title: 'Logs',
	_width: 800,
	_setup: function(paths, modal){
		//bind the local settings table
		var dataTable = $('#table-logs').dataTable(this.table(paths.load));

		//Bind the check button
		$('#table-logs').on('click', '.btn-check', function (e) {
			e.preventDefault();
			var id = $(this).data('id');
			$.get(paths.view, {id: id}, function(d){
				$('#modal-header').text('Viewing log #'+ d.id);

				$('#log-user').html('<a href="./user/'+ d.id+'/view" target="_blank">'+ d.username+'</a>');
				$('#log-time').text(d.time);
				$('#log-alias').text(d.alias);
				$('#log-location').text(d.location);
				$('#log-type').text(d.type);
				$('#log-client').html('IP: '+d.client.ip+'<br /> Agent: '+ d.client.agent);
				$('#log-msg').text(d.message);
				$('#log-param').html('<pre>'+jsonView(JSON.stringify(d.params, undefined, 4))+'</pre>');

				modal.openModalStack($('#modal-log-read'));
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
						return '<button data-id="' + data + '" class="btn btn-warning btn-check">Check</button>';
					}
				},
				{
					"bSortable":false,
					"aTargets":[ 2 ],
					"mRender":function (data, type, full) {
						if(data.length > 22)
						{
							return data.substr(0, 22)+'..';
						}
						else
							return data;

					}
				},
				{
					"aTargets":[ -2 ],
					"mRender":function (data, type, full) {
						var date = new Date(data *1000);
						var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

						return date.getDate()+' '+months[date.getMonth()]+' '+date.getHours()+':'+date.getMinutes()+':'+date.getSeconds();
					}
				}
			]
		}
	}
});

$(document).ready(function () {
	handlers.logs = logsModal;
});

function jsonView(json) {
	json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
	return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
		var cls = 'number';
		if (/^"/.test(match)) {
			if (/:$/.test(match)) {
				cls = 'key';
			} else {
				cls = 'string';
			}
		} else if (/true|false/.test(match)) {
			cls = 'boolean';
		} else if (/null/.test(match)) {
			cls = 'null';
		}
		return '<span class="' + cls + '">' + match + '</span>';
	});
}