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
                    @endif>Удалить из галлереи</button>
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