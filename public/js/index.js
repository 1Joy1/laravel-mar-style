
//$(function() {

    var nowContent;

    $(document).ready(function() {

        $('body').flowtype();   //Резиновый шрифт

                                //Стилизованный скролинг на отзывах.
        $('#comments_block').niceScroll({ cursorcolor: "#897E68",
                                          cursoropacitymin: 0.3,
                                          cursorwidth: "3px",
                                          cursorborder: "1px solid rgba(48, 18, 2, 0)"
                                        });


        setFrameRatio();

        $(window).on("orientationchange",function(event) {
            setFrameRatio();
        });


        $('.frame').fadeIn(1500);


        if (document.location.hash && document.location.hash !== "#main") {
            $('.main').css('display', 'none');

            setTimeout(function () {
                selectPage(document.location.hash);
            }, 800);

            if ($('.servis_description').css('display') == 'none') {
                $('.servis_description').css('display', 'block');
            }
            if ($('.tel').css('display') == 'none') {
                $('.tel').css('display', 'block');
            }

        } else {
            nowContent = "main";
            setTimeout(function () {
                $('.servis_description').fadeIn(1500);
                setTimeout(function () {
                    $('.tel').fadeIn(1000);}, 300);
            }, 800);
        };



        window.addEventListener('hashchange', function(event) {
            selectPage(document.location.hash);

            $('#modal_close').click();
        });






        $('a#gofeedback').click( function(event){ // лoвим клик пo ссылки с id="gofeedback"
            event.preventDefault(); // выключaем стaндaртную рoль элементa
            $.get( "/message/create", function( data ) {
                $("#modal_close").after(data);

                // снaчaлa плaвнo пoкaзывaем темную пoдлoжку
                $('#overlay').fadeIn(400,
                    function(){ // пoсле выпoлнения предъидущей aнимaции
                        $('#modal_form')
                            .css('display', 'block') // убирaем у мoдaльнoгo oкнa display: none;
                            .animate({opacity: 1, top: '50%'}, 200); // плaвнo прибaвляем прoзрaчнoсть oднoвременнo сo съезжaнием вниз
                });
            });

        });



        /* Зaкрытие мoдaльнoгo oкнa, тут делaем тo же сaмoе нo в oбрaтнoм пoрядке */
        $('#modal_close, #overlay').click( function(){ // лoвим клик пo крестику или пoдлoжке
            $('#modal_form')
                .animate({opacity: 0, top: '45%'}, 200,  // плaвнo меняем прoзрaчнoсть нa 0 и oднoвременнo двигaем oкнo вверх
                    function(){ // пoсле aнимaции
                        $(this).css('display', 'none'); // делaем ему display: none;
                        $('#overlay').fadeOut(400); // скрывaем пoдлoжку
                        $('#form').remove();
                    }
                );
        });
    });


    function setFrameRatio() {
       if (device.desktop()) {
           $('.frame').width($('.frame').height()*1.5);
           $(window).resize(function() {
               $('.frame').width($('.frame').height()*1.5);
           });

       } else if(window.orientation != undefined) {
           if(window.orientation == 90 || window.orientation == -90){
               //alert("albom");
               $('.frame').width($('.frame').height()*1.5);
               $(window).resize(function() {
                   $('.frame').width($('.frame').height()*1.5);
               });

           }
           if(window.orientation == 0 || window.orientation == 180){
               //alert("portret");
               $('.frame').width('100%');
               $(window).resize(function(){
                   $('.frame').width('100%');
               });

           }
       }
    }


    function selectPage(hash) {
        var page = hash.replace("#", "");

        //console.log("page", page);
        //console.log("nowConten", nowContent);

        if(nowContent !== page){
            $('.' + nowContent).fadeOut(1500);
            $('.' + page).fadeIn(1500);
        }
        nowContent = page;

        if (page == "feadback") {
            $("#comments_block").getNiceScroll().resize();
        }
    }


    function afterSendForm(obj){
        obj.style.display = 'none';

        //var loading = document.getElementById('loading');
        //loading.style.display = 'block';

        var iframe = obj.parentElement.getElementsByTagName('iframe')[0];
        iframe.contentDocument.getElementsByTagName("body")[0].innerHTML = "";

        iframe.style.display = 'block';
        iframe.onload = (function(){
            //loading.style.display = 'none';
            if(iframe.contentDocument.getElementById("form_req").innerHTML == "Отзыв отправлен"){
                $("#comments_block_wrap").empty();
                $("#comments_block_wrap").load('/message');
            }
        });
    }

    function getGalleryJSON(obj, selectGrup) {
        obj.preventDefault();

        if (selectGrup === "all") {
            selectGrup = "";
        } else {
            selectGrup = "group/" + selectGrup + "/";
        }

        $.ajax({
            url: selectGrup +"photo",
            method: "GET",

            success: function (data) {

                $(obj).lightGallery({
                    dynamic:true,
                    dynamicEl:data,
                    download: false,
                });
            }
        });

    }

//});

