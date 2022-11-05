<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthenticationController extends Controller
{

    private $rest_api_url = 'http://simple-api.test/api';

    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $response = Http::timeout(3)->acceptJson()->post("{$this->rest_api_url}/login", $request->except('_token'));

        //isset: checking if the variable have the stdClass name "errors"
        if ($response->successful()) {
            $response = json_decode($response->getBody()->getContents());
            session()->regenerate();

            session()->put('user_token', $response->token);

            // sharing $user data to all view (so, layout can always use this data)
            $user = json_decode(Http::acceptJson()->withToken(session()->get('user_token'))->get("{$this->rest_api_url}/user")->getBody()->getContents());
            session()->put('user', $user);

            return redirect()->intended('/home');
        }
        $response = json_decode($response->getBody()->getContents());
        return back()->with([
            'response' => $response
        ])->withInput();
    }

    public function logout()
    {
        $response = Http::acceptJson()->withToken(session()->get('user_token'))->post("{$this->rest_api_url}/logout");
        if ($response->successful()) {
            session()->forget('user_token');
            session()->invalidate();
            session()->regenerateToken();
            return redirect('/');
        }
        return back();
    }
}
