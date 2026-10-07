<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brutebank_settings', function (Blueprint $table) {
            $table->id();
            $table->string('api_url')->nullable();
            $table->string('public_key')->nullable();
            $table->text('secret_key')->nullable();
            $table->boolean('enabled')->default(false);
            $table->boolean('two_factor_enabled')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brutebank_settings');
    }
};
