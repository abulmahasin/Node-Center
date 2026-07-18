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
        Schema::table('monitored_apps', function (Blueprint $table) {
            $table->dateTime('ssl_expires_at')->nullable()->after('ping_error');
            $table->string('ssl_issuer')->nullable()->after('ssl_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monitored_apps', function (Blueprint $table) {
            $table->dropColumn(['ssl_expires_at', 'ssl_issuer']);
        });
    }
};
