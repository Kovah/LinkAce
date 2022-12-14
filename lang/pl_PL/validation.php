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

    'accepted'             => ':attribute musi zostać zaakceptowany.',
    'active_url'           => ':attribute nie jest prawidłowym adresem URL.',
    'after'                => ':attribute musi być datą po :date.',
    'after_or_equal'       => ':attribute musi być datą po lub równą :date.',
    'alpha'                => ':attribute może zawierać tylko litery.',
    'alpha_dash'           => ':attribute może zawierać tylko litery, cyfry, myślniki i podkreślenia.',
    'alpha_num'            => ':attribute może zawierać tylko litery i cyfry.',
    'array'                => ':attribute musi być tablicą.',
    'before'               => ':attribute musi być datą przed :date.',
    'before_or_equal'      => ':attribute musi być datą przed lub równą :date.',
    'between'              => [
        'numeric' => ':attribute musi być pomiędzy :min a :max.',
        'file'    => ':attribute musi zawierać pomiędzy :min a :max kilobajtów.',
        'string'  => ':attribute musi mieć od :min do :max znaków.',
        'array'   => ':attribute musi mieć od :min do :max elementów.',
    ],
    'boolean'              => ':attribute musi mieć wartość prawda albo fałsz.',
    'confirmed'            => 'Potwierdzenie :attribute nie pasuje.',
    'date'                 => ':attribute nie jest prawidłową datą.',
    'date_format'          => ':attribute nie pasuje do formatu :format.',
    'different'            => ':attribute i :other muszą być różne.',
    'digits'               => ':attribute musi mieć :digits cyfr.',
    'digits_between'       => ':attribute musi zawierać się między :min a :max cyfr.',
    'dimensions'           => ':attribute ma nieprawidłowe wymiary obrazu.',
    'distinct'             => 'Pole :attribute ma zduplikowane wartości.',
    'email'                => ':attribute musi być prawidłowym adresem email.',
    'exists'               => 'Wybrany :attribute jest nieprawidłowy.',
    'file'                 => ':attribute musi być plikiem.',
    'filled'               => 'Pole :attribute musi mieć wartość.',
    'gt'                   => [
        'numeric' => ':attribute musi być większy niż :value.',
        'file'    => ':attribute musi być większy niż :value kilobajtów.',
        'string'  => ':attribute musi być większy niż :value znaków.',
        'array'   => ':attribute musi mieć więcej niż :value elementów.',
    ],
    'gte'                  => [
        'numeric' => ':attribute musi być większy lub równy :value.',
        'file'    => ':attribute musi być większy lub równy :value kilobajtów.',
        'string'  => ':attribute musi być większy lub równy :value znaków.',
        'array'   => ':attribute musi mieć :value elementów lub więcej.',
    ],
    'image'                => ':attribute musi być zdjęciem.',
    'in'                   => 'Wybrany :attribute jest nieprawidłowy.',
    'in_array'             => 'Pole :attribute nie istnieje w :other.',
    'integer'              => ':attribute musi być liczbą całkowitą.',
    'ip'                   => ':attribute musi być prawidłowym adresem IP.',
    'ipv4'                 => ':attribute musi być prawidłowym adresem IPv4.',
    'ipv6'                 => ':attribute musi być prawidłowym adresem IPv6.',
    'json'                 => ':attribute musi być prawidłowym ciągiem znaków JSON.',
    'lt'                   => [
        'numeric' => ':attribute musi być mniejszy niż :value.',
        'file'    => ':attribute musi być mniejszy niż :value kilobajtów.',
        'string'  => ':attribute musi być mniejszy niż :value znaków.',
        'array'   => ':attribute musi mieć mniej niż :value elementów.',
    ],
    'lte'                  => [
        'numeric' => ':attribute musi być mniejszy lub równy :value.',
        'file'    => ':attribute musi być mniejszy lub równy :value kilobajtów.',
        'string'  => ':attribute musi być mniejszy lub równy :value znaków.',
        'array'   => ':attribute nie może mieć więcej niż :value elementów.',
    ],
    'max'                  => [
        'numeric' => ':attribute nie może być większy niż :max.',
        'file'    => ':attribute nie może być większy niż :max kilobajtów.',
        'string'  => ':attribute nie może być większy niż :max znaków.',
        'array'   => ':attribute nie może mieć więcej niż :max elementów.',
    ],
    'mimes'                => ':attribute musi być plikiem typu: :values.',
    'mimetypes'            => ':attribute musi być plikiem typu: :values.',
    'min'                  => [
        'numeric' => ':attribute musi być co najmniej :min.',
        'file'    => ':attribute musi mieć co najmniej :min kilobajtów.',
        'string'  => ':attribute musi mieć co najmniej :min znaków.',
        'array'   => ':attribute musi mieć co najmniej :min elementów.',
    ],
    'not_in'               => 'Wybrany :attribute jest nieprawidłowy.',
    'not_regex'            => 'Format :attribute jest nieprawidłowy.',
    'numeric'              => ':attribute musi być liczbą.',
    'present'              => 'Pole :attribute musi być obecne.',
    'regex'                => 'Format :attribute jest nieprawidłowy.',
    'required'             => 'Pole :attribute jest wymagane.',
    'required_if'          => 'Pole :attribute jest wymagane, gdy :other jest :value.',
    'required_unless'      => 'Pole :attribute jest wymagane, chyba że :other jest w :values.',
    'required_with'        => 'Pole :attribute jest wymagane, gdy :values jest obecne.',
    'required_with_all'    => 'Pole :attribute jest wymagane, gdy :values jest obecne.',
    'required_without'     => 'Pole :attribute jest wymagane, gdy :values nie jest obecny.',
    'required_without_all' => 'Pole :attribute jest wymagane, gdy żaden z :values nie jest obecny.',
    'same'                 => ':attribute i :other muszą się zgadzać.',
    'size'                 => [
        'numeric' => ':attribute musi być :size.',
        'file'    => ':attribute musi mieć :size kilobajtów.',
        'string'  => ':attribute musi mieć :size znaków.',
        'array'   => ':attribute musi zawierać :size elementów.',
    ],
    'string'               => ':attribute musi być ciągiem liter.',
    'timezone'             => ':attribute musi być prawidłową strefą.',
    'unique'               => ':attribute jest już zajęty.',
    'uploaded'             => ':attribute nie udało się przesłać.',
    'url'                  => 'Format :attribute jest nieprawidłowy.',

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
            'rule-name' => 'Wiadomość niestandardowa',
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
