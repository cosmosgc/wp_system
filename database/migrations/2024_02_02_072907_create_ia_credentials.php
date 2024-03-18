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
        Schema::create('ia_credentials', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string("open_ai");
            $table->string("language");
            $table->string("writing_style");
            $table->string("writing_tone");
            $table->integer("sections");
            $table->integer("pagraphs");
            $table->uuid('Editor_id');
            $table->timestamps();
            $table->foreign('Editor_id')
            ->references('id')
            ->on('editors')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ia_credentials');
    }
};
