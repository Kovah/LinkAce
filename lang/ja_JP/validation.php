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

    'accepted'             => ':attribute は承認されている必要があります。',
    'active_url'           => ':attribute は有効なURLではありません。',
    'after'                => ':attribute は :date より後の日付にしてください。',
    'after_or_equal'       => ':attribute は :date と同じか後の日付にしてください。',
    'alpha'                => ':attribute にはアルファベットのみ使用できます。',
    'alpha_dash'           => ':attribute には英数字とハイフン、アンダースコアが使用できます。',
    'alpha_num'            => ':attribute には英数字のみ使用できます。',
    'array'                => ':attribute は配列にしてください。',
    'before'               => ':attribute は :date より前の日付にしてください。',
    'before_or_equal'      => ':attribute は :date と同じか前の日付にしてください。',
    'between'              => [
        'numeric' => ':attribute は :min から :max までの間で指定してください。',
        'file'    => ':attribute は :min KB から :max KB までの間で指定してください。',
        'string'  => ':attribute は :min 文字から :max 文字までの間で指定してください。',
        'array'   => ':attribute は :min 個から :max 個までの間で指定してください。',
    ],
    'boolean'              => ':attribute は true もしくは false を指定してください。',
    'confirmed'            => ':attribute が一致しません。',
    'date'                 => ':attribute は無効な日付です。',
    'date_format'          => ':attribute は :format 形式と一致しません。',
    'different'            => ':attribute と :other は異なっている必要があります。',
    'digits'               => ':attribute は :digits 桁にしてください。',
    'digits_between'       => ':attribute は :min 桁から :max 桁までの間で指定してください。',
    'dimensions'           => ':attribute の画像サイズが正しくありません。',
    'distinct'             => ':attribute フィールドに重複する値があります。',
    'email'                => ':attribute は有効なメールアドレスにしてください。',
    'exists'               => '選択された :attribute は不正です。',
    'file'                 => ':attribute はファイルにしてください。',
    'filled'               => ':attribute フィールドは空に出来ません。',
    'gt'                   => [
        'numeric' => ':attribute は :value より大きい値にしてください。',
        'file'    => ':attribute は :value KB より大きいサイズにしてください。',
        'string'  => ':attribute は :value 文字より多くしてください。',
        'array'   => ':attribute は :value 個より多くしてください。',
    ],
    'gte'                  => [
        'numeric' => ':attribute は :value 以上にしてください。',
        'file'    => ':attribute は :value KB 以上にしてください。',
        'string'  => ':attribute は :value 文字以上にしてください。',
        'array'   => ':attribute は :value 個以上にしてください。',
    ],
    'image'                => ':attribute は画像にしてください。',
    'in'                   => '選択された :attribute は不正です。',
    'in_array'             => ':attribute フィールドが :other に存在しません。',
    'integer'              => ':attribute は整数にしてください。',
    'ip'                   => ':attribute は有効なIPアドレスにしてください。',
    'ipv4'                 => ':attribute は有効なIPv4アドレスにしてください。',
    'ipv6'                 => ':attribute は有効なIPv6アドレスにしてください。',
    'json'                 => ':attribute は有効なJSON文字列にしてください。',
    'lt'                   => [
        'numeric' => ':attribute は :value 未満にしてください。',
        'file'    => ':attribute は :value KB 未満にしてください。',
        'string'  => ':attribute は :value 文字未満にしてください。',
        'array'   => ':attribute は :value 個未満にしてください。',
    ],
    'lte'                  => [
        'numeric' => ':attribute は :value 以下にしてください。',
        'file'    => ':attribute は :value KB 以下にしてください。',
        'string'  => ':attribute は :value 文字以下にしてください。',
        'array'   => ':attribute には :value 以下にしてください。',
    ],
    'max'                  => [
        'numeric' => ':attribute には :max 以下にしてください。',
        'file'    => ':attribute は :max KB 以下にしてください。',
        'string'  => ':attribute は :max 文字以下にしてください。',
        'array'   => ':attribute には :max 個以下にしてください。',
    ],
    'mimes'                => ':attribute はファイルタイプ :values にしてください。',
    'mimetypes'            => ':attribute はファイルタイプ :values にしてください。',
    'min'                  => [
        'numeric' => ':attribute は少なくとも :min 以上にしてください。',
        'file'    => ':attribute は少なくとも :min KB 以上にしてください。',
        'string'  => ':attribute は少なくとも :min 文字以上にしてください。',
        'array'   => ':attribute は少なくとも :min 個以上にしてください。',
    ],
    'not_in'               => '選択された :attribute は不正です。',
    'not_regex'            => ':attribute 形式は不正です。',
    'numeric'              => ':attribute は数値にしてください。',
    'present'              => ':attribute フィールドは必須です。',
    'regex'                => ':attribute 形式は不正です。',
    'required'             => ':attribute フィールドは必須です。',
    'required_if'          => ':other が :value の場合、:attribute フィールドは必須です。',
    'required_unless'      => ':other が :value 以外の場合、:attribute フィールドは必須です。',
    'required_with'        => ':values が存在する場合、:attribute フィールドは必須です。',
    'required_with_all'    => ':values が存在する場合、:attribute フィールドは必須です。',
    'required_without'     => ':values が存在しない場合、:attribute フィールドは必須です。',
    'required_without_all' => ':values がすべて存在しない場合、:attribute フィールドは必須です。',
    'same'                 => ':attribute と :other は一致している必要があります。',
    'size'                 => [
        'numeric' => ':attribute は :size にしてください。',
        'file'    => ':attribute は :size KB にしてください。',
        'string'  => ':attribute は :size 文字にしてください。',
        'array'   => ':attribute は :size 個にしてください。',
    ],
    'string'               => ':attribute は文字列にしてください。',
    'timezone'             => ':attribute は有効なゾーンにしてください。',
    'unique'               => ':attribute は既に使用されています。',
    'uploaded'             => ':attribute のアップロードに失敗しました。',
    'url'                  => ':attribute 形式は不正です。',

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
            'visibility' => '公開範囲は1(公開)、2(内部)、または3(プライベート)のいずれかでなければなりません。',
        ],
        'api_token_ability' => [
            'api_token_ability' => 'APIトークンは、少なくとも定義済みのトークン権限から1つの権限を持つ必要があります。',
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
