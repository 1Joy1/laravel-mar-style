<form id="form" action="{{ route('message.store') }}" method="post" target="otvet">
    {{ csrf_field() }}
    <p style="text-align: left">Ваше имя:<br>
        <input id="form_name" name="name" value="" size="50" type="text" title="Введите ваше имя." required="required">
    </p>
    <p style="text-align: left">Ваш email:<br>
        <input id="form_email" name="email" value="" size="50" title="Введите ваш email адрес." type="email" required="required">
    </p>
    <p style="text-align: left">Отзыв:<br>
        <textarea id="form_message" name="message" title="Введите ваш отзыв." required="required"></textarea>
    </p>
    <p style="text-align: center; padding-bottom: 10px;">
        <input id="form_submit" value="Отправить" type="submit">
    </p>
</form>