<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ClientZipRule;

class OwnerRequest extends FormRequest
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
            'name' => 'required|max:50',
            'area' => 'required',
            'genre' => 'required',
            'overview' => 'required|max:200',
            'photo' => 'required|file|image|mimes:jpg,jpeg,png,gif',
            'manager' => 'required|max:50',
            'email' => 'required|email:filter,dns',
            'post_code' =>
            ['required', new ClientZipRule()],
            'address' => 'required|max:100',
            'tel' =>
            'required|max:20|regex:/^(0{1}\d{1,4}-{0,1}\d{1,4}-{0,1}\d{4})$/',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '※店名を入力してください',
            'name.max' => '※店名は50文字以内までです',
            'area.required' => '※都道府県を選択してください',
            'genre.required' => '※ジャンルを選択してください',
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
