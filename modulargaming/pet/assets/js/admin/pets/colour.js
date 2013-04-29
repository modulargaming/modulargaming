/**
 * Admin pet colour js file.
 */

$(document).ready(function() {

	if ( ! $('body').hasClass('admin/pet-colour')) {
		return;
	}

    $('#data-table').CRUD({
        base_url: './colour/',
        identifier: {data: 'name', table: 1},
        dataTable: {
            "aoColumnDefs": [
                {
                    "aTargets": [ 1 ],
                    "mRender": function ( data, type, full ) {
                        if(data == 1)
                            return '<i class="icon-lock offset5"> </i>';
                        else
                            return '<i class="icon-eye-open offset5"> </i>';
                    }
                }
            ]
        },
        table: {
            span: 5,
            offset: 3
        }
    });
});