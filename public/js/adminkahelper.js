$( document ).ready(function(){
    // Add padding-right for the navigation bar when opening the modal window to compensate for the disappearing scrolbar.
    // реализация через изменение прототипа объекта модал в bootstrap.js
    $.fn.modal.Constructor.prototype.$nav = $('nav');
    $.fn.modal.Constructor.prototype.originalNavPad = null;
    $.fn.modal.Constructor.prototype.$notifies = $('#notifies_alert');
    $.fn.modal.Constructor.prototype.originalNotifiesPad = null;

    $.fn.modal.Constructor.prototype.setScrollbar = function () {
        let bodyPad = parseInt((this.$body.css('padding-right') || 0), 10),
            navPad = parseInt((this.$nav.css('padding-right') || 0), 10),
            notifesPad = parseInt((this.$notifies.css('padding-right') || 0), 10);
        this.originalBodyPad = document.body.style.paddingRight || '';
        this.originalNavPad = $('nav').css('padding-right') || '';
        this.originalNotifiesPad = $('#notifies_alert').css('padding-right') || '';
        if (this.bodyIsOverflowing) {
            this.$body.css('padding-right', bodyPad + this.scrollbarWidth);
            this.$nav.css('padding-right', navPad + this.scrollbarWidth);
            this.$notifies.css('padding-right', notifesPad + this.scrollbarWidth);
        }
    };
    $.fn.modal.Constructor.prototype.resetScrollbar = function () {
        this.$body.css('padding-right', this.originalBodyPad);
        this.$nav.css('padding-right', this.originalNavPad);
        this.$notifies.css('padding-right', this.originalNotifiesPad);
    };
    /////////////////////////////////////////////////////////////////////////////////




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
        let $input = $(this),
            numFiles = $input.get(0).files ? $input.get(0).files.length : 1,
            label = $input.val().replace(/\\/g, '/').replace(/.*\//, '');

        $input.trigger('fileselect', [numFiles, label]);
    });



    // We can watch for our custom `fileselect` event like this
    // Мы можем наблюдать за нашим пользовательским событием `fileselect`, таким образом.

    $(':file').on('fileselect', function(event, numFiles, label) {
        let $input = $(this).parents('.input-group').find(':text');

        $input.val( numFiles > 1 ? 'Выбрано ' + numFiles + ' файла(ов).' : label).tooltip('hide');
        $('#pgb').parent('div.progress').addClass('hidden');

        let files = event.target.files;
        validateSizeFiles(files);
    });




    $('input[data-toggle="tooltip"]').tooltip({title: "Перед отправкой нужно выбрать файлы", trigger: "manual"});




    $('#fileform').on('submit', function(event) {
        event.preventDefault();

        let formData = new FormData(this),
            url = $(this).attr('action'),
            buttons = $('#load_file_modal').find('.modal-body').find('.btn, input'),
            progress_bar = $('#pgb');

            if (!formData.get('file') && !formData.getAll('file[]')[0]) {
                $('input[data-toggle="tooltip"]').tooltip('show');
                return;
            }


        progress_bar.css('width', '0%').parent('div.progress').removeClass('hidden');
        //progress_bar.outerHeight();
        progress_bar.removeClass('not-transition-width progress-bar-danger');

        buttons.attr('disabled', true);

        $.ajax({
            url: url,
            type: "post",
            processData: false,
            contentType: false,
            dataType: "json",
            data:  formData,

            xhr: function() {
                let xhr = new window.XMLHttpRequest();
                //Upload progress
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        let percentComplete = Math.round(evt.loaded / evt.total * 100);
                        //Do something with upload progress
                        progress_bar.text(percentComplete + "%").css('width', percentComplete + "%");

                        if (percentComplete === 100) {

                            setTimeout(function(){
                                if (progress_bar.text() === "100%") {
                                    progress_bar.text("Cервер обрабатывает информацию...").addClass('progress-blink');
                                }
                            }, 1000);

                        }
                    }
                }, false);
                return xhr;
            },

            success: function(data) {

                buttons.attr('disabled', false);
                progress_bar.removeClass('progress-blink');

                if (window.location.pathname === '/admin/group') {

                    $('#groupName_' + data['name']).find('img').attr("src", data['photo_src']);
                    NotifyAlert("Отлично!!!", "Файл загружен успешно", "success");

                    setTimeout(function(){
                        $('#load_file_modal').modal('hide');
                    }, 800);
                }

                if (window.location.pathname === '/admin/photo') {

                    if (data['error'].length > 0) {
                        $.each(data['error'], function(i, error) {
                             NotifyAlert("Произошла ошибка." , Array.isArray(error) ? error.join('<br>') : error, "error");
                        });
                        progress_bar.addClass('progress-bar-danger not-transition-width').text("!!! Не все файлы сохранены на сервере !!!");
                    } else {
                        setTimeout(function(){
                            $('#load_file_modal').modal('hide');
                        }, 800);
                    }

                    if (data['uploaded'].length > 0) {
                        let ids = [];
                        $.each(data['uploaded'], function(i, item) {
                            ids.push(item['id']);
                        });

                        getNewUploadedPhoto(ids);
                    }
                }
            },

            error: function(data) {

                buttons.attr('disabled', false);

                if (data['responseJSON'] && data['responseJSON']['message']) {
                    let message = data['responseJSON']['message'];
                    NotifyAlert("Произошла ошибка.", Array.isArray(message) ? message.join('<br>') : message, "error");
                } else {
                    NotifyAlert("Произошла ошибка.", "Не корректный ответ сервера.<br> StatusCode: " + data['status'], "error");
                }
                progress_bar.removeClass('progress-blink').css('width', '0%').parent('div.progress').addClass('hidden');
            },

        });
    });



    $('#load_file_modal').on('show.bs.modal', function (event) {

        $('#pgb').css('width', '0%').parent('div.progress').addClass('hidden');
        $('#load_file_modal').find('.modal-body').find('.btn, input').attr('disabled', false);

        if($(event.relatedTarget).attr('data-sourse')) {

            $('#fileform').attr('action', 'group/' + $(event.relatedTarget).attr('data-sourse'));
        } else {

            $('#fileform').attr('action', 'photo');
        }
    }).on('hidden.bs.modal', function() {

        $('#fileform').attr('action', '').trigger('reset');
        $('#pgb').css('width', '0%').parent('div.progress').addClass('hidden');
        $('input[data-toggle="tooltip"]').tooltip('hide');
    });



    $('#clear_file_form').click(function() {
        $('#fileform').trigger('reset');
        $('#pgb').parent('div.progress').addClass('hidden');
    });
