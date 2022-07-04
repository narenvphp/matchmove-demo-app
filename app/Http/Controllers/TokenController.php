<?php

namespace App\Http\Controllers;

use App\Http\Requests\TokenRequest;
use App\Models\Token;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    /**
     * tokens overview
     * this api is returning active and inactive tokens
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function overview(Request $request)
    {
        return response()->json(['data' => [
            'activeTokens' => Token::where('status', true)->count(),
            'inActiveTokens' => Token::where('status', false)->count()
        ]]);
    }

    /**
     * generate
     * this api is creating the new token
     *
     * @param TokenRequest $request
     * @return JsonResponse
     */
    public function generate(TokenRequest $request)
    {
        $token = new Token(array_filter($request->all(), function ($value) {
            return $value !== '';
        }));
        $token->token_hash = Token::createTokenHash();
        $token->expire_at = Token::prepareExpireAt($request->days);
        $token->save();
        return response()->json(['data' => $token]);
    }

    /**
     * recall
     * this api is disabling the token
     *
     * @param Request $request
     * @param $hash
     * @return JsonResponse
     */
    public function recall(Request $request, $hash)
    {
        $token = Token::where('token_hash', $hash)->first();
        if (! $token) {
            return response()->json(['data' => [], 'message' => 'no data found']);
        }
        $token->status = false;
        $token->save();
        return response()->json(['data' => $token]);
    }

    /**
     * validate token
     * this api is verifying the token
     *
     * @param Request $request
     * @param $hash
     * @return array|JsonResponse
     */
    public function validateToken(Request $request, $hash)
    {
        $token = Token::where('token_hash', $hash)->first();
        if (! $token || ! $token->status || $token->isExpired()) {
            return response()->json(['message' => 'invalid token', 'status' => 'fail']);
        }
        return response()->json(['message' => 'valid token', 'status' => 'success']);
    }
}
