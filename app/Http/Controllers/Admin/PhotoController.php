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
        //
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
