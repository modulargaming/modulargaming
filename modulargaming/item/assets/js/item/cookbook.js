/**
 * Modular Gaming Inventory UI handler.
 */

$(document).ready(function () {
    $('li > a').click(function (e) {
        e.preventDefault();

        var url = this.href;
        var id = $(this).data('recipe-id');
        $('.modal-footer > .btn-success').addClass('hide');
        //do request
        $.getJSON(url,function (data) {
            //handle result
            if (data.status == 'success') {
                //show the needed ingredients
                $.each(data.materials, function (key, val) {
                    var color = (val.amount_needed == val.amount_owned) ? 'green' : 'red';
                    var item = $('<li style="display: inline-block; float: none;"><div class="thumbnail span2 pagination-centered"><img src="' + val.img + '" /><br /> <em>' + val.name + '</em><br /> <span style="color: ' + color + ';">' + val.amount_owned + '/' + val.amount_needed + '</span></div></li>');
                    $('ul.thumbnails').append(item);
                });
                $('#recipe').text(data.name);
                if (data.collected == true) {
                    $('.modal-footer > .btn-success').removeClass('hide').data('id', id).data('url', url.replace('view', 'complete')).data('csrf', data.csrf);
                }
                $('.modal').modal('show');
            }
            else {
                $.each(data.errors, function (k, v) {
                    $('#notifications').append('<div class="alert alert-error fade"> <button type="button" class="close" data-dismiss="alert">&times;</button> <strong>Error: </strong> ' + v + '</div>');
                });
                $('#notifications').find('.alert').addClass('in');
                ;
            }
        }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            });
    });

    $('.modal-footer > .btn-success').click(function (e) {
        e.preventDefault();
        id = $(this).data('id');

        $.post($(this).data('url'), {action:'complete', csrf:$(this).data('csrf')}, function (data) {
            if (data.status == 'error') {
                $.each(data.errors, function (k, v) {
                    $('#notifications').append('<div class="alert alert-error fade"> <button type="button" class="close" data-dismiss="alert">&times;</button>' + v + '</div>');
                });

                $('#notifications').find('.alert').addClass('in');
            }
            else {
                if (typeof data.result == 'string')
                    data.result = [data.result];

                if (data.item == 0) {
                    var a = $('a[data-recipe-id="' + id + '"]');
                    a.parent('li').css('background-color', '#fcf8e3');
                    a.attr('href', '').addClass('disabled'),
                }

                $.each(data.result, function (k, v) {
                    $('#notifications').append('<div class="alert alert-success fade"><button type="button" class="close" data-dismiss="alert">&times;</button>' + v + '</div>');
                });

                $('#notifications').find('.alert').addClass('in');
            }

            $('.modal').modal('hide');
        });
    });
});