<?php

namespace App\Http\Controllers;

use App\Services\Room\RemoveRoomService;
use App\Services\RoomService;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DeleteRoomController extends Controller
{
    protected $removeRoomService;

    public function __construct(RemoveRoomService $removeRoomService)
    {
        $this->removeRoomService = $removeRoomService;
    }

    public function __invoke(int $id)
    {
        try {
            $response = $this->removeRoomService->removeRoom($id);
            return response()->json($response);
        } catch (HttpException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
