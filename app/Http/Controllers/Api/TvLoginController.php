<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TvLoginRequest;
use App\Http\Resources\TvLoginResource;
use App\Services\TvLoginService;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\Request;

class TvLoginController extends Controller
{
    protected TvLoginService $tvLoginService;

    /**
     * Inject dependencies.
     */
    public function __construct(TvLoginService $tvLoginService)
    {
        $this->tvLoginService = $tvLoginService;
    }

    /**
     * Authenticate TV and register a new connected device if limits are respected.
     */
    public function login(TvLoginRequest $request)
    {
        try {
            $result = $this->tvLoginService->authenticateTv($request->validated());

            return new TvLoginResource($result);
        } catch (HttpException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], $e->getStatusCode());
        }
    }

    /**
     * Authenticate TV in Demo Mode (1-Click or Distributor Referral).
     */
    public function demoLogin(Request $request)
    {
        $validated = $request->validate([
            'deviceId' => 'required|string|max:100',
            'macAddress' => 'required|string|max:100',
            'room_no' => 'nullable|string|max:50',
            'ipAddress' => 'nullable|string|max:45',
            'model' => 'nullable|string|max:100',
            'brand' => 'nullable|string|max:100',
            'osVersion' => 'nullable|string|max:50',
            'distributor_code' => 'nullable|string|max:50',
        ]);

        $validated['is_demo'] = true;
        $validated['demo_login'] = true;

        try {
            $result = $this->tvLoginService->authenticateTv($validated);

            return new TvLoginResource($result);
        } catch (HttpException $e) {
            return response()->json([
                'status' => false,
                'error_code' => $e->getStatusCode() === 403 ? 'DEMO_EXPIRED' : 'DEMO_ERROR',
                'message' => $e->getMessage()
            ], $e->getStatusCode());
        }
    }
}
