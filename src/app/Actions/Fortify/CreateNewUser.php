<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Mail;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     *
     * @throws ValidationException
     */
    public function create(array $input)
    {
        $request = new RegisterRequest();

        $validated = \Illuminate\Support\Facades\Validator::make(
            $input,
            $request->rules(),
            $request->messages(),
            [
                'name' => 'お名前',
                'email' => 'メールアドレス',
                'password' => 'パスワード',
            ]
        )->validate();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'verification_code' => str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT),
        ]);

        Mail::raw(
            "認証コードは {$user->verification_code} です。",
            function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('メール認証コード');
            }
        );

        session(['verify_email' => $user->email]);

        return $user;
    }
}
