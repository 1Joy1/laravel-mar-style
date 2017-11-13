<?php

namespace App\Http\Controllers\Admin;

use App\Group;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Validator;
use Illuminate\Support\Facades\Storage;

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

        return view('admin_group', ['groups'=>$groups]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        $validator = Validator::make($request->all(), [
                                'file' => 'required_without:display_name|image|mimes:jpeg|max:100|dimensions:max_width=557,max_height=800',
                                'display_name' => 'required_without:file|string|max:50|unique:groups',
                                ]);

        if ($validator->fails()) {

            $jsonResponse = ['message' => $validator->errors()->all() ];

            return response($jsonResponse, 400);
        }

        if (isset($request['file'])) {

            $ext = $request->file('file')->getClientOriginalExtension();

            $current_file_path = $group->photo_path;

            $filename_clear = explode('-', pathinfo($current_file_path, PATHINFO_FILENAME))[0];

            $new_file_path = $request->file('file')->storeAs('img/group', $filename_clear . '-' . time() . '.' . $ext, 'public');

            $group->photo_path = $new_file_path;

            if (strpos(pathinfo($current_file_path, PATHINFO_FILENAME), '-') !== false) {

                Storage::disk('public')->delete($current_file_path);
            }
        }

        if (isset($request['display_name'])) {

            $group->display_name = $request['display_name'];
        }

        $group->save();

        return $group;
    }

}
