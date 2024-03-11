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
        Schema::create('drive_credential', function (Blueprint $table) {
            $table->id();
            $table->string('client_id');
            $table->string('project_id');
            $table->string('uth_uri');
            $table->string('token_uri');
            $table->string('auth_provider_x509_cert_url');
            $table->string('client_secret');
            $table->string('redirect_uris');
            $table->string('api_key');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drive_credential');
    }
};
