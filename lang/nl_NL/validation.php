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

    'accepted'             => ':attribute moet worden geaccepteerd.',
    'active_url'           => ':attribute is geen geldig webadres.',
    'after'                => ':attribute moet een datum zijn later dan :date.',
    'after_or_equal'       => ':attribute moet een datum zijn later dan of gelijk aan :date.',
    'alpha'                => ':attribute mag enkel letters bevatten.',
    'alpha_dash'           => ':attribute mag alleen letters, cijfers, streepjes en liggende streepjes bevatten.',
    'alpha_num'            => ':attribute mag enkel letters en cijfers bevatten.',
    'array'                => ':attribute moet een lijst zijn.',
    'before'               => ':attribute moet een datum zijn voor :date.',
    'before_or_equal'      => ':attribute moet een datum zijn voor of gelijk aan :date.',
    'between'              => [
        'numeric' => ':attribute moet tussen :min en :max zijn.',
        'file'    => ':attribute moet tussen :min en :max kilobytes zijn.',
        'string'  => ':attribute moet tussen :min en :max tekens zijn.',
        'array'   => ':attribute moet tussen :min en :max entiteiten bevatten.',
    ],
    'boolean'              => ':attribute moet waar of onwaar zijn.',
    'confirmed'            => 'De bevestiging van :attribute komt niet overeen.',
    'date'                 => ':attribute is geen geldige datum.',
    'date_format'          => ':attribute komt niet overeen met het formaat :format.',
    'different'            => ':attribute en :other mogen niet hetzelfde zijn.',
    'digits'               => ':attribute moet bestaan uit :digits cijfers.',
    'digits_between'       => ':attribute moet tussen :min en :max tekens lang zijn.',
    'dimensions'           => ':attribute heeft ongeldige afmetingen.',
    'distinct'             => ':attribute heeft een dubbele waarde.',
    'email'                => ':attribute moet een geldig e-mailadres zijn.',
    'exists'               => 'Geselecteerde waarde :attribute is ongeldig.',
    'file'                 => ':attribute moet een bestand zijn.',
    'filled'               => ':attribute moet een waarde hebben.',
    'gt'                   => [
        'numeric' => ':attribute moet groter zijn dan :value.',
        'file'    => ':attribute moet groter zijn dan :value kilobytes.',
        'string'  => ':attribute moet meer dan :value tekens bevatten.',
        'array'   => ':attribute moet meer dan :value entiteiten bevatten.',
    ],
    'gte'                  => [
        'numeric' => ':attribute moet groter dan of gelijk zijn aan :value.',
        'file'    => ':attribute moet groter zijn dan :value kilobytes.',
        'string'  => ':attribute moet :value of meer tekens bevatten.',
        'array'   => ':attribute moet :value of meer entiteiten bevatten.',
    ],
    'image'                => ':attribute moet een afbeelding zijn.',
    'in'                   => 'Geselecteerde waarde :attribute is ongeldig.',
    'in_array'             => ':attribute bestaat niet in :other.',
    'integer'              => ':attribute moet een getal zijn.',
    'ip'                   => ':attribute moet een geldig IP-adres zijn.',
    'ipv4'                 => ':attribute moet een geldig IPv4-adres zijn.',
    'ipv6'                 => ':attribute moet een geldig IPv6-adres zijn.',
    'json'                 => ':attribute moet eem geldige JSON-tekenreeks zijn.',
    'lt'                   => [
        'numeric' => ':attribute moet kleiner zijn dan :value.',
        'file'    => ':attribute moet kleiner zijn dan :value kilobytes.',
        'string'  => ':attribute moet minder dan :value tekens bevatten.',
        'array'   => ':attribute moet minder dan :value entiteiten bevatten.',
    ],
    'lte'                  => [
        'numeric' => ':attribute moet kleiner of gelijk zijn aan :value.',
        'file'    => ':attribute moet kleiner dan of gelijk zijn aan :value.',
        'string'  => ':attribute moet :value of minder tekens bevatten.',
        'array'   => ':attribute mag niet meer dan :value entiteiten bevatten.',
    ],
    'max'                  => [
        'numeric' => ':attribute mag niet groter zijn dan :max.',
        'file'    => ':attribute mag niet groter zijn dan :max kilobytes.',
        'string'  => ':attribute mag niet langer zijn dan :max tekens.',
        'array'   => ':attribute mag niet meer dan :max entiteiten bevatten.',
    ],
    'mimes'                => ':attribute moet een bestand zijn van het type :values.',
    'mimetypes'            => ':attribute moet een bestand zijn van het type :values.',
    'min'                  => [
        'numeric' => ':attribute moet minstens :min zijn.',
        'file'    => ':attribute moet minstens :min kilobytes groot zijn.',
        'string'  => ':attribute moet minstens :min tekens lang zijn.',
        'array'   => ':attribute moet minstens :min entiteiten bevatten.',
    ],
    'not_in'               => 'Geselecteerde waarde :attribute is ongeldig.',
    'not_regex'            => 'De indeling van :attribute is ongeldig.',
    'numeric'              => ':attribute moet een getal zijn.',
    'present'              => ':attribute moet aanwezig zijn.',
    'regex'                => 'De indeling van :attribute is ongeldig.',
    'required'             => ':attribute is verplicht.',
    'required_if'          => ':attribute is verplicht als :other gelijk is aan :value.',
    'required_unless'      => ':attribute is verplicht tenzij :other voorkomt in :values.',
    'required_with'        => ':attribute is verplicht als :value aanwezig is.',
    'required_with_all'    => ':attribute is verplicht als :value aanwezig is.',
    'required_without'     => ':attribute is verplicht als :values niet aanwezig is.',
    'required_without_all' => ':attribute is verplicht al geen van :values aanwezig is.',
    'same'                 => ':attribute en :other moeten hetzelfde zijn.',
    'size'                 => [
        'numeric' => ':attribute moet :size zijn.',
        'file'    => ':attribute moet :size kilobytes zijn.',
        'string'  => ':attribute moet :size tekens zijn.',
        'array'   => ':attribute moet :size entiteiten bevatten.',
    ],
    'string'               => ':attribute moet een tekenreeks zijn.',
    'timezone'             => ':attribute moet een geldige tijdzone zijn.',
    'unique'               => ':attribute is al in gebruik.',
    'uploaded'             => 'Uploaden van :attribute is mislukt.',
    'url'                  => 'De indeling van :attribute is ongeldig.',

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
            'visibility' => 'De zichtbaarheid moet ofwel 1 (openbaar), 2 (intern) of 3 (privé) zijn.',
        ],
        'api_token_ability' => [
            'api_token_ability' => 'De API-token moet ten minste één machtiging hebben van de vooraf gedefinieerde tokenmachtigingen.',
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
