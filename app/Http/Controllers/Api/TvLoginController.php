<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TvLoginRequest;
use App\Http\Resources\TvLoginResource;
use App\Services\TvLoginService;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
}
