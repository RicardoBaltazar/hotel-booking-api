<?php

namespace App\Http\Controllers;

use App\Services\Room\RoomService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EditRoomController extends Controller
{
    protected $roomService;

    public function __construct(RoomService $roomService)
    {
        $this->roomService = $roomService;
    }

    public function __invoke(Request $request, int $id)
    {
        $data = $request->all();

        try {
            $response = $this->roomService->editRoom($id, $data);
            return response()->json($response);
        } catch (HttpException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
