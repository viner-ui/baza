<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RepairRequestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MasterApiController extends Controller
{
    public function __construct(
        private RepairRequestService $requestService
    ) {}

    /**
     * Взять заявку в работу (для проверки гонки: один 200, второй 409).
     */
    public function takeInWork(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'request_id' => 'required|integer|exists:repair_requests,id',
        ]);

        $user = Auth::user();
        if (!$user || !$user->isMaster()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $result = $this->requestService->takeInWork(
            (int) $validated['request_id'],
            $user
        );

        if ($result === false) {
            return response()->json([
                'error' => 'Заявка уже взята в работу или недоступна для взятия.',
            ], 409);
        }

        return response()->json([
            'message' => 'Заявка взята в работу.',
            'request' => $result->fresh(),
        ], 200);
    }
}
