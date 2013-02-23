/**
 * Item admin shop list.
 */

$(document).ready(function () {
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
        table:{
            span:8,
            offset:2
        }
    });

});