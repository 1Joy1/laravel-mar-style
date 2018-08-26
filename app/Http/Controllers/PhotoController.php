<?php

namespace App\Http\Controllers;

use App\Photo;
use Illuminate\Http\Request;

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
        return $photos;
    }

}
