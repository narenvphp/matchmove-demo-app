<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    /**
     * Login api
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        // check login attempt
        if (! Auth::attempt($request->only('email', 'password'))) {
            // return failed response
            return response()->json(['message' => 'Invalid login details'], 401);
        }
        // find user
        $user = User::where('email', $request['email'])->firstOrFail();
        // create access token
        $token = $user->createToken('auth_token')->plainTextToken;
        // return success response
        return response()->json(['access_token' => $token, 'token_type' => 'Bearer',]);
    }
}
