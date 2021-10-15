<?php

namespace App\Http\Requests\Admin;

use App\Model\Admin\Level;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LevelRequest extends FormRequest
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
            'level_name' => 'required',
        ];
    }


    public function messages()
    {
        return [
            'level_name.required' => trans('adminUser.name_empty'), // '名称不能为空',
        ];
    }
}
