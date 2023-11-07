<?php

namespace Tests\Unit;

use App\Services\LoginService;
use App\Models\User;
use App\Models\Role;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Hash;
use Mockery;
// use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class LoginServiceTest extends TestCase
{
    use RefreshDatabase; // Use RefreshDatabase para reverter o banco de dados após o teste

    // public function testLoginSuccessful()
    // {
    //     $user = User::factory()->create([
    //         'id' => 500,
    //         'email' => 'testuser@example.com',
    //         'password' => Hash::make('1234'),
    //     ]);

    //     $roleMock = $this->createMock(Role::class);
    //     $roleMock->method('scopeGetByUserId')->willReturn((object) [
    //         'id' => $user->id,
    //         'role' => 'user',
    //     ]);

    //     // Create LoginService with mocked dependencies
    //     $service = new LoginService($user, $roleMock);

    //     $data = [
    //         'email' => 'testuser@example.com',
    //         'password' => '1234',
    //     ];

    //     $result = $service->login($data);

    //     $this->assertEquals('Login bem-sucedido', $result['message']);
    //     // $this->assertArrayHasKey('token', $result);
    //     // $this->assertArrayHasKey('access', $result);
    // }

    public function testLogout()
    {
        Auth::shouldReceive('logout')->once();

        $userMock = $this->createMock(User::class);
        $roleMock = $this->createMock(Role::class);

        $loginService = new LoginService($userMock, $roleMock);

        $this->assertEquals('logout realizado!', $loginService->logout());
    }
}
