<?php

namespace App\Http\Controllers;

use App\Photo;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $photos = [];

        $photos_collection = Photo::where('active', true)->get();

        foreach ($photos_collection as $photo) {

            $photos[] = $photo->only(['id', 'src_mini_thumb', 'src']);
        }
        return response()->json($photos);
    }

    /**
     * Display a listing grouped photo
     * @param string $group_name
     * @return \Illuminate\Http\Response
     */
    public function indexGroupPhoto($group_name)
    {

        $photos = [];

        $photos_collection = Photo::where('active', true)
            ->whereHas('groups', function (\Illuminate\Database\Eloquent\Builder $query) use ($group_name) {
                $query->where('name', $group_name);
            })->get();

        foreach ($photos_collection as $photo) {

            $photos[] = $photo->only(['id', 'src_mini_thumb', 'src']);
        }

        return response()->json($photos);
    }

}
