<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function show()
    {
        try {
            $setting = Setting::first();

            // Return success response
            return response()->json($setting, 201);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            
            $data = $request->all();
            $setting = Setting::first() ?? new Setting();
            $setting->mehul_opening_balance = $data['mehul_opening_balance'];
            $setting->bhavin_opening_balance = $data['bhavin_opening_balance'];
            $setting->save();

            // Return success response
            return response()->json(['message' => 'Setting updated successfully!', 'data' => $setting], 201);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }
}
