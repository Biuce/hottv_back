<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Model\Admin\AdminUser;
use Illuminate\Validation\Rule;

class AdminUserRequest extends FormRequest
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
        if ($this->method() == 'POST' ||
            ($this->method() == 'PUT' && request()->post('password') !== '')) {
            $passwordRule = [
                'required',
                'regex:/^(?![0-9]+$)(?![a-zA-Z]+$)[\w\x21-\x7e]{6,18}$/'
            ];
        }
        return [
            'name' => 'required|max:150',
//            'password' => $passwordRule,
//            'account' => 'required|max:50',
            'level_id' => 'required|numeric|not_in:0',
            'balance' => 'required|max:50',
//            'status' => [
//                Rule::in($status_in),
//            ],
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
            'name.required' => trans('adminUser.user_empty'),  // '用户名不能为空'
//            'password.required' => '密码不能为空',
            'regex' => trans('adminUser.pass_not'), // '密码不符合规则',
//            'account' => '账号不能为空',
            'level_id' => trans('adminUser.agency_require'), // '代理级别必填',
            'balance' => trans('adminUser.amount_require'), // '金额必填',
        ];
    }
}
