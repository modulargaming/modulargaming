/**
 * Item admin shop list.
 */

$(document).ready(function () {

	if ( ! $('body').hasClass('admin/item-shops')) {
		return;
	}

    var current = {};

    $('#input-stock-item_name').typeahead({
        source: function (query, process) {
            return $.get('./search/', { type: 'item', item: query }, function (data) {
                return process(data);
            });
        }
    });

    //handle restock modal
    $('#data-table').on('click', '.btn-stock', function(e){
        RestockModal($(this).data('id'));
    });

    //load restock modal
    function RestockModal (id) {
        var stock_type = '';

        $('#table-stock-content > tr').remove();
        $('#modal-stock-cap').addClass('hide').html('');

        $.getJSON('./shops/stock', {id: id}, function(data){
            var table = [];

            if(data.items.length > 0) {
                $.each(data.items, function(id, val){
                    table.push('<tr><td><img src="'+val.img+'" /></td><td>'+val.name+'</td><td>'+val.price+'</td><td>'+val.amount+'</td><td>'+val.cap_amount+'</td><td><a href="#" data-toggle="tooltip" title="'+val.last_restock+'">'+val.frequency+' seconds</a></td><td><button class="btn btn-warning btn-restock-item-edit" data-id="'+val.id+'">Edit</button></td></tr>');
                });
                $('#table-stock-content').append(table.join('\n'));
            }
            else
            {
                $('#table-stock-content').append('<tr><td colspan="6" class="pagination-centered">No items in stock</td></tr>');
            }
            $('#modal-stock-count').text(data.total_amount);

            stock_type = data.stock_type;

            current.stock = stock_type;
            current.shop_id = id;
            $('#input-stock-shop-id').val(id);
            if(stock_type == 'restock') {
                $('#modal-stock-cap').html(' and will restock maximum '+data.stock_cap+' items.').removeClass('hide');
            }

            //hide previous modal and show modal-stock
            $('#modal-crud').modal('hide');
            $('#modal-stock').modal('show');
        });

        //when hiding the stock item modal we'll want go back to the stock modal
        $('#modal-stock-item').on('hidden', function(){
            RestockModal(current.shop_id);
        });

        //Unset a previously defined click event
        $('#btn-stock-add').off('click');

        //register a new click event
        $('#btn-stock-add').on('click', function(){

            setupItemForm(id, 0, stock_type, {});

            // hide previous modal and show modal-stock-item
            $('#modal-stock').modal('hide');
            $('#modal-stock-item').modal('show');
        });
    }

    //save an item restock
    $('#btn-stock-add-complete').click(function(){
        var values = $('#form-stock-item').serialize();

        $.post('./shops/stock/save', values, function(data){
            if(data.status == 'error') {
                var errs = [];

                $.each(data.errors, function(k, v){
                    errs.push('<li>'+v+'</li>');
                });

                $('#modal-stock-error-list').html(errs.join('\n'));
                $('#modal-stock-errors').removeClass('hide');
            }
            else {
                $('.bottom-right').notify({
                    message: { text: 'New item restock added!' }
                }).show();
                $('#modal-stock-item').modal('hide');
            }
        });
    });

    //handle the edit restock buttons
    $('#table-stock-content').on('click', '.btn-restock-item-edit', function(){
        var item_id = $(this).data('id');

        $.getJSON('./shops/stock/load', {item_id: item_id, shop_id: current.shop_id}, function(data){
            setupItemForm(current.shop_id, item_id, current.stock, data);

            // hide previous modal and show modal-stock-item
            $('#modal-stock').modal('hide');
            $('#modal-stock-item').modal('show');
        });
    });

    $('#input-image').fileupload({uploadtype:'image', name:'image'});

    //don't forget to add the npc image to the image field
    $('#data-table').on('crud.load', function (e, data){
        $('#input-image > .fileupload-preview').append('<img src="/assets/img/npc/shop/'+data.npc_img+'" />');
        $('#input-image').addClass('fileupload-exists').removeClass('fileupload-new');
    });

    //clean the image field
    $('#crud-container').on('crud.clean', function (e){
        $('#input-image').fileupload('clear').addClass('fileupload-new').removeClass('fileupload-exists');
        $('#input-stock_cap').val('0');
    });

    $('#input-stock_type').on('change', function(){
        if($(this).val() == 'steady')
            $("#input-stock_cap").attr('disabled', 'disabled');
        else
            $("#input-stock_cap").removeAttr('disabled');
    });

    //Activate crud UI behaviour
    $('#data-table').CRUD({
        base_url:'./shops/',
        identifier:{data:'title', table:1},
        upload:true,
        buttons: function(id, type, full) {
            return ' <button data-id="'+id+'" class="btn btn-info btn-stock">Stock</button> ';
        },
        table:{
            span:8,
            offset:2
        }
    });

});

function setupItemForm(shop_id, item_id, stock_type, data) {
    //empty out the form
    $('#form-stock-item')[0].reset();

    //reset errors
    $('#modal-stock-errors').addClass('hide');
    $('#modal-stock-error-list > li').remove();

    //set csrf
    $('#form-stock-item input[name="csrf"]').val($('#csrf').text());

    //set the shop's ID
    $('#input-stock-shop_id').val(shop_id);

    //set item id
    $('#input-stock-item_id').val(item_id);

    //if it's a steady stock we can hide most of the form elements
    if(stock_type != 'restock')
        $('.restock').addClass('hide');
    else
        $('.restock').removeClass('hide');

    //if data is specified fill the form
    if(data.length > 0) {
        $.each(data, function(key, value){
            $('#input-stock-'+key).val(value);
        });
        $('#input-stock-item_name').attr('disabled', 'disabled');
    }
    else {
        $('#input-stock-item_name').removeAttr('disabled');
    }
}