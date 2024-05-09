<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProjectIdToWpPostContentsTable extends Migration
{
    public function up()
    {
        Schema::table('wp_post_contents', function (Blueprint $table) {
            $table->foreign('Project_id')
                ->references('id')
                ->on('projects')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('wp_post_contents', function (Blueprint $table) {
            $table->dropForeign(['Project_id']);
        });
    }
}
