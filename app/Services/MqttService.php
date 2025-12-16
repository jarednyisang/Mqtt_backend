<?php

namespace App\Services;

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use Illuminate\Support\Facades\Log;

class MqttService
{
    protected $client;
    protected $connectionSettings;

    public function __construct()
    {
        $this->client = new MqttClient(
            config('mqtt.host'),
            config('mqtt.port'),
            config('mqtt.client_id') . '_' . uniqid()
        );

        $this->connectionSettings = (new ConnectionSettings)
            ->setUsername(config('mqtt.username'))
            ->setPassword(config('mqtt.password'))
            ->setKeepAliveInterval(60)
            ->setLastWillTopic('chloride/status')
            ->setLastWillMessage('offline')
            ->setLastWillQualityOfService(1);
    }

    /**
     * Publish message to MQTT topic
     */
    public function publish($topic, $message, $qos = 1, $retain = false)
    {
        try {
            $this->client->connect($this->connectionSettings, true);
            
            $payload = is_array($message) ? json_encode($message) : $message;
            
            $this->client->publish($topic, $payload, $qos, $retain);
            
            $this->client->disconnect();
            
            Log::info("MQTT Published to {$topic}: {$payload}");
            
            return true;
        } catch (\Exception $e) {
            Log::error('MQTT Publish Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Publish battery status
     */
    public function publishBatteryStatus($batteryData)
    {
        return $this->publish('chloride/batteries/status', $batteryData);
    }

    /**
     * Publish solar data
     */
    public function publishSolarData($solarData)
    {
        return $this->publish('chloride/solar/data', $solarData);
    }

    /**
     * Publish notification to all users
     */
    public function publishNotification($message)
    {
        return $this->publish('chloride/notifications', [
            'message' => $message,
            'timestamp' => now()->toIso8601String()
        ]);
    }

    /**
     * Publish alert to specific user
     */
    public function publishUserAlert($userId, $alertMessage)
    {
        return $this->publish("chloride/user/{$userId}/alerts", [
            'alert' => $alertMessage,
            'timestamp' => now()->toIso8601String()
        ]);
    }
}