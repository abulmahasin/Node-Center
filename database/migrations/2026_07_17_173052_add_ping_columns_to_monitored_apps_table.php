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
            $table->string('ping_status')->default('unknown');
            $table->integer('ping_response_time')->nullable();
            $table->text('ping_error')->nullable();
            $table->timestamp('last_active_ping_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monitored_apps', function (Blueprint $table) {
            $table->dropColumn(['ping_status', 'ping_response_time', 'ping_error', 'last_active_ping_at']);
        });
    }
};
