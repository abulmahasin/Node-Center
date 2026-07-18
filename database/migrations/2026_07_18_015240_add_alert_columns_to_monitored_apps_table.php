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
            $table->string('telegram_chat_id')->nullable()->after('ssl_issuer');
            $table->string('alert_email')->nullable()->after('telegram_chat_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monitored_apps', function (Blueprint $table) {
            $table->dropColumn(['telegram_chat_id', 'alert_email']);
        });
    }
};
