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

    'accepted'             => ':attribute 허용됩니다。',
    'active_url'           => ':attribute 는 유효한 URL아닙니다。',
    'after'                => ':attribute \'는 데이터 이후 날짜 여야합니다.\', 。',
    'after_or_equal'       => ':attribute 는 데이터 이후 혹은 동일한 날짜여야 합니다',
    'alpha'                => ':attribute는 문자만 포함 할 수 있습니다',
    'alpha_dash'           => ':attribute는 문자, 숫자 및 대시 만 포함 할 수 있습니다',
    'alpha_num'            => ':attribute 문자와 숫자 만 포함 할 수 있습니다',
    'array'                => ':attribute는 숫자조합이여야 합니다',
    'before'               => ':attribute : 데이터 이전 날짜 여야합니다.',
    'before_or_equal'      => ':attribute : 데이터 이전의 날짜 혹은 같는 날짜 여야합니다.',
    'between'              => [
        'numeric' => ':attribute  :min - :max 사이여야 합니다.',
        'file'    => ':attribute  :min - :max KB사이여야 합니다.',
        'string'  => ':attribute  :min - :max  자 사이 여야합니다.',
        'array'   => ':attribute  :min - :max 사이의 아이템이어야합니다',
    ],
    'boolean'              => ':attribute 필드는 true 또는 false 여야합니다.。',
    'confirmed'            => ':attribute 확인이 일치하지 않습니다.',
    'date'                 => ':attribute 유효하지 않은 날짜.',
    'date_format'          => ':: attribute가 형식 : format과 일치하지 않습니다',
    'different'            => ':: attribute와 : other는 틀려야합니다.',
    'digits'               => ':: attribute는 : digitdigits 여야합니다.',
    'digits_between'       => ':: attribute는 : min과 : max 사이 여야합니다.',
    'dimensions'           => ':: 속성에 잘못된 이미지 크기가 있습니다.',
    'distinct'             => ':: attribute 필드에 중복 값이 ​​있습니다',
    'email'                => ':: attribute는 유효한 이메일 주소 여야합니다。',
    'exists'               => '선택한 : 속성이 잘못되었습니다',
    'file'                 => ':: attribute는 파일이어야합니다',
    'filled'               => ':attribute 필드는 필수입니다.',
    'image'                => ':attribute 이미지여야 합니다.(jpeg, png, bmp 或者 gif)',
    'in'                   => '선택한 :attribute 이 잘못되였습니다.',
    'in_array'             => ':attribute 필드는 : other에 없습니다.',
    'integer'              => ':attribute 는 정수 여야합니다。',
    'ip'                   => ':attribute The :attribute must be a valid IP address.',
    'ipv4'                 => 'attribute는 유효한 IPv4 주소 여야합니다.',
    'ipv6'                 => 'attribute는 유효한 IPv6 주소 여야합니다.',
    'json'                 => ':attribute는 유효한 JSON 문자열이어야합니다.',
    'max'                  => [
        'numeric' => ':attribute는 : max보다 클 수 없습니다. ',
        'file'    => ':: attribute는 : max kilobytes보다 클 수 없습니다。',
        'string'  => ':: attribute는 : max자를 초과 할 수 없습니다',
        'array'   => ':: attribute는 : max 개를 초과 할 수 없습니다.',
    ],
    'mimes'                => ':: attribute는 다음 유형의 파일이어야합니다. : values.',
    'mimetypes'            => ': attribute는 다음 유형의 파일이어야합니다. : values.',
    'min'                  => [
        'numeric' => ':: attribute는 최소한 : min이어야합니다.',
        'string'  => ':: attribute는 최소한 : min 자 여야합니다.',
        'file'    => ':: attribute는 최소한 : min KB 이상이어야합니다.',
        'array'   => ':: 속성에는 최소한 : 분 이상의 항목이 있어야합니다.',
    ],
    'not_in'               => '선택한 :attribute 잘못되었습니다',
    'numeric'              => ':attribute 는 숫자 여야합니다',
    'present'              => ':attribute 필드가 있어야합니다',
    'regex'                => ':attribute 형식이 잘못되었습니다',
    'required'             => ':attribute 은 비워 둘 수 없습니다',
    'required_if'          => ':attribute 필드는 : other가 : value 인 경우 필수입니다',
    'required_unless'      =>  'The :attribute field is required unless :other is in :values.',
     'required_with'        => 'The :attribute field is required when :values is present.',
    'required_with_all'    => 'The :attribute field is required when :values is present.',
    'required_without'     => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same'                 => 'The :attribute and :other must match.',
    'size'                 => [
       'numeric' => 'The :attribute must be :size.',
        'file'    => 'The :attribute must be :size kilobytes.',
        'string'  => 'The :attribute must be :size characters.',
        'array'   => 'The :attribute must contain :size items.',
    ],
    'string'               => 'The :attribute must be a string.',
    'timezone'             => 'The :attribute must be a valid zone.',
    'unique'               => 'The :attribute has already been taken.',
    'uploaded'             => 'The :attribute failed to upload.',
    'url'                  => 'The :attribute format is invalid.',


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
