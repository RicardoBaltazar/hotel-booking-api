<?php

namespace Tests\Unit;

use App\Services\LoginService;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class LoginServiceTest extends TestCase
{
    public function testLogout()
    {
        Auth::shouldReceive('logout')->once();

        $userMock = $this->createMock(User::class);
        $roleMock = $this->createMock(Role::class);

        $loginService = new LoginService($userMock, $roleMock);

        $this->assertEquals('logout realizado!', $loginService->logout());
    }
}
