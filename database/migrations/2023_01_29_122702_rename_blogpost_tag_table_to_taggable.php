<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blogpost_tag', function (Blueprint $table) {
            $table->dropForeign(['blogpost_id']);
            $table->dropColumn('blogpost_id');
        });

        Schema::rename('blogpost_tag', 'taggables');

        Schema::table('taggables', function (Blueprint $table) {
            $table->morphs('taggable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('taggables', function (Blueprint $table) {
            $table->dropMorphs('taggable');
        });

        Schema::rename('taggables', 'blogpost_tag');

        Schema::disableForeignKeyConstraints();

        Schema::table('blogpost_tag', function (Blueprint $table) {
            $table->foreignId('blogpost_id')->constrained()->cascadeOnDelete();
        });

        Schema::enableForeignKeyConstraints();
    }
};
