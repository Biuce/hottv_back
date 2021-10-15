<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdminLoginRequest extends FormRequest
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
            'account' => 'required',
            'password' => 'required',
            'captcha' => 'required|captcha'
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
            'account.required' => trans('adminUser.user_empty'), // '用户名不能为空',
            'password.required' => trans('adminUser.pass_empty'), // '密码不能为空',
            'captcha.required' => trans('adminUser.captcha_empty'), // '图形验证码不能为空',
            'captcha.captcha' => trans('adminUser.captcha_error'), // '图形验证码错误',
        ];
    }
}
