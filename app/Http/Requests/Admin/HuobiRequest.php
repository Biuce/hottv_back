<?php

namespace App\Http\Requests\Admin;

use App\Model\Admin\Huobi;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HuobiRequest extends FormRequest
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
            'money' => 'required',
        ];
    }


    public function messages()
    {
        return [
            'money.required' => trans('adminUser.amount_require'), // '金额不能为空',
        ];
    }
}
