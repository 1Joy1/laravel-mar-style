<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    //
    protected $table = 'groups';



    protected $fillable = ['name', 'photo_src', 'display_name',];



    protected $primaryKey = 'name';



    public $incrementing = false;




    public function photos()
    {
        return $this->belongsToMany('App\Photo');
    }
}
