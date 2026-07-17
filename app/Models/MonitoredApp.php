<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class MonitoredApp extends Model {
    use HasApiTokens;

    protected $guarded = [];
    protected $casts = [
        'last_ping_at' => 'datetime',
    ];

    public function metrics() {
        return $this->hasMany(AppMetric::class);
    }
}
