<?php

namespace App\Services;

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

class MqttService
{
    protected $client;
    protected $connectionSettings;

    public function __construct()
    {
        $this->client = new MqttClient(
            config('mqtt.host'),
            config('mqtt.port'),
            config('mqtt.client_id')
        );

        $this->connectionSettings = (new ConnectionSettings)
            ->setUsername(config('mqtt.username'))
            ->setPassword(config('mqtt.password'))
            ->setKeepAliveInterval(60)
            ->setCleanSession(config('mqtt.clean_session'));
    }

    public function publish($topic, $message, $qos = 0, $retain = false)
    {
        try {
            $this->client->connect($this->connectionSettings);
            
            $payload = is_array($message) ? json_encode($message) : $message;
            
            $this->client->publish($topic, $payload, $qos, $retain);
            
            $this->client->disconnect();
            
            return true;
        } catch (\Exception $e) {
            \Log::error('MQTT Publish Error: ' . $e->getMessage());
            return false;
        }
    }

    public function subscribe($topic, $callback, $qos = 0)
    {
        try {
            $this->client->connect($this->connectionSettings);
            
            $this->client->subscribe($topic, $callback, $qos);
            
            $this->client->loop(true);
            
        } catch (\Exception $e) {
            \Log::error('MQTT Subscribe Error: ' . $e->getMessage());
        }
    }
}