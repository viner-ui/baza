<?php

namespace App\Services;

use App\Models\RepairRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RepairRequestService
{
    /**
     * Безопасно переводит заявку в статус "в работе" (assigned → in_progress).
     * При параллельных запросах только один успешен, остальные получают конфликт.
     */
    public function takeInWork(int $requestId, User $master): RepairRequest|false
    {
        return DB::transaction(function () use ($requestId, $master) {
            $request = RepairRequest::where('id', $requestId)
                ->lockForUpdate()
                ->first();

            if (!$request) {
                return false;
            }

            if ($request->status !== RepairRequest::STATUS_ASSIGNED) {
                return false;
            }

            if ((int) $request->assigned_to !== (int) $master->id) {
                return false;
            }

            $request->update(['status' => RepairRequest::STATUS_IN_PROGRESS]);

            return $request;
        });
    }
}
