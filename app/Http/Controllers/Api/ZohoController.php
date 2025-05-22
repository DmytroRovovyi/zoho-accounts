<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ZohoService;

class ZohoController extends Controller
{
    protected $zohoService;

    public function __construct(ZohoService $zohoService)
    {
        $this->zohoService = $zohoService;
    }

    public function createDealAndAccount(Request $request, ZohoService $zohoService)
    {
        $validated = $request->validate([
            'dealName' => 'required|string',
            'dealStage' => 'required|string',
            'accountName' => 'required|string',
            'accountWebsite' => 'nullable|url',
            'accountPhone' => 'required|string',
        ]);

        try {
            $result = $zohoService->createDealAndAccount($validated);
            return response()->json(['success' => true, 'data' => $result]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
