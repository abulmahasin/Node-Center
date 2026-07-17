<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppController extends Controller
{
    public function index()
    {
        $apps = \App\Models\MonitoredApp::with(['metrics' => function($q) {
            $q->latest()->take(1);
        }])->get();
        return view('apps.index', compact('apps'));
    }

    public function show(\App\Models\MonitoredApp $app)
    {
        // Get metrics from the last 24 hours, ordered by oldest first so chart flows left to right
        $metrics = $app->metrics()
            ->where('created_at', '>=', now()->subHours(24))
            ->orderBy('created_at', 'asc')
            ->get();
            
        return view('apps.show', compact('app', 'metrics'));
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

        \App\Models\MonitoredApp::create($validated);

        return redirect()->route('apps.index')->with('success', 'Application registered successfully.');
    }

    public function edit(\App\Models\MonitoredApp $app)
    {
        return view('apps.edit', compact('app'));
    }

    public function update(Request $request, \App\Models\MonitoredApp $app)
    {
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
        $app->metrics()->delete();
        $app->tokens()->delete();
        $app->delete();
        
        return redirect()->route('apps.index')->with('success', 'Application deleted successfully.');
    }

    public function generateToken(\App\Models\MonitoredApp $app)
    {
        // Delete old tokens to keep it simple (one token per app)
        $app->tokens()->delete();
        
        $token = $app->createToken('monitoring-token')->plainTextToken;
        
        return redirect()->route('apps.index')->with('new_token', $token)->with('token_app_id', $app->id);
    }
}