// End Modal File Form



// Start Photo page
    if (window.location.pathname === '/admin/photo') {

        $('.list-group').on('click', '.deactive-gallery, .active-gallery ', function() {
            let $thisButton = $(this),
                item = $thisButton.parents(".itemblock"),
                id = Number(item[0].id.split('_')[1]),
                active = $thisButton.hasClass('deactive-gallery') ? 0 : 1;

            $.ajax({
                url: 'photo/' + id,
                type: 'put',
                dataType: "json",
                data: { active: active },

                success: function(data) {
                    if (data['active'] === '0') {
                        $(item).find('.photoblock, .checkblock').removeClass("enabled").addClass("disabled");
                        $(item).find('.checkblock input').attr('disabled', true);
                        $(item).find('.active-gallery').attr('disabled', false);
                        $thisButton.attr('disabled', true);
                    } else if (data['active'] === '1') {
                        $(item).find('.photoblock, .checkblock').removeClass("disabled").addClass("enabled");
                        $(item).find('.checkblock input, .deactive-gallery').attr('disabled', false);
                        $thisButton.attr('disabled', true);
                    } else {
                        NotifyAlert("Произошла ошибка.", "Не корректный ответ сервера.", "error");
                    }
                },

                error: function(data) {
                    if (data['responseJSON'] && data['responseJSON']['message']) {
                        let message = data['responseJSON']['message'];
                        NotifyAlert("Произошла ошибка.", Array.isArray(message) ? message.join('<br>') : message, "error");
                    } else {
                        NotifyAlert("Произошла ошибка.", "Не корректный ответ сервера.<br> StatusCode: " + data['status'], "error");
                    }
                }
            });

        }).on('change', '.category-checker', function() {
            let $thisCheckbox = $(this),
                $item = $thisCheckbox.parents(".itemblock"),
                id = Number($item[0].id.split('_')[1]),
                chkVal = $thisCheckbox.attr("value"),
                url, checkProp;

            if ($thisCheckbox.is(':checked')) {
                url = 'photo/' + id + '/attach/group';
                checkProp = false;
            } else {
                url = 'photo/' + id + '/detach/group';
                checkProp = true;
            }

            $.ajax({
                url: url,
                type: 'put',
                dataType: "json",
                data: { group_name: chkVal },

                error: function(data) {
                    if (data['responseJSON'] && data['responseJSON']['message']) {
                        let message = data['responseJSON']['message'];
                        NotifyAlert("Произошла ошибка.", Array.isArray(message) ? message.join('<br>') : message, "error");
                    } else {
                        NotifyAlert("Произошла ошибка.", "Не корректный ответ сервера.<br> StatusCode: " + data['status'], "error");
                    }

                    $thisCheckbox.prop('checked', checkProp);
                }
            });

        }).on('click', '.delete-of-storage', function() {
            if (confirm("Вы уврены, что хотите удалить этот файл? \n"+
                         "После этой опперации востановление будет невозможно!")) {
                let $thisButton = $(this),
                    $item = $thisButton.parents(".itemblock"),
                    id = Number($item[0].id.split('_')[1]),
                    url = 'photo/' + id;

                $.ajax({
                    url: url,
                    dataType: "json",
                    type: 'delete',

                    success: function() {
                        $item.parents(".list-group-item").slideUp(600, function() {
                            this.remove();
                        });
                    },

                    error: function(data) {
                        if (data['responseJSON'] && data['responseJSON']['message']) {
                            let message = data['responseJSON']['message'];
                            NotifyAlert("Произошла ошибка.", Array.isArray(message) ? message.join('<br>') : message, "error");
                        } else {
                            NotifyAlert("Произошла ошибка.", "Не корректный ответ сервера.<br> StatusCode: " + data['status'], "error");
                        }
                    }
                });
            }
        });


        $('#dell-all-inactive').on('click', function() {
            if (confirm("Вы уврены, что хотите удалить все неактивные фото? \n"+
                         "После этой опперации востановление этих фотографий будет невозможно!")) {
                setBodyClassProgress();

                $.ajax({
                    url: 'photo',
                    dataType: "json",
                    type: 'delete',

                    success: function(data) {
                        delBodyClassProgress();

                        let deleted = data['deleted'];

                        deleted.forEach(function(id) {
                            $('#photoId_' + id).parents(".list-group-item").slideUp(600, function() {
                                this.remove();
                            });

                        });
                        let message = data['message'];
                        NotifyAlert("Отлично!!!", Array.isArray(message) ? message.join('<br>') : message, "success");
                    },

                    error: function(data) {
                        delBodyClassProgress();

                        if (data['responseJSON'] && data['responseJSON']['message']) {
                            let message = data['responseJSON']['message'];
                            NotifyAlert("Произошла ошибка.", Array.isArray(message) ? message.join('<br>') : message, "error");
                        } else {
                            NotifyAlert("Произошла ошибка.", "Не корректный ответ сервера.<br> StatusCode: " + data['status'], "error");
                        }
                    }
                });
            }
        });

        $('#modal_photo').on('show.bs.modal', function (event) {
            let $sourse = $(event.relatedTarget),
                src = $sourse.data('src');
            $(this).find('img').attr('src', src).css('max-height', $(window).height() - 100);
        });
    }
