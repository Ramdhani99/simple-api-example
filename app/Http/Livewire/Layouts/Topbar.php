<?php

namespace App\Http\Livewire\Layouts;

use App\Http\Controllers\AuthenticationController;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Topbar extends Component
{
    private $rest_api_url = 'http://simple-api.test/api';

    public function render()
    {
        return view('livewire.layouts.topbar');
    }

    public function logout()
    {
        $response = Http::acceptJson()->withToken(session()->get('user_token'))->post("{$this->rest_api_url}/logout");

        if ($response->successful()) {
            session()->flush();
            session()->invalidate();
            session()->regenerateToken();
            return redirect('/');
        } else {
            $this->dispatchBrowserEvent('error', ['message' => "Something went wrong!"]);
        }
    }
}
