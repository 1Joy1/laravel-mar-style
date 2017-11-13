<?php

use Illuminate\Database\Seeder;
use App\Group;

class GroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        Group::create([
                    'name' => 'studya',
                    'photo_path' => 'img/group/gr_stud.jpg',
                    'display_name' => 'Работа в студии',
                ]);
        Group::create([
                    'name' => 'wedding',
                    'photo_path' => 'img/group/gr_wedd.jpg',
                    'display_name' => 'Свадебный макияж, причёска',
                ]);
        Group::create([
                    'name' => 'evning',
                    'photo_path' => 'img/group/gr_evn.jpg',
                    'display_name' => 'Вечерний макияж, причёска',
                ]);
        Group::create([
                    'name' => 'age',
                    'photo_path' => 'img/group/gr_age.jpg',
                    'display_name' => 'Возрастной макияж',
                ]);
    }
}
