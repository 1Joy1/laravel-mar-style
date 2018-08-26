<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage;

use App\Events\PhotoDeleted;
use App\Events\PhotoDeleting;

class Photo extends Model
{
    //
    protected $table = 'photos';


    protected $fillable = ['path', 'active'];

    protected $attributes = ['active' => false];

    protected $appends = array('src', 'src_midi', 'src_mini', 'src_mini_thumb');


    protected $dispatchesEvents = ['deleted' => PhotoDeleted::class,
                                   'deleting' => PhotoDeleting::class,
                                   ];


    public function getSrcAttribute() {

        return Storage::url($this->big_photo_path);
    }

    public function getSrcMidiAttribute() {

        return Storage::url($this->midi_photo_path);
    }

    public function getSrcMiniAttribute() {

        return Storage::url($this->mini_photo_path);
    }

    public function getSrcMiniThumbAttribute() {

        return Storage::url($this->thumb_photo_path);
    }

    public function groups()
    {
        return $this->belongsToMany('App\Group');
    }
}
