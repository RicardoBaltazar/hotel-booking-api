<?php

namespace Tests\Unit;

use App\Models\Hotels;
use App\Models\Role;
use App\Services\Hotel\EditHotelService;
use App\Services\UserPermissionCheckerService;
use App\Traits\AuthenticatedUserIdTrait;
use Mockery;
use Tests\TestCase;

class EditHotelServiceTest extends TestCase
{
    use AuthenticatedUserIdTrait;

    private $userPermissionCheckerServiceMock;
    private $roleMock;
    private $hotelsMock;
    private $hotelService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userPermissionCheckerServiceMock = $this->createMock(UserPermissionCheckerService::class);
        $this->roleMock = $this->createMock(Role::class);
        $this->hotelsMock = Mockery::mock(Hotels::class);

        $this->hotelService = new EditHotelService(
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
}
