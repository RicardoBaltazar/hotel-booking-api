<?php

namespace Tests\Unit;

use App\Models\Hotels;
use App\Models\Room;
use App\Services\AuthenticatedUserHandlerService;
use App\Services\Room\RoomService;
use App\Services\UserPermissionCheckerService;
use App\Services\Utils\HotelValidatorService;
use App\Services\Utils\ModelValidatorService;
use App\Traits\AuthenticatedUserIdTrait;
use Mockery;
use Tests\TestCase;

class RoomServiceTest extends TestCase
{
    use AuthenticatedUserIdTrait;

    private $authenticatedUserHandlerServiceMock;
    private $userPermissionCheckerServiceMock;
    private $modelValidatorServiceMock;
    private $hotelValidatorServiceMock;
    private $hotelMock;
    private $roomMock;
    private $roomService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authenticatedUserHandlerServiceMock = $this->createMock(AuthenticatedUserHandlerService::class);
        $this->userPermissionCheckerServiceMock = $this->createMock(UserPermissionCheckerService::class);
        $this->modelValidatorServiceMock = $this->createMock(ModelValidatorService::class);
        $this->hotelValidatorServiceMock = $this->createMock(HotelValidatorService::class);
        // $this->hotelValidatorServiceMock = Mockery::mock(HotelValidatorService::class);
        $this->hotelMock = Mockery::mock(Hotels::class);
        $this->roomMock = Mockery::mock(Room::class);

        $this->roomService = new RoomService(
            $this->authenticatedUserHandlerServiceMock,
            $this->userPermissionCheckerServiceMock,
            $this->modelValidatorServiceMock,
            $this->hotelValidatorServiceMock,
            $this->hotelMock,
            $this->roomMock
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function tests_if_room_is_registered()
    {
        $id = 1;
        $hotel = Mockery::mock('Hotel');

        $this->hotelMock
        ->shouldReceive('find')
        ->with($id)
        ->andReturn($hotel);

        $this->userPermissionCheckerServiceMock
            ->expects($this->once())
            ->method('checkIfUserHasAdminPermission');

        $this->modelValidatorServiceMock
            ->expects($this->once())
            ->method('validateIfModelHasRecords');

        $this->authenticatedUserHandlerServiceMock
            ->expects($this->once())
            ->method('getAuthenticatedUser')
            ->willReturn((object)[
                'id' => 1
        ]);

        $this->hotelValidatorServiceMock
            ->expects($this->once())
            ->method('validateIfIsAdminOfHotel');

        $this->roomMock
        ->shouldReceive('create')
        ->once()
        ->andReturn(true);

        $data = [
            "hotel_id"=> 1,
            "room_type_id"=> 1,
            "description"=> "Com tons suaves e mobiliário minimalista, o quarto padrão deste hotel oferece uma atmosfera relaxante, onde uma cama acolhedora e uma área funcional se combinam para garantir conforto e praticidade aos hóspedes.",
            "price"=> 150.00
        ];

        $result = $this->roomService->registerRoom($data);
        $this->assertEquals('Hotel room registered successfully', $result);
    }
}
