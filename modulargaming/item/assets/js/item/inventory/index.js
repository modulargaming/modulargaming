/**
 * Modular Gaming Inventory UI handler.
 */

$(document).ready(function () {
    //delegate a click event to the table containing actions
    $('#item-actions').on('click', 'li[class!="dropdown"] > a', function (e) {
        e.preventDefault();

        $.post($(this).parent('li').data('follow'), {action:$(this).parent('li').data('action'), csrf:$('#csrf').text()}, function (data) {
            handle_consume(data);
        });

    });

    $('#item-actions').on('click', '.form-inline > input[type="submit"]', function (e) {
        e.preventDefault();

        var param = $(this).parent('form').serialize() + '&action=' + $(this).parent('form').data('action') + '&csrf=' + $('#csrf').text();

        $.post($(this).parent('form').data('follow'), param, function (data) {
            handle_consume(data);
        });
    });

    $('#item-action-close').click(function (e) {
        e.preventDefault();
        $('#item-consume').slideUp('slow');
        //reset item
        var el = $('.thumbnails > li > a[data-item-id="' + $('#item-actions').data('active-item') + '"]');
        reset_item(el);
    });

    $('ul.thumbnails > li > a').click(function (e) {
        e.preventDefault();

        var url = this.href;
        var id = $(this).data('item-id');

        //reset previous selected item
        if ($('#item-actions').data('active-item') != '') {
            //hide if visible
            if ($('#item-consume').is(':visible'))
                $('#item-consume').slideUp('slow');

            //reset list
            $('#item-actions').find('li').remove();

            //reset item
            var el = $('.thumbnails > li > a[data-item-id="' + $('#item-actions').data('active-item') + '"]');
            reset_item(el);
        }

        //set the current selected item
        $('#item-actions').data('active-item', id);
        $(this).css('border-color', '#468847');

        //do request
        $.getJSON(url,function (data) {
            //handle result
            if (data.status == 'success') {
                var url_consume = url.replace('view', 'consume');

                $.each(data.actions, function (key, val) {
                    if (val.extra == null) {
                        var entry = '<li data-action="' + key + '" data-follow="' + url_consume + '"><a href="#">' + val.item + '</a></li>';
                    }
                    else {
                        if (typeof val.extra.field.classes !== 'undefined') {
                            var classes = val.extra.field.classes;
                        }
                        else
                            var classes = '';

                        var entry = $('<li class="dropdown">'
                            + '<a class="dropdown-toggle" data-toggle="dropdown" href="#">' + val.item + ' <span class="caret pull-right"></span></a>'
                            + '<ul class="dropdown-menu" role="menu" style="width: 178px;">'
                            + '<li><form class="form-inline" data-action="' + key + '" data-follow="' + url_consume + '">'
                            + '<input style="margin-left: 10px;" type="' + val.extra.field.type + '" name="' + val.extra.field.name + '" class="' + classes + '" /> '
                            + '<input type="submit" class="btn btn-primary" value="' + val.extra.field.button + '" />'
                            + '</form>'
                            + '</li></ul>'
                            + '</li>');

                        if (classes.indexOf('search') > -1) {
                            entry.find('form > input:first').typeahead({
                                minLength:4,
                                source:function (query, process) {
                                    return $.get('./search', { type:'user', item:query }, function (data) {
                                        return process(data);
                                    });
                                }
                            });
                        }
                    }
                    $('#item-actions').append(entry);
                });
                $('#item-consume > h5').text("'" + data.name + "'");
                $('#item-consume').slideDown('slow');
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
});

function reset_item(item) {
    item.css('border-color', '#ddd');
    item.hover(function () {
        $(this).css('border-color', '#0088cc');
    }, function () {
        $(this).css('border-color', '#ddd');
    });
}

function handle_consume(data) {
    var id = $('#item-actions').data('active-item');
    var item = $('.thumbnails > li > a[data-item-id="' + id + '"]');

    if (data.status == 'success') {

        if (typeof data.result == 'string')
            data.result = [data.result];

        $.each(data.result, function (k, v) {
            $('#notifications').append('<div class="alert alert-success fade"><button type="button" class="close" data-dismiss="alert">&times;</button>' + v + '</div>');
        });
        $('#notifications').find('.alert').addClass('in');

        if (data.new_amount == '0') {
            item.removeAttr('href');
            item.parent().fadeOut();
        }
        else {
            item.find('span').text(data.new_amount);
        }

        reset_item(item);
        $('#item-consume').slideUp('slow');
    }
    else {
        $.each(data.errors, function (k, v) {
            $('#notifications').append('<div class="alert alert-error fade"> <button type="button" class="close" data-dismiss="alert">&times;</button>' + v.text + '</div>');
        });

        $('#notifications').find('.alert').addClass('in');
        item.css('border-color', '#953b39');
    }
}