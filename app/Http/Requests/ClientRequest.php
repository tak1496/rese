<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '※姓を入力してください',
            'email.required' => '※メールアドレスを入力してください',
            'email.email' => '※メールアドレスは例の形式で入力してください',
            'password.required' => '※パスワードを入力して下さい',
        ];
    }
}
