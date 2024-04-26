<?php

namespace Tests\Unit\Room;

use App\Models\Room;
use App\Models\User;
use App\Services\AuthenticatedUserHandlerService;
use App\Services\Room\RemoveRoomService;
use App\Services\UserPermissionCheckerService;
use App\Services\Utils\ModelValidatorService;
use App\Services\Utils\RoomValidatorService;
use App\Traits\AuthenticatedUserIdTrait;
use Mockery;
use PHPUnit\Framework\TestCase;

class RemoveRoomTest extends TestCase
{
    use AuthenticatedUserIdTrait;

    private $authenticatedUserHandlerServiceMock;
    private $userPermissionCheckerServiceMock;
    private $modelValidatorServiceMock;
    private $roomValidatorServiceMock;
    private $hotelMock;
    private $roomMock;
    private $removeRoomService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authenticatedUserHandlerServiceMock = $this->createMock(AuthenticatedUserHandlerService::class);
        $this->userPermissionCheckerServiceMock = $this->createMock(UserPermissionCheckerService::class);
        $this->modelValidatorServiceMock = $this->createMock(ModelValidatorService::class);
        $this->roomValidatorServiceMock = $this->createMock(RoomValidatorService::class);
        $this->roomMock = Mockery::mock(Room::class);

        $this->removeRoomService = new RemoveRoomService(
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
        ->willReturn($this->createMock(User::class));

        $this->roomValidatorServiceMock
            ->expects($this->once())
            ->method('validateUserIsAdminOfHotelRoom');

        $result = $this->removeRoomService->removeRoom($id);
        $this->assertEquals('Hotel successfully removed', $result);
    }
}
