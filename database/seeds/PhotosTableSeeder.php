<?php

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use App\Photo;

class PhotosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Schema::hasColumn('photos', 'big_photo_path')) {

            $files = Storage::disk('public')->files('img/gallery/big/');

            foreach ($files as $file) {

                $group = App\Group::where('id', random_int(1, 4))->first();

                Photo::create([
                    'big_photo_path' => $file,
                    'midi_photo_path' => str_replace('big', 'midi', $file),
                    'mini_photo_path' => str_replace('big', 'mini', $file),
                    'thumb_photo_path' => str_replace('big', 'thumb', $file),
                    'active' => true,
                ])->groups()->attach($group);

            }
        } elseif (Schema::hasColumn('photos', 'src')) {

            factory(App\Photo::class, 20)->create()->each(function ($u) {

                $group = App\Group::where('id', random_int(1, 4))->first();

                $u->groups()->save($group)->make();
            });
        }
    }
}
