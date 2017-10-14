<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupPhotoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_photo', function (Blueprint $table) {
            $table->increments('id');

            $table->string('group_name', 100);
            $table->foreign('group_name')->references('name')->on('groups');

            $table->integer('photo_id')->unsigned();
            $table->foreign('photo_id')->references('id')->on('photos');

            $table->timestamps();
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
    }
}
