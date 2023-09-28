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

    'accepted'             => 'Câmpul :attribute trebuie să fie acceptat.',
    'active_url'           => 'Câmpul :attribute nu este un URL valid.',
    'after'                => 'Câmpul :attribute trebuie să fie o dată după :date.',
    'after_or_equal'       => 'Câmpul :attribute trebuie să fie o dată ulterioară sau egală cu :date.',
    'alpha'                => 'Câmpul :attribute poate conține doar litere.',
    'alpha_dash'           => 'Câmpul :attribute poate conține doar litere, numere și cratime.',
    'alpha_num'            => 'Câmpul :attribute poate conține doar litere și numere.',
    'array'                => 'Câmpul :attribute trebuie să fie un array.',
    'before'               => 'Câmpul :attribute trebuie să fie o dată înainte de :date.',
    'before_or_equal'      => 'Câmpul :attribute trebuie să fie o dată înainte sau egală cu :date.',
    'between'              => [
        'numeric' => ':attribute trebuie să fie între :min și :max.',
        'file'    => ':attribute trebuie să aibă dimensiunea cuprinsă între :min și :max kilobaiți.',
        'string'  => ':attribute trebuie să aibă între :min și :max caractere.',
        'array'   => ':attribute trebuie să aibă între :min și :max elemente.',
    ],
    'boolean'              => 'Câmpul :attribute trebuie să fie adevărat sau fals.',
    'confirmed'            => 'Confirmarea :attribute nu se potrivește.',
    'date'                 => 'Câmpul :attribute nu este o dată validă.',
    'date_format'          => 'Câmpul :attribute trebuie să fie în formatul :format.',
    'different'            => 'Câmpurile :attribute și :other trebuie să fie diferite.',
    'digits'               => 'Câmpul :attribute trebuie să aibă :digits cifre.',
    'digits_between'       => 'Câmpul :attribute trebuie să aibă între :min și :max cifre.',
    'dimensions'           => 'Câmpul :attribute are dimensiuni de imagine nevalide.',
    'distinct'             => 'Câmpul :attribute are o valoare duplicat.',
    'email'                => 'Câmpul :attribute trebuie să fie o adresă de e-mail validă.',
    'exists'               => 'Câmpul :attribute selectat nu este valid.',
    'file'                 => 'Câmpul :attribute trebuie să fie un fișier.',
    'filled'               => 'Câmpul :attribute trebuie completat.',
    'gt'                   => [
        'numeric' => ':attribute trebuie să fie mai mare decât :value.',
        'file'    => ':attribute trebuie să aibă dimensiunea mai mare decât :value kilobaiți.',
        'string'  => ':attribute trebuie să aibă mai mult decât :value caractere.',
        'array'   => ':attribute trebuie să aibă mai mult decât :value elemente.',
    ],
    'gte'                  => [
        'numeric' => ':attribute trebuie să fie mai mare sau egal cu :value.',
        'file'    => ':attribute trebuie să aibă dimensiunea egală cu sau mai mare decât :value kilobaiți.',
        'string'  => ':attribute trebuie să fie mai mare sau egal cu :value caractere.',
        'array'   => ':attribute trebuie să aibă :value elemente sau mai multe.',
    ],
    'image'                => 'Câmpul :attribute trebuie să fie o imagine.',
    'in'                   => 'Câmpul :attribute selectat nu este valid.',
    'in_array'             => 'Câmpul :attribute nu există în :other.',
    'integer'              => 'Câmpul :attribute trebuie să fie un număr întreg.',
    'ip'                   => 'Câmpul :attribute trebuie să fie o adresă IP validă.',
    'ipv4'                 => 'Câmpul :attribute trebuie să fie o adresă IPv4 validă.',
    'ipv6'                 => 'Câmpul :attribute trebuie să fie o adresă IPv6 validă.',
    'json'                 => 'Câmpul :attribute trebuie să fie un string JSON valid.',
    'lt'                   => [
        'numeric' => ':attribute trebuie să fie mai mic decât :value.',
        'file'    => ':attribute trebuie să aibă dimensiunea mai mică decât :value kilobaiți.',
        'string'  => ':attribute trebuie să aibă mai puțin decât :value caractere.',
        'array'   => ':attribute trebuie să aibă mai puțin decât :value elemente.',
    ],
    'lte'                  => [
        'numeric' => ':attribute trebuie să fie mai mic sau egal cu :value.',
        'file'    => ':attribute trebuie să aibă dimensiunea egală cu sau mai mică decât :value kilobaiți.',
        'string'  => ':attribute trebuie să fie mai mic sau egal cu :value caractere.',
        'array'   => ':attribute nu trebuie să aibă mai mult decât :value elemente.',
    ],
    'max'                  => [
        'numeric' => ':attribute nu poate fi mai mare decât :max.',
        'file'    => ':attribute nu trebuie să aibă dimensiunea mai mare decât :max kilobaiți.',
        'string'  => ':attribute nu trebuie să aibă mai mult decât :max caractere.',
        'array'   => ':attribute nu trebuie să aibă mai mult decât :value elemente.',
    ],
    'mimes'                => 'Câmpul :attribute trebuie să fie un fișier de tipul: :values.',
    'mimetypes'            => 'Câmpul :attribute trebuie să fie un fișier de tipul: :values.',
    'min'                  => [
        'numeric' => ':attribute trebuie sa aiba cel putin :min.',
        'file'    => ':attribute trebuie să aibă dimensiunea de cel puțin :min kilobaiți.',
        'string'  => ':attribute trebuie să aibă cel puțin :min caractere.',
        'array'   => ':attribute trebuie să aibă cel puțin :min elemente.',
    ],
    'not_in'               => 'Câmpul :attribute selectat nu este valid.',
    'not_regex'            => 'Câmpul :attribute nu are un format valid.',
    'numeric'              => 'Câmpul :attribute trebuie să fie un număr.',
    'present'              => 'Câmpul :attribute trebuie să fie prezent.',
    'regex'                => 'Câmpul :attribute nu are un format valid.',
    'required'             => 'Câmpul :attribute este obligatoriu.',
    'required_if'          => 'Câmpul :attribute este necesar când :other este :value.',
    'required_unless'      => 'Câmpul :attribute este necesar, cu excepția cazului :other este in :values.',
    'required_with'        => 'Câmpul :attribute este necesar când există :values.',
    'required_with_all'    => 'Câmpul :attribute este necesar când există :values.',
    'required_without'     => 'Câmpul :attribute este necesar când nu există :values.',
    'required_without_all' => 'Câmpul :attribute este necesar când niciuna dintre valorile :values nu există.',
    'same'                 => 'Câmpurile :attribute și :other trebuie să fie identice.',
    'size'                 => [
        'numeric' => 'Câmpul :attribute trebuie să fie :size.',
        'file'    => ':attribute trebuie să aibă dimensiunea de :size kilobaiți.',
        'string'  => ':attribute trebuie sa aiba :size caractere.',
        'array'   => 'Câmpul :attribute trebuie să aibă :size elemente.',
    ],
    'string'               => 'Câmpul :attribute trebuie să fie string.',
    'timezone'             => 'Câmpul :attribute trebuie să fie un fus orar valid.',
    'unique'               => 'Câmpul :attribute a fost deja folosit.',
    'uploaded'             => 'Câmpul :attribute nu a reușit încărcarea.',
    'url'                  => 'Câmpul :attribute nu este un URL valid.',

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
            'rule-name' => 'mesaj-personalizat',
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
