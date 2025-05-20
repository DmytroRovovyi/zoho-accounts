<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ZohoAuthController extends Controller
{
    public function handleCallback(Request $request)
    {
        $code = $request->query('code');

        if (!$code) {
            return response('Authorization code not found', 400);
        }
        $client_id = config('services.zoho.client_id');
        $client_secret = config('services.zoho.client_secret');
        $redirect_uri = config('services.zoho.redirect_uri');

        $response = Http::asForm()->post('https://accounts.zoho.com/oauth/v2/token', [
            'grant_type' => 'authorization_code',
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'redirect_uri' => $redirect_uri,
            'code' => $code,
        ]);

        if ($response->failed()) {
            Log::error('Zoho token error', ['response' => $response->body()]);
            return response('Failed to get tokens: ' . $response->body(), 500);
        }

        $data = $response->json();

        return response()->json($data);
    }

}
