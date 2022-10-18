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
        Schema::table('comments', function (Blueprint $table) {
            if (env('DB_CONNECTION') != 'sqlite_for_tests') {
                $table->dropForeign(['blogpost_id']);
                $table->dropForeign(['author_id']);
            }

            $table->foreign('author_id')
            ->references('id')
            ->on('authors')
            ->cascadeOnDelete();

            $table->foreign('blogpost_id')
            ->references('id')
            ->on('blogposts')
            ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['blogpost_id']);
            $table->dropForeign(['author_id']);

            $table->foreign('author_id')
            ->references('id')
            ->on('authors');

            $table->foreign('blogpost_id')
            ->references('id')
            ->on('blogposts');
        });
    }
};
