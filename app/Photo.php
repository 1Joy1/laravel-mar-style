<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage;

use App\Events\PhotoDeleted;
use App\Events\PhotoDeleting;

/**
 * App\Photo
 *
 * @property int $id
 * @property int $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $big_photo_path
 * @property string $midi_photo_path
 * @property string $mini_photo_path
 * @property string $thumb_photo_path
 * @property-read mixed $src
 * @property-read mixed $src_midi
 * @property-read mixed $src_mini
 * @property-read mixed $src_mini_thumb
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Group[] $groups
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Photo whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Photo whereBigPhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Photo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Photo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Photo whereMidiPhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Photo whereMiniPhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Photo whereThumbPhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Photo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
