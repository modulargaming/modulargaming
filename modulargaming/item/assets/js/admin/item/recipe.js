/**
 * Admin item recipe's js file.
 */

$(document).ready(function () {

	if ( ! $('body').hasClass('admin/item-recipes')) {
		return;
	}

    $('#input-crafted_item').typeahead({
        source:function (query, process) {
            return $.get('./search/', { type:'item', item:query }, function (data) {
                return process(data);
            });
        },
    });

    var ingrEl = 0;
    var ingrCount = 0;

    $('#data-table').on('crud.clean', function () {
        $('#ingredient-body > tr').each(function () {
            $(this).remove();
        });
        ingrCount = 0;
        ingrEl = 0;
    });

    $('#data-table').on('crud.load', function (e, data) {
        if (data.materials.length > 0) {
            $.each(data.materials, function (key, val) {
                $('#ingredient-body').append('<tr><td><input type="text" class="input-small disabled" name="materials[' + val.id + '][name]" value="' + val.name + '" /></td><td><input type="text" class="input-mini" name="materials[' + val.id + '][amount]" value="' + val.amount + '" /></td><td><a href="#" class="btn btn-mini btn-danger ingredient-remove">x</a></td></tr>');

            });
        }
    });

    $('#data-table').on('crud.save', function (e, values, data) {
        $('#container-' + $('#input-id').val()).find('[data-prop="ingredients"]').text($('#ingredient-body > tr').length);
    });

    $('#btn-add-ingredient').click(function (e) {
        e.preventDefault();
        $('#ingredient-body').append('<tr><td><input type="text" class="input-small" name="materials[key' + ingrEl + '][name]"/></td><td><input type="text" class="input-mini" name="materials[key' + ingrEl + '][amount]" value="1" /></td><td><a href="#" class="btn btn-mini btn-danger ingredient-remove">x</a></td></tr>');

        $('input[name="materials[key' + ingrEl + '][name]"]').typeahead({
            source:function (query, process) {
                return $.get('./search/', { type:'item', item:query }, function (data) {
                    return process(data);
                });
            },
        });
        ingrCount++;
        ingrEl++;
    });

    $('#ingredient-body').on('click', 'tr > td.ingredient-remove', function (e) {
        e.preventDefault();
        $(this).parents('tr').remove();
        ingrCount--;
    });

    $('#data-table').CRUD({
        base_url:'./recipes/',
        identifier:{data:'name', table:1},
        upload:true,
        dataTable:{
            "aoColumnDefs":[
                {
                    "aTargets":[ 2 ],
                    "mRender":function (data, type, full) {
                        return '<img src="' + data + '" />';
                    }
                },
                {
                    "aTargets":[ 1 ],
                    "mRender":function (data, type, full) {
                        return '<span class="badge badge-info offset4">' + data + '</span>';
                    }
                }
            ]
        },
        table:{
            span:6,
            offset:3
        }
    });
});