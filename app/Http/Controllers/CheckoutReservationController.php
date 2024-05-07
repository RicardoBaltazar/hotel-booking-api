<?php

namespace App\Http\Controllers;

use App\Services\Reservation\ReservationCheckoutService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CheckoutReservationController extends Controller
{
    protected $reservationCheckoutService;

    public function __construct(ReservationCheckoutService $reservationCheckoutService)
    {
        $this->reservationCheckoutService = $reservationCheckoutService;
    }

    public function __invoke(Request $request)
    {
        $data = $request->all();

        try {
            $response = $this->reservationCheckoutService->checkout($data);
            return response()->json($response);
        } catch (HttpException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
