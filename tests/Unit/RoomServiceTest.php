<?php

namespace Tests\Unit;

use App\Models\Hotels;
use App\Models\Room;
use App\Models\User;
use App\Services\AuthenticatedUserHandlerService;
use App\Services\RoomService;
use App\Services\UserPermissionCheckerService;
use App\Services\Utils\HotelValidatorService;
use App\Services\Utils\ModelValidatorService;
use App\Services\Utils\RoomValidatorService;
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
    private $roomValidatorServiceMock;
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
        $this->roomValidatorServiceMock = $this->createMock(RoomValidatorService::class);
        $this->hotelMock = Mockery::mock(Hotels::class);
        $this->roomMock = Mockery::mock(Room::class);

        $this->roomService = new RoomService(
            $this->authenticatedUserHandlerServiceMock,
            $this->userPermissionCheckerServiceMock,
            $this->modelValidatorServiceMock,
            $this->hotelValidatorServiceMock,
            $this->roomValidatorServiceMock,
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
        //     ->willReturn((object)[
        //         'id' => 1
        // ]);
        ->willReturn($this->createMock(User::class));

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

    public function tests_if_room_is_edited()
    {
        $id = 1;
        $data = [
            "hotel_id" => 1,
            "room_type_id" => 1,
            "description" => "Nova descrição",
            "price" => 175.00
        ];

        $room = Mockery::mock('Room');
        $this->roomMock->shouldReceive('find')->with($id)->andReturn($room);

        $this->userPermissionCheckerServiceMock
            ->expects($this->once())
            ->method('checkIfUserHasAdminPermission');

        $this->modelValidatorServiceMock
            ->expects($this->once())
            ->method('validateIfModelHasRecords');

        $this->authenticatedUserHandlerServiceMock
            ->expects($this->once())
            ->method('getAuthenticatedUser')
        //     ->willReturn((object)[
        //         'id' => 1
        // ]);
        ->willReturn($this->createMock(User::class));

        $this->roomValidatorServiceMock
            ->expects($this->once())
            ->method('validateUserIsAdminOfHotelRoom');

        $room->shouldReceive('fill')->once()->with($data);
        $room->shouldReceive('save')->once()->andReturn(true);

        $result = $this->roomService->editRoom($id, $data);
        $this->assertEquals('Hotel room edited successfully', $result);
    }

    public function test_if_room_was_removed()
    {
        $id = 1;
        $room = Mockery::mock('Room');
        $this->roomMock->shouldReceive('find')->with($id)->andReturn($room);
        $room->shouldReceive('delete')->once()->andReturn(true);

        $this->userPermissionCheckerServiceMock
        ->expects($this->once())
        ->method('checkIfUserHasAdminPermission');

        $this->modelValidatorServiceMock
            ->expects($this->once())
            ->method('validateIfModelHasRecords');

        $this->authenticatedUserHandlerServiceMock
            ->expects($this->once())
            ->method('getAuthenticatedUser')
        //     ->willReturn((object)[
        //         'id' => 1
        // ]);
        ->willReturn($this->createMock(User::class));

        $this->roomValidatorServiceMock
            ->expects($this->once())
            ->method('validateUserIsAdminOfHotelRoom');

        $result = $this->roomService->removeRoom($id);
        $this->assertEquals('Hotel successfully removed', $result);
    }
}
