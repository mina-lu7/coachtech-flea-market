<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Illuminate\Support\Facades\Auth;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request)
    {
        $email = session('verify_email');

        Auth::logout();

        session(['verify_email' => $email]);

        return redirect('/email/verify?email=' . $request->input('email'));
    }
}
