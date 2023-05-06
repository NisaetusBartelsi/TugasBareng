<?php

namespace App\Http\Controllers;

use App\Models\Sanctum\PersonalAccessToken;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;
use Laravel\Sanctum\Sanctum;

class AuthController extends Controller
{
    public function registrasi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['data' => $user, 'access_token' => $token, 'token_type' => 'Bearer',]);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()
                ->json(['message' => 'Unauthorized'], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()
            ->json(['message' => 'Hi ' . $user->name . ', welcome to home', 'access_token' => $token, 'token_type' => 'Bearer',]);
    }

    public function logout()
    {
        auth('sanctum')->user()->tokens()->delete();

        return [
            'message' => 'You have successfully logged out and the token was successfully deleted'
        ];
    }

    public function profile(Request $request)
    {
        $user = $request->user()->name;
        return response()->json(['message' => 'Halo Assalamualaikum ' . $user . ' What do you think ?']);
    }

    public function admin(Request $request)
    {
        // Ambil token dari request
        // Cek apakah token valid
        if (!SanctumPersonalAccessToken::findToken($token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return response()->json(['pesan' => 'TES'], 200);

    //     // Ambil user dari token
    //     $user = SanctumPersonalAccessToken::findToken($token)->tokenable;

    //     // Lakukan sesuatu dengan user
    //     // Contoh: Menampilkan nama user
    //     return response()->json(['message' => 'Hello, ' . $user->name], 200);
    // }
}
}