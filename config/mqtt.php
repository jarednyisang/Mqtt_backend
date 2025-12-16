<?php

return [
    'host' => env('MQTT_HOST', 'broker.hivemq.com'),
    'port' => env('MQTT_PORT', 1883),
    'username' => env('MQTT_USERNAME', null),
    'password' => env('MQTT_PASSWORD', null),
    'client_id' => env('MQTT_CLIENT_ID', 'laravel_' . uniqid()),
    'clean_session' => env('MQTT_CLEAN_SESSION', true),
];