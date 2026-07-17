<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppIncident extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'started_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function app()
    {
        return $this->belongsTo(MonitoredApp::class, 'monitored_app_id');
    }
}
