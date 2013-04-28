/**
 * Admin forum js file.
 */

$(document).ready(function() {
    $('#data-table').CRUD({
        base_url: './category/',
        identifier: {data: 'title', table: 1},
        dataTable: {
            "aoColumnDefs": [
                {
                    "aTargets": [ 1 ],
                    "mRender": function ( data, type, full ) {
                        if(data == 1)
                            return '<i class="icon-lock"> </i>';
                        else
                            return '<i class="icon-eye-open"> </i>';
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
