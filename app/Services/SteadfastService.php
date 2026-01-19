<?php

namespace App\Services;

use App\Models\SteadfastSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SteadfastService
{
    protected $baseUrl;
    protected $apiKey;
    protected $secretKey;
    protected $settings;

    public function __construct()
    {
        $this->settings = SteadfastSetting::first();
       

        $this->baseUrl = rtrim($this->settings?->base_url, '/');
        $this->apiKey = $this->settings?->api_key;
        $this->secretKey = $this->settings?->secret_key;
    }

    /**
     * Get headers for Steadfast API requests
     */
    protected function getHeaders()
    {
        return [
            'Api-Key' => $this->apiKey,
            'Secret-Key' => $this->secretKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    /**
     * Create a single order
     */
    public function placeOrder(array $orderData)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->post($this->baseUrl . '/create_order', $orderData);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Steadfast API Error', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            throw new \Exception('Failed to create order: ' . ($response->json()['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            Log::error('Steadfast API Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Create bulk orders
     */
    public function bulkCreateOrders(array $orders)
    {
        try {
            Log::debug('Steadfast bulk request payload', [
                'order_count' => count($orders),
                'data' => $orders
            ]);

            $response = Http::withHeaders($this->getHeaders())
                ->post($this->baseUrl . '/create_order/bulk-order', [
                    'data' => json_encode($orders)
                ]);

            $responseData = $response->json();

            Log::debug('Steadfast bulk response', [
                'status' => $response->status(),
                'body' => $responseData
            ]);

            if ($response->successful()) {
                return $responseData;
            }

            throw new \Exception('Failed to create bulk orders: ' . ($responseData['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            Log::error('Steadfast Bulk API Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Check delivery status by consignment ID
     */
    public function checkDeliveryStatusByConsignmentId($consignmentId)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get($this->baseUrl . '/status_by_cid/' . $consignmentId);

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Failed to get status: ' . ($response->json()['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            Log::error('Steadfast Status API Exception', [
                'consignment_id' => $consignmentId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Check delivery status by invoice
     */
    public function checkStatusByInvoice(string $invoice)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get($this->baseUrl . '/status_by_invoice/' . urlencode($invoice));

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Failed to get status by invoice: ' . ($response->json()['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            Log::error('Steadfast StatusByInvoice API Exception', [
                'invoice' => $invoice,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Get current balance
     */
    public function getCurrentBalance()
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get($this->baseUrl . '/get_balance');

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Failed to get balance: ' . ($response->json()['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            Log::error('Steadfast Balance API Exception', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
