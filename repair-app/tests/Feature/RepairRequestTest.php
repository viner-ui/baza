<?php

namespace Tests\Feature;

use App\Models\RepairRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RepairRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_repair_request_form_renders(): void
    {
        $response = $this->get(route('requests.create'));
        $response->assertStatus(200);
        $response->assertSee('Создание заявки');
        $response->assertSee('client_name');
    }

    public function test_store_creates_repair_request_with_status_new(): void
    {
        $data = [
            'client_name' => 'Иван Иванов',
            'phone' => '+7 999 111-22-33',
            'address' => 'ул. Тестовая, 1',
            'problem_text' => 'Не работает розетка',
        ];
        $response = $this->post(route('requests.store'), $data);
        $response->assertRedirect(route('requests.create'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('repair_requests', [
            'client_name' => $data['client_name'],
            'phone' => $data['phone'],
            'status' => RepairRequest::STATUS_NEW,
        ]);
    }
}
