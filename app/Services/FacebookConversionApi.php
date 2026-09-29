<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FacebookConversionApi
{
    protected $pixelId;
    protected $accessToken;

    public function __construct()
    {
        $this->pixelId = config('services.facebook.pixel_id');
        $this->accessToken = config('services.facebook.access_token');
    }

    public function sendEvent(
        string $eventName,
        array $userData = [],
        array $customData = [],
        ?string $eventId = null
    ) {
        $payload = [
            'data' => [
                [
                    'event_name' => $eventName,
                    'event_time' => time(),
                    'action_source' => 'website',
                    'event_id' => $eventId ?? uniqid(),
                    'user_data' => $userData,
                    'custom_data' => $customData,
                ]
            ]
        ];

        return Http::post(
            "https://graph.facebook.com/v23.0/{$this->pixelId}/events?access_token={$this->accessToken}",
            $payload
        );
    }
}