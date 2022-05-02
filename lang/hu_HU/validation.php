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

    'accepted'             => 'A(z) :attribute el kell legyen fogadva.',
    'active_url'           => 'A(z) :attribute nem egy érvényes URL-cím.',
    'after'                => 'A(z) :attribute :date utáni dátumnak kell lennie.',
    'after_or_equal'       => 'A(z) :attribute :date utáni vagy vele egyenlő dátumnak kell lennie.',
    'alpha'                => 'A(z) :attribute csak betűket tartalmazhat.',
    'alpha_dash'           => 'A(z) :attribute csak betűket, számokat, kötőjeleket és aláhúzásjeleket tartalmazhat.',
    'alpha_num'            => 'A(z) :attribute csak betűket és számokat tartalmazhat.',
    'array'                => 'A(z) :attribute csak tömb lehet.',
    'before'               => 'A(z) :attribute :date előtti dátumnak kell lennie.',
    'before_or_equal'      => 'A(z) :attribute :date előtti vagy vele egyenlő dátumnak kell lennie.',
    'between'              => [
        'numeric' => 'A(z) :attribute :min és :max között kell lennie.',
        'file'    => 'A(z) :attribute :min és :max kilobájt között kell lennie.',
        'string'  => 'A(z) :attribute :min és :max karakter között kell lennie.',
        'array'   => 'A(z) :attribute legalább :min és legfeljebb :max elemet tartalmazhat.',
    ],
    'boolean'              => 'A(z) :attribute-mezőnek igaznak vagy hamisnak kell lennie.',
    'confirmed'            => 'A(z) :attribute megerősítése nem egyezik.',
    'date'                 => 'A(z) :attribute nem egy érvényes dátum.',
    'date_format'          => 'A(z) :attribute nem felel meg a következő formátumnak: :format.',
    'different'            => 'A(z) :attribute és a(z) :other értékének különbözőnek kell lennie.',
    'digits'               => 'A(z) :attribute :digits számjegyűnek kell lennie.',
    'digits_between'       => 'A(z) :attribute legalább :min és legfeljebb :max számjegy lehet.',
    'dimensions'           => 'A(z) :attribute képméretei érvénytelenek.',
    'distinct'             => 'A(z) :attribute mező ismétlődő értéket tartalmaz.',
    'email'                => 'A(z) :attribute egy érvényes e-mail-cím kell legyen.',
    'exists'               => 'A kiválasztott :attribute érvénytelen.',
    'file'                 => 'A(z) :attribute egy fájl kell legyen.',
    'filled'               => 'A(z) :attribute-mezőnek rendelkeznie kell értékkel.',
    'gt'                   => [
        'numeric' => 'A(z) :attribute nagyobb kell legyen, mint :value.',
        'file'    => 'A(z) :attribute nagyobb kell legyen, mint :value kilobájt.',
        'string'  => 'A(z) :attribute nagyobb kell legyen, mint :value karakter.',
        'array'   => 'A(z) :attribute több mint :value elemet kell tartalmazzon.',
    ],
    'gte'                  => [
        'numeric' => 'A(z) :attribute legalább :value kell legyen.',
        'file'    => 'A(z) :attribute legalább :value kilobájt kell legyen.',
        'string'  => 'A(z) :attribute legalább :value karakter kell legyen.',
        'array'   => 'A(z) :attribute legalább :value elemet kell tartalmazzon.',
    ],
    'image'                => 'A(z) :attribute egy kép kell legyen.',
    'in'                   => 'A kiválasztott :attribute érvénytelen.',
    'in_array'             => 'A(z) :attribute-mező nem létezik a következőben: :other.',
    'integer'              => 'A(z) :attribute csak egész szám lehet.',
    'ip'                   => 'A(z) :attribute egy érvényes IP-cím kell legyen.',
    'ipv4'                 => 'A(z) :attribute egy érvényes IPv4-cím kell legyen.',
    'ipv6'                 => 'A(z) :attribute egy érvényes IPv6-cím kell legyen.',
    'json'                 => 'A(z) :attribute egy érvényes JSON-karakterlánc kell legyen.',
    'lt'                   => [
        'numeric' => 'A(z) :attribute kisebb kell legyen, mint :value.',
        'file'    => 'A(z) :attribute kisebb kell legyen, mint :value kilobájt.',
        'string'  => 'A(z) :attribute kisebb kell legyen, mint :value karakter.',
        'array'   => 'A(z) :attribute kevesebb mint :value elemet kell tartalmazzon.',
    ],
    'lte'                  => [
        'numeric' => 'A(z) :attribute legfeljebb :value lehet.',
        'file'    => 'A(z) :attribute legfeljebb :value kilobájt lehet.',
        'string'  => 'A(z) :attribute legfeljebb :value karakter lehet.',
        'array'   => 'A(z) :attribute legfeljebb :value elemet tartalmazhat.',
    ],
    'max'                  => [
        'numeric' => 'A(z) :attribute nem lehet nagyobb, mint :max.',
        'file'    => 'A(z) :attribute nem lehet nagyobb, mint :max kilobájt.',
        'string'  => 'A(z) :attribute nem lehet nagyobb, mint :max karakter.',
        'array'   => 'A(z) :attribute nem tartalmazhat több mint :max elemet.',
    ],
    'mimes'                => 'A(z) :attribute egy következő típusú fájlnak kell lennie: :values.',
    'mimetypes'            => 'A(z) :attribute egy következő típusú fájlnak kell lennie: :values.',
    'min'                  => [
        'numeric' => 'A(z) :attribute nem lehet kisebb, mint :min.',
        'file'    => 'A(z) :attribute nem lehet kisebb, mint :min kilobájt.',
        'string'  => 'A(z) :attribute nem lehet kisebb, mint :min karakter.',
        'array'   => 'A(z) :attribute nem tartalmazhat kevesebb mint :min elemet.',
    ],
    'not_in'               => 'A kiválasztott :attribute érvénytelen.',
    'not_regex'            => 'A(z) :attribute formátuma érvénytelen.',
    'numeric'              => 'A(z) :attribute szám kell legyen.',
    'present'              => 'A(z) :attribute-mező jelen kell legyen.',
    'regex'                => 'A(z) :attribute formátuma érvénytelen.',
    'required'             => 'A(z) :attribute-mező kötelező.',
    'required_if'          => 'A(z) :attribute-mező kötelező, ha :other :values.',
    'required_unless'      => 'A(z) :attribute-mező kötelező, kivéve, ha :other a következőben található: :values.',
    'required_with'        => 'A(z) :attribute-mező kötelező, ha :values jelen van.',
    'required_with_all'    => 'A(z) :attribute-mező kötelező, ha :values jelen van.',
    'required_without'     => 'A(z) :attribute-mező kötelező, ha :values nincs jelen.',
    'required_without_all' => 'A(z) :attribute-mező kötelező, ha egyik :values sincs jelen.',
    'same'                 => 'A(z) :attribute és a(z) :other kell egyezzen.',
    'size'                 => [
        'numeric' => 'A(z) :attribute :size kell legyen.',
        'file'    => 'A(z) :attribute :size kilobájt kell legyen.',
        'string'  => 'A(z) :attribute :size karakter kell legyen.',
        'array'   => 'A(z) :attribute :size elemet kell tartalmazzon.',
    ],
    'string'               => 'A(z) :attribute egy érvényes karakterlánc kell legyen.',
    'timezone'             => 'A(z) :attribute egy érvényes időzóna kell legyen.',
    'unique'               => 'A(z) :attribute már le van foglalva.',
    'uploaded'             => 'A(z) :attribute feltöltése nem sikerült.',
    'url'                  => 'A(z) :attribute formátuma érvénytelen.',

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
            'rule-name' => 'egyéni-üzenet',
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
