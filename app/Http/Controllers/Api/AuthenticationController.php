<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'The provided credentials are incorrect.',
            ]);
        }

        // $token = $user->createToken($user->email)->plainTextToken;
        // create token: save track email to database, and abilities is role name in api route
        $token = $user->createToken($user->email, [strtolower($user->role->name)])->plainTextToken;

        return response()->json([
            'message' => 'success',
            'token' => $token,
        ], 200);
    }

    public function logout()
    {
        // request()->user()->tokens()->delete();
        session()->invalidate();
        session()->regenerateToken();

        request()->user()->tokens()->delete();

        return response()->json([
            'message' => 'logout success',
        ], 200);
    }
}
