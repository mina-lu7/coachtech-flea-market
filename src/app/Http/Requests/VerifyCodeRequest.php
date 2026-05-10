<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyCodeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'verification_code' => 'required|digits:6',
        ];
    }

    public function messages()
    {
        return [
            'verification_code.required' => '認証コードを入力してください',
            'verification_code.digits' => '認証コードは6桁で入力してください',
        ];
    }
}
