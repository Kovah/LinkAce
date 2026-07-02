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

    'accepted'             => 'Je potřeba potvrdit :attribute.',
    'active_url'           => ':attribute není platná URL adresa.',
    'after'                => ':attribute nemůže být dříve než :date.',
    'after_or_equal'       => ':attribute musí být datum po nebo rovno :date.',
    'alpha'                => ':attribute může obsahovat pouze písmena.',
    'alpha_dash'           => ':attribute může obsahovat pouze písmena, číslice, pomlčky a podtržítka.',
    'alpha_num'            => ':attribute může obsahovat pouze písmena a číslice.',
    'array'                => ':attribute musí být pole.',
    'before'               => ':attribute musí být datum před :date.',
    'before_or_equal'      => ':attribute musí být datum před nebo rovné :date.',
    'between'              => [
        'numeric' => ':attribute musí být hodnota mezi :min a :max.',
        'file'    => ':attribute musí být mezi :min - :max kilobajtů.',
        'string'  => ':attribute musí být delší než :min a kratší než :max znaků.',
        'array'   => ':attribute musí mít mezi :min a :max položkami.',
    ],
    'boolean'              => ':attribute musí být pravda nebo nepravda.',
    'confirmed'            => 'Potvrzení :attribute nesouhlasí.',
    'date'                 => ':attribute není platné datum.',
    'date_format'          => ':attribute neodpovídá formátu :format.',
    'different'            => ':attribute a :other se musí lišit.',
    'digits'               => ':attribute musí obsahovat :digits číslic.',
    'digits_between'       => ':attribute musí být v rozmezí :min a :max číslic.',
    'dimensions'           => ':attribute má neplatné rozměry obrázku.',
    'distinct'             => 'Pole :attribute má duplicitní hodnotu.',
    'email'                => ':attribute musí být platná e-mailová adresa.',
    'exists'               => 'Vybraný :attribute je neplatný.',
    'file'                 => ':attribute musí být soubor.',
    'filled'               => 'Pole :attribute musí mít hodnotu.',
    'gt'                   => [
        'numeric' => ':attribute musí být větší než :value.',
        'file'    => ':attribute musí být větší než :value kilobajtů.',
        'string'  => ':attribute musí být větší než :value znaků.',
        'array'   => ':attribute musí obsahovat více než :value položek.',
    ],
    'gte'                  => [
        'numeric' => ':attribute musí být větší nebo rovno :value.',
        'file'    => ':attribute musí být větší nebo roven :value kilobajtů.',
        'string'  => ':attribute musí být větší nebo roven :value znaků.',
        'array'   => ':attribute musí mít :value položky nebo více.',
    ],
    'image'                => ':attribute musí být obrázek.',
    'in'                   => 'Vybraný :attribute je neplatný.',
    'in_array'             => 'Pole :attribute neexistuje v :other.',
    'integer'              => ':attribute musí být celé číslo.',
    'ip'                   => ':attribute musí být platná IP adresa.',
    'ipv4'                 => ':attribute musí být platná IPv4 adresa.',
    'ipv6'                 => ':attribute musí být platná IPv6 adresa.',
    'json'                 => ':attribute musí být platný řetězec JSON.',
    'lt'                   => [
        'numeric' => ':attribute musí být menší než :value.',
        'file'    => ':attribute musí být menší než :value kilobajtů.',
        'string'  => ':attribute musí být menší než :value znaků.',
        'array'   => ':attribute musí mít méně než :value položek.',
    ],
    'lte'                  => [
        'numeric' => ':attribute musí být menší nebo rovno :value.',
        'file'    => ':attribute musí být menší nebo roven :value kilobajtů.',
        'string'  => ':attribute musí mít menší nebo rovno :value znaků.',
        'array'   => ':attribute nesmí obsahovat více než :value položek.',
    ],
    'max'                  => [
        'numeric' => ':attribute nesmí být větší než :max.',
        'file'    => ':attribute nesmí být větší než :max kilobajtů.',
        'string'  => ':attribute nesmí být větší než :max znaků.',
        'array'   => ':attribute nesmí obsahovat více než :max položek.',
    ],
    'mimes'                => ':attribute musí být soubor typu: :values.',
    'mimetypes'            => ':attribute musí být soubor typu: :values.',
    'min'                  => [
        'numeric' => ':attribute musí být alespoň :min.',
        'file'    => ':attribute musí mít alespoň :min kilobajtů.',
        'string'  => ':attribute musí mít alespoň :min znaků.',
        'array'   => ':attribute musí obsahovat alespoň :min položek.',
    ],
    'not_in'               => 'Vybraný :attribute je neplatný.',
    'not_regex'            => 'Formát :attribute je neplatný.',
    'numeric'              => ':attribute musí být číslo.',
    'present'              => ':attribute musí být vyplněno.',
    'regex'                => 'Formát :attribute je neplatný.',
    'required'             => 'Pole :attribute je povinné.',
    'required_if'          => ':attribute je vyžadováno pokud :other je :value.',
    'required_unless'      => ':attribute je vyžadováno pokud :other není v :values.',
    'required_with'        => 'Pole :attribute je vyžadováno, pokud je zvoleno :values.',
    'required_with_all'    => 'Pole :attribute je vyžadováno, pokud je zvoleno :values.',
    'required_without'     => 'Pole :attribute je vyžadováno, pokud :values není k dispozici.',
    'required_without_all' => 'Pole :attribute je vyžadováno, pokud není k dispozici žádná z :values.',
    'same'                 => ':attribute a :other se musí shodovat.',
    'size'                 => [
        'numeric' => ':attribute musí být :size.',
        'file'    => ':attribute musí mít :size kilobajtů.',
        'string'  => ':attribute musí mít :size znaků.',
        'array'   => ':attribute musí obsahovat :size položek.',
    ],
    'string'               => ':attribute musí být řetězec.',
    'timezone'             => ':attribute musí být platná zóna.',
    'unique'               => ':attribute již byl použit.',
    'uploaded'             => ':attribute se nepodařilo nahrát.',
    'url'                  => 'Formát :attribute je neplatný.',

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
        'visibility' => [
            'visibility' => 'Nastavení viditelnosti musí být buď 1 (veřejné), 2 (interní) nebo 3 (soukromé).',
        ],
        'api_token_ability' => [
            'api_token_ability' => 'Token API musí mít alespoň jednu schopnost z předdefinovaných schopností tokenu.',
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
