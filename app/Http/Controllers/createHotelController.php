<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use Illuminate\Http\Request;

class createHotelController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $request->all();
        try {
            // $response = $this->loginService->login($credentials);
            return response()->json($data);

        } catch (CustomException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}
