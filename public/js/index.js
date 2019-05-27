$(document).ready(function() {

    let nowContent;

    $('body').flowtype();   //Резиновый шрифт


    //Стилизованный скролинг на отзывах.
    $('#comments_block').niceScroll({ cursorcolor: "#897E68",
        cursoropacitymin: 0.3,
        cursorwidth: "3px",
        cursorborder: "1px solid rgba(48, 18, 2, 0)"
    });


    // отключаем ссылки на телефон на десктопе.
    $("a[href^='tel']").on('click', function(event) {
        if (device.desktop()) {
            event.preventDefault();
        }
    });


    setFrameRatio();


    $(window).resize(function() {
        setFrameRatio();
    });


    /*$(window).on("orientationchange",function(event) {
        setFrameRatio();
    });*/


    $('.frame').fadeIn(1500);


    if (document.location.hash && document.location.hash !== "#main") {
        let $tel = $('.tel'),
            $servis_description = $('.servis_description');

        $('.main').css('display', 'none');

        setTimeout(function () {
            selectPage(document.location.hash);
        }, 800);

        if ($servis_description.css('display') === 'none') {
            $servis_description.css('display', 'block');
        }
        if ($tel.css('display') === 'none') {
            $tel.css('display', 'block');
        }

    } else {
        nowContent = "main";
        setTimeout(function () {
            $('.servis_description').fadeIn(1500);
            setTimeout(function () {
                $('.tel').fadeIn(1000);}, 300);
        }, 800);
    }



    window.addEventListener('hashchange', function() {
        selectPage(document.location.hash);

        $('#modal_close').click();
    });



    /* Открытие мoдaльнoгo oкнa, для отзывов */

    $('a#gofeedback').click( function(event) {
        event.preventDefault();
        $.get( "/message/create", function(data) {
            $("#modal_close").after(data);

            $('#overlay').fadeIn(400, function(){
                $('#modal_form')
                    .css('display', 'block')
                    .animate({opacity: 1, top: '50%'}, 200);
            });
        });

    });



    /* Зaкрытие мoдaльнoгo oкнa */

    $('#modal_close, #overlay').click(function() {
        $('#modal_form').animate({opacity: 0, top: '45%'}, 200, function() {
            $(this).css('display', 'none');
            $('#overlay').fadeOut(400);
            $('#form').remove();
        });
    });



    $('.portfolio').on('click', 'a', function(event) {
        event.preventDefault();

        let obj = event.currentTarget,
            select_group = $(this).data('name');

        if (select_group === "all") {
            select_group = "";
        } else {
            select_group = "group/" + select_group + "/";
        }

        $.ajax({
            url: select_group +"photo",
            method: "GET",

            success: function (data) {

                $(obj).lightGallery({
                    dynamic:true,
                    dynamicEl:data,
                    download: false,
                });
            }
        });
    });



    $('#modal_form').on('submit', '#form', function(event) {

        let obj = event.currentTarget,
            $comments_block_wrap = $("#comments_block_wrap");

        obj.style.display = 'none';

        //let loading = document.getElementById('loading');
        //loading.style.display = 'block';

        let iframe = obj.parentElement.getElementsByTagName('iframe')[0];
        iframe.contentDocument.getElementsByTagName("body")[0].innerHTML = "";

        iframe.style.display = 'block';
        iframe.onload = (function(){
            //loading.style.display = 'none';
            if(iframe.contentDocument.getElementById("form_req").innerHTML === "Отзыв отправлен"){
                $comments_block_wrap.empty();
                $comments_block_wrap.load('/message');
            }
        });
    });



    function setFrameRatio() {
        let $frame = $('.frame');

        if(!device.desktop()) {

            if(window.orientation === 90 || window.orientation === -90 || window.innerWidth > window.innerHeight) { //album orientation

                $frame.width($frame.height()*1.5);
            }
            if(window.orientation === 0 || window.orientation === 180 || window.innerWidth < window.innerHeight) { //portret orientation

                $frame.width('100%');
            }
        } else {
            $frame.width($frame.height()*1.5);
        }
    }




    function selectPage(hash) {
        let page = hash.replace("#", "");
        //console.log("page", page);
        //console.log("nowConten", nowContent);

        if(nowContent !== page){
            $('.' + nowContent).fadeOut(1500);
            $('.' + page).fadeIn(1500);
        }
        nowContent = page;

        if (page === "feadback") {
            $("#comments_block").getNiceScroll().resize();
        }
    }
});

