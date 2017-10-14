<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    //
    protected $table = 'photos';


    protected $fillable = ['src', 'src_midi', 'src_mini', 'src_mini_thumb', 'active'];

    protected $attributes = ['active' => false];


    public function groups()
    {
        return $this->belongsToMany('App\Group');
    }
}
