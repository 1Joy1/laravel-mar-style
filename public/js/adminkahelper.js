$( document ).ready(function(){

    $.ajaxSetup({
        headers: {
          'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
    });



    $("html").trigger('reset');


// Start Modal File Form

    // We can attach the `fileselect` event to all file inputs on the page
    // Мы можем присоединить `fileselect` событие  ко всем input файлам на странице

    $(document).on('change', ':file', function() {
        var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });



    // We can watch for our custom `fileselect` event like this
    // Мы можем наблюдать за нашим пользовательским событием `fileselect`, таким образом.

    $(':file').on('fileselect', function(event, numFiles, label) {
        var input = $(this).parents('.input-group').find(':text');
            input.val( numFiles > 1 ? 'Выбрано ' + numFiles + ' файла(ов).' : label).tooltip('hide');

    });



    $('input[data-toggle="tooltip"]').tooltip({title: "Перед отправкой нужно выбрать файлы", trigger: "manual"});




    $('#fileform').on('submit', function(event) {
        event.preventDefault();

        var form = $(this),
            formData = new FormData(this),
            url = $(this).attr('action');

            /*if (!formData.get('file') && !formData.getAll('file[]')[0]) {
                $('input[data-toggle="tooltip"]').tooltip('show');
                return;
            }*/


        $('#pgb').parent('div.progress').removeClass('hidden');

        $.ajax({
            url: url,
            type: "post",
            processData: false,
            contentType: false,
            data:  formData,

            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                //Upload progress
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total * 100;
                        //Do something with upload progress
                        $('#pgb').css('width', percentComplete + "%");
                    }
                }, false);
                return xhr;
            },

            success: function(data) {
                $('#groupName_' + data['name']).find('img').attr("src", data['photo_src']);

                setTimeout(function(){
                    $('#load_file_modal').modal('hide');
                }, 800);
                //NotifySuccess("Файл загружен успешно");
            },

            error: function(data) {
                if (data['responseJSON']['message']) {
                    var message = data['responseJSON']['message'];
                    NotifyAlert( Array.isArray(message) ? message.join('<br>') : message);
                } else {
                    NotifyAlert("Не известная ошибка<br> StatusCode: " + data['status']);
                };
                $('#pgb').css('width', '0%').parent('div.progress').addClass('hidden');
            },

        });
    })



    $('#load_file_modal').on('show.bs.modal', function (event) {
        if($(event.relatedTarget).attr('data-sourse')) {
            $('#fileform').attr('action', 'group/' + $(event.relatedTarget).attr('data-sourse'));
        } else {
            $('#fileform').attr('action', 'photo');
        }
    }).on('hidden.bs.modal', function(event) {
        $('#fileform').attr('action', '').trigger('reset');
        $('#pgb').css('width', '0%').parent('div.progress').addClass('hidden');
        $('input[data-toggle="tooltip"]').tooltip('hide');
    });



    $('#clear_file_form, #close_file_form').click(function() {
        $('#fileform').trigger('reset');
    });
