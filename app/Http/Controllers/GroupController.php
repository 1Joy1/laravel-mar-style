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
     * Display the specified resource.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {

        return $group;
    }



    /**
     * Display a listing grouped photo
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function indexGroupPhoto($group_name)
    {
        return Group::find($group_name)->photos()->where('active', true)->select('src', 'src_mini_thumb')->get();
    }

}
