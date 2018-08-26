<?php

namespace App\Http\Controllers;

use App\Group;
use Illuminate\Http\Request;


class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = Group::all();

        return $groups;
    }


    /**
     * Display a listing grouped photo
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function indexGroupPhoto($group_name)
    {
        $photos = [];

        $photos_collection = Group::find($group_name)->photos()->where('active', true)->get();

        foreach ($photos_collection as $photo) {

            $photos[] = $photo->only(['id', 'src_mini_thumb', 'src']);
        }
        return $photos;
    }

}
