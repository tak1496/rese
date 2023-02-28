<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReserveRequest extends FormRequest
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
            'res_date' => 'required|date_format:Y/m/d',
            'res_time' => 'required|date_format:H:i',
            'member' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'res_date.required' => '※日付を入力して下さい',
            'res_date.date_format' => '※日付を入力して下さい',
            'res_time.required' => '※時間を入力して下さい',
            'res_time.date_format' => '※時間を入力して下さい',
            'member.required' => '※人数を入力して下さい',
            'member.numeric' => '※人数を入力して下さい',
        ];
    }
}
