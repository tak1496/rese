<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ClientZipRule;
use Illuminate\Validation\Validator;

class AdminRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $data = $this->all();

        $rules = [
            'name' => 'required|max:50',
            'overview' => 'max:200',
            'manager' => 'max:50',
            'email' => 'required|email:filter,dns',
            'address' => 'max:100'
        ];

        if($data['id'] === null) {
            $rules['photo'] = 'file|image|mimes:jpg,jpeg,png,gif';
        }

        if($data['post_code'] !='') {
            $rules['post_code'] = [new ClientZipRule()];
        }

        if ($data['tel'] != '') {
            $rules['tel'] = 'max:20|regex:/^(0{1}\d{1,4}-{0,1}\d{1,4}-{0,1}\d{4})$/';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => '※店名を入力してください',
            'name.max' => '※店名は50文字以内までです',
            'overview.required' => '※お店の概要を入力してください',
            'overview.max' => '※お店の概要は200文字以内までです',
            'photo.required' => '※写真を選択してください',
            'photo.max' => '※画像サイズが大きすぎます',
            'photo.file' =>
            '※指定されたファイルが画像(jpg,jpge,png,bmp,gif)ではありません。',
            'photo.image' =>
            '※指定されたファイルが画像(jpg,jpge,png,bmp,gif)ではありません。',
            'photo.mimes' => '※指定されたファイルが画像(jpg,jpge,png,bmp,gif)ではありません。',
            'manager.required' => '※担当者名を入力してください',
            'manager.max' => '※担当者名は50文字以内までです',
            'email.required' => '※メールアドレスを入力して下さい',
            'email.email' => '※メールアドレスの形式が間違っています',
            'post_code.required' =>
            '※郵便番号を入力して下さい',
            'address.required' => '※住所を入力して下さい',
            'address.max' => '※住所の文字数は100文字以内までです',
            'tel.required' => '※TELを入力して下さい',
            'tel.max' => '※TELの文字数は20文字以内までです',
            'tel.regex' => '※TELの入力が不正です',
        ];
    }
}
