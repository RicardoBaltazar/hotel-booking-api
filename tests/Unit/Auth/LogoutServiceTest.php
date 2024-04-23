<?php

namespace Tests\Unit\Auth;

use App\Services\Auth\LogoutService;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

class LogoutServiceTest extends TestCase
{
    private $logoutService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->logoutService = new LogoutService();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_logout()
    {
        Auth::shouldReceive('logout')->once();
        $this->assertEquals('logout realizado!', $this->logoutService->logout());
    }
}
