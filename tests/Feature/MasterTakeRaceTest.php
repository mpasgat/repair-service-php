<?php

namespace Tests\Feature;

use App\Models\RepairRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MasterTakeRaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_second_take_attempt_gets_conflict(): void
    {
        $master = User::query()->create([
            'name' => 'Мастер Тест',
            'email' => 'master-test@example.com',
            'role' => User::ROLE_MASTER,
            'password' => Hash::make('password'),
        ]);

        $request = RepairRequest::query()->create([
            'client_name' => 'Клиент Гонка',
            'phone' => '+7-900-111-22-33',
            'address' => 'ул. Гоночная, 1',
            'problem_text' => 'Проверка гонки',
            'status' => RepairRequest::STATUS_ASSIGNED,
            'assigned_to' => $master->id,
        ]);

        $this->actingAs($master);

        $first = $this->post(route('master.take', $request), [], ['Accept' => 'application/json']);
        $second = $this->post(route('master.take', $request), [], ['Accept' => 'application/json']);

        $first->assertOk();
        $second->assertStatus(409);

        $this->assertDatabaseHas('repair_requests', [
            'id' => $request->id,
            'status' => RepairRequest::STATUS_IN_PROGRESS,
        ]);
    }
}
