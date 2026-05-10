<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->verification_code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->save();

        Mail::to($user->email)->send(new \App\Mail\VerificationMail($user->verification_code));

        return redirect('/email/verify');
    }
}
