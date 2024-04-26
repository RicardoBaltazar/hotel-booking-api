<?php

namespace Tests\Feature\Hotel;

use App\Models\Hotels;
use App\Models\Role;
use App\Services\AuthenticatedUserHandlerService;
use App\Services\Hotel\RemoveHotelService;
use App\Services\UserPermissionCheckerService;
use App\Traits\AuthenticatedUserIdTrait;
use Mockery;
use Tests\TestCase;

class RemoveHotelServiceTest extends TestCase
{
    use AuthenticatedUserIdTrait;

    private $userPermissionCheckerServiceMock;
    private $roleMock;
    private $hotelsMock;
    private $removeHotelService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userPermissionCheckerServiceMock = $this->createMock(UserPermissionCheckerService::class);
        $this->roleMock = $this->createMock(Role::class);
        $this->hotelsMock = Mockery::mock(Hotels::class);

        $this->removeHotelService = new RemoveHotelService(
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
    public function test_if_hotel_was_removed()
    {
        $id = 1;
        $hotel = Mockery::mock('Hotel');
        $this->hotelsMock->shouldReceive('find')->with($id)->andReturn($hotel);
        $hotel->shouldReceive('delete')->once()->andReturn(true);

        $result = $this->removeHotelService->removeHotel($id);
        $this->assertEquals('Hotel successfully removed', $result);
    }
}
