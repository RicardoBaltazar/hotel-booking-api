<?php

namespace Tests\Unit\Room;

use App\Models\Room;
use App\Models\User;
use App\Services\AuthenticatedUserHandlerService;
use App\Services\Room\EditRoomService;
use App\Services\UserPermissionCheckerService;
use App\Services\Utils\ModelValidatorService;
use App\Services\Utils\RoomValidatorService;
use App\Traits\AuthenticatedUserIdTrait;
use Mockery;
use PHPUnit\Framework\TestCase;

class EditRoomTest extends TestCase
{
    use AuthenticatedUserIdTrait;

    private $authenticatedUserHandlerServiceMock;
    private $userPermissionCheckerServiceMock;
    private $modelValidatorServiceMock;
    private $roomValidatorServiceMock;
    private $roomMock;
    private $editRoomService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authenticatedUserHandlerServiceMock = $this->createMock(AuthenticatedUserHandlerService::class);
        $this->userPermissionCheckerServiceMock = $this->createMock(UserPermissionCheckerService::class);
        $this->modelValidatorServiceMock = $this->createMock(ModelValidatorService::class);
        $this->roomValidatorServiceMock = $this->createMock(RoomValidatorService::class);
        $this->roomMock = Mockery::mock(Room::class);

        $this->editRoomService = new EditRoomService(
            $this->authenticatedUserHandlerServiceMock,
            $this->userPermissionCheckerServiceMock,
            $this->modelValidatorServiceMock,
            $this->roomValidatorServiceMock,
            $this->roomMock
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
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
        ->willReturn($this->createMock(User::class));

        $this->roomValidatorServiceMock
            ->expects($this->once())
            ->method('validateUserIsAdminOfHotelRoom');

        $room->shouldReceive('fill')->once()->with($data);
        $room->shouldReceive('save')->once()->andReturn(true);

        $result = $this->editRoomService->editRoom($id, $data);
        $this->assertEquals('Hotel room edited successfully', $result);
    }
}
