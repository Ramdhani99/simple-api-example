<?php

namespace App\Helpers;

class WebHelpers
{
    public static function endpoint_status(object $response)
    {
        if ($response->ok()) {
            return $response->status();
        }
        if ($response->status() == 401) {
            session()->forget('user_token');
            redirect('/');
        }
        return $response->status();
        // $a->dispatchBrowserEvent('toastify_error', ['message' => 'aaa']);
    }
}
