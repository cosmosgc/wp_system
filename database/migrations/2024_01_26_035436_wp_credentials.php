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
        //
        Schema::create('wp_credentials', function (Blueprint $table){
            $table->uuid('id')->primary();
            $table->uuid('Editor_id');
            $table->string("wp_login");
            $table->string("wp_password");
            $table->string("wp_domain");
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
        Schema::dropIfExists('wp_credentials');
    }
};
