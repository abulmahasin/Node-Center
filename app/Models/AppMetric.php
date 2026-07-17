<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AppMetric extends Model {
    protected $guarded = [];
    protected $casts = [
        'error_details' => 'array',
        'queue_details' => 'array',
        'schedule_details' => 'array',
        'slow_queries' => 'array',
        'security_warnings' => 'array',
    ];
    public function app() {
        return $this->belongsTo(MonitoredApp::class, 'monitored_app_id');
    }
}
