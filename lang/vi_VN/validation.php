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

    'accepted'             => 'Trường :attribute phải được chấp nhận.',
    'active_url'           => 'Trường :attribute không phải là một URL hợp lệ.',
    'after'                => ':attribute phải có ngày sau ngày :date.',
    'after_or_equal'       => 'Thuộc tính: phải là một ngày sau hoặc bằng: date.',
    'alpha'                => 'Trường :attribute chỉ có thể chứa các chữ cái.',
    'alpha_dash'           => 'Trường :attribute chỉ có thể chứa chữ cái, số và dấu gạch ngang.',
    'alpha_num'            => 'Trường :attribute chỉ có thể chứa chữ cái và số.',
    'array'                => 'Thuộc tính :attribute phải là một mảng.',
    'before'               => ':attribute phải có ngày trước :date.',
    'before_or_equal'      => 'Thuộc tính: phải là ngày trước hoặc bằng :date.',
    'between'              => [
        'numeric' => ':attribute phải nằm trong khoảng :min và :max.',
        'file'    => ':attribute phải nằm giữa :min và :max kilobytes.',
        'string'  => 'Trường :attribute phải từ :min đến :max ký tự.',
        'array'   => 'Trường :attribute phải có từ :min đến :max phần tử.',
    ],
    'boolean'              => 'Trường thuộc tính: phải là đúng hoặc sai.',
    'confirmed'            => 'Giá trị xác nhận trong trường :attribute không khớp.',
    'date'                 => ':attribute có ngày không hợp lệ.',
    'date_format'          => 'Trường :attribute không trùng với định dạng :format.',
    'different'            => ':attribute và :other phải khác nhau.',
    'digits'               => ':attribute phải có :digits số.',
    'digits_between'       => ':attribute phải ở giữa :min và :max số.',
    'dimensions'           => 'Thuộc tính: có kích thước hình ảnh không hợp lệ.',
    'distinct'             => 'Trường thuộc tính: có một giá trị trùng lặp.',
    'email'                => 'Thuộc tính :attribute phải là email hợp lệ.',
    'exists'               => ':attribute đã chọn không hợp lệ.',
    'file'                 => 'Trường :attribute phải là 1 tệp tin.',
    'filled'               => 'Trường :attribute phải có 1 giá trị.',
    'gt'                   => [
        'numeric' => ':attribute có thể lớn hơn :value.',
        'file'    => ':attribute có thể không lớn hơn :value kilobytes.',
        'string'  => ':attribute phải lớn hơn :value ký tự.',
        'array'   => ':attribute phải nhiều hơn :value phần tử.',
    ],
    'gte'                  => [
        'numeric' => ':attribute có thể lớn hơn hoặc bằng :value.',
        'file'    => ':attribute có thể không lớn hơn hoặc bằng :value kilobytes.',
        'string'  => ':attribute có thể lớn hơn hoặc bằng :value ký tự.',
        'array'   => ':attribute phải có :value phần tử hoặc hơn.',
    ],
    'image'                => ':attribute phải là một hình ảnh.',
    'in'                   => ':attribute đã chọn không hợp lệ.',
    'in_array'             => 'Trường :attribute không tồn trại trong :other.',
    'integer'              => ':attribute phải là một số nguyên.',
    'ip'                   => 'Trường :attribute phải là một địa chỉ IP.',
    'ipv4'                 => 'Thuộc tính: phải là địa chỉ IPv4 hợp lệ.',
    'ipv6'                 => 'Thuộc tính: phải là địa chỉ IPv6 hợp lệ.',
    'json'                 => 'Trường :attribute phải là một chuỗi JSON.',
    'lt'                   => [
        'numeric' => ':attribute có thể nhỏ hơn :value.',
        'file'    => ':attribute phải nhỏ hơn :value kilobytes.',
        'string'  => ':attribute phải nhỏ hơn :value ký tự.',
        'array'   => ':attribute phải ít hơn :value phần tử.',
    ],
    'lte'                  => [
        'numeric' => ':attribute có thể lớn hơn hoặc bằng :value.',
        'file'    => ':attribute phải nhỏ hơn hoặc bằng :value kilobytes.',
        'string'  => ':attribute phải nhỏ hơn hoặc bằng :value ký tự.',
        'array'   => ':attribute phải nhiều hơn :value phần tử.',
    ],
    'max'                  => [
        'numeric' => 'Trường :attribute không được lớn hơn :max.',
        'file'    => ':attribute có thể không lớn hơn :max kilobytes.',
        'string'  => ':attribute không thể lớn hơn :max ký tự.',
        'array'   => ':attribute không thể nhiều hơn :max phần tử.',
    ],
    'mimes'                => ':attribute phải là 1 tệp tin kiểu: :values.',
    'mimetypes'            => ':attribute phải là 1 tệp tin kiểu: :values.',
    'min'                  => [
        'numeric' => ':attribute phải có ít nhất :min.',
        'file'    => ':attribute phải ít nhất là :min kilobytes.',
        'string'  => 'Trường :attribute phải có tối thiểu :min ký tự.',
        'array'   => 'Trường :attribute phải có tối thiểu :min phần tử.',
    ],
    'not_in'               => ':attribute đã chọn không hợp lệ.',
    'not_regex'            => 'Định dạng :attribute không hợp lệ.',
    'numeric'              => 'Trường :attribute phải là một số.',
    'present'              => 'Trường thuộc tính phải có mặt.',
    'regex'                => 'Định dạng :attribute không hợp lệ.',
    'required'             => 'Trường :attribute là bắt buộc.',
    'required_if'          => 'Trường :attribute thì bắt buộc khi :other là :value.',
    'required_unless'      => 'Trường :attribute không được bỏ trống trừ khi :other là :values.',
    'required_with'        => 'Trường :attribute không được bỏ trống khi :values có mặt.',
    'required_with_all'    => 'Trường :attribute không được bỏ trống khi :values xuất hiện.',
    'required_without'     => 'Trường :attribute không được bỏ trống khi một trong :values không xuất hiện.',
    'required_without_all' => 'Trường :attribute không được bỏ trống khi không :values xuất hiện.',
    'same'                 => 'Trường :attribute và :other phải giống nhau.',
    'size'                 => [
        'numeric' => 'Trường :attribute phải bằng :size.',
        'file'    => ':attribute phải có cỡ :size kilobytes.',
        'string'  => ':attribute phải có :size ký tự.',
        'array'   => 'Trường :attribute phải chứa :size phần tử.',
    ],
    'string'               => 'Thuộc tính: phải là một chuỗi.',
    'timezone'             => 'Thuộc tính: phải là một vùng hợp lệ.',
    'unique'               => ':attribute đã được sử dụng.',
    'uploaded'             => 'Thuộc tính: không thể tải lên.',
    'url'                  => 'Định dạng :attribute không hợp lệ.',

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
            'rule-name' => 'thông báo tùy chỉnh',
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