// End Modal File Form



    // Start Photo page
    if (window.location.pathname === '/admin/photo') {

        $('button.deactive-gallery').click(function() {
            var thisButton = $(this);
            var item = thisButton.parents(".itemblock");
            var id = Number(item[0].id.split('_')[1]);

            $.ajax({
                url: 'photo/' + id,
                type: 'put',
                data: { active: 0 },

                success: function(data) {
                    if (data['active'] == 0) {
                        $(item).find('.photoblock').removeClass("enabled").addClass("disabled");
                        $(item).find('.checkblock').removeClass("enabled").addClass("disabled");
                        $(item).find('.checkblock input').attr('disabled', true);
                        $(item).find('.active-gallery').removeClass("disabled").addClass("enabled");
                        $(thisButton).removeClass("enabled").addClass("disabled");
                    }
                },

                error: function(data) {
                    if (data['responseJSON']['message']) {
                        var message = data['responseJSON']['message'];
                        NotifyAlert( Array.isArray(message) ? message.join('<br>') : message);
                    } else {
                        NotifyAlert("Не известная ошибка<br> StatusCode: " + data['status']);
                    };
                }
            });
        });



        $('button.active-gallery').click(function() {
            var thisButton = $(this);
            var item = thisButton.parents(".itemblock");
            var id = Number(item[0].id.split('_')[1]);

            $.ajax({
                url: 'photo/' + id,
                type: 'put',
                data: { active: 1 },

                success: function(data) {
                    if (data['active'] == 1) {
                        $(item).find('.photoblock').removeClass("disabled").addClass("enabled");
                        $(item).find('.checkblock').removeClass("disabled").addClass("enabled");
                        $(item).find('.checkblock input').attr('disabled', false);
                        $(item).find('.deactive-gallery').removeClass("disabled").addClass("enabled");
                        $(thisButton).removeClass("enabled").addClass("disabled");
                    }
                },

                error: function(data) {
                    if (data['responseJSON']['message']) {
                        var message = data['responseJSON']['message'];
                        NotifyAlert( Array.isArray(message) ? message.join('<br>') : message);
                    } else {
                        NotifyAlert("Не известная ошибка<br> StatusCode: " + data['status']);
                    };
                }
            });
        });



        $('.category-checker').change(function(event) {
                var thisCheckbox = $(this)
                var item = thisCheckbox.parents(".itemblock");
                var id = Number(item[0].id.split('_')[1]);
                var chkVal = thisCheckbox.attr("value");

                if (thisCheckbox.is(':checked')) {
                    var url = 'photo/' + id + '/attach/group';
                    var checkProp = false;
                } else {
                    var url = 'photo/' + id + '/detach/group';
                    var checkProp = true;
                }

                $.ajax({
                    url: url,
                    type: 'put',
                    data: { group_name: chkVal },

                    error: function(data) {
                        if (data['responseJSON']['message']) {
                            var message = data['responseJSON']['message'];
                            NotifyAlert( Array.isArray(message) ? message.join('<br>') : message);
                        } else {
                            NotifyAlert("Не известная ошибка<br> StatusCode: " + data['status']);
                        };

                        thisCheckbox.prop('checked', checkProp);
                    }
                });

        });



        $('.delete-of-storage').click(function() {
            if (confirm("Вы уврены, что хотите удалить этот файл? \n"+
                         "После этой опперации востановление будет невозможно!")) {
                var thisButton = $(this);
                var item = thisButton.parents(".itemblock");
                var id = Number(item[0].id.split('_')[1]);
                var url = 'photo/' + id;

                $.ajax({
                    url: url,
                    type: 'delete',

                    success: function() {
                        item.parents(".list-group-item").slideUp(600, function() {
                            this.remove();
                        });
                    },

                    error: function(data) {
                        if (data['responseJSON']['message']) {
                            var message = data['responseJSON']['message'];
                            NotifyAlert( Array.isArray(message) ? message.join('<br>') : message);
                        } else {
                            NotifyAlert("Не известная ошибка<br> StatusCode: " + data['status']);
                        };
                    }
                });
            }
        });
    }
    // End Photo page



// Start Message page
    if (window.location.pathname === '/admin/message') {

        $('.delete-message').click(function() {
            if (confirm("Вы уврены, что хотите удалить это сообщение? \n"+
                         "После этой опперации востановление будет невозможно!")) {
                var thisButton = $(this);
                var item = thisButton.parents(".itemblock");
                var id = Number(item[0].id.split('_')[1]);
                var url = 'message/' + id;

                $.ajax({
                    url: url,
                    type: 'delete',

                    success: function() {
                        item.parents(".list-group-item").slideUp(600, function() {
                            this.remove();
                        });
                    },

                    error: function(data) {
                        if (data['responseJSON']['message']) {
                            var message = data['responseJSON']['message'];
                            NotifyAlert( Array.isArray(message) ? message.join('<br>') : message);
                        } else {
                            NotifyAlert("Не известная ошибка<br> StatusCode: " + data['status']);
                        };
                    }
                });
            }
        });


        $('.edit-message').click(function() {
            var thisButton = $(this),
                sendButton = thisButton.parents("div:eq(0)").find(".edit-message-send"),
                cancelButton = thisButton.parents("div:eq(0)").find(".edit-message-cancel"),
                item = thisButton.parents(".itemblock"),
                author_name_block = $(item).find("b.author-name"),
                textarea = $(item).find("textarea");

            thisButton.parent().addClass('hidden');
            sendButton.parent().removeClass('hidden');
            cancelButton.parent().removeClass('hidden');

            author_name_block.addClass('hidden');
            textarea.prop('disabled', false).data('old_text', textarea.val());
            $(item).find("input.author-name").attr('type', 'text').val(author_name_block.text());
        });



        $('form.edit-mess').on('submit', function(event) {
            event.preventDefault();
            var form = $(this),
                url = this.action,
                data = form.serializeArray(),

                sendButton = $(form).find(".edit-message-send"),
                cancelButton = $(form).find(".edit-message-cancel"),
                editButton = $(form).find(".edit-message"),
                author_name_block = $(form).find("b.author-name"),
                input_name = $(form).find("input.author-name"),
                textarea = $(form).find("textarea");


            $.ajax({
                url: url,
                type: 'put',
                data: data,

                success: function(data) {
                    textarea.val(data['text']).prop('disabled', true).data('old_text', '');
                    author_name_block.text(data['name']).removeClass('hidden');

                    sendButton.parent().addClass('hidden');
                    editButton.parent().removeClass('hidden');
                    cancelButton.parent().addClass('hidden');

                    input_name.attr('type', 'hidden').val('');

                    NotifySuccess("Сообщение отредактировано успешно.");
                },

                error: function(data) {
                    if (data['responseJSON']['message']) {
                        var message = data['responseJSON']['message'];
                        NotifyAlert( Array.isArray(message) ? message.join('<br>') : message);
                    } else {
                        NotifyAlert("Не известная ошибка<br> StatusCode: " + data['status']);
                    };
                }
            });
        });



        $('.edit-message-cancel').click(function() {
            var thisButton = $(this),
                sendButton = thisButton.parents("div:eq(0)").find(".edit-message-send"),
                editButton = thisButton.parents("div:eq(0)").find(".edit-message"),
                item = thisButton.parents(".itemblock"),
                author_name_block = $(item).find("b.author-name"),
                textarea = $(item).find("textarea");

            thisButton.parent().addClass('hidden');
            sendButton.parent().addClass('hidden');
            editButton.parent().removeClass('hidden');

            author_name_block.removeClass('hidden');
            textarea.prop('disabled', true).val(textarea.data('old_text'));
            $(item).find("input.author-name").attr('type', 'hidden').val('');

        });
    }
