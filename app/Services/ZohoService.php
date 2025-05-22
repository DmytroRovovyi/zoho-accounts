<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ZohoService
{
    protected string $client_id;
    protected string $client_secret;
    protected string $refresh_token;
    protected string $token_endpoint = 'https://accounts.zoho.eu/oauth/v2/token';
    protected string $api_base_url = 'https://www.zohoapis.eu/crm/v2';

    public function __construct()
    {
        $this->client_id = config('services.zoho.client_id');
        $this->client_secret = config('services.zoho.client_secret');
        $this->refresh_token = config('services.zoho.refresh_token');
    }

    /**
     * Get access token, from cache or refresh.
     */
    protected function getAccessToken()
    {
        return Cache::remember('zoho_access_token', 3000, function () {
            return $this->refreshAccessToken();
        });
    }

    /**
     * Update access token via refresh token.
     */
    protected function refreshAccessToken()
    {
        $response = Http::asForm()->post($this->token_endpoint, [
            'refresh_token' => $this->refresh_token,
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'grant_type' => 'refresh_token',
        ]);

        if ($response->failed()) {
            Log::error('Zoho token refresh failed', ['response' => $response->body()]);
            throw new \Exception('Cannot refresh Zoho access token');
        }

        $data = $response->json();
        if (!isset($data['access_token'])) {
            throw new \Exception('Invalid Zoho token response');
        }

        return $data['access_token'];
    }

    /**
     * Headers for API requests.
     */
    protected function getHeaders()
    {
        return [
            'Authorization' => 'Zoho-oauthtoken ' . $this->getAccessToken(),
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * Create Deal + Account.
     */
    public function createDealAndAccount(array $data)
    {
        $accountPayload = [
            'data' => [
                [
                    'Account_Name' => $data['accountName'],
                    'Website' => $data['accountWebsite'],
                    'Phone' => $data['accountPhone'],
                ],
            ],
        ];

        $accountResponse = Http::withHeaders($this->getHeaders())
            ->post($this->api_base_url . '/Accounts', $accountPayload);

        if ($accountResponse->failed()) {
            Log::error('Zoho Account creation failed', ['response' => $accountResponse->body()]);
            throw new \Exception('Failed to create Account');
        }

        $accountData = $accountResponse->json();

        if (!isset($accountData['data'][0]['details']['id'])) {
            throw new \Exception('Account creation response missing ID');
        }

        $accountId = $accountData['data'][0]['details']['id'];

        $dealPayload = [
            'data' => [
                [
                    'Deal_Name' => $data['dealName'],
                    'Stage' => $data['dealStage'],
                    'Account_Name' => ['id' => $accountId],
                ],
            ],
        ];

        $dealResponse = Http::withHeaders($this->getHeaders())
            ->post($this->api_base_url . '/Deals', $dealPayload);

        if ($dealResponse->failed()) {
            Log::error('Zoho Deal creation failed', ['response' => $dealResponse->body()]);
            throw new \Exception('Failed to create Deal');
        }

        return $dealResponse->json();
    }
}
