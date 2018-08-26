@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Панель Управления - Настройка Фотографий</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                </div>

                <ul class="list-group">

                @foreach($photos as $photo)


                <li class="list-group-item">
                    <div id = "photoId_{{ $photo['id'] }}" class="row itemblock">
                        <div class="col-sm-3 photoblock {{ ($photo['active'] == false)?'disabled':'enabled' }}">
                            <img class="center-block cursor-pointer" src="{{ $photo['src_mini'] }}" data-toggle="modal" data-target="#modal_photo" data-src="{{ $photo['src'] }}"/>
                        </div>
                        <div class="col-sm-5 checkblock {{ ($photo['active'] == false)?'disabled':'enabled' }}">
                            <table cellpadding="0" >
                                <col width=35> <col width=265>
                                <tr>
                                    <td><input class="category-checker" type="checkbox" name="cateory" value="studya"
                                        @foreach($photo['groups'] as $group)
                                            @if($group['name'] === 'studya')
                                                checked
                                            @endif
                                        @endforeach
                                        {{ ($photo['active'] == false)?'disabled':'enabled' }}
                                        ></td>
                                    <td>{{ $group_name['studya'] }}</td>
                                </tr>
                                <tr>
                                    <td><input class="category-checker" type="checkbox" name="cateory" value="wedding"
                                        @foreach($photo['groups'] as $group)
                                            @if($group['name'] === 'wedding')
                                                checked
                                            @endif
                                        @endforeach
                                        {{ ($photo['active'] == false)?'disabled':'enabled' }}
                                        ></td>
                                    <td>{{ $group_name['wedding'] }}</td>
                                </tr>
                                <tr>
                                    <td><input class="category-checker" type="checkbox" name="cateory" value="evning"
                                        @foreach($photo['groups'] as $group)
                                            @if($group['name'] === 'evning')
                                                checked
                                            @endif
                                        @endforeach
                                        {{ ($photo['active'] == false)?'disabled':'enabled' }}
                                        ></td>
                                    <td>{{ $group_name['evning'] }}</td>
                                </tr>
                                <tr>
                                    <td><input class="category-checker" type="checkbox" name="cateory" value="age"
                                        @foreach($photo['groups'] as $group)
                                            @if($group['name'] === 'age')
                                                checked
                                            @endif
                                        @endforeach
                                       {{ ($photo['active'] == false)?'disabled':'enabled' }}
                                        ></td>
                                    <td>{{ $group_name['age'] }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-sm-4">
                            <p>
                                    <button type="button" class="deactive-gallery btn btn-sm btn-primary btn-block"
                                    @if($photo['active'] == false)
                                        disabled="disabled"
                                    @endif >Удалить из галлереи</button>
                            </p>
                            <p>
                                    <button type="button" class="active-gallery btn btn-sm btn-primary btn-block"
                                    @if($photo['active'] == true)
                                        disabled="disabled"
                                    @endif>Добавить в галлерею</button>
                            </p>
                            <p>
                                    <button type="button" class="delete-of-storage btn btn-sm btn-danger btn-block">Удалить с сайта</button>
                            </p>
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
<div class="modal fade" id="load_file_modal" tabindex="-1" role="dialog" aria-labelledby="load_file_modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title" align="center">Загрузка файлов</h2>
                <h5 align="center">К загрузке допускаются JPG файлы не более 2Мб. <br>
                    Размер файла не должен быть больше 3000х3000 пикселей.<br>
                    А также суммарный размер отправляемых файлов не должен привышать 8Мб.</h5>

            </div>

            <div class="modal-body">
                <div align="center">

                    <form id="fileform" action="" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <span>Выбрать файлы:</span>
                        <div class="input-group" style="width: 70%;">
                            <label class="input-group-btn">
                                <span class="btn btn-primary" title="Выбрать файлы"><i class="glyphicon glyphicon-folder-open"></i>&nbsp;
                                    Обзор&hellip; <input name='file[]' type="file" style="display: none;" multiple accept="image/jpeg">
                                </span>
                            </label>
                            <input type="text" class="form-control" placeholder="Файлы не выбраны" readonly data-toggle="tooltip">
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
                <div id="pgb" class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;"></div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>


<!-- Модаль Фото-->
<div class="modal fade" id="modal_photo" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="glyphicon glyphicon-remove-circle"></span>
                </button>
                <img class="img-responsive center-block" src="" alt="">
            </div>

        </div>
    </div>
</div>
@endsection
