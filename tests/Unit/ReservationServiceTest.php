<?php

namespace Tests\Unit;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomStatus;
use App\Models\User;
use App\Services\AuthenticatedUserHandlerService;
use App\Services\Reservation\ReservationCalculator;
use App\Services\ReservationService;
use App\Services\Utils\DateValidator;
use App\Services\Utils\ModelValidatorService;
use App\Services\Utils\RoomValidatorService;
use App\Traits\AuthenticatedUserIdTrait;
use Mockery;
use stdClass;
use Tests\TestCase;

class ReservationServiceTest extends TestCase
{
    use AuthenticatedUserIdTrait;

    private $authenticatedUserHandlerServiceMock;
    private $modelValidatorServiceMock;
    private $reservationCalculatorMock;
    private $roomValidatorServiceMock;
    private $dateValidatorMock;
    private $reservationMock;
    private $roomStatusMock;
    private $roomMock;
    private $reservationService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dateValidatorMock = $this->createMock(DateValidator::class);
        $this->roomValidatorServiceMock = $this->createMock(RoomValidatorService::class);

        $this->authenticatedUserHandlerServiceMock = $this->createMock(AuthenticatedUserHandlerService::class);

        $this->modelValidatorServiceMock = Mockery::mock(ModelValidatorService::class);
        $this->reservationCalculatorMock = $this->createMock(ReservationCalculator::class);
        $this->reservationMock = Mockery::mock(Reservation::class);
        $this->roomStatusMock = Mockery::mock(RoomStatus::class);
        $this->roomMock = Mockery::mock(Room::class);

        $this->reservationService = new ReservationService(
            $this->authenticatedUserHandlerServiceMock,
            $this->modelValidatorServiceMock,
            $this->reservationCalculatorMock,
            $this->roomValidatorServiceMock,
            $this->dateValidatorMock,
            $this->reservationMock,
            $this->roomMock,
            $this->roomStatusMock,
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    // public function test_it_if_resebrvation_is_registered_successfully()
    // {
    //     $data = [
    //         "room_id" => 1,
    //         "daily_rates" => 2,
    //         "reservation_date" => "2024-05-25"
    //     ];

    //     $this->dateValidatorMock->expects($this->once())->method('validateDate');

    //     $room = Mockery::mock('Room');
    //     $room->status_id = 1;
    //     $room->price = 100;
    //     $room->hotel_id = 1;
    //     $this->roomMock->shouldReceive('find')->with($data['room_id'])->andReturn($room);

    //     $roomStatus = Mockery::mock('RoomStatus');
    //     $this->roomStatusMock->shouldReceive('find')->with($room->status_id)->andReturn($roomStatus);

    //     $this->roomValidatorServiceMock->expects($this->once())->method('validateRoomStatus');
    //     $this->authenticatedUserHandlerServiceMock->expects($this->once())->method('getAuthenticatedUser')->willReturn($this->createMock(User::class));
    //     $this->reservationCalculatorMock->expects($this->once())->method('calculateTotalCost')->willReturn(200.00);

    //     $this->reservationMock->shouldReceive('create')->once()->andReturn(true);

    //     $result = $this->reservationService->reserveRoom($data);

    //     $this->assertEquals('successfully booked hotel room', $result);
    // }
}
