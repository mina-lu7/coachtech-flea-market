<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|max:20',
            'postal_code' => 'required|regex:/^\d{3}-\d{4}$/',
            'address' => 'required|max:255',
            'building' => 'nullable|max:255',
            'profile_image' => 'nullable|file|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'お名前を入力してください',
            'name.max' => 'お名前は20文字以内で入力してください',

            'postal_code.required' => '郵便番号を入力してください',
            'postal_code.regex' => '郵便番号はハイフンありの形式で入力してください',

            'address.required' => '住所を入力してください',
            'address.max' => '住所は255文字以内で入力してください',
            'building.max' => '建物名は255文字以内で入力してください',

            'profile_image.mimes' => '画像はjpegまたはpng形式でアップロードしてください',
            'profile_image.max' => '画像サイズは2MB以内でアップロードしてください',
            'profile_image.uploaded' => '画像サイズは2MB以内でアップロードしてください',
        ];
    }
}
