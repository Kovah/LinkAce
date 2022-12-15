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

    'accepted'             => 'El camp :attribute ha de ser acceptat.',
    'active_url'           => 'El camp :attribute no és una URL vàlida.',
    'after'                => 'El camp :attribute ha de ser una data posterior a :date.',
    'after_or_equal'       => 'El camp :attribute ha de ser una data posterior o igual a :date.',
    'alpha'                => 'El camp :attribute només pot contenir lletres.',
    'alpha_dash'           => 'El camp :attribute només pot contenir lletres, números i guions.',
    'alpha_num'            => 'El camp :attribute només pot contenir lletres i números.',
    'array'                => 'El camp :attribute ha de ser una matriu.',
    'before'               => 'El camp :attribute ha de ser una data anterior a :date.',
    'before_or_equal'      => 'El camp :attribute ha de ser una data anterior o igual a :date.',
    'between'              => [
        'numeric' => 'El camp :attribute ha d\'estar entre :min i :max.',
        'file'    => 'El camp :attribute ha de tenir entre :min i :max kilobytes.',
        'string'  => 'El camp :attribute ha de tenir entre :min i :max caràcters.',
        'array'   => 'El camp :attribute ha de tenir entre :min i :max elements.',
    ],
    'boolean'              => 'El camp :attribute ha de ser verdader o fals.',
    'confirmed'            => 'La confirmació de :attribute no coincideix.',
    'date'                 => 'El camp :attribute no és una data vàlida.',
    'date_format'          => 'El camp :attribute no concorda amb el format :format.',
    'different'            => ':attribute i :other han de coincidir.',
    'digits'               => 'El camp :attribute ha de tenir :digits dígits.',
    'digits_between'       => 'El camp :attribute ha de tenir entre :min i :max dígits.',
    'dimensions'           => 'Les dimensions de la imatge :attribute no són vàlides.',
    'distinct'             => 'El camp :attribute té un valor duplicat.',
    'email'                => 'El camp :attribute ha de ser una adreça de correu electrònic vàlida.',
    'exists'               => 'El camp :attribute seleccionat és invàlid.',
    'file'                 => 'El camp :attribute ha de ser un arxiu.',
    'filled'               => 'El camp :attribute ha de ser un valor numèric.',
    'gt'                   => [
        'numeric' => 'El camp :attribute ha de ser més gran de :max.',
        'file'    => 'El camp :attribute ha de ser més gran de :max kilobytes.',
        'string'  => 'El camp :attribute ha de ser més gran de :value caràcters.',
        'array'   => 'El camp :attribute ha de tenir més de :value elements.',
    ],
    'gte'                  => [
        'numeric' => 'El camp :attribute ha de ser més gran o igual de :value.',
        'file'    => 'El camp :attribute ha de ser més gran o igual de :value kilobytes.',
        'string'  => 'El camp :attribute ha de tenir més o igual de :value caràcters.',
        'array'   => ':attribute ha de tenir :value elements o més.',
    ],
    'image'                => 'El camp :attribute ha de ser una imatge.',
    'in'                   => 'El camp :attribute seleccionat és invàlid.',
    'in_array'             => 'El camp :attribute no existeix dins de :other.',
    'integer'              => 'El camp :attribute ha de ser un nombre sencer.',
    'ip'                   => 'El camp :attribute ha de ser una adreça IP vàlida.',
    'ipv4'                 => 'El camp :attribute ha de ser una adreça IPv4 vàlida.',
    'ipv6'                 => 'El camp :attribute ha de ser una adreça IPv6 vàlida.',
    'json'                 => 'El camp :attribute ha de ser una cadena JSON vàlida.',
    'lt'                   => [
        'numeric' => 'El camp :attribute ha de ser menys de :value.',
        'file'    => 'El camp :attribute ha de ser menys de :value kilobytes.',
        'string'  => 'El camp :attribute ha de ser menys de :value caràcters.',
        'array'   => 'El camp :attribute ha de tenir menys de :value elements.',
    ],
    'lte'                  => [
        'numeric' => 'El camp :attribute ha de ser menys o igual de :value.',
        'file'    => 'El camp :attribute ha de ser menys o igual de :value kilobytes.',
        'string'  => 'The :attribute must be less than or equal :value characters.',
        'array'   => 'El camp :attribute no ha de tenir més de :value elements.',
    ],
    'max'                  => [
        'numeric' => 'El camp :attribute no pot ser més gran de :max.',
        'file'    => 'El camp :attribute no pot ser més gran de :max kilobytes.',
        'string'  => 'El camp :attribute no pot tenir més de :max caràcters.',
        'array'   => 'El camp :attribute no pot tenir més de :max elements.',
    ],
    'mimes'                => 'El camp :attribute ha de ser un arxiu amb format: :values.',
    'mimetypes'            => 'El camp :attribute ha de ser un arxiu amb format: :values.',
    'min'                  => [
        'numeric' => 'La mida de :attribute ha de ser d\'almenys :min.',
        'file'    => 'La mida de :attribute ha de ser d\'almenys :min kilobytes.',
        'string'  => 'El camp :attribute ha de contenir almenys :min caràcters.',
        'array'   => 'El camp :attribute ha de tenir almenys :min elements.',
    ],
    'not_in'               => 'El camp :attribute seleccionat és invàlid.',
    'not_regex'            => 'El camp :attribute és d\'un format invàlid.',
    'numeric'              => 'El camp :attribute ha de ser numèric.',
    'present'              => 'El camp :attribute ha d\'existir.',
    'regex'                => 'El camp :attribute és d\'un format invàlid.',
    'required'             => 'El camp :attribute es requereix.',
    'required_if'          => 'El camp :attribute és obligatori quan :other és :value.',
    'required_unless'      => 'El camp :attribute és obligatori a no ser que :other sigui a :values.',
    'required_with'        => 'El camp :attribute és obligatori quan hi ha :values.',
    'required_with_all'    => 'El camp :attribute és obligatori quan hi ha :values.',
    'required_without'     => 'El camp :attribute és obligatori quan no hi ha :values.',
    'required_without_all' => 'El camp :attribute és obligatori quan no hi ha cap valor dels següents: :values.',
    'same'                 => ':attribute i :other han de coincidir.',
    'size'                 => [
        'numeric' => 'El tamany de :attribute ha de ser :size.',
        'file'    => 'El tamany de :attribute ha de ser :size kilobytes.',
        'string'  => 'El camp :attribute ha de ser d\'almenys :size caràcters.',
        'array'   => ':attribute ha de contenir :size elements.',
    ],
    'string'               => 'El camp :attribute ha de ser una cadena.',
    'timezone'             => 'El camp :attribute ha de ser una zona vàlida.',
    'unique'               => ':attribute ja s\'havia introduït.',
    'uploaded'             => ':attribute no s\'ha pogut pujar.',
    'url'                  => 'El camp :attribute és d\'un format invàlid.',

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
            'rule-name' => 'personalitza el missatge',
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
