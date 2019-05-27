<link href="{{ asset('css/modalform.css') }}" rel="stylesheet">

<script type="text/javascript">
    function backToForm(){
        window.parent.$("form").css('display', 'block');
        window.parent.$("iframe").css('display', 'none');
    }
</script>

<h3 id="form_req" style="color:#fff; text-align:center">Отзыв не отправлен. <br> Не заполнены все поля.</h3>
<div style="color:#fff">
    <ul style="padding: 0 0 0 10px;">
    @foreach ($errors->all() as $message)
        <li>{{ $message }}</li>
    @endforeach
    </ul>
</div>
<button id="form_back" onclick = "backToForm();">Назад</button>