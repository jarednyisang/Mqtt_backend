<?php

namespace App\Http\Controllers;

use App\Services\MqttService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MqttController extends Controller
{
    protected $mqtt;

    public function __construct(MqttService $mqtt)
    {
        $this->mqtt = $mqtt;
    }

    /**
     * Update battery status and publish to MQTT
     */
    public function updateBatteryStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'voltage' => 'required|string',
            'status' => 'required|string',
            'temperature' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $batteryData = [
            'voltage' => $request->voltage,
            'status' => $request->status,
            'temperature' => $request->temperature,
            'timestamp' => now()->toIso8601String(),
        ];

        // Save to database if needed
        // Battery::create($batteryData);

        // Publish to MQTT
        $published = $this->mqtt->publishBatteryStatus($batteryData);

        return response()->json([
            'success' => $published,
            'message' => $published ? 'Battery status updated' : 'Failed to publish',
            'data' => $batteryData
        ]);
    }

    /**
     * Update solar data and publish to MQTT
     */
    public function updateSolarData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'power' => 'required|string',
            'efficiency' => 'required|string',
            'status' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $solarData = [
            'power' => $request->power,
            'efficiency' => $request->efficiency,
            'status' => $request->status,
            'timestamp' => now()->toIso8601String(),
        ];

        // Save to database if needed
        // SolarPanel::create($solarData);

        // Publish to MQTT
        $published = $this->mqtt->publishSolarData($solarData);

        return response()->json([
            'success' => $published,
            'message' => $published ? 'Solar data updated' : 'Failed to publish',
            'data' => $solarData
        ]);
    }

    /**
     * Send notification to all users
     */
    public function sendNotification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $published = $this->mqtt->publishNotification($request->message);

        return response()->json([
            'success' => $published,
            'message' => $published ? 'Notification sent' : 'Failed to send notification'
        ]);
    }

    /**
     * Send alert to specific user
     */
    public function sendUserAlert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'alert' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $published = $this->mqtt->publishUserAlert(
            $request->user_id,
            $request->alert
        );

        return response()->json([
            'success' => $published,
            'message' => $published ? 'Alert sent to user' : 'Failed to send alert'
        ]);
    }

    /**
     * Simulate real-time data (for testing)
     */
    public function simulateData()
    {
        // Simulate battery data
        $batteryData = [
            'voltage' => rand(110, 130) / 10 . 'V',
            'status' => ['Optimal', 'Charging', 'Good'][rand(0, 2)],
            'temperature' => rand(20, 35) . '°C',
            'timestamp' => now()->toIso8601String(),
        ];

        // Simulate solar data
        $solarData = [
            'power' => rand(0, 100) / 10 . 'kW',
            'efficiency' => rand(60, 95) . '%',
            'status' => ['Active', 'Optimal', 'Good'][rand(0, 2)],
            'timestamp' => now()->toIso8601String(),
        ];

        $this->mqtt->publishBatteryStatus($batteryData);
        $this->mqtt->publishSolarData($solarData);

        return response()->json([
            'success' => true,
            'message' => 'Simulated data published',
            'battery' => $batteryData,
            'solar' => $solarData
        ]);
    }
}