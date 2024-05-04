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
        Schema::create('wp_post_infos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('post_id');
            $table->string('post_name');
            $table->string('post_url');
            $table->uuid('Config_id');

            $table->foreign('Config_id')
            ->references('id')
            ->on('wp_post_contents')
            ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wp_post_infos');
    }
};
