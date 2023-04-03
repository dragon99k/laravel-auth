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

    'global_message' => '入力内容に誤りがあるため処理を完了できませんでした。入力内容をご確認ください。',
    'alpha' => ':attributeには英字のみからなる文字列を指定してください。',
    'alpha_dash' => ':attributeは半角数値とダッシュ記号（–）、アンダースコア（_）以外指定できません。',
    'alpha_num' => ':attributeは半角数値以外指定できません。',
    'before' => ':attributeは:dateよりも前の日付を指定してください。',
    'before_or_equal' => ':attributeには:dateかそれ以前の日付を指定してください。',
    'after' => ':attributeは:dateよりも後の日付を指定してください。',
    'after_or_equal' => ':attributeには:dateかそれ以降の日付を指定してください。',
    'between' => [
        'array' => ':attributeは:min個から:max個までしか保存できません。',
        'file' => ':attributeは:minから:maxキロバイトまでのファイルしか保存できません。',
        'numeric' => ':attributeは:minから:maxまでの値しか指定できません。',
        'string' => ':attributeは:min文字から:max文字までしか入力できません。',
    ],
    'boolean' => ':attributeには真偽値を指定してください。',
    'confirmed' => ':attributeが確認用の値と一致しません。',
    'current_password' => 'パスワードが間違えています。再度確認の上ご入力ください。',
    'date' => ':attributeには正しい日付型で入力してください。',
    'date_equals' => ':attributeと:dateが一致していません。',
    'date_format' => '":format"という形式の日付を指定してください。',
    'different' => ':attributeには:otherとは異なる値を指定してください。',
    'digits' => ':attributeには:digits桁の数値を指定してください。',
    'digits_between' => ':attributeには:min〜:max桁の数値を指定してください。',
    'email' => ':attributeには正しい形式のメールアドレスを指定してください。',
    'enum' => 'The selected :attribute is invalid.',
    'exists' => '指定された:attributeの値はご利用できません。',
    'file' => ':attributeにはファイルを指定してください。',
    'gt' => [
        'array' => ':attributeは:value個以上保存する必要があります。',
        'file' => ':attributeは:value以上のキロバイトまでのファイルしか保存できません。',
        'numeric' => ':attributeは:valueより大きくなければなりません。',
        'string' => ':attributeは :valueよりも大きい文字でなければならない。.',
    ],
    'gte' => [
        'array' => ':attributeは:value個以上であることが必要です。',
        'file' => ':attributeは:value以上のキロバイトを保存できません。',
        'numeric' => ':attributeは:valueより大きくなければなりません。',
        'string' => ':attributeは :valueよりも大きい文字でなければならない。.',
    ],
    'image' => ':attributeには画像ファイルを指定してください。',
    'in' => '選択された:attributeの値は正しくありません。',
    'integer' => ':attributeは数値以外指定できません。',
    'json' => ':attributeはJSON型以外指定できません。',
    'lt' => [
        'array' => ':attributeは:value個以下であることが必要です。',
        'file' => ':attributeは:value以下のキロバイトを保存できません。',
        'numeric' => ':attributeは:valueより小きくなければなりません。',
        'string' => ':attributeは :valueよりも小きい文字でなければならない。.',
    ],
    'lte' => [
        'array' => ':attributeは:value個以下であることが必要です。',
        'file' => ':attributeは:value以下のキロバイトを保存できません。',
        'numeric' => ':attributeは:valueより小きくなければなりません。',
        'string' => ':attributeは :valueよりも小きい文字でなければならない。.',
    ],
    'max' => [
        'array' => ':attributeは最大:max個まで保存できます。',
        'file' => ':attributeは最大:maxキロバイトまでを保存できません。',
        'numeric' => ':attributeは最大:maxまで指定可能です。',
        'string' => ':attributeは最大:max文字まで保存可能です。',
    ],
    'min' => [
        'array' => ':attributeは最低:min個以上指定しなければ保存できません。',
        'file' => ':attributeは最小:min以上のキロバイトを保存できません。',
        'numeric' => ':attributeの最小値は:minです。',
        'string' => ':attributeは:min文字以上指定しなければ保存できません。',
    ],
    'not_in' => ':attributeには:valuesのうちいずれとも異なる値を指定してください。',
    'not_regex' => 'The :attribute format is invalid.',
    'numeric' => ':attributeは数値以外指定できません。',
    'password' => [
        'letters' => ':attributeは少なくとも1つの文字が含まれていなければならない。',
        'mixed' => ':attributeは少なくとも大文字と小文字を1つずつ含む必要があります。',
        'numbers' => ':attributeには少なくとも1つの数字が含まれていなければならない。',
        'symbols' => ':attributeには少なくとも1つの記号が含まれていなければならない。',
        'uncompromised' => '指定された :attributeがデータ リークに現れました。 別の:attributeを選択してください。',
    ],
    'present' => ':attributeには現在時刻を指定してください。',
    'regex' => '正しい形式の:attributeを指定してください。',
    'filled' => ':attributeは必須です。',
    'required' => ':attributeは必須です。',
    'required_if' => ':attributeは:otherに:valueを指定した場合必須です。',
    'required_unless' => ':otherが:values以外の時:attributeは必須です。',
    'required_with' => ':attributeは:valuesを指定した場合必須です。',
    'required_without' => ':attributeは必須項目です。',
    'required_with_all' => ':valuesのうちすべてが指定された時:attributeは必須です。',
    'required_without_all' => ':valuesのうちすべてが指定されなかった時:attributeは必須です。',
    'same' => ':attributeと:otherが一致していません。',
    'size' => [
        'numeric' => ':attributeには:sizeを指定してください。',
        'file' => ':attributeには:size KBのファイルを指定してください。',
        'string' => ':attributeには:size文字の文字列を指定してください。',
        'array' => ':attributeには:size個の要素を持つ配列を指定してください。',
    ],
    'string' => ':attributeは文字列以外指定できません。',
    'unique' => '指定された:attributeの値は既に使用されています。',
    'url' => ':attributeには正しい形式のURLを指定してください。',
    'password_char' => 'パスワードは半角英数および記号の組み合わせで入力してください。',
    'num_dash' => ':attributeは半角数字またはハイフン(-)のみで入力してください。',
    'number' => ':attributeは半角数字のみで入力してください。',
    'decimal' => ':attributeには数値を指定してください。',
    'decimal_length' => ':attributeには:length桁以上の値は指定できません。',
    'decimal_precision' => ':attributeには小数点第:precision位までの値しか指定できません。',
    'decimal_precision_2' => ':attributeには小数点以下の値は指定できません。',
    'min_percent' => ':attributeは:min%以上の値を指定してください。',
    'max_percent' => ':attributeは:max%以下の値を指定してください。',
    'further_errors' => 'その他、合計:count件のエラーが発生しました。',
    'prohibited' => ':attributeは更新できません。',
    'distinct' => ':attributeが重複しています。',

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
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],
];
