<?php

namespace Database\Seeders;

use App\Models\RepairRequest;
use App\Models\User;
use Illuminate\Database\Seeder;

class RepairRequestSeeder extends Seeder
{
    public function run(): void
    {
        if (RepairRequest::exists()) {
            return;
        }
        $master1 = User::where('email', 'master1@repair.local')->first();
        $master2 = User::where('email', 'master2@repair.local')->first();

        RepairRequest::create([
            'client_name' => 'Клиент А',
            'phone' => '+7 999 111-22-33',
            'address' => 'ул. Примерная, д. 1',
            'problem_text' => 'Не работает розетка в комнате',
            'status' => RepairRequest::STATUS_NEW,
        ]);

        RepairRequest::create([
            'client_name' => 'Клиент Б',
            'phone' => '+7 999 222-33-44',
            'address' => 'ул. Тестовая, д. 5',
            'problem_text' => 'Протекает кран на кухне',
            'status' => RepairRequest::STATUS_ASSIGNED,
            'assigned_to' => $master1?->id,
        ]);

        RepairRequest::create([
            'client_name' => 'Клиент В',
            'phone' => '+7 999 333-44-55',
            'address' => 'пр. Центральный, д. 10',
            'problem_text' => 'Замена проводки',
            'status' => RepairRequest::STATUS_IN_PROGRESS,
            'assigned_to' => $master1?->id,
        ]);

        RepairRequest::create([
            'client_name' => 'Клиент Г',
            'phone' => '+7 999 444-55-66',
            'address' => 'ул. Новая, д. 3',
            'problem_text' => 'Установка люстры',
            'status' => RepairRequest::STATUS_DONE,
            'assigned_to' => $master2?->id,
        ]);

        RepairRequest::create([
            'client_name' => 'Клиент Д',
            'phone' => '+7 999 555-66-77',
            'address' => 'ул. Старая, д. 7',
            'problem_text' => 'Отмена заявки',
            'status' => RepairRequest::STATUS_CANCELED,
        ]);
    }
}
