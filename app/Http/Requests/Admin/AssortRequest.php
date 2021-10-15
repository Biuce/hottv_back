<?php

namespace App\Http\Requests\Admin;

use App\Model\Admin\Assort;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssortRequest extends FormRequest
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
            'assort_name' => 'required',
        ];
    }


    public function messages()
    {
        return [
            'assort_name.required' => trans('adminUser.name_empty'), // '名称不能为空',
        ];
    }
}
