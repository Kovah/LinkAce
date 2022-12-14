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

    'accepted'             => ':attribute deve essere accettato.',
    'active_url'           => ':attribute non è un URL valido.',
    'after'                => ':attribute deve essere una data successiva al :date.',
    'after_or_equal'       => ':attribute deve essere una data successiva o uguale al :date.',
    'alpha'                => ':attribute può contenere solo lettere.',
    'alpha_dash'           => ':attribute può contenere solo lettere, numeri, trattini e trattini bassi.',
    'alpha_num'            => ':attribute può contenere solo lettere e numeri.',
    'array'                => ':attribute deve essere un array.',
    'before'               => ':attribute deve essere una data precedente al :date.',
    'before_or_equal'      => ':attribute deve essere una data precedente o uguale al :date.',
    'between'              => [
        'numeric' => ':attribute deve essere compreso tra :min e :max.',
        'file'    => ':attribute deve essere compreso tra :min e :max kilobyte.',
        'string'  => ':attribute deve essere tra :min - :max caratteri.',
        'array'   => ':attribute deve contenere almeno :min - :max elementi.',
    ],
    'boolean'              => 'Il campo :attribute deve essere vero o falso.',
    'confirmed'            => 'La conferma di :attribute non corrisponde.',
    'date'                 => ':attribute non è una data valida.',
    'date_format'          => ':attribute non coincide con il formato :format.',
    'different'            => ':attribute e :other devono corrispondere.',
    'digits'               => ':attribute deve essere composto da :digits cifre.',
    'digits_between'       => ':attribute deve essere composto da :min a :max cifre.',
    'dimensions'           => ':attribute ha dimensioni dell\'immagine non valide.',
    'distinct'             => 'Il campo :attribute contiene un valore duplicato.',
    'email'                => ':attribute deve essere un indirizzo email valido.',
    'exists'               => 'Il campo :attribute selezionato non è valido.',
    'file'                 => 'Il campo :attribute deve essere un file.',
    'filled'               => 'Il campo :attribute deve avere un valore.',
    'gt'                   => [
        'numeric' => 'Il campo :attribute deve essere maggiore di :value.',
        'file'    => 'Il campo :attribute deve essere maggiore di :value kilobytes.',
        'string'  => 'Il campo :attribute deve contenere più di :value caratteri.',
        'array'   => 'Il campo :attribute deve contenere più di :value elementi.',
    ],
    'gte'                  => [
        'numeric' => 'Il campo :attribute deve essere maggiore o uguale a :value.',
        'file'    => 'Il campo :attribute deve essere maggiore o uguale a :value kilobyte.',
        'string'  => 'Il campo :attribute deve essere composto da almeno :value caratteri.',
        'array'   => 'Il campo :attribute deve contenere almeno :value elementi.',
    ],
    'image'                => 'Il campo :attribute deve essere un\'immagine.',
    'in'                   => 'Il campo :attribute selezionato non è valido.',
    'in_array'             => 'Il campo :attribute non esiste in :other.',
    'integer'              => 'Il campo :attribute deve essere un numero intero.',
    'ip'                   => 'Il campo :attribute deve essere un indirizzo IP valido.',
    'ipv4'                 => 'Il campo :attribute deve essere un indirizzo IPv4 valido.',
    'ipv6'                 => 'Il campo :attribute deve essere un indirizzo IPv6 valido.',
    'json'                 => 'Il campo :attribute deve essere una stringa JSON valida.',
    'lt'                   => [
        'numeric' => 'Il campo :attribute deve essere inferiore a :value.',
        'file'    => 'Il campo :attribute deve essere inferiore a :value kilobytes.',
        'string'  => 'Il campo :attribute deve contenere meno di :min caratteri.',
        'array'   => 'Il campo :attribute deve contenere meno di :value elementi.',
    ],
    'lte'                  => [
        'numeric' => 'Il campo :attribute deve essere inferiore o uguale a :value.',
        'file'    => 'Il campo :attribute deve essere inferiore o uguale a :value kilobytes.',
        'string'  => 'Il campo :attribute deve essere inferiore o uguale a :value caratteri.',
        'array'   => 'Il campo :attribute non può avere più di :value elementi.',
    ],
    'max'                  => [
        'numeric' => 'Il campo :attribute non può essere maggiore di :max.',
        'file'    => 'Il campo :attribute non può essere maggiore di :max kilobyte.',
        'string'  => 'Il campo :attribute non può essere maggiore di :max caratteri.',
        'array'   => 'Il campo :attribute non può avere più di :max elementi.',
    ],
    'mimes'                => 'Il campo :attribute deve essere un file di tipo: :values.',
    'mimetypes'            => 'Il campo :attribute deve essere un file di tipo: :values.',
    'min'                  => [
        'numeric' => 'Il campo :attribute deve essere almeno :min.',
        'file'    => 'Il campo :attribute deve essere almeno di :value kilobytes.',
        'string'  => 'Il campo :attribute deve essere almeno di :min caratteri.',
        'array'   => ':attribute deve contenere almeno :min elementi.',
    ],
    'not_in'               => ':attribute selezionato non è valido.',
    'not_regex'            => 'Il formato di :attribute non è valido.',
    'numeric'              => ':attribute deve essere un numero.',
    'present'              => 'Il campo :attribute deve essere presente.',
    'regex'                => 'Il formato di :attribute non è valido.',
    'required'             => 'Il campo :attribute è richiesto.',
    'required_if'          => 'Il campo :attribute è richiesto quando :other è :value.',
    'required_unless'      => 'Il campo :attribute è obbligatorio a meno che :other sia contenuto in :values.',
    'required_with'        => 'Il campo :attribute è obbligatorio quando :values è presente.',
    'required_with_all'    => 'Il campo :attribute è obbligatorio quando :values è presente.',
    'required_without'     => 'Il campo :attribute è obbligatorio quando :values non è presente.',
    'required_without_all' => 'Il campo :attribute è obbligatorio quando nessuno dei :values è presente.',
    'same'                 => ':attribute e :other devono corrispondere.',
    'size'                 => [
        'numeric' => ':attribute deve essere :size.',
        'file'    => ':attribute deve essere :size kilobytes.',
        'string'  => ':attribute deve contenere :size caratteri.',
        'array'   => ':attribute deve contenere :size elementi.',
    ],
    'string'               => ':attribute deve essere una stringa.',
    'timezone'             => ':attribute deve essere una zona fuso orario valida.',
    'unique'               => ':attribute è già stato utilizzato.',
    'uploaded'             => ':attribute non è stato caricato.',
    'url'                  => 'Il formato :attribute non è valido.',

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
            'rule-name' => 'messaggio-personalizzato',
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
