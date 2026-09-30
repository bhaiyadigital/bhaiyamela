<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class FacebookConversionApi
{
    /**
     * Send an event to Facebook Conversions API
     *
     * @param string $eventName e.g., 'PageView', 'Lead', 'ViewContent'
     * @param string|null $eventId Unique ID for the event to deduplicate with browser pixel
     * @param array $customData e.g., ['currency' => 'USD', 'value' => 100]
     * @param array $userData Optional user data to override defaults
     * @return void
     */
    public static function sendEvent(string $eventName, ?string $eventId = null, array $customData = [], array $userData = [])
    {
        $pixelId = config('services.facebook.pixel_id');
        $token = config('services.facebook.access_token'); // matching bhaiya-housing-banckend config key

        \Illuminate\Support\Facades\Log::info("Facebook CAPI Debug: sendEvent called for {$eventName}. Pixel: " . ($pixelId ?? 'NULL') . ", Token: " . ($token ? 'YES' : 'NULL'));

        if (!$pixelId || !$token) {
            \Illuminate\Support\Facades\Log::warning("Facebook CAPI aborted: Missing Pixel ID or Token.");
            return;
        }

        $defaultUserData = [
            'client_ip_address' => Request::ip(),
            'client_user_agent' => Request::userAgent(),
            'fbc' => request()->cookie('_fbc'),
            'fbp' => request()->cookie('_fbp'),
        ];

        // Hash any PII data (email, phone, etc.) if provided in $userData
        foreach (['em', 'ph', 'fn', 'ln'] as $piiField) {
            if (isset($userData[$piiField]) && !empty($userData[$piiField])) {
                $userData[$piiField] = hash('sha256', strtolower(trim($userData[$piiField])));
            } else {
                unset($userData[$piiField]);
            }
        }

        $finalUserData = array_filter(array_merge($defaultUserData, $userData));

        $eventData = [
            'event_name' => $eventName,
            'event_time' => time(),
            'action_source' => 'website',
            'event_source_url' => Request::url(),
            'user_data' => $finalUserData,
        ];

        if ($eventId) {
            $eventData['event_id'] = $eventId;
        }

        if (!empty($customData)) {
            $eventData['custom_data'] = $customData;
        }

        $payload = [
            'data' => [$eventData]
        ];

        $testEventCode = config('services.facebook.test_event_code');
        if (!empty($testEventCode)) {
            $payload['test_event_code'] = $testEventCode;
        }

        try {
            $response = Http::timeout(3)
                ->withToken($token)
                ->post("https://graph.facebook.com/v19.0/{$pixelId}/events", $payload);
                
            if ($response->successful()) {
                Log::info("Facebook CAPI Success: {$eventName} sent. Event ID: {$eventId}");
            } else {
                Log::error("Facebook CAPI Failed: " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Facebook CAPI Error: ' . $e->getMessage());
        }
    }
}