<?php

namespace Tests\Unit;

use App\Models\Hotels;
use App\Models\Role;
use App\Services\AuthenticatedUserHandlerService;
use App\Services\HotelService;
use App\Services\UserPermissionCheckerService;
use App\Traits\AuthenticatedUserIdTrait;
use Mockery;
use Tests\TestCase;

class HotelServiceTest extends TestCase
{
    use AuthenticatedUserIdTrait;

    private $authenticatedUserHandlerServiceMock;
    private $userPermissionCheckerServiceMock;
    private $roleMock;
    private $hotelsMock;
    private $hotelService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authenticatedUserHandlerServiceMock = $this->createMock(AuthenticatedUserHandlerService::class);
        $this->userPermissionCheckerServiceMock = $this->createMock(UserPermissionCheckerService::class);
        $this->roleMock = $this->createMock(Role::class);
        $this->hotelsMock = Mockery::mock(Hotels::class);

        $this->hotelService = new HotelService(
            $this->authenticatedUserHandlerServiceMock,
            $this->userPermissionCheckerServiceMock,
            $this->roleMock,
            $this->hotelsMock
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testItCreatesHotelSuccessfully()
    {
        $this->hotelsMock
            ->shouldReceive('create')
            ->once()
            ->andReturn(true);

        $this->userPermissionCheckerServiceMock
            ->expects($this->once())
            ->method('checkIfUserHasAdminPermission');


        $this->authenticatedUserHandlerServiceMock
            ->expects($this->once())
            ->method('getAuthenticatedUser')
            ->willReturn((object)[
                'id' => 1
        ]);

        $data = [
            "name" => "Hotel Excelência",
            "location" => "Rua das Flores, 123",
            "amenities" => "Quarto acolhedor, Wi-Fi gratuito, Restaurante"
        ];

        $result = $this->hotelService->createHotel($data);

        $this->assertEquals('Hotel cadastrado com sucesso', $result);
    }
}
