<?php

namespace App\Http\Controllers;

use App\Services\RoomService;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DeleteRoomController extends Controller
{
    protected $roomService;

    public function __construct(RoomService $roomService)
    {
        $this->roomService = $roomService;
    }

    public function __invoke(int $id)
    {
        try {
            $response = $this->roomService->removeRoom($id);
            return response()->json($response);
        } catch (HttpException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
