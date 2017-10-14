
    function СhangeItem(ButtonItem) {
        var arr = [];
        var item = $(ButtonItem).parents(".itemblock");
        var id = Number(item[0].id.split('_')[2]);

        $(item).find('input:checkbox:checked').each(function(){
            arr.push($(this).val());
        });
        if (arr.length === 0) {
            arr.push('no_grup');
        }

        $.ajax({
            url: 'activatdeactivat.php',
            method: "POST",
            data: {
                'id': id,
                'grup': arr,
            },
            success: function (data) {
                if (data == "complite") {
                    $(ButtonItem).attr('disabled', true).css('color', '').css('font-weight', '');
                    var str = "";
                    $(item).find('input:checkbox').each(function() {
                        if (this.checked) {
                            str = str + "1";
                        } else {
                            str = str + "0";
                        }
                    });
                    $(ButtonItem).data('grup', str);
                } else if (data.search('<!DOCTYPE html PUBLIC') !== -1) {
                    $('html').html(data);
                };
            }
        });
    }

    function ActivateButton(checkbox) {
        var item = $(checkbox).parents('.itemblock');
        var button = $(item).find('.buttonhangeitem');
        var str = "";
        $(item).find('input:checkbox').each(function() {
            if (this.checked) {
                str = str + "1";
            } else {
                str = str + "0";
            }
        });
        if (str != $(button).data('grup')) {
            $(button).attr('disabled', false).css('color', '#ff001b').css('font-weight', '600');
        } else {
            $(button).attr('disabled', true).css('color', '').css('font-weight', '');
        }

    }

    function DelGallery(ButtonItem) {
        var item = $(ButtonItem).parents(".itemblock");
        var id = Number(item[0].id.split('_')[2]);

        $.ajax({
            url: 'activatdeactivat.php',
            method: "POST",
            data: {
                'id': id,
                'active': 0,
            },
            success: function (data) {
                if (data == "complite") {
                    $(ButtonItem).attr('disabled', true);
                    $(item).find('.photoblock').removeClass("enable").addClass("disable");
                    $(item).find('.checkboxgroup').removeClass("enable").addClass("disable");
                    $(item).find('.checkboxgroup input').attr('disabled', true);
                    $(item).find('.buttonhretgallery').attr('disabled', false);
                } else if (data.search('<!DOCTYPE html PUBLIC') !== -1) {
                    $('html').html(data);
                };
            },
        });
    }

    function RetGallery(ButtonItem) {
        var item = $(ButtonItem).parents(".itemblock");
        var id = Number(item[0].id.split('_')[2]);

        $.ajax({
            url: 'activatdeactivat.php',
            method: "POST",
            data: {
                'id': id,
                'active': 1,
            },
            success: function (data) {
                if (data == "complite") {
                    $(ButtonItem).attr('disabled', true);
                    $(item).find('.photoblock').removeClass("disable").addClass("enable");
                    $(item).find('.checkboxgroup').removeClass("disable").addClass("enable");
                    $(item).find('.checkboxgroup input').attr('disabled', false);
                    $(item).find('.buttondelgallery').attr('disabled', false);
                } else if (data.search('<!DOCTYPE html PUBLIC') !== -1) {
                    $('html').html(data);
                };
            }
        });
    }

    function UploadFile() {
        var formData = new FormData($('#fileform')[0]);
        $("#loader").css('display', 'block');
        var itemblock_replace = $('#answerfile').find('.itemblock').remove();
        $('.itemblock:last').after($(itemblock_replace));
        $.ajax({
              type: "POST",
              processData: false,
              contentType: false,
              url: "uploadfile.php",
              data:  formData
              })
              .done(function( data ) {
                if (data.search('<!DOCTYPE html PUBLIC') !== -1) {
                    $('html').html(data);
                };
                $('#answerfile').html(data);
                $("#loader").css('display', 'none');
                var h = $("html");
                var p = h.height();
                h.animate({ scrollTop: p}, 5000);
        });
    }

    function DelFile() {
        if (confirm("Вы уврены, что хотите удалить неактивные файлы? \n"+
                    "После этой опперации востановление их невозможно!")) {
            $("#loader").css('display', 'block');
            $.ajax({
                type: "POST",
                url: "delphoto.php",
                data:  "",
                success: function( data, status, sss ) {
                    $("#loader").css('display', 'none');

                    if (data.search('<!DOCTYPE html PUBLIC') !== -1) {
                        $('html').html(data);
                        //location.reload('index.php');
                    } else {
                        $('#answerdelfile').html(data).css('display', 'block');
                        $(".disable").parents(".itemblock").prev("center").remove();
                        $(".disable").parents(".itemblock").remove();

                        var itemblock_replace = $('#answerfile').find('.itemblock').remove();
                        $('.itemblock:last').after($(itemblock_replace));
                        $('#answerfile').find('center').remove();
                    };
                },
            });
        }
    }
