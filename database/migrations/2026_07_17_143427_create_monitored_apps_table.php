<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('monitored_apps', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url');
            $table->string('api_key')->nullable();
            $table->string('type')->nullable();
            $table->string('status')->default('unknown');
            $table->timestamp('last_ping_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('monitored_apps'); }
};
