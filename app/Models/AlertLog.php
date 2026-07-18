<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AlertLog extends Model {
    protected $guarded = ['id'];

    public function app() {
        return $this->belongsTo(MonitoredApp::class, 'monitored_app_id');
    }
}
