<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PasswordRequest extends FormRequest
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
//        $status_in = [
//            AdminUser::STATUS_DISABLE,
//            AdminUser::STATUS_ENABLE,
//        ];

        $passwordRule = '';
        if ($this->method() == 'POST' || $this->method() == 'PUT') {
            $passwordRule = [
                'required',
//                'regex:/^(?![0-9]+$)(?![a-zA-Z]+$)[\w\x21-\x7e]{8,18}$/'
                'regex:/^(?![\d]+$)(?![a-zA-Z]+$)(?![^\da-zA-Z]+$).{8,18}$/'
            ];
        }
        return [
            'old_password' => $passwordRule,
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
            'old_password.required' => trans('adminUser.old_empty'), // '旧密码不能为空',
            'old_password.min' => trans('adminUser.old_not_nlt'), // '旧密码不能小于8位数',
            'old_password.max' => trans('adminUser.old_not_glt'), // '旧密码不能大于18位数',
            'password.required' => trans('adminUser.new_empty'), // '新密码不能为空',
            'password.min' => trans('adminUser.new_not_nlt'), // '新密码不能小于8位数',
            'password.max' => trans('adminUser.new_not_glt'), // '新密码不能大于18位数',
            'password_confirmation.required' => trans('adminUser.affirm_empty'), // '确认密码不能为空',
            'regex' => trans('adminUser.pass_not'), // '密码不符合规则',
        ];
    }
}
