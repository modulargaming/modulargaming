/**
 * Admin user users js file.
 */

$(document).ready(function() {

    $('#crud-container').CRUD({
        base_url: './user/',
        identifier: {data: 'name', table: 1},
        table: {
            span: 5,
            offset: 4
        }
    });
});
