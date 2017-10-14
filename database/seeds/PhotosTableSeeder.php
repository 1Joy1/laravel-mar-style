<?php

use Illuminate\Database\Seeder;

class PhotosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(App\Photo::class, 20)->create()->each(function ($u) {

            $group = App\Group::where('id', random_int(1, 4))->first();

            $u->groups()->save($group)->make();
        });
    }
}
