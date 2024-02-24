<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wp_post_contents', function (Blueprint $table) {
            $table->id();
            $table->string("theme")->nullable();
            $table->string("keyword")->nullable();
            $table->string("category")->nullable();
            $table->string("anchor_1")->nullable();
            $table->string("url_link_1")->nullable();
            $table->boolean("do_follow_link_1")->default(1);
            $table->string("anchor_2")->nullable();
            $table->string("url_link_2")->nullable();
            $table->boolean("do_follow_link_2")->default(1);
            $table->string("anchor_3")->nullable();
            $table->boolean("do_follow_link_3")->default(1);
            $table->string("url_link_3")->nullable();
            $table->string("post_image")->nullable();
            $table->string("internal_link")->nullable();
            $table->text('post_content')->nullable();
            $table->boolean("insert_image")->default(1);
            $table->string("status")->nullable();
            $table->string("schedule_date")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wp_post_contents');
    }
};