// End Massage page



// Start Group page
    $('#change_name_group_modal').on('show.bs.modal', function (event) {
        if($(event.relatedTarget).attr('data-sourse')) {
            var groupId = $(event.relatedTarget).attr('data-sourse'),
                curr_name_group = $('#groupName_' + groupId + ' .group-name').text();

            $('#title_change_name_group_modal')
                    .html("Текущее имя группы <b> \"" + curr_name_group + "\"</b>");
            $('#change_name_group_form').attr('action', 'group/' + groupId);
        }

    }).on('hidden.bs.modal', function(event) {
        $('#title_change_name_group_modal').html("");
        $('#change_name_group_form').attr('action', '').trigger('reset');
    });


    $('#change_name_group_form').on('submit', function(event) {
        event.preventDefault();
        var form = $(this);
        var url = this.action;
        var data = form.serializeArray();

        $.ajax({
            url: url,
            type: 'put',
            data: data,

            success: function(data) {
                $('#groupName_' + data['name'] + ' .group-name').text(data['display_name']);
                $('#change_name_group_modal').modal('hide');
            },

            error: function(data) {
                if (data['responseJSON']['message']) {
                    var message = data['responseJSON']['message'];
                    NotifyAlert( Array.isArray(message) ? message.join('<br>') : message);
                } else {
                    NotifyAlert("Не известная ошибка<br> StatusCode: " + data['status']);
                };
            }
        });
    });
// End Group page


    function NotifyAlert(text) {
        var alertBlock = $("<div class='alert alert-danger alert-dismissible fade in' role='alert'>" +
                            "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>" +
                            "<span aria-hidden='true'>×</span></button>" +
                            "<h4>Упcс!!! Что-то пошло не так!!!</h4>" +
                            "<h5>Произошла ошибка. </h5><br>" + text + "</div>"
                            );

        $("#notifies_alert").hide();
        alertBlock.appendTo($("#notifies_alert"));
        $("#notifies_alert").slideDown(600);
    }

    function NotifySuccess(text) {
        var alertBlock = $("<div class='alert alert-success alert-dismissible fade in' role='alert'>" +
                            "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>" +
                            "<span aria-hidden='true'>×</span></button>" +
                            "<h4>Отлично!!!</h4>" +
                            "<br>" + text + "</div>"
                            );

        $("#notifies_alert").hide();
        alertBlock.appendTo($("#notifies_alert"));
        $("#notifies_alert").slideDown(600);
    }

});

    // function UploadFile() {
    //     var formData = new FormData($('#fileform')[0]);
    //     $("#loader").css('display', 'block');
    //     var itemblock_replace = $('#answerfile').find('.itemblock').remove();
    //     $('.itemblock:last').after($(itemblock_replace));
    //     $.ajax({
    //           type: "POST",
    //           processData: false,
    //           contentType: false,
    //           url: "uploadfile.php",
    //           data:  formData
    //           })
    //           .done(function( data ) {
    //             if (data.search('<!DOCTYPE html PUBLIC') !== -1) {
    //                 $('html').html(data);
    //             };
    //             $('#answerfile').html(data);
    //             $("#loader").css('display', 'none');
    //             var h = $("html");
    //             var p = h.height();
    //             h.animate({ scrollTop: p}, 5000);
    //     });
    // }
