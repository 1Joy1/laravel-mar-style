<?php

namespace App\Http\Controllers;

use App\Group;


class GroupController extends Controller
{
    //TODO: Kill this class & dependent route

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = Group::all();

        return response()->json($groups);
    }


    /**
     * Display a listing grouped photo
     * @param string $group_name
     * @return \Illuminate\Http\Response
     */
    public function indexGroupPhoto($group_name)
    {
        $photos = [];

        $photos_collection = Group::find($group_name)->photos()->where('active', true)->get();

        foreach ($photos_collection as $photo) {

            $photos[] = $photo->only(['id', 'src_mini_thumb', 'src']);
        }
        return response()->json($photos);
    }

}
