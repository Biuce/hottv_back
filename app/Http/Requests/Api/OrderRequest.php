<?php

namespace App\Http\Requests\Api;

use App\Model\Api\Order;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
        $status_in = [
            Order::STATUS_CHECK,
            Order::STATUS_PUT,
            Order::STATUS_FINISH,
            Order::STATUS_CANCEL,
        ];

        return [
            'ad_type' => 'required',
            'ad_duration' => 'required',
            'ad_num' => 'required',
//            'schedule_time' => 'required',
            'ad_area' => 'required',
            'ad_channel' => 'required',
            'user_group' => 'required',
            'ad_policy' => 'required',
            'ad_times' => 'required',
//            'remark' => 'required',
            'status' => [
                Rule::in($status_in),
            ],
        ];
    }


    public function messages()
    {
        return [
            'ad_type.required' => '名称不能为空',
        ];
    }
}