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

    'accepted'             => 'Поле :attribute має бути прийнято.',
    'active_url'           => 'Поле :attribute не є правильним URL.',
    'after'                => 'Поле :attribute має містити дату не раніше :date.',
    'after_or_equal'       => 'Поле :attribute має містити дату не раніше або дорівнювати :date.',
    'alpha'                => 'Поле :attribute має містити лише літери.',
    'alpha_dash'           => 'Поле :attribute має містити лише літери, цифри, дефіси та підкреслення.',
    'alpha_num'            => 'Поле :attribute має містити лише літери та цифри.',
    'array'                => 'Поле :attribute має бути масивом.',
    'before'               => 'Поле :attribute має містити дату не пізніше :date.',
    'before_or_equal'      => 'Поле :attribute має містити дату не пізніше або дорівнювати :date.',
    'between'              => [
        'numeric' => 'Поле :attribute має бути в межах від :min до :max.',
        'file'    => 'Розмір :attribute має бути в межах від :min до :max кілобайт.',
        'string'  => 'Текст в полі :attribute має містити не менше :min та не більше :max символів.',
        'array'   => 'Поле :attribute має бути між :min та :max елементами.',
    ],
    'boolean'              => 'Поле :attribute має бути true або false.',
    'confirmed'            => 'Підтвердження для :attribute не співпадає.',
    'date'                 => 'Поле :attribute не є датою.',
    'date_format'          => 'Поле :attribute не відповідає формату :format.',
    'different'            => 'Поля :attribute та :other повинні бути різними.',
    'digits'               => 'Поле :attribute має бути :digits цифр.',
    'digits_between'       => 'Довжина цифрового поля :attribute повинна бути в межах від :min до :max.',
    'dimensions'           => 'Поле :attribute має невірні розміри зображення.',
    'distinct'             => 'Значення поля :attribute вже існує.',
    'email'                => 'Поле :attribute повинне містити коректну електронну адресу.',
    'exists'               => 'Вибраний :attribute не коректний.',
    'file'                 => ':attribute :має бути файлом.',
    'filled'               => 'Поле :attribute повинно бути заповнене.',
    'gt'                   => [
        'numeric' => 'Поле :attribute повинно бути більше :value.',
        'file'    => 'Поле :attribute повинно бути більше :value кілобайт.',
        'string'  => 'Кількість символів поля :attribute повинно бути більше :value.',
        'array'   => 'Кількість елементів поля :attribute повинно бути більше :value.',
    ],
    'gte'                  => [
        'numeric' => 'Поле :attribute повинно бути більше або дорівнювати :value.',
        'file'    => 'Поле :attribute повинно бути більше або дорівнювати :value кілобайт.',
        'string'  => 'Кількість символів поля :attribute повинно бути більше :value.',
        'array'   => 'Кількість елементів поля :attribute повинно бути більше або дорівнювати :value.',
    ],
    'image'                => 'Поле :attribute має бути зображенням.',
    'in'                   => 'Вибраний :attribute не коректний.',
    'in_array'             => 'Значення поля :attribute не існує в :other.',
    'integer'              => 'Поле :attribute має містити ціле число.',
    'ip'                   => 'Поле :attribute має містити IP адресу.',
    'ipv4'                 => 'Поле :attribute має бути коректною адресою IPv4.',
    'ipv6'                 => 'Поле :attribute має бути коректною адресою IPv6.',
    'json'                 => 'Дані поля :attribute мають бути в форматі JSON.',
    'lt'                   => [
        'numeric' => 'Поле :attribute повинно бути менше ніж :value.',
        'file'    => 'Поле :attribute повинно бути менше ніж :value кілобайт.',
        'string'  => 'Кількість символів поля :attribute повинно бути менше ніж :value.',
        'array'   => 'Кількість елементів поля :attribute повинно бути менше ніж :value.',
    ],
    'lte'                  => [
        'numeric' => 'Поле :attribute повинно бути менше або дорівнювати :value.',
        'file'    => 'Поле :attribute повинно бути менше або дорівнювати :value кілобайт.',
        'string'  => 'Кількість символів поля :attribute повинно бути більше або дорівнювати :value.',
        'array'   => 'Кількість елементів поля :attribute повинно бути більше :value.',
    ],
    'max'                  => [
        'numeric' => 'Поле :attribute має бути не більше :max.',
        'file'    => 'Поле :attribute має бути не більше :max кілобайт.',
        'string'  => 'Текст в полі :attribute повинен містити не більше, ніж :max символів.',
        'array'   => 'Поле :attribute не повинне містити більше :max елементів.',
    ],
    'mimes'                => 'Поле :attribute повинне містити файл одного з типів: :values.',
    'mimetypes'            => 'Поле :attribute повинне містити файл одного з типів: :values.',
    'min'                  => [
        'numeric' => 'Поле :attribute не повинне бути менше :min.',
        'file'    => 'Розмір файлу в полі :attribute має бути не меншим :min кілобайт.',
        'string'  => 'Текст в полі :attribute повинен містити не менше :min символів.',
        'array'   => 'Поле :attribute повинне містити не менше :min елементів.',
    ],
    'not_in'               => 'Вибране для :attribute значення не коректне.',
    'not_regex'            => 'Формат поля :attribute неправильний.',
    'numeric'              => 'Поле :attribute має бути числом.',
    'present'              => 'Поле :attribute повинно бути заповнене.',
    'regex'                => 'Формат поля :attribute неправильний.',
    'required'             => 'Поле :attribute є обов\'язковим для заповнення.',
    'required_if'          => 'Поле :attribute є обов\'язковим для заповнення, коли :other є :value.',
    'required_unless'      => 'Поле :attribute обов\'язкове, якщо :other не в :values.',
    'required_with'        => 'Поле :attribute є обов\'язковим для заповнення, коли :values вказано.',
    'required_with_all'    => 'Поле :attribute є обов\'язковим для заповнення, коли :values вказано.',
    'required_without'     => 'Поле :attribute є обов\'язковим для заповнення, коли :values не вказано.',
    'required_without_all' => 'Поле :attribute є обов\'язковим для заповнення, коли :values не вказано.',
    'same'                 => 'Поля :attribute та :other мають співпадати.',
    'size'                 => [
        'numeric' => 'Поле :attribute має бути довжиною :size.',
        'file'    => 'Поле :attribute має бути :size кілобайт.',
        'string'  => 'Поле :attribute має бути довжиною :size символів.',
        'array'   => 'Поле :attribute має містити :size елементів.',
    ],
    'string'               => 'Поле :attribute повинне містити текст.',
    'timezone'             => 'Поле :attribute повинне містити коректну часову зону.',
    'unique'               => ':attribute вже зайнятий.',
    'uploaded'             => 'Завантаження поля :attribute не вдалося.',
    'url'                  => 'Формат поля :attribute неправильний.',

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
            'visibility' => 'Видимість повинна бути 1 (публічна), 2 (внутрішня) або 3 (приватна).',
        ],
        'api_token_ability' => [
            'api_token_ability' => 'API-токен повинен мати хоча б одну можливість з визначених можливостей токену.',
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
