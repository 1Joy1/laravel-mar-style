@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Панель Управления - Настройка Сообщений отзывов</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                </div>

                <ul class="list-group">

                @foreach($messages as $message)

                <li class="list-group-item">
                    <div id = "messId_{{ $message['id'] }}" class="row itemblock">

                        <form class="edit-mess" action="message/{{ $message['id'] }}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <div class="col-sm-5">
                                <b class="author-name">{{ $message['name'] }}</b>
                                <input class="form-control author-name success" type="hidden" name="name" required="required">
                            </div>

                            <div class="col-sm-8">
                                <textarea class="form-control" style="resize:vertical" name="text" rows="5" required="required" disabled >{{ $message['text'] }}</textarea>
                            </div>

                            <div class="col-sm-4">
                                <p><button type="button" class="edit-message btn btn-sm btn-success btn-block" data-sourse="{{ $message['id'] }}">Редактировать сообщение</button></p>

                                <p class="hidden"><button type="submit" class="edit-message-send btn btn-sm btn-success btn-block" data-sourse="{{ $message['id'] }}">Сохранить</button></p>

                                <p class="hidden"><button type="button" class="edit-message-cancel btn btn-sm btn-warning btn-block" data-sourse="{{ $message['id'] }}">Отмена</button></p>

                                <p><button type="button" class="delete-message btn btn-sm btn-danger btn-block" data-sourse="{{ $message['id'] }}">Удалить сообщение</button></p>
                            </div>
                            <div class="col-sm-12">
                                <div class="col-sm-4"><i><b>{{ $message['email'] }}</b></i></div>
                                <div class="col-sm-3"><i><b>IP: {{ $message['ip'] }}</b></i></div>
                                <div class="col-sm-5" align="right"><i><b>Дата создания: {{ $message['created_at'] }}</b></i></div>
                            </div>
                        </form>
                    </div>
                </li>


                @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection