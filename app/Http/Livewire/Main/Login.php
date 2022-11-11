<?php

namespace App\Http\Livewire\Main;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class Login extends Component
{
    private $rest_api_url = 'http://simple-api.test/api';
    public $email, $password;

    protected $listeners = [
        'toastify_error', //function clear for livewire.emit
    ];

    public function login()
    {
        $response = Http::timeout(3)->acceptJson()->post("{$this->rest_api_url}/login", [
            'email' => $this->email,
            'password' => $this->password
        ]);

        if ($response->ok()) {
            $response = json_decode($response->getBody()->getContents());
            session()->regenerate();
            session()->put('user_token', $response->token);

            // sharing $user data to all view (so, layout can always use this data)
            $user = json_decode(Http::acceptJson()->withToken(session()->get('user_token'))->get("{$this->rest_api_url}/user")->getBody()->getContents());
            session()->put('user', array_merge(['token' => $response->token], (array) $user));

            // return redirect()->intended('/home');
            return redirect()->to('/home');
        } else {
            $response = json_decode($response->getBody()->getContents());
            $this->dispatchBrowserEvent('toastify_error', $response);
        }
    }

    public function render()
    {
        return view('livewire.main.login');
    }
}
