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

    'accepted' => ':attributten skal accepteres.',
    'active_url' => ':attributten er ikke en gyldig URL.',
    'after' => ':attributten skal være en dato efter :date.',
    'after_or_equal' => ':attributten skal være en dato efter eller lig med :date.',
    'alpha' => ':attributten må kun indeholde bogstaver.',
    'alpha_dash' => ':attributten må kun indeholde bogstaver, tal, bindestreger og understregninger.',
    'alpha_num' => ':attributten må kun indeholde bogstaver og tal.',
    'array' => ':attributten skal være en matrix.',
    'before' => ':attributten skal være en dato før :date.',
    'before_or_equal' => ':attributten skal være en dato før eller lig med :date.',
    'between' => [
        'numeric' => ':attributten skal være mellem :min og :max.',
        'file' => ':attributten skal være mellem :min og :max kilobytes.',
        'string' => ':attributten skal være mellem :min og :max tegn.',
        'array' => ':attributten skal have mellem :min og :max elementer.',
    ],
    'boolean' => ':attributtefeltet skal være sandt eller falsk.',
    'confirmed' => 'Bekræftelsen af :attribute stemmer ikke overens.',
    'date' => ':attributten er ikke en gyldig dato.',
    'date_equals' => ':attributten skal være en dato lig med :date.',
    'date_format' => ':attributten matcher ikke formatet :format.',
    'different' => ':attributten og :other skal være forskellige.',
    'digits' => ':attributten skal være :digits-cifre.',
    'digits_between' => ':attributten skal være mellem :min og :max cifre.',
    'dimensions' => ':attributten har ugyldige billeddimensioner.',
    'distinct' => ':attribute-feltet har en dubletværdi.',
    'email' => ':attributten skal være en gyldig e-mailadresse.',
    'exists' => 'Den valgte :attribut er ugyldig.',
    'file' => ':attributten skal være en fil.',
    'filled' => ':attributtefeltet skal have en værdi.',
    'gt' => [
        'numeric' => ':attributten skal være større end :værdi.',
        'file' => ':attributten skal være større end :value kilobytes.',
        'string' => ':attributten skal være større end :value-tegn.',
        'array' => ':attributten skal have mere end :value elementer.',
    ],
    'gte' => [
        'numeric' => ':attributten skal være større end eller lig med :værdi.',
        'file' => ':attributten skal være større end eller lig med :value kilobytes.',
        'string' => ':attributten skal være større end eller lig med :value-tegn.',
        'array' => ':attributten skal have :value elementer eller flere.',
    ],
    'image' => ':attributten skal være et billede.',
    'in' => 'Den valgte :attribut er ugyldig.',
    'in_array' => ':attributfeltet findes ikke i :other.',
    'integer' => ':attributten skal være et heltal.',
    'ip' => ':attributten skal være en gyldig IP-adresse.',
    'ipv4' => ':attributten skal være en gyldig IPv4-adresse.',
    'ipv6' => ':attributten skal være en gyldig IPv6-adresse.',
    'json' => ':attributten skal være en gyldig JSON-streng.',
    'lt' => [
        'numeric' => ':attributten skal være mindre end :værdi.',
        'file' => ':attributten skal være mindre end :value kilobytes.',
        'string' => ':attributten skal være mindre end :value-tegn.',
        'array' => ':attributten skal have mindre end :value elementer.',
    ],
    'lte' => [
        'numeric' => ':attributten skal være mindre end eller lig med :værdi.',
        'file' => ':attributten skal være mindre end eller lig med :value kilobytes.',
        'string' => ':attributten skal være mindre end eller lig med :value-tegn.',
        'array' => ':attributten må ikke have mere end :value elementer.',
    ],
    'max' => [
        'numeric' => ':attributten må ikke være større end :max.',
        'file' => ':attributten må ikke være større end :max kilobytes.',
        'string' => ':attributten må ikke være større end :max tegn.',
        'array' => ':attributten må ikke have mere end :max elementer.',
    ],
    'mimes' => ':attributten skal være en fil af typen: :values.',
    'mimetypes' => ':attributten skal være en fil af typen: :values.',
    'min' => [
        'numeric' => ':attributten skal være mindst :min.',
        'file' => ':attributten skal være mindst :min kilobytes.',
        'string' => ':attributten skal være mindst :min tegn.',
        'array' => ':attributten skal have mindst :min elementer.',
    ],
    'not_in' => 'Den valgte :attribut er ugyldig.',
    'not_regex' => ':attribute-formatet er ugyldigt.',
    'numeric' => ':attributten skal være et tal.',
    'present' => ':attributfeltet skal være til stede.',
    'regex' => ':attribute-formatet er ugyldigt.',
    'required' => 'Feltet :attribute er påkrævet.',
    'required_if' => ':attributfeltet er påkrævet, når :other er :værdi.',
    'required_unless' => ':attributfeltet er påkrævet, medmindre :other er i :values.',
    'required_with' => 'Feltet :attribute er påkrævet, når :values er til stede.',
    'required_with_all' => ':attributfeltet er påkrævet, når :værdier er til stede.',
    'required_without' => 'Feltet :attribute er påkrævet, når :values ikke er til stede.',
    'required_without_all' => ':attributfeltet er påkrævet, når ingen af :værdierne er til stede.',
    'same' => ':attributten og :other skal matche.',
    'size' => [
        'numeric' => ':attributten skal være :størrelse.',
        'file' => ':attributten skal være :size kilobytes.',
        'string' => ':attributten skal være :størrelsestegn.',
        'array' => ':attributten skal indeholde :size elementer.',
    ],
    'starts_with' => ':attributten skal starte med en af følgende: :værdier',
    'string' => ':attributten skal være en streng.',
    'timezone' => ':attributten skal være en gyldig zone.',
    'unique' => ':attributten er allerede blevet taget.',
    'uploaded' => ':attributten kunne ikke uploades.',
    'url' => ':attribute-formatet er ugyldigt.',
    'uuid' => ':attributten skal være et gyldigt UUID.',

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
            'rule-name' => 'tilpasset besked',
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
