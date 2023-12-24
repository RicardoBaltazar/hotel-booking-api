<?php

namespace Tests\Unit;

use App\Models\Hotels;
use App\Models\Role;
use App\Services\AuthenticatedUserHandlerService;
use App\Services\HotelService;
use App\Services\UserPermissionCheckerService;
use App\Traits\AuthenticatedUserIdTrait;
use Mockery;
use Symfony\Component\HttpKernel\Exception\HttpException;
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

    public function test_it_creates_hotel_successfully()
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

        $this->assertEquals('Hotel registered successfully', $result);
    }

    public function test_if_hotel_was_removed()
    {
        $id = 1;
        $hotel = Mockery::mock('Hotel');
        $this->hotelsMock->shouldReceive('find')->with($id)->andReturn($hotel);
        $hotel->shouldReceive('delete')->once()->andReturn(true);

        $result = $this->hotelService->removeHotel($id);
        $this->assertEquals('Hotel successfully removed', $result);
    }

    public function test_it_throws_exception_when_hotel_not_found()
    {
        $id = 1;
        $this->hotelsMock->shouldReceive('find')->with($id)->andReturn(null);

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('Hotel not found');

        $this->hotelService->removeHotel($id);
    }

    public function test_if_hotel_was_edited()
    {
        $id = 1;
        $data = [
            "name" => "Hotel Excelência",
            "location" => "Rua das Flores, 123",
            "amenities" => "Quarto acolhedor, Wi-Fi gratuito, Restaurante"
        ];

        $hotel = Mockery::mock('Hotel');
        $this->hotelsMock->shouldReceive('find')->with($id)->andReturn($hotel);

        $hotel->shouldReceive('fill')->once()->with($data);
        $hotel->shouldReceive('save')->once()->andReturn(true);

        $result = $this->hotelService->editHotel($id, $data);
        $this->assertEquals('Hotel edited successfully', $result);
    }

    public function test_it_throws_exception_when_hotel_not_found_in_edit()
    {
        $id = 1;
        $this->hotelsMock->shouldReceive('find')->with($id)->andReturn(null);
        $this->expectException(HttpException::class);
        $this->hotelService->removeHotel($id);
    }
}
