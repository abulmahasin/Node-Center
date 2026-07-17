<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class MonitoredApp extends Model {
    use HasApiTokens;

    protected $guarded = ['id'];
    protected $casts = [
        'last_ping_at' => 'datetime',
        'last_active_ping_at' => 'datetime',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function incidents() {
        return $this->hasMany(AppIncident::class, 'monitored_app_id');
    }

    public function metrics() {
        return $this->hasMany(AppMetric::class);
    }
}
