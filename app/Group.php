<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage;

class Group extends Model
{
    //
    protected $table = 'groups';



    protected $fillable = ['name', 'photo_path', 'display_name',];



    protected $primaryKey = 'name';



    public $incrementing = false;



    protected $appends = array('photo_src');



    public function getPhotoSrcAttribute() {

        return Storage::url($this->photo_path);
    }




    public function photos()
    {
        return $this->belongsToMany('App\Photo');
    }
}