// End Photo page



// Start Message page
    if (window.location.pathname === '/admin/message') {

        $('.delete-message').click(function() {
            if (confirm("Вы уврены, что хотите удалить это сообщение? \n"+
                         "После этой опперации востановление будет невозможно!")) {
                let $thisButton = $(this),
                    $item = $thisButton.parents(".itemblock"),
                    id = Number($item[0].id.split('_')[1]),
                    url = 'message/' + id;

                $.ajax({
                    url: url,
                    dataType: "json",
                    type: 'delete',

                    success: function() {
                        $item.parents(".list-group-item").slideUp(600, function() {
                            this.remove();
                        });
                    },

                    error: function(data) {
                        if (data['responseJSON'] && data['responseJSON']['message']) {
                            let message = data['responseJSON']['message'];
                            NotifyAlert("Произошла ошибка.", Array.isArray(message) ? message.join('<br>') : message, "error");
                        } else {
                            NotifyAlert("Произошла ошибка.", "Не корректный ответ сервера.<br> StatusCode: " + data['status'], "error");
                        }
                    }
                });
            }
        });


        $('.edit-message').click(function() {
            let $thisButton = $(this),
                $sendButton = $thisButton.parents("div:eq(0)").find(".edit-message-send"),
                $cancelButton = $thisButton.parents("div:eq(0)").find(".edit-message-cancel"),
                $item = $thisButton.parents(".itemblock"),
                $author_name_block = $item.find("b.author-name"),
                $textarea = $item.find("textarea");

            $thisButton.parent().addClass('hidden');
            $sendButton.parent().removeClass('hidden');
            $cancelButton.parent().removeClass('hidden');

            $author_name_block.addClass('hidden');
            $textarea.prop('disabled', false).data('old_text', $textarea.val());
            $item.find("input.author-name").attr('type', 'text').val($author_name_block.text());
        });



        $('form.edit-mess').on('submit', function(event) {
            event.preventDefault();
            let $form = $(this),
                url = this.action,
                data = $form.serializeArray(),

                $sendButton = $form.find(".edit-message-send"),
                $cancelButton = $form.find(".edit-message-cancel"),
                $editButton = $form.find(".edit-message"),
                $author_name_block = $form.find("b.author-name"),
                $input_name = $form.find("input.author-name"),
                $textarea = $form.find("textarea");


            $.ajax({
                url: url,
                type: 'put',
                dataType: "json",
                data: data,

                success: function(data) {
                    $textarea.val(data['text']).prop('disabled', true).data('old_text', '');
                    $author_name_block.text(data['name']).removeClass('hidden');

                    $sendButton.parent().addClass('hidden');
                    $editButton.parent().removeClass('hidden');
                    $cancelButton.parent().addClass('hidden');

                    $input_name.attr('type', 'hidden').val('');

                    NotifyAlert("Отлично!!!", "Сообщение отредактировано успешно.", "success");
                },

                error: function(data) {
                    if (data['responseJSON'] && data['responseJSON']['message']) {
                        let message = data['responseJSON']['message'];
                        NotifyAlert("Произошла ошибка.", Array.isArray(message) ? message.join('<br>') : message, "error");
                    } else {
                        NotifyAlert("Произошла ошибка.", "Не корректный ответ сервера.<br> StatusCode: " + data['status'], "error");
                    }
                }
            });
        });



        $('.edit-message-cancel').click(function() {
            let $thisButton = $(this),
                $sendButton = $thisButton.parents("div:eq(0)").find(".edit-message-send"),
                $editButton = $thisButton.parents("div:eq(0)").find(".edit-message"),
                $item = $thisButton.parents(".itemblock"),
                $author_name_block = $item.find("b.author-name"),
                $textarea = $item.find("textarea");

            $thisButton.parent().addClass('hidden');
            $sendButton.parent().addClass('hidden');
            $editButton.parent().removeClass('hidden');

            $author_name_block.removeClass('hidden');
            $textarea.prop('disabled', true).val($textarea.data('old_text'));
            $item.find("input.author-name").attr('type', 'hidden').val('');

        });
    }
