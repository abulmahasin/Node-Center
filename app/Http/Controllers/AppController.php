<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppController extends Controller
{
    public function index()
    {
        $apps = auth()->user()->apps()->with(['metrics' => function($q) {
            $q->latest()->take(1);
        }])->get();
        return view('apps.index', compact('apps'));
    }

    public function show(Request $request, \App\Models\MonitoredApp $app)
    {
        if ($app->user_id !== auth()->id()) abort(403);
        
        $range = $request->query('range', '24h');
        
        if ($range === '7d') {
            $metrics = $app->metrics()
                ->selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d %H:00:00") as hour, 
                             AVG(cpu_usage) as cpu_usage, 
                             AVG(memory_usage) as memory_usage, 
                             AVG(db_latency) as db_latency, 
                             AVG(cache_latency) as cache_latency, 
                             MAX(active_users) as active_users,
                             MIN(created_at) as created_at')
                ->where('created_at', '>=', now()->subDays(7))
                ->groupBy('hour')
                ->orderByRaw('MIN(created_at) asc')
                ->get();
        } elseif ($range === '30d') {
            $metrics = $app->metrics()
                ->selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d") as day, 
                             AVG(cpu_usage) as cpu_usage, 
                             AVG(memory_usage) as memory_usage, 
                             AVG(db_latency) as db_latency, 
                             AVG(cache_latency) as cache_latency, 
                             MAX(active_users) as active_users,
                             MIN(created_at) as created_at')
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('day')
                ->orderByRaw('MIN(created_at) asc')
                ->get();
        } else {
            $metrics = $app->metrics()
                ->where('created_at', '>=', now()->subHours(24))
                ->orderBy('created_at', 'asc')
                ->get();
        }
            
        return view('apps.show', compact('app', 'metrics', 'range'));
    }

    public function create()
    {
        return view('apps.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:255',
            'type' => 'nullable|string|max:50',
        ]);

        auth()->user()->apps()->create($validated);

        return redirect()->route('apps.index')->with('success', 'Application registered successfully.');
    }

    public function edit(\App\Models\MonitoredApp $app)
    {
        if ($app->user_id !== auth()->id()) abort(403);
        return view('apps.edit', compact('app'));
    }

    public function update(Request $request, \App\Models\MonitoredApp $app)
    {
        if ($app->user_id !== auth()->id()) abort(403);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:255',
            'type' => 'nullable|string|max:50',
        ]);

        $app->update($validated);

        return redirect()->route('apps.index')->with('success', 'Application updated successfully.');
    }

    public function destroy(\App\Models\MonitoredApp $app)
    {
        if ($app->user_id !== auth()->id()) abort(403);
        
        $app->metrics()->delete();
        $app->tokens()->delete();
        $app->delete();
        
        return redirect()->route('apps.index')->with('success', 'Application deleted successfully.');
    }

    public function generateToken(\App\Models\MonitoredApp $app)
    {
        if ($app->user_id !== auth()->id()) abort(403);
        
        // Delete old tokens to keep it simple (one token per app)
        $app->tokens()->delete();
        
        $token = $app->createToken('monitoring-token')->plainTextToken;
        
        return redirect()->route('apps.index')->with('new_token', $token)->with('token_app_id', $app->id);
    }
}
