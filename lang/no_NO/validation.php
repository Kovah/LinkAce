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

    'accepted'             => ':attribute må bli aksepteres.',
    'active_url'           => ':attribute er ikke en gyldig nettadresse.',
    'after'                => ':attribute må være en dato etter :date.',
    'after_or_equal'       => ':attribute må være en dato etter eller lik :date.',
    'alpha'                => ':attribute kan kun inneholde bokstaver.',
    'alpha_dash'           => ':attribute kan kun inneholde bokstaver, tall, bindestreker og understreker.',
    'alpha_num'            => ':attribute kan bare inneholde bokstaver og tall.',
    'array'                => ':attribute må være en matrise.',
    'before'               => ':attribute må være en dato før :date.',
    'before_or_equal'      => ':attribute må være en dato før eller lik :date.',
    'between'              => [
        'numeric' => ':attribute må v\'re mellom :min og :max.',
        'file'    => ':attribute må være mellom :min og :max kilobytes.',
        'string'  => ':attribute må inneholde mellom :min og :max tegn.',
        'array'   => ':attribute må inneholde mellom :min og :max elementer.',
    ],
    'boolean'              => ':attribute feltet må være sann eller usann.',
    'confirmed'            => ':attribute bekreftelsen stemmer ikke overens.',
    'date'                 => ':attribute er ikke en gyldig dato.',
    'date_format'          => ':attribute samsvarer ikke med formatet :format.',
    'different'            => ':attribute og :other må være forskjellige.',
    'digits'               => ':attribute må være :digits sifre.',
    'digits_between'       => ':attribute må være mellom :min og :max sifre.',
    'dimensions'           => ':attribute har ugyldige bildedimensjoner.',
    'distinct'             => ':attribute feltet har en duplisert verdi.',
    'email'                => ':attribute må være en gyldig e-postadresse.',
    'exists'               => 'Det valgte attributtet :attribute er ugyldig.',
    'file'                 => ':attribute må være en fil.',
    'filled'               => ':attribute må ha en verdi.',
    'gt'                   => [
        'numeric' => ':attribute må være større enn :value.',
        'file'    => ':attribute må være større enn :value kilobytes.',
        'string'  => ':attribute må inneholde mer enn :value tegn.',
        'array'   => ':attribute må inneholde mer enn :value elementer.',
    ],
    'gte'                  => [
        'numeric' => ':attribute må være større enn eller lik :value.',
        'file'    => ':attribute må være større enn eller lik :value kilobytes.',
        'string'  => ':attribute må inneholde mer enn eller lik :value tegn.',
        'array'   => ':attribute må inneholde :value elementer eller flere.',
    ],
    'image'                => ':attribute må være et bilde.',
    'in'                   => 'Det valgte attributtet :attribute er ugyldig.',
    'in_array'             => ':attribute feltet finnes ikke i :other.',
    'integer'              => ':attribute må være en integer.',
    'ip'                   => ':attribute må være en gyldig IP-adresse.',
    'ipv4'                 => ':attribute må være en gyldig IPv4-adresse.',
    'ipv6'                 => ':attribute må være en gyldig IPv6-adresse.',
    'json'                 => ':attribute må være en gyldig JSON-streng.',
    'lt'                   => [
        'numeric' => ':attribute må være mindre enn :value.',
        'file'    => ':attribute må være mindre enn :value kilobytes.',
        'string'  => ':attribute må inneholde mindre enn :value tegn.',
        'array'   => ':attribute må inneholde mindre enn :value elementer.',
    ],
    'lte'                  => [
        'numeric' => ':attribute må være mindre enn eller lik :value.',
        'file'    => ':attribute må være mindre enn eller lik :value kilobytes.',
        'string'  => ':attribute må inneholde mindre enn eller lik :value tegn.',
        'array'   => ':attribute må ikke inneholde mer enn :value elementer.',
    ],
    'max'                  => [
        'numeric' => ':attribute må ikke være størren enn :max.',
        'file'    => ':attribute må ikke være større enn :max kilobytes.',
        'string'  => ':attribute må ikke inneholde mer enn :max tegn.',
        'array'   => ':attribute må ikke inneholde mer enn :value elementer.',
    ],
    'mimes'                => ':attribute må være en fil av filtypen: :values.',
    'mimetypes'            => ':attribute må være en fil av filtypen: :values.',
    'min'                  => [
        'numeric' => ':attribute må være minst :min.',
        'file'    => ':attribute må være minst :min kilobytes.',
        'string'  => ':attribute må inneholde minst :min tegn.',
        'array'   => ':attribute må inneholde minst :value elementer.',
    ],
    'not_in'               => 'Det valgte attributtet :attribute er ugyldig.',
    'not_regex'            => ':attribute formatet er ugyldig.',
    'numeric'              => ':attribute må være et tall.',
    'present'              => ':attribute feltet må være fylt ut.',
    'regex'                => ':attribute formatet er ugyldig.',
    'required'             => ':attribute feltet er påkrevd.',
    'required_if'          => ':attribute feltet kreves når :other er i: values.',
    'required_unless'      => ':attribute feltet kreves med mindre :other er i: values.',
    'required_with'        => ':attribute feltet er påkrevd når :values er tilstede.',
    'required_with_all'    => ':attribute feltet er påkrevd når :values er tilstede.',
    'required_without'     => ':attribute feltet er påkrevd når :values ikke er tilstede.',
    'required_without_all' => ':attribute feltet er påkrevd når ingen av :values er tilstede.',
    'same'                 => ':attribute og :other må være like.',
    'size'                 => [
        'numeric' => ':attribute må være :size.',
        'file'    => ':attribute må være :size kilobytes.',
        'string'  => ':attribute må inneholde :size tegn.',
        'array'   => ':attribute må inneholde :size elementer.',
    ],
    'string'               => ':attribute må være tekst.',
    'timezone'             => ':aatribute må være en gyldig tidssone.',
    'unique'               => ':attribute har allerede blitt brukt.',
    'uploaded'             => ':attribute kunne ikke lastes opp.',
    'url'                  => ':attribute formatet er ugyldig.',

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
            'rule-name' => 'egenvalgt melding',
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
