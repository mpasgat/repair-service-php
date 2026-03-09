<?php

namespace Database\Seeders;

use App\Models\RepairRequest;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $dispatcher = User::query()->updateOrCreate(
            ['email' => 'dispatcher@example.com'],
            [
                'name' => 'Диспетчер Анна',
                'role' => User::ROLE_DISPATCHER,
                'password' => Hash::make('password'),
            ]
        );

        $masterOne = User::query()->updateOrCreate(
            ['email' => 'master1@example.com'],
            [
                'name' => 'Мастер Иван',
                'role' => User::ROLE_MASTER,
                'password' => Hash::make('password'),
            ]
        );

        $masterTwo = User::query()->updateOrCreate(
            ['email' => 'master2@example.com'],
            [
                'name' => 'Мастер Петр',
                'role' => User::ROLE_MASTER,
                'password' => Hash::make('password'),
            ]
        );

        RepairRequest::query()->delete();

        RepairRequest::query()->create([
            'client_name' => 'Сергей К.',
            'phone' => '+7-900-000-00-01',
            'address' => 'ул. Ленина, 10',
            'problem_text' => 'Не включается стиральная машина',
            'status' => RepairRequest::STATUS_NEW,
        ]);

        RepairRequest::query()->create([
            'client_name' => 'Марина В.',
            'phone' => '+7-900-000-00-02',
            'address' => 'пр. Мира, 22',
            'problem_text' => 'Течет кран в ванной',
            'status' => RepairRequest::STATUS_ASSIGNED,
            'assigned_to' => $masterOne->id,
        ]);

        RepairRequest::query()->create([
            'client_name' => 'Алексей Т.',
            'phone' => '+7-900-000-00-03',
            'address' => 'ул. Советская, 5',
            'problem_text' => 'Не греет батарея в комнате',
            'status' => RepairRequest::STATUS_IN_PROGRESS,
            'assigned_to' => $masterTwo->id,
        ]);

        unset($dispatcher);
    }
}
