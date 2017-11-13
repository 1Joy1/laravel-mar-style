<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameColumnSrcToPathGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropUnique('groups_photo_src_unique');
            $table->renameColumn('photo_src', 'photo_path');
            $table->unique('photo_path', 'groups_photo_path_unique');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropUnique('groups_photo_path_unique');
            $table->renameColumn('photo_path', 'photo_src');
            $table->unique('photo_src', 'groups_photo_src_unique');
        });
    }
}
