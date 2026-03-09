<?php

namespace Tests\Feature;

use App\Models\RepairRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RequestCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_request_creation_sets_new_status(): void
    {
        $response = $this->post('/requests', [
            'client_name' => 'Тест Клиент',
            'phone' => '+7-999-999-99-99',
            'address' => 'Тестовый адрес',
            'problem_text' => 'Тестовая проблема',
        ]);

        $response->assertRedirect(route('requests.create'));

        $this->assertDatabaseHas('repair_requests', [
            'client_name' => 'Тест Клиент',
            'status' => RepairRequest::STATUS_NEW,
        ]);
    }
}
