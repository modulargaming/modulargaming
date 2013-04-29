/**
 * Admin pet specie's js file.
 */

$(document).ready(function() {

	if ( ! $('body').hasClass('admin/pet-specie')) {
		return;
	}

    $('#search').typeahead({
        source: function (query, process) {
            return $.get('./search/', { type: 'pet-specie', item: query }, function (data) {
                return process(data);
            });
        },
        updater: function(obj){
            $('#crud-container').trigger('crud.FormOpen', {name: obj});
        }
    });

    //colour buttons
    $('#crud-container').on('click', '.btn-colour', function(e){
        e.preventDefault();
        var id = $(this).data('id');
        var btn = $(this);
        $('#specie-id').val(id);
        btn.text('loading');

        $.get('./specie/colour/load', {id: id}, function (data) {
            var options = [];
            var table = [];
            $('#add-colour').data('specie-id', id);

            $.each(pet_colours, function(key, value){
                if(data.colours.indexOf(key) >= 0) {
                    table.push('<tr><td>'+value.name+'</td><td><button class="btn btn-danger btn-colour-remove" data-id="'+key+'">Remove colour</button></td></tr>');
                }
                else {
                    options.push('<option value="'+key+'">'+value.name+'</option>');
                }
            });

            //add the options and table rows
            $('#colour-list-options').find('option').remove();
            $('#colour-list-options').html(options.join(''));
            $('#colour-list > tbody').find('tr').remove();
            $('#colour-list > tbody').append(table.join(''));
            btn.text('Colours');
            $('#modal-colour').modal('show');
        });
    });

    //remove a colour
    $('#colour-list tbody').on('click', '.btn-colour-remove', function(e){
        e.preventDefault();
        var colour = $(this).data('id');
        var specie = $('#add-colour').data('specie-id');

        $.get('./specie/colour/delete', {specie_id: specie, colour_id: colour}, function (data) {
            //remove the table row
            $('#colour-list > tbody').find('button[data-id="'+colour+'"]').parents('tr').remove();
            //add back the option
            $('#colour-list-options').append('<option value="'+colour+'">'+pet_colours[colour].name+'</option>');
        });
    });

    //add selected colours
    $('#add-colour').click(function (e) {
        e.preventDefault();

        //set modal values
        var colour_id = $('#colour-list-options option:selected').val();
        var colour_name = $('#colour-list-options option:selected').text();
        var specie_id = $('#specie-id').val();

        $('#add-colour-id').val(colour_id);
        $('#add-specie-id').val(specie_id);
        $('#add-colour-name').text(colour_name);

        //reset upload
        $('#modal-footer-upload-progress').width('1').parent().addClass('hide');
        $('#modal-footer-upload-progress').html('');

        //only when a colour has been selected
        if(colour_name != '')
        {
            //hide the main modal
            $('#modal-colour').modal('hide');

            //show the upload colour modal
            $('#modal-colour-add').modal('show');

            //when closed we'll want to open the previous modal again
            $('#modal-colour-add').one('hidden', function(){
                $('#modal-colour').modal('show');
            });

            $('#modal-colour-add-done').click(function(e){
                e.preventDefault();

                var ajax_options = {
                    type:"POST",
                    url:'./specie/colour/update',

                    success: function(data){
                        if(data.status == 'success')
                        {
                            $('#colour-list-options option:selected').remove();
                            $('#colour-list > tbody').append('<tr><td>' + colour_name + '</td><td><button class="btn btn-danger btn-colour-remove" data-id="' + colour_id + '">Remove colour</button></td></tr>');

                            $('#modal-colour-add').modal('hide');

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
                ajax_options.xhr = function () {  // custom xhr
                    myXhr = $.ajaxSettings.xhr();
                    if (myXhr.upload) { // check if upload property exists
                        myXhr.upload.addEventListener('progress', function(e){
                            if (e.lengthComputable) {
                                var percentage = Math.round((e.loaded * 100) / e.total);
                                $('#modal-footer-upload-progress').css('width', percentage + '%');

                                if (percentage == 100) {
                                    $('#modal-footer-upload-progress').html('<span class="pagination-centered" style="color: #ffffff;">Processing form</span>')
                                }
                            }
                        }, false); // for handling the progress of the upload
                    }
                    return myXhr;
                };
                ajax_options.cache = false;
                ajax_options.contentType = false;
                ajax_options.processData = false;
                ajax_options.data = new FormData($('#add-colour-form')[0]);

                $('#modal-footer-upload-progress').parent().removeClass('hide');

                $.ajax(ajax_options);
            });
        }
    });

    $('#crud-container').CRUD({
        base_url: './specie/',
        identifier: {data: 'name', table: 1},
        buttons: function(id, type, full) {
            return ' <button data-id="'+id+'" class="btn btn-info btn-colour">Colours</button> ';
        },
        table: {
            span: 5,
            offset: 4
        }
    });
});
