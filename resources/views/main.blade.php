<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">

    <title>Маршак Наталья стилист-визажист | Свадебные, вечерние причёски и макияж | Одесса</title>

    <link href="css/index.css" rel="stylesheet">
    <link href="css/modalform.css" rel="stylesheet">
    <link href="css/lightgallery.css" rel="stylesheet">
    <link href="img/favicon.png" rel="icon">

    <script type="text/javascript" src="js/jquery-2.2.1.min.js"></script>
    <script type="text/javascript" src="js/flowtype.js"></script>
    <script type="text/javascript" src="js/jquery.nicescroll.min.3.6.6.js"></script>
    <script type="text/javascript" src="js/device.min.js"></script>
    <script type="text/javascript" src="js/lightgallery.min.js"></script>
    <script type="text/javascript" src="js/lg-thumbnail.min.js"></script>
    <script type="text/javascript" src="js/jquery.mousewheel_3.1.13.min.js"></script>

    <script type="text/javascript" src="js/index.js"></script>


</head>

<body>
    <div class="frame">
        <!--img class="background" src="img/BG_albom.jpg" /-->
        <!--img class="foto" src="img/Nata333x450.png" /-->
        <div class="layer2">
            <div class="header">
                <p class="name">Маршак&nbsp;Наталья</p>
                <p class="profession">парикмахер-визажист</p>
            </div>

            <div class="menu">
                <div class="top-menu">

                @foreach ($menu as $item)
                    <div id={{ $item['id'] }}>
                        <a class="hov" href={{ $item['href'] }} ></a>
                        <div class="menu-el">
                            <span>
                                @for ($i = 1; $i < 4; $i++)
                                    <img class="sneg{{ $i }}" src="img/sneg.png" />

                                @endfor
                                {{ $item['name'] }}
                            </span>
                        </div>
                    </div>
                @endforeach

                </div>
            </div>

            <div class="main">
                <div class="wrap">
                    <p class="servis_description">Свадебные, вечерние причёски и макияж.</p>
                    <p class="tel">тел. (067) 710-48-73<br>(048) 700-04-06</p>
                </div>
            </div>

            <div class="portfolio">
                <div class="wrap" >

                @foreach($portfol as $field=>$value)

                    <div class="portphol-main">
                        <a href="#" onClick="getGalleryJSON(event, '{{ $field }}')">
                        <img src="{{ $value['img'] }}" />
                        <p>{{ $value['name'] }}</p></a>
                    </div>

                @endforeach

                    <div class="portphol-main-all">

                        <p><a href="#" onClick="getGalleryJSON(event, 'all')">Просмотреть всё.</a></p>
                    </div>
                </div>

            </div>

            <div class="services">
                <div class="wrap">
                    <div class="uslugi">
                        <ul>В мои услуги входят:
                            <li style="margin-left: 10%; margin-top: 5px">-cвадебный макияж, причёска</li>
                            <li style="margin-left: 13%">-вечерний макияж, причёска</li>
                            <li style="margin-left: 16%">-макияж для фотосессии</li>
                            <li style="margin-left: 19%">-возрастной макияж</li>
                            <li style="margin-left: 21%">-мужской макияж</li>
                            <li style="margin-left: 24%">-альтернативный макияж</li>
                            <li style="margin-left: 21%">-коррекция и покраска бровей и ресниц</li>
                            <li style="margin-left: 19%">-обучение визажистов "Базовый курс"</li>
                            <li style="margin-left: 16%">-уроки "Макияж для себя"</li>
                        </ul>
                    </div>
                    <div class="uslugi-cosmetics">
                        <p>В работе использую профессиональную косметику Make up Atelier, Mас, Paris Berlin.<br>
                            <span style="margin-left: 3%">Закончила авторскую школу Натальи Найды (карандашная техника макияжа).</span><br>
                            <span style="margin-left: 3%">Закончила авторскую школу Оксаны Воронцовой (европейская техника макияжа).</span><br>
                            <span style="margin-left: 3%">Обучение в школе-студии Ольги Войниковой.</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="feadback">
                <div class="wrap" >
                    <div>
                        <p id="leave_feedback"><a id="gofeedback" href="#" >Оставить отзыв</a></p>
                    </div>
                    <div id="comments_block">
                        <div id="comments_block_wrap" style="width: 80%; margin: auto;">

                            @include('message')

                        </div>
                    </div>
                </div>
            </div>

            <div class="link">
                <div class="wrap">
                    <p class="my_links pml1">
                        <span>По этой ссылке вы также можете увидеть</span><br>
                        <span class="spl1">отзывы о моей работе на Одесском форуме:</span><br>
                        <span class="spl2"><a href="http://forum.od.ua/showthread.php?t=827975" target="_blank">
                        http://forum.od.ua/showthread.php?t=827975</a></span>
                    </p>

                    <p class="my_links pml2">
                        <span>Мои страницы в социальных сетях:</span><br>
                        <span class="spl2"><a href="http://vk.com/marshak.nata">
                        http://vk.com/marshak.nata</a></span><br>
                        <span class="spl1"><a href="http://vk.com/pricheski_makijag" target="_blank">
                        http://vk.com/pricheski_makijag</a></span>
                    </p>

                    <p class="my_links pml3">
                        <span class="spl1"><a href="http://facebook.com/marshak.nata">
                        http://facebook.com/marshak.nata</a></span><br>
                        <span class="spl2"><a href="http://facebook.com/makeup.marshak" target="_blank">
                        http://facebook.com/makeup.marshak</a></span>
                    </p>

                    <p class="my_links pml4">
                        <span class="spl1"><a href="http://instagram.com/marshak.nata/" target="_blank">
                        http://instagram.com/marshak.nata/</a></span><br>
                        <span></span>
                    </p>

                    <p class="my_links pml5">
                        <span>Обучение макияжу:</span><br>
                        <span class="spl1"><a href="http://learn.marshak-style.od.ua" target="_blank">
                        http://learn.marshak-style.od.ua</a></span>
                    </p>
                </div>
            </div>

            <div class="futer">
                <p>www.marshak-style.od.ua<br>marshak_n@mail.ru</p>
            </div>
        </div>
    </div>

    <!--*****************************************************************-->
            <div id="modal_form"><!-- Сaмo oкнo -->
                <span id="modal_close">X</span> <!-- Кнoпкa зaкрыть -->


                <!--send form from ajax-->

                <iframe name="otvet">Ваш браузер не поддерживает плавающие фреймы!</iframe>
            </div>

            <div id="overlay"></div><!-- Пoдлoжкa -->

    <!--********************************************************************-->

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-73131248-2', 'auto');
        ga('send', 'pageview');

    </script>
</body>
</html>


