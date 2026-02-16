<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function show(Request $request)
    {
        $settings = $request->user()->settings;
        
        if (!$settings) {
            return response()->json([
                'yandex_maps_url' => null
            ]);
        }
        
        return response()->json($settings);
    }

    public function store(Request $request)
    {
        $request->validate([
            'yandex_maps_url' => 'required|url'
        ]);

        $settings = $request->user()->settings()->updateOrCreate(
            ['user_id' => $request->user()->id],
            ['yandex_maps_url' => $request->yandex_maps_url]
        );

        return response()->json([
            'success' => true,
            'settings' => $settings
        ]);
    }
}
