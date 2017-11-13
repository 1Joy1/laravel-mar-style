<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUniqueIndexPhotoIdGroupName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_photo', function (Blueprint $table) {
            $table->unique(['group_name','photo_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('group_photo');

        Schema::create('group_photo', function (Blueprint $table) {
            $table->increments('id');

            $table->string('group_name', 50);
            $table->foreign('group_name')->references('name')->on('groups');

            $table->integer('photo_id')->unsigned();
            $table->foreign('photo_id')->references('id')->on('photos');

            $table->timestamps();
        });


    }
}
