<form id="form" action="/message" method="post" target="otvet" onsubmit="afterSendForm(this);">
    {{ csrf_field() }}
    <p style="text-align: left">Ваше имя:<br>
        <input id="form_name" name="name" value="" size="50" type="text" required="required">
    </p>
    <p style="text-align: left">Ваш email:<br>
        <input id="form_email" name="email" value="" size="50" type="email" required="required">
    </p>
    <p style="text-align: left">Отзыв:<br>
        <textarea id="form_message" name="message" required="required"></textarea>
    </p>
    <p style="text-align: center; padding-bottom: 10px;">
        <input id="form_submit" value="Отправить" type="submit">
    </p>
</form>