<?php

namespace App\Http\Controllers\Admin;

use App\Photo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Route;
use Validator;
use App\Group;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $photos = Photo::select('id', 'src_mini_thumb', 'active')
                        ->with(['groups'=>function ($query) {
                                              $query->select('name');
                                         }])->get();

        $groups = Group::all();

        $group_name =[];

        foreach ($groups as $group) {
            $group_name[$group['name']] = $group['display_name'];
        }

        //dump($group_name);

        return view('admin_photo', ['photos'=>$photos, 'group_name'=>$group_name]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
                                    'file' => 'required',
                                ]);

        // Реализуем поддержку multiple и простой формы загрузки файла
        $validator->sometimes('file.*', 'file', function($input) {
            return is_array($input->file);
        });

        $validator->sometimes('file', 'file', function($input) {
            return !is_array($input->file);
        });
        /////////////////////////////////////////////////////////////

        if ($validator->fails()) {

            $jsonResponse = ['message' => $validator->errors()->all() ];

            return response($jsonResponse, 400);
        }


        $files = $request->allFiles();

        // Реализуем поддержку multiple и простой формы загрузки файла
        $arr_files = is_array($files['file']) ? $files['file'] : [$files['file']];
        /////////////////////////////////////////////////////////////

        $err_mess = [];

        foreach ($arr_files as $file) {

            $origin_file_name = $file->getClientOriginalName();

            $messages = [ 'image' => 'Файл ' . $origin_file_name . ' должен быть изображением.',
                          'mimes' => 'Файл ' . $origin_file_name . ' должен быть файлом одного из следующих типов: :values.',
                          'max' => 'Размер файла ' . $origin_file_name . ' не может быть более :max Килобайт(а).',
                          'dimensions' => 'Файл ' . $origin_file_name . ' имеет недопустимые размеры изображения.',
                        ];

            $validator = Validator::make(['img_file' => $file], [
                                'img_file' => 'image|mimes:jpeg|max:2048|dimensions:max_width=3000,max_height=3000',
                            ], $messages);

            if ($validator->passes()) {

                $file_path = $file->store('img/upload', 'public');

                $photo = new Photo;
                $photo->src = $file_path;
                $photo->src_midi = $file_path;
                $photo->src_mini = $file_path;
                $photo->src_mini_thumb = $file_path;
                $photo->save();

            } else {

               $err_mess[] =  $validator->errors()->all();

            }

            $jsonResponse = ['message' => $err_mess];
        }
        return $photo;


        /*[
   [
   "name" => "hkhj"
   "status" => "error"
    "error" => "ghj"
   ],
   [
    'name' => "asdasd"
    'status' => "ok"
   ]
]*/

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Photo $photo)
    {
        $validator = Validator::make($request->all(), [
                                            'active' => 'required|bool',
                                            ]);


        if ($validator->fails()) {

            $jsonResponse = ['message' => $validator->errors()->all() ];

            return response($jsonResponse, 400);

        }


        $photo->active = $request['active'];

        $photo->save();

        return $photo;
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function attachGroup(Request $request, $id)
    {
        if(!$photo = Photo::find($id)) {

            $jsonResponse = ['message' => [__('Photo not found.')] ];

            return response($jsonResponse, 404);
        }


        $validator = Validator::make($request->all(), [
                            'group_name' => 'required|string|in:studya,wedding,evning,age',
                            ]);


        if ($validator->fails()) {

            $jsonResponse = ['message' => $validator->errors()->all() ];

            return response($jsonResponse, 400);

        }


        if($photo->groups()->where('name', $request['group_name'])->count() !== 0 ) {

            $jsonResponse = ['message' => [__('This photo already in this group.')] ];

            return response($jsonResponse, 403);
        }


        $photo->groups()->attach($request['group_name']);

        return $photo->groups;
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function detachGroup(Request $request, $id)
    {
        if(!$photo = Photo::find($id)) {

            $jsonResponse = ['message' => [__('Photo not found.')] ];

            return response($jsonResponse, 404);
        }


        $validator = Validator::make($request->all(), [
                             'group_name' => 'required|string|in:studya,wedding,evning,age',
                            ]);


        if ($validator->fails()) {

            $jsonResponse = ['message' => $validator->errors()->all() ];

            return response($jsonResponse, 400);
        }

        if($photo->groups()->where('name', $request['group_name'])->count() !== 1 ) {

            $jsonResponse = ['message' => [__('This photo does not belong to this group.')] ];

            return response($jsonResponse, 403);
        }


        $photo->groups()->detach($request['group_name']);

        return $photo->groups;
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Photo $photo)
    {
        $photo->delete();

        $jsonResponse = ['message' => [__('Delete completed.')] ];

        return $jsonResponse;
    }
}
