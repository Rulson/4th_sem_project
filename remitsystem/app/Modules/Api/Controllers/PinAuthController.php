<?php

namespace App\Modules\Api\Controllers;

use App\Http\Requests\SetPinRequest;
use App\Http\Requests\ValidatePinRequest;
use App\Modules\User\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class PinAuthController extends ApiController
{
    public function setPin(SetPinRequest $request): JsonResponse
    {
        $access_token = $request->header('X-Access-Token');
        if (!$access_token) {
            return response()->json(['message' => 'Access token is not set.', 'status' => 404]);
        }
        $user = User::where('api_token', $access_token)->where('level_id', 5)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found', 'status' => 404]);
        }
        $validated = $request->validated();
        $pin = $validated['pin'];
        $user->pin = Hash::make($pin);
        $user->save();
        return response()->json(['message' => 'Pin set successfully! ', 'status' => 200]);
    }
    public function validatePin(ValidatePinRequest $request): JsonResponse
    {
        $access_token = $request->header('X-Access-Token');
        if (!$access_token) {
            return response()->json(['message' => 'Unauthorized', 'status' => 401],401);
        }
        $user = User::where('api_token', $access_token)->where('level_id', 5)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found', 'status' => 404],404);
        }
        $validated = $request->validated();
        $pin = $validated['pin'];
        if (!Hash::check($pin, $user->pin)) {
            return response()->json(['message' => 'Wrong pin', 'status' => 404],404);
        }
        return response()->json(['message' => 'Pin validated.', 'status' => 200]);
    }
}
