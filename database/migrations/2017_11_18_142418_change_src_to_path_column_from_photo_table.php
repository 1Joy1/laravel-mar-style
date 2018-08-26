<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Photo;

class ChangeSrcToPathColumnFromPhotoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $photos = Photo::all();

        foreach ($photos as $photo) {
            $photo->delete();
        }

        Schema::table('photos', function (Blueprint $table) {
            $table->dropUnique('photos_src_unique');
            $table->dropUnique('photos_src_midi_unique');
            $table->dropUnique('photos_src_mini_unique');
            $table->dropUnique('photos_src_mini_thumb_unique');

            $table->dropColumn(['src', 'src_midi', 'src_mini', 'src_mini_thumb']);

            $table->string('big_photo_path', 100)->unique();
            $table->string('midi_photo_path', 100)->unique();
            $table->string('mini_photo_path', 100)->unique();
            $table->string('thumb_photo_path', 100)->unique();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $photos = Photo::all();

        foreach ($photos as $photo) {
            $photo->delete();
        }

        Schema::table('photos', function (Blueprint $table) {
            $table->dropUnique('photos_big_photo_path_unique');
            $table->dropUnique('photos_midi_photo_path_unique');
            $table->dropUnique('photos_mini_photo_path_unique');
            $table->dropUnique('photos_thumb_photo_path_unique');

            $table->dropColumn(['big_photo_path', 'midi_photo_path', 'mini_photo_path','thumb_photo_path']);

            $table->string('src', 100)->unique();
            $table->string('src_midi', 100)->unique();
            $table->string('src_mini', 100)->unique();
            $table->string('src_mini_thumb', 100)->unique();
        });
    }
}
