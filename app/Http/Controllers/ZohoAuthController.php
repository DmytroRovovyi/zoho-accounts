<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ZohoAuthController extends Controller
{

    /**
     * Handles the OAuth2 callback from Zoho after user authorization.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     * @throws \Illuminate\Http\Client\ConnectionException When HTTP connection fails
     */
    public function handleCallback(Request $request)
    {
        $code = $request->query('code');

        if (!$code) {
            return response('Authorization code not found', 400);
        }

        $client_id = config('services.zoho.client_id');
        $client_secret = config('services.zoho.client_secret');
        $redirect_uri = config('services.zoho.redirect_uri');

        $tokenUrl = 'https://accounts.zoho.eu/oauth/v2/token';

        $response = Http::asForm()->post($tokenUrl, [
            'grant_type' => 'authorization_code',
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'redirect_uri' => $redirect_uri,
            'code' => $code,
        ]);

        $tokens = $response->json();

        return response()->json($tokens);
    }

    /**
     * Refreshes the Zoho access token using the stored refresh token.
     *
     * Sends a POST request to Zoho OAuth2 token endpoint to obtain a new access token.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     * @throws \Illuminate\Http\Client\ConnectionException When HTTP connection fails
     */
    public function refreshAccessToken()
    {
        $client_id = config('services.zoho.client_id');
        $client_secret = config('services.zoho.client_secret');
        $refresh_token = config('services.zoho.refresh_token');

        $response = Http::asForm()->post('https://accounts.zoho.eu/oauth/v2/token', [
            'refresh_token' => $refresh_token,
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'grant_type' => 'refresh_token',
        ]);

        if ($response->failed()) {
            Log::error('Zoho token refresh error', ['response' => $response->body()]);
            return response('Failed to refresh token: ' . $response->body(), 500);
        }

        $data = $response->json();

        return response()->json($data);
    }
}
