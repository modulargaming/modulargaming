/**
 * Admin user users js file.
 */

$(document).ready(function() {

	if ( ! $('body').hasClass('admin/user-user')) {
		return;
	}

    $('#search').typeahead({
        source: function (query, process) {
            return $.get('./search/', { type: 'role', item: query }, function (data) {
                return process(data);
            });
        },
        updater: function(obj){
            $('#crud-container').trigger('crud.FormOpen', {name: obj});
        }
    });

    //role buttons
    $('#crud-container').on('click', '.btn-roles', function(e){
        e.preventDefault();
        var id = $(this).data('id');
        var btn = $(this);
        $('#user-id').val(id);
        btn.text('loading');

        $.get('./user/role/load', {id: id}, function (data) {
            var options = [];
            var table = [];
            $('#add-role').data('user-id', id);

            $.each(user_roles, function(key, value){
                if(data.roles.indexOf(key) >= 0) {
                    table.push('<tr><td>'+value.name+'</td><td><button class="btn btn-danger btn-role-remove" data-id="'+key+'">Remove role</button></td></tr>');
                }
                else {
                    options.push('<option value="'+key+'">'+value.name+'</option>');
                }
            });

            //add the options and table rows
            $('#role-list-options').find('option').remove();
            $('#role-list-options').html(options.join(''));
            $('#role-list > tbody').find('tr').remove();
            $('#role-list > tbody').append(table.join(''));
            btn.text('Roles');
            $('#modal-role').modal('show');
        });
    });

    //remove a role
    $('#role-list tbody').on('click', '.btn-role-remove', function(e){
        e.preventDefault();
        var role = $(this).data('id');
        var user = $('#add-role').data('user-id');

        $.get('./user/role/delete', {user_id: user, role_id: role}, function (data) {
            //remove the table row
            $('#role-list > tbody').find('button[data-id="'+role+'"]').parents('tr').remove();
            //add back the option
            $('#role-list-options').append('<option value="'+role+'">'+user_roles[role].name+'</option>');
        });
    });

    //add selected roles
    $('#add-role').click(function (e) {
        e.preventDefault();

        //set modal values
        var role_id = $('#role-list-options option:selected').val();
        var role_name = $('#role-list-options option:selected').text();
        var user_id = $('#user-id').val();

        $('#add-role-id').val(role_id);
        $('#add-user-id').val(user_id);

        //only when a role has been selected
        if(role_name != '')
        {
                var ajax_options = {
                    type:"POST",
                    url:'./user/role/update',

                    success: function(data){
                        if(data.status == 'success')
                        {
                            $('#role-list-options option:selected').remove();
                            $('#role-list > tbody').append('<tr><td>' + role_name + '</td><td><button class="btn btn-danger btn-role-remove" data-id="' + role_id + '">Remove role</button></td></tr>');


                            $('#crud-notify').notify({
                                message:{ text:data.msg },
                                type:'success'
                            }).show();
                        }
                        else {
                            $('#crud-notify').notify({
                                message:{ text:data.msg },
                                type:'error'
                            }).show();
                        }
                    }
                };
                ajax_options.cache = false;
                ajax_options.contentType = false;
                ajax_options.processData = false;
                ajax_options.data = new FormData($('#add-role-form')[0]);
                $.ajax(ajax_options);
        }
    });
    $('#crud-container').CRUD({
        base_url: './user/',
        identifier: {data: 'username', table: 1},
        buttons: function(id, type, full) {
            return ' <button data-id="'+id+'" class="btn btn-info btn-roles">Roles</button> ';
        },
        table: {
            span: 5,
            offset: 4
        }
    });
});
