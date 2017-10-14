<link href="css/modalform.css" rel="stylesheet">
    <script type="text/javascript">
        function backToForm(){
            window.parent.$("form").css('display', 'block');
            window.parent.$("iframe").css('display', 'none');
        }
    </script>
    <center><h3 id="form_req" style="color:#fff;">Отзыв не отправлен. <br \> Не заполнены все поля.</h3>
        <h5 style="color:#fff;">
            @foreach ($errors->all() as $message)
                {{ $message }} <br>
            @endforeach

        </h5>
    <button id="form_back" onclick = "backToForm();">Назад</button></center>