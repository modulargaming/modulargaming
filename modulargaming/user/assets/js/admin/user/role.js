/**
 * Admin user role js file.
 */

$(document).ready(function() {
    $('#data-table').CRUD({
        base_url: './role/',
        identifier: {data: 'name', table: 1},
        table: {
            span: 5,
            offset: 3
        }
    });
});