// End Massage page



// Start Group page
    if (window.location.pathname === '/admin/group') {
        $('#change_name_group_modal').on('show.bs.modal', function (event) {
            if($(event.relatedTarget).attr('data-sourse')) {
                let groupId = $(event.relatedTarget).attr('data-sourse'),
                    curr_name_group = $('#groupName_' + groupId + ' .group-name').text();

                $('#title_change_name_group_modal')
                        .html("Текущее имя группы <b> \"" + curr_name_group + "\"</b>");
                $('#change_name_group_form').attr('action', 'group/' + groupId);
            }

        }).on('hidden.bs.modal', function() {
            $('#title_change_name_group_modal').html("");
            $('#change_name_group_form').attr('action', '').trigger('reset');
        });


        $('#change_name_group_form').on('submit', function(event) {
            event.preventDefault();
            let $form = $(this),
                url = this.action,
                data = $form.serializeArray();

            $.ajax({
                url: url,
                type: 'put',
                dataType: "json",
                data: data,

                success: function(data) {
                    $('#groupName_' + data['name'] + ' .group-name').text(data['display_name']);
                    $('#change_name_group_modal').modal('hide');
                },

                error: function(data) {
                    if (data['responseJSON'] && data['responseJSON']['message']) {
                        let message = data['responseJSON']['message'];
                        NotifyAlert("Произошла ошибка.", Array.isArray(message) ? message.join('<br>') : message, "error");
                    } else {
                        NotifyAlert("Произошла ошибка.", "Не корректный ответ сервера.<br> StatusCode: " + data['status'], "error");
                    }
                }
            });
        });
    }
