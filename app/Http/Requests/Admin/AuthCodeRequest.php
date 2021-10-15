<?php

namespace App\Http\Requests\Admin;

use App\Model\Admin\AuthCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AuthCodeRequest extends FormRequest
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
            'assort_id' => 'required',
        ];
    }


    public function messages()
    {
        return [
            'assort_id.required' => trans('adminUser.type_empty'), // '类型不能为空',
        ];
    }
}
