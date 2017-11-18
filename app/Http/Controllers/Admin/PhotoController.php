<?php

namespace App\Http\Controllers\Admin;

use App\Photo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Route;
use Validator;
use App\Group;

use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

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

        $validator = Validator::make($request->all(), ['file' => 'required']);

        // Реализуем поддержку multiple и простой формы загрузки файла
        $validator->sometimes('file.*', 'file', function($input) {
            return is_array($input->file);
        });

        $validator->sometimes('file', 'file', function($input) {
            return !is_array($input->file);
        });


        if ($validator->fails()) {

            $jsonResponse = ['message' => $validator->errors()->all() ];

            return response($jsonResponse, 400);
        }


        $files = $request->allFiles();

        // Реализуем поддержку multiple и простой формы загрузки файла
        $arr_files = is_array($files['file']) ? $files['file'] : [$files['file']];

        $err_mess = [];

        $photos = [];

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

                $orig_ext = $file->guessExtension();

                $file_name = implode(explode($orig_ext, $file->hashName(), -1));

                $big_image = Image::make($file)->heighten(1500)->encode('jpg', 75);
                Storage::disk('public')->put('img/big/' . $file_name . 'jpg', $big_image);

                $midi_image = Image::make($file)->heighten(350)->encode('jpg', 75);
                Storage::disk('public')->put('img/midi/' . $file_name . 'jpg', $midi_image);

                $mini_image = Image::make($file)->heighten(110)->encode('jpg', 75);
                Storage::disk('public')->put('img/mini/' . $file_name . 'jpg', $mini_image);

                $thumb_image = Image::make($file);
                $thumb_image->height() > $thumb_image->width()
                                                ? $thumb_image->heighten(110)
                                                : $thumb_image->widen(138);
                $thumb_image->resizeCanvas(138, 110, 'center', false, '000000')->encode('jpg');
                Storage::disk('public')->put('img/thumb/' . $file_name . 'jpg', $thumb_image);

                $photo = new Photo;
                $photo->src = 'img/big/' . $file_name . 'jpg';
                $photo->src_midi = 'img/midi/' . $file_name . 'jpg';
                $photo->src_mini = 'img/mini/' . $file_name . 'jpg';
                $photo->src_mini_thumb = 'img/thumb/' . $file_name . 'jpg';
                $photo->save();

                $photos[] = $photo;

            } else {

               $err_mess[] =  $validator->errors()->all();

            }

            $jsonResponse = ['uploaded'=>$photos, 'error' => $err_mess];
        }
        return $jsonResponse;


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

        Storage::disk('public')->delete($photo->src);
        Storage::disk('public')->delete($photo->src_midi);
        Storage::disk('public')->delete($photo->src_mini);
        Storage::disk('public')->delete($photo->src_mini_thumb);
        $photo->delete();

        $jsonResponse = ['message' => [__('Delete completed.')] ];

        return $jsonResponse;
    }
}
