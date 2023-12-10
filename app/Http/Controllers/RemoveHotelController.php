<?php

namespace App\Http\Controllers;

use App\Services\HotelService;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RemoveHotelController extends Controller
{
    protected $hotelService;

    public function __construct(HotelService $hotelService)
    {
        $this->hotelService = $hotelService;
    }

    public function __invoke(int $id)
    {
        try {
            $response = $this->hotelService->removeHotel($id);
            return response()->json($response);
        } catch (HttpException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