// End Group page



    function validateSizeFiles(files) {
        let sum_size_files = 0,
            url = window.URL || window.webkitURL,
            imageType = /^image\/jpeg/,
            perm_file_size, divider_unit, unit;

        if (window.location.pathname === '/admin/photo') {
            perm_file_size = 2097152;          //2Mb
            divider_unit = 1048576;                 //1Mb
            unit = "Mb";
        }
        if (window.location.pathname === '/admin/group') {
            perm_file_size = 102400;           //100Kb
            divider_unit = 1024;                    //1Kb
            unit = "Kb";
        }

        for (let key in files) {

            if (files.hasOwnProperty(key) && typeof(files[key]) === "object") {

                sum_size_files = sum_size_files + files[key].size;

                if (!imageType.test(files[key].type)) {
                    NotifyAlert("Внимание!!! Недопустимый формат файла <strong>files[key].name</strong>",
                                  "Данный файл должен быть изображением формата jpeg",
                                  "warning");
                    continue;
                }

                let img = new Image();

                img.size = files[key].size;
                img.filename = files[key].name;
                img.src = url.createObjectURL(files[key]);
                img.onload = function () {
                    let text = "",
                        alert_flag = false;


                    if (this.size > perm_file_size) {
                        alert_flag = true;
                        text = "Размер данного файла <span class='red-bold-text'>" +
                            (this.size / divider_unit).toFixed(2) + unit + "</span> " +
                            "Допустимый размер, не должен привышать " +
                            "<span class='red-bold-text'>" + perm_file_size / divider_unit + unit + "</span>.";
                    }

                    if (this.width > 3000 || this.height > 3000) {
                        alert_flag === true ? text += "<br>" : alert_flag = true;
                        text += "Ширина и высота данного файла <span class='red-bold-text'>" +
                            this.width + " x " + this.height + "</span> " +
                            " пикс. Допустимые размеры, не должны превышать" +
                            "<span class='red-bold-text'> 3000 x 3000</span> пикс.";
                    }

                    if (alert_flag) {
                        NotifyAlert("Внимание!!! Недопустимый размер файла <strong>" + this.filename + "</strong>",
                            text, "warning");
                    }

                    url.revokeObjectURL(this.src);
                }
            }
        }

        if (sum_size_files > 8388608) {
            NotifyAlert("Внимание!!! Вы превысили максимальный объём отправляемых файлов",
                          "Текущий объём отправляемых файлов <span class='red-bold-text'>" +
                           (sum_size_files / 1048576).toFixed(2) +
                          "Mb</span> Разрешённый объём <span class='red-bold-text'>8Mb</span>",
                          "warning");
        }
    }



    function NotifyAlert(head, text, type) {
        let css_class, content_template,
            notifies_alert = $("#notifies_alert");

        if (type === 'error') {
            css_class = 'alert-danger';
            content_template = "<h4>Упcс!!! Что-то пошло не так!!!</h4>" +
                               "<h5>" + head + "</h5><br>" + text + "</div>"
        } else if (type === 'warning') {
            css_class = 'alert-warning-file';
            content_template = "<h4>" + head + "</h4>" + text + "</div>"
        } else if (type === 'success') {
            css_class = 'alert-success';
            content_template = "<h4>" + head + "</h4><br>" + text + "</div>"
        } else {
            console.error("Not specified notify type");
            return;
        }

        let alert_block_temlate = $("<div class='alert " + css_class + " alert-dismissible fade in' role='alert'>" +
                           "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>" +
                           "<span aria-hidden='true'>×</span></button>" + content_template
                           );

        notifies_alert.hide();
        alert_block_temlate.appendTo(notifies_alert);
        notifies_alert.slideDown(600);
    }



    function getNewUploadedPhoto(ids) {
        $.ajax({
            url: 'photo',
            type: 'get',
            dataType: "html",
            data: {ids: ids},

            success: function(data) {
                $('.list-group').append(data);
                let h = $("html"),
                    p = h.height();
                h.animate({ scrollTop: p}, 5000);
            },

            error: function(data) {
                if (data['responseJSON'] && data['responseJSON']['message']) {
                    let message = data['responseJSON']['message'];
                    NotifyAlert("Произошла ошибка.", Array.isArray(message) ? message.join('<br>') : message, "error");
                } else {
                    NotifyAlert("Произошла ошибка.", "Не корректный ответ сервера.<br> StatusCode: " + data['status'], "error");
                }
            }
        });
    }

    function setBodyClassProgress() {
        let $nav = $('nav'),
            $body = $('body'),
            $notifies = $('#notifies_alert'),
            $loader = $('#loader'),
            original_pad = {
                'body': $body.css('padding-right') || '',
                'nav': $nav.css('padding-right') || '',
                'notifies': $notifies.css('padding-right') || '',
            };

        $loader.data('original_pad', original_pad);

        if (document.body.clientWidth < window.innerWidth) {
            let bodyPad = parseInt(($body.css('padding-right') || 0), 10),
                navPad = parseInt(($nav.css('padding-right') || 0), 10),
                notifesPad = parseInt(($notifies.css('padding-right') || 0), 10),
                scrollbarWidth = window.innerWidth - document.body.clientWidth;

            $body.css('padding-right', bodyPad + scrollbarWidth);
            $nav.css('padding-right', navPad + scrollbarWidth);
            $notifies.css('padding-right', notifesPad + scrollbarWidth);
        }

        $body.addClass('progress');
        $loader.fadeIn();

    }

    function delBodyClassProgress() {
        let $loader = $('#loader'),
            $body = $('body'),
            original_pad = $loader.data('original_pad');

        $body.css('padding-right', original_pad.body);
        $('nav').css('padding-right', original_pad.nav);
        $('#notifies_alert').css('padding-right', original_pad.notifies);

        $loader.removeData('original_pad');

        $body.removeClass('progress');
        $loader.fadeOut();
    }

});
