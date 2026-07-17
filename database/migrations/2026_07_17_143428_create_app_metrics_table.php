<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('app_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monitored_app_id')->constrained()->cascadeOnDelete();
            $table->float('cpu_usage')->nullable();
            $table->float('memory_usage')->nullable();
            $table->integer('active_users')->nullable();
            $table->float('response_time')->nullable();
            $table->float('error_rate')->nullable();
            
            // Comprehensive metrics
            $table->integer('db_latency')->nullable();
            $table->integer('cache_latency')->nullable();
            $table->float('disk_usage')->nullable();
            $table->integer('pending_jobs')->nullable();
            $table->integer('failed_jobs')->nullable();
            $table->integer('error_count')->nullable();
            
            // Framework Info
            $table->string('php_version')->nullable();
            $table->string('laravel_version')->nullable();
            $table->string('app_env')->nullable();
            
            // Detailed JSON Metrics
            $table->json('error_details')->nullable();
            $table->json('queue_details')->nullable();
            $table->json('schedule_details')->nullable();
            $table->json('slow_queries')->nullable();
            $table->json('security_warnings')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('app_metrics'); }
};
