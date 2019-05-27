<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Storage;

/**
 * App\Group
 *
 * @property int $id
 * @property string $name
 * @property string $photo_path
 * @property string $display_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $photo_src
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Photo[] $photos
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Group whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Group whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Group whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Group whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Group wherePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Group whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
