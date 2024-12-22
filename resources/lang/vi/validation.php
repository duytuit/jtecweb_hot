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

    'accepted' => 'Trường :attribute phải được chấp nhận.',
    'active_url' => 'Trường :attribute Không có giá trị URL.',
    'after' => 'Trường :attribute Phải là một ngày sau :date.',
    'after_or_equal' => 'Trường :attribute Phải là một ngày sau hoặc bằng :date.',
    'alpha' => 'Trường :attribute chỉ có thể chứa các chữ cái.',
    'alpha_dash' => 'Trường :attribute chỉ có thể chứa các chữ cái, số, dấu gạch ngang và nhấn mạnh.',
    'alpha_num' => 'Trường :attribute chỉ có thể chứa các chữ cái và số.',
    'array' => 'Trường :attribute Phải là một mảng.',
    'before' => 'Trường :attribute Phải là một ngày trước :date.',
    'before_or_equal' => 'Trường :attribute Phải là một ngày trước hoặc bằng :date.',
    'between' => [
        'numeric' => 'Trường :attribute phải ở giữa :min Và :max.',
        'file' => 'Trường :attribute phải ở giữa :min Và :max kilobytes.',
        'string' => 'Trường :attribute phải ở giữa :min Và :max characters.',
        'array' => 'Trường :attribute must have between :min Và :max items.',
    ],
    'boolean' => 'Trường :attribute trường phải đúng hoặc sai.',
    'confirmed' => 'Trường :attribute nhận đinh không phù hợp.',
    'date' => 'Trường :attribute Không phải là ngày hợp lệ.',
    'date_equals' => 'Trường :attribute Phải là một ngày bằng :date.',
    'date_format' => 'Trường :attribute không khớp với định dạng :format.',
    'different' => 'Trường :attribute Và :orther phải khác nhau.',
    'digits' => 'Trường :attribute phải là :digits digits.',
    'digits_between' => 'Trường :attribute phải ở giữa :min and :max digits.',
    'dimensions' => 'Trường :attribute has invalid image dimensions.',
    'distinct' => 'Trường :attribute field has a duplicate value.',
    'email' => 'Trường :attribute phải là a valid email address.',
    'ends_with' => 'Trường :attribute must end with one of Trường following: :values.',
    'exists' => 'Trường selected :attribute is invalid.',
    'file' => 'Trường :attribute phải là a file.',
    'filled' => 'Trường :attribute field must have a value.',
    'gt' => [
        'numeric' => 'Trường :attribute phải là greater than :value.',
        'file' => 'Trường :attribute phải là greater than :value kilobytes.',
        'string' => 'Trường :attribute phải là greater than :value characters.',
        'array' => 'Trường :attribute must have more than :value items.',
    ],
    'gte' => [
        'numeric' => 'Trường :attribute phải là greater than or equal :value.',
        'file' => 'Trường :attribute phải là greater than or equal :value kilobytes.',
        'string' => 'Trường :attribute phải là greater than or equal :value characters.',
        'array' => 'Trường :attribute must have :value items or more.',
    ],
    'image' => 'Trường :attribute phải là an image.',
    'in' => 'Trường selected :attribute is invalid.',
    'in_array' => 'Trường :attribute field does not exist in :orther.',
    'integer' => 'Trường :attribute phải là an integer.',
    'ip' => 'Trường :attribute phải là a valid IP address.',
    'ipv4' => 'Trường :attribute phải là a valid IPv4 address.',
    'ipv6' => 'Trường :attribute phải là a valid IPv6 address.',
    'json' => 'Trường :attribute phải là a valid JSON string.',
    'lt' => [
        'numeric' => 'Trường :attribute phải là less than :value.',
        'file' => 'Trường :attribute phải là less than :value kilobytes.',
        'string' => 'Trường :attribute phải là less than :value characters.',
        'array' => 'Trường :attribute must have less than :value items.',
    ],
    'lte' => [
        'numeric' => 'Trường :attribute phải là less than or equal :value.',
        'file' => 'Trường :attribute phải là less than or equal :value kilobytes.',
        'string' => 'Trường :attribute phải là less than or equal :value characters.',
        'array' => 'Trường :attribute must not have more than :value items.',
    ],
    'max' => [
        'numeric' => 'Trường :attribute may not be greater than :max.',
        'file' => 'Trường :attribute may not be greater than :max kilobytes.',
        'string' => 'Trường :attribute may not be greater than :max characters.',
        'array' => 'Trường :attribute may not have more than :max items.',
    ],
    'mimes' => 'Trường :attribute phải là a file of type: :values.',
    'mimetypes' => 'Trường :attribute phải là a file of type: :values.',
    'min' => [
        'numeric' => 'Trường :attribute phải là at least :min.',
        'file' => 'Trường :attribute phải là at least :min kilobytes.',
        'string' => 'Trường :attribute phải là at least :min characters.',
        'array' => 'Trường :attribute must have at least :min items.',
    ],
    'multiple_of' => 'Trường :attribute phải là a multiple of :value.',
    'not_in' => 'Trường selected :attribute is invalid.',
    'not_regex' => 'Trường :attribute format is invalid.',
    'numeric' => 'Trường :attribute phải là a number.',
    'password' => 'Trường password is incorrect.',
    'present' => 'Trường :attribute field phải là present.',
    'regex' => 'Trường :attribute format is invalid.',
    'required' => 'Trường :attribute field is required.',
    'required_if' => 'Trường :attribute field is required when :orther is :value.',
    'required_unless' => 'Trường :attribute field is required unless :orther is in :values.',
    'required_with' => 'Trường :attribute field is required when :values is present.',
    'required_with_all' => 'Trường :attribute field is required when :values are present.',
    'required_without' => 'Trường :attribute field is required when :values is not present.',
    'required_without_all' => 'Trường :attribute field is required when none of :values are present.',
    'same' => 'Trường :attribute and :orther must match.',
    'size' => [
        'numeric' => 'Trường :attribute phải là :size.',
        'file' => 'Trường :attribute phải là :size kilobytes.',
        'string' => 'Trường :attribute phải là :size characters.',
        'array' => 'Trường :attribute must contain :size items.',
    ],
    'starts_with' => 'Trường :attribute must start with one of Trường following: :values.',
    'string' => 'Trường :attribute phải là a string.',
    'timezone' => 'Trường :attribute phải là a valid zone.',
    'unique' => 'Trường :attribute không được trùng lặp. đã tồn tại dữ liệu này.',
    'uploaded' => 'Trường :attribute failed to upload.',
    'url' => 'Trường :attribute format is invalid.',
    'uuid' => 'Trường :attribute phải là a valid UUID.',

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
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
