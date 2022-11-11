<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;

class AuthenticationController extends Controller
{
    use WithRateLimiting;

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

        try {
            $this->rateLimit(
                3, // $maxAttemps = The number of times that the rate limit can be hit in the given decay period.
                120, // $decaySeconds = The length of the decay period in seconds.
            );

            // $token = $user->createToken($user->email)->plainTextToken;
            // create token: save track email to database, and abilities is role name in api route (expires in 2 hours)
            $token = $user->createToken($user->email, [strtolower($user->role->name)], now()->addMinutes(config('session.lifetime')))->plainTextToken;
            // without expires time
            // $token = $user->createToken($user->email, [strtolower($user->role->name)])->plainTextToken;

            return response()->json([
                'message' => 'success',
                'token' => $token,
            ], 200);
        } catch (TooManyRequestsException $exception) {
            return response()->json([
                // 'message' => "Please wait another {$exception->secondsUntilAvailable} seconds to log in.",
                'message' => "Please wait another minutes to log in.",
            ], 429);
        }
    }

    public function logout()
    {
        session()->invalidate();
        session()->regenerateToken();

        // delete all token from user 
        // request()->user()->tokens()->delete();
        // delete current token
        request()->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'logout success',
        ], 200);
    }
}
