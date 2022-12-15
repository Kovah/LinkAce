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

    'accepted'             => 'Le champ :attribute doit être accepté.',
    'active_url'           => 'Le champ :attribute n\'est pas une URL valide.',
    'after'                => 'Le champ :attribute doit être une date postérieure à :date.',
    'after_or_equal'       => 'Le champ :attribute doit être une date postérieure ou égale à :date.',
    'alpha'                => 'Le champ :attribute ne peut contenir que des lettres.',
    'alpha_dash'           => 'Le champ :attribute ne peut contenir que des lettres, des chiffres, des tirets et des tirets bas.',
    'alpha_num'            => 'Le champ :attribute ne peut contenir que des lettres et des chiffres.',
    'array'                => 'Le champ :attribute doit être un tableau.',
    'before'               => 'Le champ :attribute doit être une date antérieure à :date.',
    'before_or_equal'      => 'Le champ :attribute doit être une date antérieure ou égale à :date.',
    'between'              => [
        'numeric' => 'Le champ :attribute doit être entre :min et :max.',
        'file'    => 'Le champ :attribute doit être compris entre :min et :max kilo-octets.',
        'string'  => 'Le champ :attribute doit contenir entre :min et :max caractères.',
        'array'   => 'Le champ :attribute doit avoir entre :min et :max éléments.',
    ],
    'boolean'              => 'Le champ :attribute doit être vrai ou faux.',
    'confirmed'            => 'La confirmation de :attribute ne correspond pas.',
    'date'                 => 'Le champ :attribute n\'est pas une date valide.',
    'date_format'          => 'Le champ :attribute ne correspond pas au format :format.',
    'different'            => 'Les champs :attribute et :other doivent être différents.',
    'digits'               => 'Le champ :attribute doit être de :digits chiffres.',
    'digits_between'       => 'Le champ :attribute doit contenir entre :min et :max chiffres.',
    'dimensions'           => 'Le champ :attribute a des dimensions d\'image non valides.',
    'distinct'             => 'Le champ :attribute a une valeur en double.',
    'email'                => 'Le champ :attribute doit être une adresse e-mail valide.',
    'exists'               => 'Le champ :attribute sélectionné est invalide.',
    'file'                 => 'Le champ :attribute doit être un fichier.',
    'filled'               => 'Le champ :attribute doit avoir une valeur.',
    'gt'                   => [
        'numeric' => 'Le champ :attribute doit être supérieur à :value.',
        'file'    => 'Le champ :attribute doit être plus grand que :value kilo-octets.',
        'string'  => 'Le champ :attribute doit être supérieur à :value caractères.',
        'array'   => 'Le champ :attribute doit avoir plus de :value éléments.',
    ],
    'gte'                  => [
        'numeric' => 'Le champ :attribute doit être supérieur ou égal à :value.',
        'file'    => 'Le champ :attribute doit être supérieur ou égal à :value kilo-octets.',
        'string'  => 'Le champ :attribute doit être supérieur ou égal à :value caractères.',
        'array'   => 'L\'attribut :attribute doit avoir :value éléments ou plus.',
    ],
    'image'                => 'Le champ :attribute doit être une image.',
    'in'                   => 'Le champ :attribute sélectionné est invalide.',
    'in_array'             => 'Le champ :attribute n\'existe pas dans :other.',
    'integer'              => 'Le champ :attribute doit être un nombre entier.',
    'ip'                   => 'Le champ :attribute doit être une adresse IP valide.',
    'ipv4'                 => 'Le champ :attribute doit être une adresse IPv4 valide.',
    'ipv6'                 => 'Le champ :attribute doit être une adresse IPv6 valide.',
    'json'                 => 'Le champ :attribute doit être une chaîne de caractères JSON valide.',
    'lt'                   => [
        'numeric' => 'Le champ :attribute doit être inférieur à :value.',
        'file'    => 'Le champ :attribute doit être inférieur à :value kilo-octets.',
        'string'  => 'Le champ :attribute doit être inférieur à :value caractères.',
        'array'   => 'Le champ :attribute doit avoir moins de :value éléments.',
    ],
    'lte'                  => [
        'numeric' => 'Le champ :attribute doit être inférieur ou égal à :value.',
        'file'    => 'Le champ :attribute doit être inférieur ou égal à :value kilo-octets.',
        'string'  => 'Le champ :attribute doit être inférieur ou égal à :value caractères.',
        'array'   => 'Le champ :attribute ne doit pas avoir plus de :value éléments.',
    ],
    'max'                  => [
        'numeric' => 'Le champ :attribute ne peut pas être plus grand que :max.',
        'file'    => 'Le champ :attribute ne doit pas être plus grand que :max kilo-octets.',
        'string'  => 'Le champ :attribute ne doit pas être supérieur à :max caractères.',
        'array'   => 'Le champ :attribute ne peut pas avoir plus de :max éléments.',
    ],
    'mimes'                => 'Le champ :attribute doit être un fichier de type : :values.',
    'mimetypes'            => 'Le champ :attribute doit être un fichier de type : :values.',
    'min'                  => [
        'numeric' => 'Le champ :attribute doit être d\'au moins :min.',
        'file'    => 'Le champ :attribute doit être d\'au moins :min kilo-octets.',
        'string'  => 'Le champ :attribute doit contenir au moins :min caractères.',
        'array'   => 'Le champ :attribute doit avoir au moins :min éléments.',
    ],
    'not_in'               => 'Le champ :attribute sélectionné est invalide.',
    'not_regex'            => 'Le format de l\':attribute est invalide.',
    'numeric'              => 'Le champ :attribute doit être un nombre.',
    'present'              => 'Le champ :attribute doit être présent.',
    'regex'                => 'Le format de l\':attribute est invalide.',
    'required'             => 'Le champ :attribute est requis.',
    'required_if'          => 'Le champ :attribute est requis lorsque :other est :value.',
    'required_unless'      => 'Le champ :attribute est requis sauf si :other est en :values.',
    'required_with'        => 'Le champ :attribute est requis lorsque :values est présent.',
    'required_with_all'    => 'Le champ :attribute est requis lorsque :values est présent.',
    'required_without'     => 'Le champ :attribute est requis lorsque :values n\'est pas présent.',
    'required_without_all' => 'Le champ :attribute est requis quand aucune des :values n\'est présente.',
    'same'                 => 'Les champs :attribute et :other doivent correspondre.',
    'size'                 => [
        'numeric' => 'Le champ :attribute doit être :size.',
        'file'    => 'Le champ :attribute doit être de :size kilo-octets.',
        'string'  => 'Le champ :attribute doit être de :size caractères.',
        'array'   => 'Le champ :attribute doit contenir :size éléments.',
    ],
    'string'               => 'Le champ :attribute doit être une chaîne de caractères.',
    'timezone'             => 'Le champ :attribute doit être une zone valide.',
    'unique'               => 'Le champ :attribute a déjà été pris.',
    'uploaded'             => 'L\'attribut :attribute n\'a pas pu être téléversé.',
    'url'                  => 'Le format de l\':attribute est invalide.',

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
            'rule-name' => 'message personnalisé',
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
