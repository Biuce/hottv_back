<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NewPasswordRequest extends FormRequest
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
        $passwordRule = '';
        if ($this->method() == 'POST' ||
            ($this->method() == 'PUT' && request()->post('old_password') !== '')) {
            $passwordRule = [
                'required',
//                'regex:/^(?![0-9]+$)(?![a-zA-Z]+$)[\w\x21-\x7e]{8,18}$/',
                'regex:/^(?![\d]+$)(?![a-zA-Z]+$)(?![^\da-zA-Z]+$).{8,18}$/',
            ];
        }
        return [
            'password' => 'required|max:18|min:8|regex:/^(?![\d]+$)(?![a-zA-Z]+$)(?![^\da-zA-Z]+$).{8,18}$/|confirmed',
//            'password' => 'required|max:18|min:8|regex:/^(?![0-9]+$)(?![a-zA-Z]+$)[\w\x21-\x7e]{8,18}$/|confirmed',
            'password_confirmation' => 'required|max:18|min:8|regex:/^(?![\d]+$)(?![a-zA-Z]+$)(?![^\da-zA-Z]+$).{8,18}$/'
//            'password_confirmation' => 'required|max:18|min:8|regex:/^(?![0-9]+$)(?![a-zA-Z]+$)[\w\x21-\x7e]{8,18}$/'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'password.required' => trans('adminUser.new_empty'), // '新密码不能为空',
            'password_confirmation.required' => trans('adminUser.affirm_empty'), // '确认密码不能为空',
            'regex' => trans('adminUser.pass_not'), // '密码不符合规则',
        ];
    }
}
