<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MqttService;

class PublishMqttData extends Command
{
    protected $signature = 'mqtt:publish-data';
    protected $description = 'Publish test data to MQTT every 5 seconds';

    public function handle(MqttService $mqtt)
    {
        $this->info('Starting MQTT data publisher...');
        
        while (true) {
            // Battery data
            $batteryData = [
                'voltage' => rand(110, 130) / 10 . 'V',
                'status' => ['Optimal', 'Charging', 'Good'][rand(0, 2)],
                'temperature' => rand(20, 35) . '°C',
                'timestamp' => now()->toIso8601String(),
            ];
            
            // Solar data
            $solarData = [
                'power' => rand(0, 100) / 10 . 'kW',
                'efficiency' => rand(60, 95) . '%',
                'status' => ['Active', 'Optimal'][rand(0, 1)],
                'timestamp' => now()->toIso8601String(),
            ];
            
            $mqtt->publishBatteryStatus($batteryData);
            $mqtt->publishSolarData($solarData);
            
            $this->info('Published: ' . now()->format('H:i:s'));
            
            sleep(5); // Wait 5 seconds
        }
    }
}