<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthenticatedSessionController extends Controller
{
    public function store(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'email' => 'ログイン情報が登録されていません',
            ]);
        }

        if ($user->verification_code !== null) {

            session(['verify_email' => $user->email]);

            return redirect('/email/verify')
                ->with('message', 'メール認証が必要です');
        }

        Auth::login($user);

        $request->session()->regenerate();

        return redirect('/?tab=mylist');
    }
}