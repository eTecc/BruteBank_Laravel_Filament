<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('brutebank_settings', 'api_url')) {
            Schema::table('brutebank_settings', function (Blueprint $table) {
                $table->dropColumn('api_url');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('brutebank_settings', 'api_url')) {
            Schema::table('brutebank_settings', function (Blueprint $table) {
                $table->string('api_url')->nullable();
            });
        }
    }
};
