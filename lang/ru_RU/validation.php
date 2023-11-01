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

    'accepted'             => ':attribute должен быть принят.',
    'active_url'           => ':attribute некорректный URL.',
    'after'                => 'В поле :attribute должна быть дата после :date.',
    'after_or_equal'       => 'В поле :attribute должна быть дата после или равняться :date.',
    'alpha'                => 'Поле :attribute может содержать только буквы.',
    'alpha_dash'           => 'Поле :attribute может содержать только буквы, цифры, дефисы и подчеркивания.',
    'alpha_num'            => 'Поле :attribute может содержать только буквы и цифры.',
    'array'                => 'Атрибут: должен быть массивом.',
    'before'               => 'В поле :attribute должна быть дата до :date.',
    'before_or_equal'      => 'В поле :attribute должна быть дата до или равна :date.',
    'between'              => [
        'numeric' => 'Поле :attribute должно быть между :min и :max.',
        'file'    => 'Размер файла в поле :attribute должен быть между :min и :max килобайт.',
        'string'  => 'Количество символов в поле :attribute должно быть между :min и :max.',
        'array'   => 'Количество элементов в поле :attribute должно быть между :min и :max.',
    ],
    'boolean'              => 'Поле :attribute должно быть true или false.',
    'confirmed'            => 'Поле :attribute не совпадает с подтверждением.',
    'date'                 => 'Поле :attribute не является датой.',
    'date_format'          => 'Поле :attribute не соответствует формату :format.',
    'different'            => 'Поля :attribute и :other должны различаться.',
    'digits'               => ':attribute должен содержать :digits цифр.',
    'digits_between'       => 'Длина цифрового поля :attribute должна быть между :min и :max.',
    'dimensions'           => 'Поле :attribute имеет недопустимые размеры изображения.',
    'distinct'             => 'Поле :attribute содержит повторяющееся значение.',
    'email'                => 'Поле :attribute должно быть действительным адресом электронной почты.',
    'exists'               => 'Выбранное значение для :attribute некорректно.',
    'file'                 => ':attribute должно быть файлом.',
    'filled'               => 'Поле :attribute должно иметь значение.',
    'gt'                   => [
        'numeric' => 'Поле :attribute не может быть больше :value.',
        'file'    => 'Поле :attribute должно быть больше :value килобайт.',
        'string'  => 'Поле :attribute должно содержать более :value символов.',
        'array'   => 'Поле :attribute должно содержать более :value элементов.',
    ],
    'gte'                  => [
        'numeric' => 'Поле :attribute должно быть больше или равно :value.',
        'file'    => 'Поле :attribute должно быть больше или равно :value килобайт.',
        'string'  => 'Поле :attribute должно содержать :value символов или больше.',
        'array'   => 'Поле :attribute должно содержать :value элементов или больше.',
    ],
    'image'                => 'Поле :attribute должно быть изображением.',
    'in'                   => 'Выбранное значение поля :attribute недопустимо.',
    'in_array'             => 'Поле :attribute не существует в :other.',
    'integer'              => 'Поле :attribute должно быть целым числом.',
    'ip'                   => 'Поле :attribute должно быть допустимым IP-адресом.',
    'ipv4'                 => 'Поле :attribute должно быть допустимым IPv4-адресом.',
    'ipv6'                 => 'Поле :attribute должно быть допустимым IPv6-адресом.',
    'json'                 => 'Поле :attribute должно быть допустимой JSON строкой.',
    'lt'                   => [
        'numeric' => 'Поле :attribute должно быть меньше :value.',
        'file'    => 'Поле :attribute должно быть меньше :value килобайт.',
        'string'  => 'Поле :attribute должно содержать меньше :value символов.',
        'array'   => 'Поле :attribute должно содержать менее :value элементов.',
    ],
    'lte'                  => [
        'numeric' => 'Поле :attribute должно быть меньше или равно :value.',
        'file'    => 'Поле :attribute должно быть меньше или равно :value килобайт.',
        'string'  => 'Поле :attribute должно содержать :value символов или меньше.',
        'array'   => 'Поле :attribute не должно содержать более :value элементов.',
    ],
    'max'                  => [
        'numeric' => 'Поле :attribute не может быть больше :max.',
        'file'    => 'Поле :attribute не может быть больше :max килобайт.',
        'string'  => 'Поле :attribute не может содержать более :max символов.',
        'array'   => 'Поле :attribute не может содержать более :max элементов.',
    ],
    'mimes'                => 'Поле :attribute должно быть файлом типа: :values.',
    'mimetypes'            => 'Поле :attribute должно быть файлом типа: :values.',
    'min'                  => [
        'numeric' => 'Поле :attribute должно содержать как минимум :min символов.',
        'file'    => 'Размер файла в поле :attribute должен быть не менее :min килобайт.',
        'string'  => 'Поле :attribute должен содержать как минимум :min символов.',
        'array'   => 'Поле :attribute должно содержать как минимум :min элементов.',
    ],
    'not_in'               => 'Выбранное значение для :attribute недопустимо.',
    'not_regex'            => 'Неправильный формат :attribute.',
    'numeric'              => 'Поле :attribute должно быть числом.',
    'present'              => 'Поле :attribute field должно присутствовать.',
    'regex'                => 'Неправильный формат :attribute.',
    'required'             => 'Поле :attribute обязательно для заполнения.',
    'required_if'          => 'Поле :attribute обязательно для заполнения, когда :other равно :value.',
    'required_unless'      => 'Поле ":attribute" обязательно, если ":other" находится в ":values".',
    'required_with'        => 'Поле :attribute обязательно для заполнения, когда :values указано.',
    'required_with_all'    => 'Поле :attribute обязательно для заполнения, когда :values указано.',
    'required_without'     => 'Поле :attribute обязательно для заполнения, когда :values не указано.',
    'required_without_all' => 'Поле :attribute обязательно для заполнения, когда ни одно из :values не указано.',
    'same'                 => 'Значение :attribute должно совпадать с :other.',
    'size'                 => [
        'numeric' => 'Поле :attribute должно быть равным :size.',
        'file'    => 'Размер файла в поле :attribute должен быть равен :size Кб.',
        'string'  => 'Количество символов :attribute должно быть из :size символов.',
        'array'   => 'Количество элементов в поле :attribute должно быть равным :size.',
    ],
    'string'               => ':attribute должен быть строкой.',
    'timezone'             => ':attribute должен быть действительной зоной.',
    'unique'               => ':attribute уже занят.',
    'uploaded'             => 'Не удалось загрузить :attribute.',
    'url'                  => 'Неправильный формат :attribute.',

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
            'rule-name' => 'настраиваемое-сообщение',
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
