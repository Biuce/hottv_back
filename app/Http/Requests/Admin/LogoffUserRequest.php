<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class LogoffUserRequest extends FormRequest
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
            'name' => 'required',
            'bank_name' => 'required',
            'bank_account' => 'required|numeric',
            'phone' => 'required|numeric'
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
            'name.required' => trans('adminUser.user_empty'), // '用户名不能为空',
            'bank_name.required' => trans('adminUser.account_empty'), // '开户行不能为空',
            'bank_account.required' => trans('adminUser.bank_empty'), // '银行账号不能为空',
            'phone.captcha' => trans('adminUser.phone_empty'), // '联系方式不能为空',
        ];
    }
}
