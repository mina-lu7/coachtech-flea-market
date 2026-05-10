<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'description' => 'required|max:255',
            'price' => 'required|numeric|min:0',
            'condition' => 'required',
            'brand' => 'nullable',
            'categories' => 'required|array',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'description.required' => '商品説明を入力してください',
            'description.max' => '商品説明は255文字以内で入力してください',
            'price.required' => '価格を入力してください',
            'price.numeric' => '価格は数値で入力してください',
            'price.min' => '価格は0円以上で入力してください',
            'condition.required' => '商品の状態を入力してください',
            'categories.required' => 'カテゴリーを選択してください',
        ];
    }
}
