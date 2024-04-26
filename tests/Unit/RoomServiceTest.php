<?php

namespace Tests\Unit;

use App\Models\Hotels;
use App\Models\Room;
use App\Models\User;
use App\Services\AuthenticatedUserHandlerService;
use App\Services\RoomService;
use App\Services\UserPermissionCheckerService;
use App\Services\Utils\ModelValidatorService;
use App\Services\Utils\UserHotelValidatorService;
use App\Traits\AuthenticatedUserIdTrait;
use Mockery;
use Tests\TestCase;

class RoomServiceTest extends TestCase
{
    use AuthenticatedUserIdTrait;

    private $authenticatedUserHandlerServiceMock;
    private $userPermissionCheckerServiceMock;
    private $userHotelValidatorService;
    private $modelValidatorServiceMock;
    private $hotelMock;
    private $roomMock;
    private $roomService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authenticatedUserHandlerServiceMock = $this->createMock(AuthenticatedUserHandlerService::class);
        $this->userPermissionCheckerServiceMock = $this->createMock(UserPermissionCheckerService::class);
        $this->userHotelValidatorService = $this->createMock(UserHotelValidatorService::class);
        $this->modelValidatorServiceMock = $this->createMock(ModelValidatorService::class);
        $this->hotelMock = Mockery::mock(Hotels::class);
        $this->roomMock = Mockery::mock(Room::class);

        $this->roomService = new RoomService(
            $this->authenticatedUserHandlerServiceMock,
            $this->userPermissionCheckerServiceMock,
            $this->userHotelValidatorService,
            $this->modelValidatorServiceMock,
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
        $hotel = Mockery::mock('Hotels');

        $this->hotelMock->shouldReceive('find')->andReturn((object) $hotel);
        $this->userPermissionCheckerServiceMock->expects($this->once())->method('checkIfUserHasAdminPermission');
        $this->modelValidatorServiceMock->expects($this->once())->method('validateIfModelHasRecords');
        $this->userHotelValidatorService->expects($this->once())->method('ensureUserIsHotelAdmin');
        $this->authenticatedUserHandlerServiceMock->expects($this->once())->method('getAuthenticatedUser')->willReturn($this->createMock(User::class));
        $this->roomMock->shouldReceive('create')->once()->andReturn(true);

        $data = (array) [
            "hotel_id"=> 1,
            "room_type_id"=> 1,
            "description"=> "Com tons suaves e mobiliário minimalista, o quarto padrão deste hotel oferece uma atmosfera relaxante, onde uma cama acolhedora e uma área funcional se combinam para garantir conforto e praticidade aos hóspedes.",
            "price"=> 150.00
        ];

        $result = $this->roomService->registerRoom($data);
        $this->assertEquals('Hotel room registered successfully', $result);
    }
}
