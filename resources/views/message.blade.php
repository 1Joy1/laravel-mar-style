@foreach ($messages as $message)

<div class="wrapmessage">
    <div class="message {{ (($loop->iteration  % 2) != 0) ? 'left' : 'right' }} ">

        <p><b> {{ $message->name }} </b></p>
        <span> {!! nl2br($message->text) !!} </span><br>
    </div>
</div>

@endforeach