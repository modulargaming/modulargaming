/**
 * Modular Gaming js file.
 */

$(document).ready(function () {
	$('#data-table').on('click', '.btn-check', function (e) {
		e.preventDefault();
		var id = $(this).data('id');
		$.get('./logs/retrieve', {id: id}, function(d){
			$('#modal-header').text('Viewing log #'+ d.id);

			$('#log-user').html('<a href="./user/'+ d.id+'/view" target="_blank">'+ d.username+'</a>');
			$('#log-time').text(d.time);
			$('#log-alias').text(d.alias);
			$('#log-location').text(d.location);
			$('#log-type').text(d.type);
			$('#log-client').html('IP: '+d.client.ip+'<br /> Agent: '+ d.client.agent);
			$('#log-msg').text(d.message);
			$('#log-param').html('<pre>'+jsonView(JSON.stringify(d.params, undefined, 4))+'</pre>');

			$('#modal-crud').modal();
		});
	});
    $('#data-table').CRUD({
        base_url:'./logs/',
        identifier:{data:'alias', table:2},
        upload:false,
        dataTable:{
            "aoColumnDefs":[
	            {
		            "aTargets":[ -3 ],
		            "mRender":function (data, type, full) {

			            if(data.length > 20)
			            {
				            return data.substr(0, 20)+'..';
			            }

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
        },
	    table:{
		    span:9,
		    offset:1
	    },
	    edit_btn: 'check',
	    delete_btn: false
    });
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