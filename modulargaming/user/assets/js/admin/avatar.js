/**
 * Modular Gaming js file.
 */

$(document).ready(function () {

	if ( ! $('body').hasClass('admin-avatars')) {
		return;
	}

    $('#input-img').fileupload({uploadtype:'image', name:'img'});

    $('#data-table').on('crud.clean', function (e) {
        $('#input-img').fileupload('reset');
    });
    $('#data-table').on('crud.load', function (e, data) {
        $('#input-img > .fileupload-preview').append('<img src="' + data.img + '" />');
        $('#input-img').addClass('fileupload-exists').removeClass('fileupload-new');
    });

    $('#data-table').CRUD({
        base_url:'./avatar/',
        identifier:{data:'title', table:2},
        upload:true,
        dataTable:{
            "aoColumnDefs":[
                {
                    "bSortable":false,
                    "aTargets":[ 0 ],
                    "mRender":function (data, type, full) {
                        return '<img src="' + data + '" />';
                    }
                },
                {
                    "aTargets":[ 2 ],
                    "mRender":function (data, type, full) {
                        if (data == 1)
                            return '<i class="icon-ok offset5"> </i>';
                        else
                            return '<i class="icon-eye-close offset5"> </i>';
                    }
                }
            ]
        }
    });
});