<?php

namespace Tests\Unit;

use App\Services\LoginService;
use App\Models\User;
use App\Models\Role;
use App\Services\Utils\CredentialsValidator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Tests\TestCase;

class LoginServiceTest extends TestCase
{
    private $credentialsValidatorMock;
    private $userMock;
    private $roleMock;
    private $loginService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->credentialsValidatorMock = Mockery::mock(CredentialsValidator::class);
        $this->userMock = Mockery::mock(User::class);
        $this->roleMock = Mockery::mock(Role::class);

        $this->loginService = new LoginService(
            $this->credentialsValidatorMock,
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

        $this->credentialsValidatorMock
            ->shouldReceive('validateCredentials')
            ->once()
            ->with($data['password'], $user->password)
            ->andReturn(true);


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

    public function test_logout()
    {
        Auth::shouldReceive('logout')->once();
        $this->assertEquals('logout realizado!', $this->loginService->logout());
    }
}
