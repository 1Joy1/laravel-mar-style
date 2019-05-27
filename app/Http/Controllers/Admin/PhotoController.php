<?php

namespace App\Http\Controllers\Admin;

use App\Photo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Validator;
use App\Group;

use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response | \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $photos = [];

        $group_name =[];

        $groups = Group::all();

        foreach ($groups as $group) {
            $group_name[$group['name']] = $group['display_name'];
        }


        $query_photos = Photo::select(['id', 'mini_photo_path', 'big_photo_path', 'active'])
                            ->with(['groups'=>function ($query) {
                                                $query->select(['name']);
                                            }]);


        if (!count($request->all())) {

            $photos_collection = $query_photos->get();

            $view_name = 'admin_photo';

        } else {

            $validator = Validator::make($request->all(), ['ids' => 'array',
                                                           'ids.*' => 'required|integer',
                                                          ]);
            if ($validator->fails()) {

                $jsonResponse = ['message' => $validator->errors()->all()];

                return response($jsonResponse, 400);
            }


            $photos_collection = $query_photos->whereIn('id', $request->ids)->get();

            $view_name = 'admin_return_new_upload_photo';
        }

        foreach ($photos_collection as $photo) {

            $photo->groups->each(function($group) {
                $group->setHidden(['pivot', 'photo_src']);
            });

            $photos[] = $photo->only(['id', 'src_mini', 'src', 'active', 'groups']);

        }

        return view($view_name, ['photos'=>$photos, 'group_name'=>$group_name]);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*$messages = [ 'uploaded' => 'Засада :attribute или нескольких файлов.',];
        $validator = Validator::make($request->all(), ['file' => 'required'], $messages);

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
        }*/


        $files = $request->allFiles();
        $jsonResponse = [];

        if (!count($files)) {
            $jsonResponse = ['message' => "Поле файл обязательно для заполнения.",];

            return response()->json($jsonResponse, 400);
        }

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
                          'uploaded' => 'Згрузка файла ' . $origin_file_name . ' не удалась. Возможно превышен максимальный размер файла.',
                        ];

            $validator = Validator::make(['img_file' => $file], [
                                'img_file' => 'image|mimes:jpeg|max:2048|dimensions:max_width=3000,max_height=3000',
                            ], $messages);

            if ($validator->passes()) {

                $orig_ext = $file->guessExtension();

                $file_name = implode(explode('.' . $orig_ext, $file->hashName(), -1));

                $big_image = Image::make($file)->heighten(1500)->encode('jpg', 75);
                Storage::disk('public')->put('img/gallery/big/' . $file_name . '_big.jpg', $big_image);

                $midi_image = Image::make($file)->heighten(350)->encode('jpg', 75);
                Storage::disk('public')->put('img/gallery/midi/' . $file_name . '_midi.jpg', $midi_image);

                $mini_image = Image::make($file)->heighten(110)->encode('jpg', 75);
                Storage::disk('public')->put('img/gallery/mini/' . $file_name . '_mini.jpg', $mini_image);

                $thumb_image = Image::make($file);
                $thumb_image->height() > $thumb_image->width()
                                                ? $thumb_image->heighten(110)
                                                : $thumb_image->widen(138);
                $thumb_image->resizeCanvas(138, 110, 'center', false, '000000')->encode('jpg');
                Storage::disk('public')->put('img/gallery/thumb/' . $file_name . '_thumb.jpg', $thumb_image);

                $photo = new Photo;
                $photo->big_photo_path = 'img/gallery/big/' . $file_name . '_big.jpg';
                $photo->midi_photo_path = 'img/gallery/midi/' . $file_name . '_midi.jpg';
                $photo->mini_photo_path = 'img/gallery/mini/' . $file_name . '_mini.jpg';
                $photo->thumb_photo_path = 'img/gallery/thumb/' . $file_name . '_thumb.jpg';
                $photo->save();

                $photos[] = $photo->only(['id']);

            } else {

               $err_mess[] =  $validator->errors()->all();

            }

            $jsonResponse = ['uploaded'=>$photos, 'error' => $err_mess];
        }
        return response()->json($jsonResponse);
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

        return response()->json($photo);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
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

        return response()->json($photo->groups);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
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

        return response()->json($photo->groups);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Photo  $photo
     * @throws \Exception
     * @return \Illuminate\Http\Response
     */
    public function destroy(Photo $photo)
    {
        $photo->delete();

        $jsonResponse = ['message' => [__('Delete completed.')] ];

        return response()->json($jsonResponse);
    }


    /**
     * Remove the inactived photo from storage.
     *
     * @return \Illuminate\Http\Response
     */
     public function deleteInactivePhotos()
    {
        $photos = Photo::where('active', false)->select('id')->get();

        if ($photos->isEmpty()) {

            $jsonResponse = ['message' =>  [ __('No photos to delete.') ] ];

            return response($jsonResponse, 404);
        }

        $ids = $photos->pluck('id')->toArray();

        $quantity = Photo::destroy($ids);


        $messages = [ __('Delete completed.'), __('Deleted :quantity photos', ['quantity' => $quantity]) ];

        $jsonResponse = ['message' =>  $messages , 'deleted' => $ids ];

        return response()->json($jsonResponse);
    }
}
