<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request){
        //validate incoming request
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function logout() {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(
            [
                'status' => 'ok',
                "code" => 200,
                "message"=> ""
            ], 200
        );
    }
}
