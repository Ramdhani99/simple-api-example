<?php

namespace App\Http\Livewire\Main;

use Livewire\Component;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Illuminate\Support\Facades\Http;

class Login extends Component
{
    use WithRateLimiting;

    private $rest_api_url = 'http://simple-api.test/api';
    public $email, $password;

    protected $listeners = [
        'toastify_error', //function clear for livewire.emit
    ];

    public function login()
    {
        try {
            // limiter: three times attemp login
            // $this->rateLimit(3);
            $this->rateLimit(
                3, // $maxAttemps = The number of times that the rate limit can be hit in the given decay period.
                120, // $decaySeconds = The length of the decay period in seconds.
            );

            $response = Http::timeout(3)->acceptJson()->post("{$this->rest_api_url}/login", [
                'email' => $this->email,
                'password' => $this->password
            ]);

            if ($response->successful()) {

                $response = json_decode($response->getBody()->getContents());
                session()->regenerate();
                session()->put('user_token', $response->token);

                // sharing $user data to all view (so, layout can always use this data)
                $user = json_decode(Http::acceptJson()->withToken(session()->get('user_token'))->get("{$this->rest_api_url}/user")->getBody()->getContents());
                session()->put('user', $user);

                return redirect()->intended('/home');
            } else {
                $response = json_decode($response->getBody()->getContents());
                $this->dispatchBrowserEvent('toastify_error', $response);
            }
        } catch (TooManyRequestsException $exception) {
            $this->dispatchBrowserEvent('toastify_error', ['message' => "Please wait another {$exception->secondsUntilAvailable} seconds to log in."]);
            return;
        }
    }

    public function render()
    {
        return view('livewire.main.login')
            ->extends('layouts.master')
            ->section('content');
    }
}
