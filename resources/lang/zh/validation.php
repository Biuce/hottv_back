<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ':attribute 是被接受的。',
    'active_url'           => ':attribute 必须是一个合法的 URL。',
    'after'                => ':attribute 必须是 :date 之后的一个日期。',
    'after_or_equal'       => ':attribute 必须是 :date 之后或相同的一个日期',
    'alpha'                => ':attribute 必须全部由字母字符构成。',
    'alpha_dash'           => ':attribute 必须全部由字母、数字、中划线或下划线字符构成',
    'alpha_num'            => ':attribute 必须全部由字母和数字构成',
    'array'                => ':attribute 必须是一个数组。',
    'before'               => ':attribute 必须要早于 :date.',
    'before_or_equal'      => ':attribute 必须要等于 :date 或更早',
    'between'              => [
        'numeric' => ':attribute 必须介于 :min - :max 之间。',
        'file'    => ':attribute 必须介于 :min - :max KB之间。',
        'string'  => ':attribute 必须介于 :min - :max 个字符之间。',
        'array'   => ':attribute 必须只有 :min - :max 个单元',
    ],
    'boolean'              => ':attribute 必须为布尔值。',
    'confirmed'            => ':attribute 两次输入不一致。',
    'date'                 => ':attribute 不是一个有效的日期。',
    'date_format'          => ':attribute 的格式必须为 :format.',
    'different'            => ':attribute 和 :other 必须不同。',
    'digits'               => ':attribute 必须是 :digits 位的数字。',
    'digits_between'       => ':attribute 必须在 :min 和 :max 位之间。',
    'dimensions'           => ':attribute具有无效的图片尺寸',
    'distinct'             => ':attribute字段具有重复值',
    'email'                => ':attribute 必须是一个合法的电子邮件地址。',
    'exists'               => '选定的 :attribute 是无效的。',
    'file'                 => ':attribute必须是一个文件',
    'filled'               => ':attribute 的字段是必填的。',
    'image'                => ':attribute 必须是一个图片(jpeg, png, bmp 或者 gif)',
    'in'                   => '选定的 :attribute 是无效的。',
    'in_array'             => ':attribute 字段不存在于 :other',
    'integer'              => ':attribute 必须是个整数。',
    'ip'                   => ':attribute 必须是一个合法的IP地址。',
    'ipv4'                 => 'The :attribute must be a valid IPv4 address.',
    'ipv6'                 => 'The :attribute must be a valid IPv6 address.',
    'json'                 => ':attribute必须是一个合法的 JSON 字符串',
    'max'                  => [
        'numeric' => ':attribute 最大长度为 :max 位。',
        'file'    => ':attribute 的最大为 :max KB。',
        'string'  => ':attribute 的最大长度为 :max 字符。',
        'array'   => ':attribute 的最大个数为 :max 个。',
    ],
    'mimes'                => ':attribute 的文件类型必须是 :values.',
    'mimetypes'            => 'The :attribute must be a file of type: :values.',
    'min'                  => [
        'numeric' => ':attribute 的最小长度为 :min 位',
        'string'  => ':attribute 的最小长度为 :min 字符',
        'file'    => ':attribute 大小至少为:min KB',
        'array'   => ':attribute 至少有 :min 项',
    ],
    'not_in'               => '选定的 :attribute 是无效的',
    'numeric'              => ':attribute 必须是数字',
    'present'              => ':attribute 字段必须存在',
    'regex'                => ':attribute 格式是无效的',
    'required'             => ':attribute 字段必须填写',
    'required_if'          => ':attribute 字段是必须的当 :other 是 :value',
    'required_unless'      => ':attribute 字段是必须的，除非 :other 是在 :values 中',
    'required_with'        => ':attribute 字段是必须的当 :values 是存在的',
    'required_with_all'    => ':attribute 字段是必须的当 :values 是存在的',
    'required_without'     => ':attribute 字段是必须的当 :values 是不存在的',
    'required_without_all' => ':attribute 字段是必须的当 没有一个 :values 是存在的',
    'same'                 => ':attribute 和 :other 必须匹配',
    'size'                 => [
        'numeric' => ':attribute 必须是 :size 位',
        'file'    => ':attribute 必须是 :size KB',
        'string'  => ':attribute 必须是 :size 个字符',
        'array'   => ':attribute 必须包括 :size 项',
    ],
    'string'               => ':attribute 必须是一个字符串',
    'timezone'             => ':attribute 必须个有效的时区',
    'unique'               => ':attribute 已存在',
    'uploaded'             => 'The :attribute failed to upload.',
    'url'                  => ':attribute 无效的格式',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
