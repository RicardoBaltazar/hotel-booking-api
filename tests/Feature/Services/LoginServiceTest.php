<?php

namespace Tests\Feature\Services;

use App\Services\LoginService;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testLoginSuccessful()
    {
        $user = User::factory()->create([
            'id' => 1000,
            'email' => 'testuser@example.com',
            'password' => Hash::make('1234'),
        ]);

        $role = Role::factory()->create([
            'user_id' => $user->id,
            'role' => 'user',
        ]);

        $service = new LoginService($user, $role);

        $data = [
            'email' => 'testuser@example.com',
            'password' => '1234',
        ];

        $result = $service->login($data);

        $this->assertEquals('Login bem-sucedido', $result['message']);
    }
}
