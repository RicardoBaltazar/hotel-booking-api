<?php

namespace Tests\Unit;

use App\Services\Auth\LoginService;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Tests\TestCase;

class LoginServiceTest extends TestCase
{
    private $userMock;
    private $roleMock;
    private $loginService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userMock = Mockery::mock(User::class);
        $this->roleMock = Mockery::mock(Role::class);

        $this->loginService = new LoginService(
            $this->userMock,
            $this->roleMock
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_login_successful()
    {
        $data = [
            "email" => "mihaita265@uorak.com",
            "password" => '1234'
        ];

        $user = Mockery::mock('User');
        $user->id = 1;
        $user->password = Hash::make('1234');

        $this->userMock
            ->shouldReceive('getByEmail')
            ->with($data['email'])
            ->andReturn($user);

        $user->shouldReceive('createToken')
            ->with('token-name')
            ->andReturn((object)['plainTextToken' => 'user-token']);

        $this->roleMock
            ->shouldReceive('getByUserId')
            ->with($user->id)
            ->andReturn(['role' => 'admin']);

        $result = $this->loginService->login($data);

        $this->assertEquals('Login bem-sucedido', $result['message']);
        $this->assertEquals('user-token', $result['token']);
        $this->assertEquals('admin', $result['access']);
    }
}
