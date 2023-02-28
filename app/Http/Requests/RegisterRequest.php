<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '※名前を入力してください',
            'email.required' => '※メールアドレスを入力してください',
            'email.email' => '※メールアドレスは例の形式で入力してください',
            'unique' => '※登録済みのメールアドレスです',
            'password.required' => '※パスワードを入力して下さい',
        ];
    }
}
