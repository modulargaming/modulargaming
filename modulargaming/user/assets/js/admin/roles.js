/**
 * Modular Gaming js file.
 */

$(document).ready(function () {

    $('#data-table').CRUD({
        base_url:'./roles/',
        identifier:{data:'name', table:1},
        dataTable:{
            "aoColumnDefs":[ ]
        },
	    table:{
		    span:8,
		    offset:2
	    }
    });
});