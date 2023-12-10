<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateHotelRequest;
use App\Services\HotelService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EditHotelController extends Controller
{
    protected $hotelService;

    public function __construct(HotelService $hotelService)
    {
        $this->hotelService = $hotelService;
    }

    public function __invoke(int $id, CreateHotelRequest $request)
    {
        $data = $request->all();
        try {
            $response = $this->hotelService->editHotel($id, $data);
            return response()->json($response);
        } catch (HttpException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
