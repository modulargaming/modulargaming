/**
 * Admin CRUD plugin.
 */

(function ($) {
    var form = {};
    opts = {};
    var tableEl = null;
    var csrf = null;

    var methods = {
        init:function (options) {
            opts = $.extend({}, $.fn.CRUD.defaults, options);

            //get the table containing all the data
            tableEl = $(this);
            csrf = $('span#csrf').text();

            //Basic dataTable config
            var dataTable = {
                "bProcessing":true,
                "bServerSide":true,
                "sAjaxSource":opts.base_url + "paginate",
                "sDom":"<'row-fluid'<'span4 offset1'l><'span4 pull-right'f>r><'row'<'span" + opts.table.span + " offset" + opts.table.offset + "'t>><'row-fluid'<'span6'i><'span6'p>>",
                "aoColumnDefs":[
                    {
                        "bSortable":false,
                        "aTargets":[ -1 ],
                        "mRender":function (data, type, full) {
                            //check if any extra option buttons were defined
                            var buttons = ' ';

                            if (opts.buttons !== null) {
                                buttons = opts.buttons.call(this, data, type, full);
                            }

                            return '<button data-id="' + data + '" class="btn btn-warning btn-edit">Edit</button>' + buttons + '<button data-id="' + data + '" class="btn btn-danger btn-delete">Delete</button>';
                        }
                    }
                ]
            };

            //merge with provided config
            if (typeof opts.dataTable.aoColumnDefs != 'undefined') {
                opts.dataTable.aoColumnDefs = $.merge(dataTable.aoColumnDefs, opts.dataTable.aoColumnDefs);
            }
            dataTable = $.extend(true, {}, dataTable, opts.dataTable);

            //initialise dataTable
            opts.dT = tableEl.dataTable(dataTable);

            //get a map of the form fields
            form = $('#modal-crud-form');

            //prove the delete modal with the right button text
            $('#modal-delete-type').text($($('.btn-create')[0]).text().replace('Create ', ''));

            //activate erro menu
            $('#modal-crud-error-list').on('click', 'li > a', function (e) {
                e.preventDefault();
            });

            //bind events to create buttons
            $('.btn-create').click(function (e) {
                e.preventDefault();
                $('#modal-crud').data('row-id', 'false');
                methods.show.apply(form, [0]);
            });

            //bind events to edit and delete buttons
            tableEl.on('click', '.btn-edit', function (e) {
                e.preventDefault();
                var id = $(this).data('id');
                var rowIndex = opts.dT.fnGetPosition($(this).closest('tr')[0]);
                $(this).text('loading');
                $('#modal-crud').data('row-id', rowIndex);
                methods.show.apply(form, [id, {id:id}, $(this)]);
            });

            tableEl.on('click', '.btn-delete', function (e) {
                e.preventDefault();
                var id = $(this).data('id');
                var name = $(this).parents('tr').find('td:nth-child(' + opts.identifier.table + ')').text();
                var rowIndex = opts.dT.fnGetPosition($(this).closest('tr')[0]);
                $('#modal-crud').data('row-id', rowIndex);
                methods.showDelete.apply(form, [id, name]);
            });

            return this;
        },
        show:function (id, param, element) {
            //reset form
            $('#modal-crud-form')[0].reset();

            //reset errors
            $('.control-group').each(function () {
                $(this).removeClass('error');
            });

            //hide progressbar
            if (opts.upload != false) {
                $('#modal-footer-upload-progress').width('1').parent().addClass('hide');
                $('#modal-footer-upload-progress').html('');
            }

            // clean callback
            tableEl.trigger('crud.clean');

            //hide options button/menu
            $('.modal-options').addClass('hide');

            //if no id is specified we're creating
            if (id == 0 && typeof param === 'undefined') {
                $('h3#modal-header').html('Creating');
                $('#input-id').val('0');

                //Bind save functionality
                methods.bindSave.call(this, 'Create');

                $('#modal-crud').modal();
            }
            else {
                var req_data = opts.base_url + 'retrieve';

                $.get(req_data, param, function (data) {
                    //Bind save functionality
                    methods.bindSave.call(this, 'Edit');

                    if (typeof element != 'undefined') {
                        element.text('Edit');
                    }
                    $('h3#modal-header').html('Editing "' + data[opts.identifier.data] + '"');
                    $('.modal-options').removeClass('hide');

                    //register option buttons
                    tableEl.trigger('crud.options', [data.id, data]);

                    $('#option-delete').click(function (e) {
                        e.preventDefault();
                        $('#modal-crud').modal('hide');

                        $('#modal-delete').one('hidden', function () {
                            $('#modal-crud').modal('show');
                        });
                        methods.showDelete.call(form, data.id, data[opts.identifier.data]);
                    });

                    //set the field values
                    $.each(data, function (key, val) {
                        $('#input-' + key).val(val);
                    });

                    //the form ahs been loaded
                    tableEl.trigger('crud.load', [data]);

                    //show the modal
                    $('#modal-crud').modal();
                });
            }
        },
        save:function () {
            tableEl.trigger('crud.preSave');
            $('#modal-crud-form input[name="csrf"]').val(csrf);

            var values = $('#modal-crud-form').serialize();
            var id = $('#input-id').val();

            var ajax_options = {
                type:"POST",
                url:opts.base_url + 'save',
                data:values,
                success:methods.handleSave
            };

            if (opts.upload != false && $('#modal-crud-form').find('input[type="file"]').val() != "") {
                ajax_options.xhr = function () {  // custom xhr
                    myXhr = $.ajaxSettings.xhr();
                    if (myXhr.upload) { // check if upload property exists
                        myXhr.upload.addEventListener('progress', methods.handleProgress, false); // for handling the progress of the upload
                    }
                    return myXhr;
                };
                ajax_options.cache = false;
                ajax_options.contentType = false;
                ajax_options.processData = false;
                ajax_options.data = new FormData($('#modal-crud-form')[0]);

                $('#modal-footer-upload-progress').parent().removeClass('hide');

            }
            $.ajax(ajax_options);

        },
        showDelete:function (id, name) {
            $('#modal-delete-name').text(name);
            $('#btn-remove').prop('disabled', false);
            $('#modal-delete').modal();

            //bind the delete button
            $('#btn-remove').one('click', function (e) {
                e.preventDefault();
                $(this).prop('disabled', true);
                methods.doDelete.apply(this, [id, name]);
            });
        },
        doDelete:function (id, name) {
            $.post(opts.base_url + 'remove', {id:id, csrf:$('span#csrf').text()}, function (data) {
                if (data.action == 'deleted') {
                    //remove the row from the table
                    var row_id = $('#modal-crud').data('row-id');
                    opts.dT.fnDeleteRow(row_id, null, true);

                    $('#modal-delete').off('hidden');
                    $('#modal-delete').modal('hide');

                    $('#crud-notify').notify({
                        message:{ text:name + ' has been deleted successfully!' },
                        type:'warning'
                    }).show();
                }
                else {
                    //error deleting
                    $('#crud-notify').notify({
                        type:'danger',
                        message:{ text:name + ' could not be deleted!' },
                        fadeOut:{ enabled:false}
                    }).show();
                    $('#modal-delete').modal('hide');
                }
            });
        },
        handleProgress:function (e) {
            if (e.lengthComputable) {
                var percentage = Math.round((e.loaded * 100) / e.total);
                $('#modal-footer-upload-progress').css('width', percentage + '%');

                if (percentage == 100) {
                    $('#modal-footer-upload-progress').html('<span class="pagination-centered" style="color: #ffffff;">Processing form</span>')
                }
            }
        },
        handleSave:function (data) {
            if (data.action == 'saved') {
                var result = (data.type == 'new') ? 'created' : 'updated';

                if (opts.upload == true) {
                    if (data.file.status == 'error') {
                        var type = 'error';
                        var fadeOut = {enabled:false};
                    }
                    else if (data.file.status == 'success') {
                        var type = 'success';
                        var fadeOut = {enabled:true, delay:3000};
                    }

                    if (data.file.status != 'empty') {
                        $('#crud-notify').notify({
                            type:type,
                            message:{ text:data.file.msg },
                            fadeOut:fadeOut
                        }).show();
                    }
                }
                $('#crud-notify').notify({
                    message:{ text:$('#input-' + opts.identifier.data).val() + ' has been ' + result + ' successfully!' }
                }).show();

                tableEl.trigger('crud.save', [data.row, data]);

                $('#modal-crud').modal('hide');

                //update the item list table
                if (result == 'updated') {
                    opts.dT.fnUpdate(data.row, $('#modal-crud').data('row-id'));
                }
                else {
                    //we're dealing with a new table row
                    opts.dT.fnAddData(data.row, true);
                }
            }
            else {
                //rebind the save button
                methods.bindSave.call(this);
                tableEl.trigger('crud.error');
                //mark the errors on the form
	            if(data.errors.length > 0) {
		            $.each(data.errors, function (key, val) {
			            $('#input-' + val.field).parents('.control-group').addClass('error');
			            $('#modal-crud-error-list').append('<li><a href="#">' + val.msg.join('<br />') + '</a></li>');
		            });
		            $('#modal-crud-errors').removeClass('hide');
	            }
            }
        },
        bindSave:function (type) {
            $('#modal-crud-save').unbind('click');
            $('#modal-crud-save').text(type);
            $('#modal-crud-save').prop('disabled', false);
            //bind the save button
            $('#modal-crud-save').one('click', function (e) {
                e.preventDefault();
                $('#modal-crud-save').text('Saving');
                $('#modal-crud-save').prop('disabled', true);

                //reset errors
                $('#modal-crud-errors').addClass('hide');
                $('#modal-crud-error-list > li').remove();

                methods.save.apply(this);
            });
        },
        bindTblEdit:function (element, id) {
            element.click(function (e) {
                e.preventDefault();
                var rowIndex = opts.dT.fnGetPosition(element.closest('tr')[0]);

                element.text('loading');
                methods.show.apply(form, [id, {id:id}, element, rowIndex]);
            });
        },
        bindTblDelete:function (element) {
            $(element).click(function (e) {
                e.preventDefault();
                var id = element.data('id');
                var name = element.parents('tr').find('td:nth-child(' + opts.identifier.table + ')').text();
                var rowIndex = opts.dT.fnGetPosition(element.closest('tr')[0]);

                methods.showDelete.apply(form, [id, name, rowIndex]);
            });
        },
    };

    $.fn.CRUD = function (method) {

        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.CRUD');
        }
    };

    $.fn.CRUD.defaults = {
        base_url:'',
        identifier:{data:'name', table:1}, //the properties used as an identifier
        upload:false,
        table:{
            span:4,
            offset:4
        },
        dataTable:{
            "aoColumnDefs":[]
        },
        buttons:null,
        dT:null
    };

})(jQuery);