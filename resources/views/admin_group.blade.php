@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Панель Управления - Настройка Групп Фотографий</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                </div>

                <ul class="list-group">

                @foreach($groups as $group)

                <li class="list-group-item">
                    <div id = "groupName_{{ $group['name'] }}" class="row itemblock">
                        <div class="col-sm-3 photoblock">
                            <img class="center-block" style="height: 100px" src="{{ $group['photo_src'] }}" />
                        </div>
                        <div class="col-sm-5 group-name">{{ $group['display_name'] }}</div>
                        <div class="col-sm-4">
                            <p><button type="button" class="btn btn-sm btn-primary btn-block" data-toggle="modal" data-target="#load_file_modal" data-sourse="{{ $group['name'] }}">Заменить фотографию группы</button></p>
                            <p><button type="button" class="btn btn-sm btn-success btn-block" data-toggle="modal" data-target="#change_name_group_modal" data-sourse="{{ $group['name'] }}">Редактировать название группы</button></p>
                        </div>
                    </div>
                </li>


                @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Модаль Загрузки файлаов-->
<div class="modal fade" id="load_file_modal" tabindex="-1" role="dialog" aria-labelledby="Загрузка файла" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title" align="center">Загрузка файла</h2>
                <h5 align="center">Размер файла *.jpeg, должен быть 557х800 пикселей и не должен привышать 100кб.</h5>

            </div>

            <div class="modal-body">
                <div align="center">


                    <form id="fileform" action="" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <span>Выбрать файл:</span>
                        <div class="input-group" style="width: 70%;">
                            <label class="input-group-btn">
                                <span class="btn btn-primary" title="Выбрать файл"><i class="glyphicon glyphicon-folder-open"></i>&nbsp;
                                    Обзор&hellip; <input name='file' type="file" style="display: none;" accept="image/jpeg" >
                                </span>
                            </label>
                            <input type="text" class="form-control" placeholder="Файл не выбран" readonly data-toggle="tooltip">
                            <span class="input-group-btn">
                                <button id="clear_file_form" class="btn btn-default" type="button" title="Очистить выбранные файлы."><i class="glyphicon glyphicon-trash"></i></button>
                            </span>

                        </div>
                        <br>
                        <input id="send_file_form" class="btn btn-success" style="width: 150px;" type="submit" value="Загрузить" />
                    </form>


                </div>
            </div>

            <div class="progress center-block hidden" style="width: 90%;">
                <div id="pgb" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>

            <div class="modal-footer">
                <button id="close_file_form" type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>

<!-- Модаль редактирования названия группы-->
<div class="modal fade" id="change_name_group_modal" tabindex="-1" role="dialog" aria-labelledby="change_name_group_modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" align="center">Редактировать название группы</h4>
            </div>

            <div class="modal-body">


                    <form id="change_name_group_form" action="" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <span id="title_change_name_group_modal"></span>
                        <div class="input-group">
                            <input type="text" class="form-control" name="display_name" placeholder="Введите новое название группы." required="required">
                            <span class="input-group-btn">
                                <button id="send_name_group_form" class="btn btn-primary" type="submit">Отправить</button>
                            </span>
                        </div><!-- /input-group -->
                        <br>
                    </form>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div>
@endsection
