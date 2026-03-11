<?php

namespace Tests\Feature;

use App\Models\RepairRequest;
use App\Models\User;
use App\Services\RepairRequestService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TakeInWorkRaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_take_in_work_succeeds_once_then_returns_false_for_same_request(): void
    {
        $master = User::factory()->create(['role' => User::ROLE_MASTER]);
        $request = RepairRequest::create([
            'client_name' => 'Клиент',
            'phone' => '+7 999 000-00-00',
            'address' => 'Адрес',
            'problem_text' => 'Проблема',
            'status' => RepairRequest::STATUS_ASSIGNED,
            'assigned_to' => $master->id,
        ]);

        $service = new RepairRequestService();
        $first = $service->takeInWork($request->id, $master);
        $this->assertNotFalse($first);
        $this->assertSame(RepairRequest::STATUS_IN_PROGRESS, $first->fresh()->status);

        $second = $service->takeInWork($request->id, $master);
        $this->assertFalse($second);
        $this->assertSame(RepairRequest::STATUS_IN_PROGRESS, $request->fresh()->status);
    }
}
