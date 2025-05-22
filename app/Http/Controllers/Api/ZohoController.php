<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ZohoService;
use Illuminate\Validation\ValidationException;

class ZohoController extends Controller
{
    protected $zohoService;

    public function __construct(ZohoService $zohoService)
    {
        $this->zohoService = $zohoService;
    }

    public function createDealAndAccount(Request $request, ZohoService $zohoService)
    {
        try {
            $validated = $request->validate([
                'dealName' => 'required|string',
                'dealStage' => 'required|string',
                'accountName' => 'required|string',
                'accountWebsite' => 'nullable|url',
                'accountPhone' => 'required|regex:/^\+?[0-9\s\-]{7,15}$/',
            ]);

            $result = $zohoService->createDealAndAccount($validated);

            return response()->json(['success' => true, 'data' => $result]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
