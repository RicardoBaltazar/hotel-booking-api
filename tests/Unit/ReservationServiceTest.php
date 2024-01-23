<?php

namespace Tests\Unit;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomStatus;
use App\Services\AuthenticatedUserHandlerService;
use App\Services\Reservation\ReservationCalculator;
use App\Services\ReservationService;
use App\Services\Utils\DateValidator;
use App\Services\Utils\ModelValidatorService;
use App\Services\Utils\RoomValidatorService;
use App\Traits\AuthenticatedUserIdTrait;
use DateTime;
use Mockery;
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

        $this->authenticatedUserHandlerServiceMock = Mockery::mock(AuthenticatedUserHandlerService::class);
        $this->modelValidatorServiceMock = Mockery::mock(ModelValidatorService::class);
        $this->reservationCalculatorMock = Mockery::mock(ReservationCalculator::class);
        $this->roomValidatorServiceMock = Mockery::mock(RoomValidatorService::class);
        $this->dateValidatorMock = Mockery::mock(DateValidator::class);
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

    // public function test_it_if_reservation_is_registered_successfully()
    // {
    //     $data = [
    //         'reservation_date' => '2024-01-14',
    //         'room_id' => 1,
    //         'daily_rates' => 3,
    //     ];

    //     $this->authenticatedUserHandlerServiceMock
    //         ->shouldReceive('getAuthenticatedUser')
    //         ->andReturn((object)[
    //             "id" => 1,
    //             "name" => "lizbeth",
    //             "email" => "lizbeth6775@uorak.com",
    //         ])
    //         ->once();

    //     $this->dateValidatorMock
    //         ->shouldReceive('validateDate')
    //         ->with(Mockery::type(DateTime::class))
    //         ->once();

    //     $this->roomMock
    //         ->shouldReceive('find')
    //         ->with(1)
    //         ->andReturn((object) [
    //             'status_id' => 1,
    //             'price' => 100,
    //             'hotel_id' => 1
    //         ]);

    //     $this->roomStatusMock
    //         ->shouldReceive('find')
    //         ->with(1)
    //         ->andReturn((object)[
    //             "status" => "Available"
    //         ]);

    //     $this->roomValidatorServiceMock
    //         ->shouldReceive('validateRoomStatus')
    //         ->with((object) [
    //             'status' => 'Available'
    //             ])
    //         ->once();

    //     $this->reservationCalculatorMock
    //         ->shouldReceive('calculateTotalCost')
    //         ->with(100, 3)
    //         ->andReturn(300);

    //     $this->reservationMock
    //         ->shouldReceive('create')
    //         ->with(array_merge($data, ['user_id' => 1, 'price' => 300, 'hotel_id' => 1]))
    //         ->once();

    //     $roomMock = Mockery::mock('Room');
    //     // $roomMock->shouldReceive('fill')->once()->with(["status_id" => 2]);
    //     // $roomMock->shouldReceive('fill')->once()->with(["status_id" => ReservationService::OCCUPIED_STATUS_CODE]);
    //     $roomMock->shouldReceive('fill')->once()->with();
    //     $roomMock->shouldReceive('save')->once()->andReturn(true);

    //     $result = $this->reservationService->reserveRoom($data);

    //     $this->assertEquals('successfully booked hotel room', $result);
    // }

    // public function tests_it_if_reservation_checkout_was_successful()
    // {
    //     $data = [
    //         "reservation_id" => 4
    //     ];

    //     $reservationMock = Mockery::mock('Reservation');
    //     $this->reservationMock->shouldReceive('find')->once()->with($data['reservation_id'])->andReturn($reservationMock);

    //     $this->dateValidatorMock
    //     ->shouldReceive('validateIfModelHasRecords')
    //     ->with($reservationMock, 2)
    //     ->once();

    //     $roomMock = Mockery::mock('Room');

    //     $this->roomMock
    //         ->shouldReceive('find')
    //         ->with(1)
    //         ->andReturn((array) [
    //             'status_id' => 1,
    //             'price' => 100,
    //             'hotel_id' => 1
    //         ]);

    //     $this->dateValidatorMock
    //     ->shouldReceive('validateIfModelHasRecords')
    //     ->with($roomMock, 2)
    //     ->once();

    //     $roomMock->shouldReceive('fill')->once()->with(["status_id" => 1]);
    //     $roomMock->shouldReceive('save')->once()->andReturn(true);

    //     $result = $this->reservationService->checkout($data);

    //     $this->assertEquals('checkout completed successfully', $result);
    // }
}
