<?php

namespace App\Services;

use App\Models\PathaoSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PathaoService
{
    protected $settings;
    protected $baseUrl;

    public function __construct()
    {
        $this->settings = PathaoSetting::getActiveSettings();
        $this->baseUrl = $this->settings ? $this->settings->base_url : 'https://courier-api-sandbox.pathao.com';
    }

    /**
     * Get access token from Pathao API
     */
    public function getAccessToken()
    {
        if (!$this->settings) {
            throw new \Exception('Pathao settings not configured');
        }

        // Check if we have a valid token
        if ($this->settings->access_token && !$this->settings->isTokenExpired()) {
            return $this->settings->access_token;
        }

        // Try to refresh token if we have refresh token
        if ($this->settings->refresh_token && !$this->settings->isTokenExpired()) {
            try {
                return $this->refreshAccessToken();
            } catch (\Exception $e) {
                Log::error('Failed to refresh Pathao token: ' . $e->getMessage());
            }
        }

        // Get new token
        $response = Http::withHeaders(['Content-Type' => 'application/json'])->post($this->baseUrl . '/aladdin/api/v1/issue-token', [
            'client_id' => $this->settings->client_id,
            'client_secret' => $this->settings->client_secret,
            'grant_type' => $this->settings->grant_type,
            'username' => $this->settings->username,
            'password' => $this->settings->password
        ]);

        if ($response->successful()) {
            $data = $response->json();
            
            $this->settings->update([
                'access_token' => $data['access_token'],
                'refresh_token' => $data['refresh_token'],
                'token_expires_at' => now()->addSeconds($data['expires_in'])
            ]);

            return $data['access_token'];
        }

        throw new \Exception('Failed to get access token: ' . $response->body());
    }

    /**
     * Refresh access token using refresh token
     */
    public function refreshAccessToken()
    {
        if (!$this->settings->refresh_token) {
            throw new \Exception('No refresh token available');
        }

        $response = Http::withHeaders(['Content-Type' => 'application/json'])->post($this->baseUrl . '/aladdin/api/v1/issue-token', [
            'client_id' => $this->settings->client_id,
            'client_secret' => $this->settings->client_secret,
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->settings->refresh_token
        ]);

        if ($response->successful()) {
            $data = $response->json();
            
            $this->settings->update([
                'access_token' => $data['access_token'],
                'refresh_token' => $data['refresh_token'],
                'token_expires_at' => now()->addSeconds($data['expires_in'])
            ]);

            return $data['access_token'];
        }

        throw new \Exception('Failed to refresh access token: ' . $response->body());
    }

    /**
     * Get list of cities
     */
    public function getCities()
    {
        $token = $this->getAccessToken();
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        ])->get($this->baseUrl . '/aladdin/api/v1/city-list');

        if ($response->successful()) {
            return $response->json()['data']['data'] ?? [];
        }

        throw new \Exception('Failed to get cities: ' . $response->body());
    }

    /**
     * Get zones by city ID
     */
    public function getZones($cityId)
    {
        $token = $this->getAccessToken();
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        ])->get($this->baseUrl . "/aladdin/api/v1/cities/{$cityId}/zone-list");

        if ($response->successful()) {
            return $response->json()['data']['data'] ?? [];
        }

        throw new \Exception('Failed to get zones: ' . $response->body());
    }

    /**
     * Get areas by zone ID
     */
    public function getAreas($zoneId)
    {
        $token = $this->getAccessToken();
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        ])->get($this->baseUrl . "/aladdin/api/v1/zones/{$zoneId}/area-list");

        if ($response->successful()) {
            return $response->json()['data']['data'] ?? [];
        }

        throw new \Exception('Failed to get areas: ' . $response->body());
    }

    /**
     * Calculate delivery price
     */
    public function calculatePrice($data)
    {
        $token = $this->getAccessToken();
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        ])->post($this->baseUrl . '/aladdin/api/v1/merchant/price-plan', $data);

        if ($response->successful()) {
            return $response->json()['data'] ?? null;
        }

        throw new \Exception('Failed to calculate price: ' . $response->body());
    }

    /**
     * Create a single order
     */
    public function createOrder($orderData)
    {
        $token = $this->getAccessToken();
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        ])->post($this->baseUrl . '/aladdin/api/v1/orders', $orderData);

        if ($response->successful()) {
            return $response->json();
        }

        if ($response->status() == 502) {
            throw new \Exception('Pathao API is currently unavailable. Please try again later.');
        }

        throw new \Exception('Failed to create order: ' . $response->body());
    }

    /**
     * Create bulk orders
     */
    public function createBulkOrders($ordersData)
    {
        $token = $this->getAccessToken();
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        ])->post($this->baseUrl . '/aladdin/api/v1/orders/bulk', $ordersData);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Failed to create bulk orders: ' . $response->body());
    }

    /**
     * Get order info
     */
    public function getOrderInfo($consignmentId)
    {
        $token = $this->getAccessToken();
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        ])->get($this->baseUrl . "/aladdin/api/v1/orders/{$consignmentId}/info");

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Failed to get order info: ' . $response->body());
    }

    /**
     * Create a new store
     */
    public function createStore($storeData)
    {
        $token = $this->getAccessToken();
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        ])->post($this->baseUrl . '/aladdin/api/v1/stores', $storeData);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Failed to create store: ' . $response->body());
    }

    /**
     * Get store information
     */
    public function getStores()
    {
        $token = $this->getAccessToken();
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        ])->get($this->baseUrl . '/aladdin/api/v1/stores');

        if ($response->successful()) {
            return $response->json()['data']['data'] ?? [];
        }

        throw new \Exception('Failed to get stores: ' . $response->body());
    }
}
