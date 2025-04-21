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

    'accepted' => 'Das :Attribut muss akzeptiert werden.',
     'active_url' => 'Das :attribute ist keine gültige URL.',
     'after' => 'Das :attribute muss ein Datum nach :date sein.',
     'after_or_equal' => 'Das :attribute muss ein Datum nach oder gleich :date sein.',
     'alpha' => 'Das :Attribut darf nur Buchstaben enthalten.',
     'alpha_dash' => 'Das :attribute darf nur Buchstaben, Zahlen, Bindestriche und Unterstriche enthalten.',
     'alpha_num' => 'Das :attribute darf nur Buchstaben und Zahlen enthalten.',
     'array' => 'Das :attribute muss ein Array sein.',
     'before' => 'Das :attribute muss ein Datum vor :date sein.',
     'before_or_equal' => 'Das :attribute muss ein Datum vor oder gleich :date sein.',
     'zwischen' => [
         'numeric' => 'Das :attribute muss zwischen :min und :max liegen.',
         'file' => 'Das :attribute muss zwischen :min und :max Kilobyte liegen.',
         'string' => 'Das :attribute muss zwischen :min und :max Zeichen liegen.',
         'array' => 'Das :attribute muss zwischen :min und :max Elementen enthalten.',
     ],
     'boolean' => 'Das :attribute-Feld muss wahr oder falsch sein.',
     'confirmed' => 'Die :Attribut-Bestätigung stimmt nicht überein.',
     'date' => 'Das :attribute ist kein gültiges Datum.',
     'date_equals' => 'Das :attribute muss ein Datum sein, das dem :date entspricht.',
     'date_format' => 'Das :attribute stimmt nicht mit dem Format :format überein.',
     'different' => 'Das :attribute und :other müssen unterschiedlich sein.',
     'digits' => 'Das :attribute muss :digits Ziffern sein.',
     'digits_between' => 'Das :attribute muss zwischen :min und :max Ziffern liegen.',
     'dimensions' => 'Das :attribute hat ungültige Bildabmessungen.',
     'distinct' => 'Das :attribute-Feld hat einen doppelten Wert.',
     'email' => 'Das :attribute muss eine gültige E-Mail-Adresse sein.',
     'exists' => 'Das ausgewählte :Attribut ist ungültig.',
     'file' => 'Das :attribute muss eine Datei sein.',
     'filled' => 'Das :attribute-Feld muss einen Wert haben.',
     'gt' => [
        'numeric' => 'Das :attribute muss größer sein als :value.',
        'file' => 'Das :attribute muss größer als :value Kilobyte sein.',
        'string' => 'Das :attribute muss größer als :value Zeichen sein.',
        'array' => 'Das :attribute muss mehr als :value-Elemente haben.',
    ],
    'gte' => [
        'numeric' => 'Das :attribute muss größer oder gleich :value sein.',
        'file' => 'Das :attribute muss größer oder gleich :value Kilobyte sein.',
        'string' => 'Das :attribute muss größer oder gleich den :value-Zeichen sein.',
        'array' => 'Das :attribute muss :value-Elemente oder mehr enthalten.',
    ],
    'image' => 'Das :attribute muss ein Bild sein.',
    'in' => 'Das ausgewählte :Attribut ist ungültig.',
    'in_array' => 'Das Feld :attribute existiert nicht in :other.',
    'integer' => 'Das :attribute muss eine Ganzzahl sein.',
    'ip' => 'Das :attribute muss eine gültige IP-Adresse sein.',
    'ipv4' => 'Das :attribute muss eine gültige IPv4-Adresse sein.',
    'ipv6' => 'Das :attribute muss eine gültige IPv6-Adresse sein.',
    'json' => 'Das :attribute muss eine gültige JSON-Zeichenfolge sein.',
    'lt' => [
        'numeric' => 'Das :attribute muss kleiner sein als :value.',
        'file' => 'Das :attribute muss kleiner als :value Kilobyte sein.',
        'string' => 'Das :attribute muss weniger als :value Zeichen enthalten.',
        'array' => 'Das :attribute muss weniger als :value-Elemente enthalten.',
    ],
    'lte' => [
        'numeric' => 'Das :attribute muss kleiner oder gleich :value sein.',
        'file' => 'Das :attribute muss kleiner oder gleich :value Kilobyte sein.',
        'string' => 'Das :attribute muss kleiner oder gleich :value Zeichen sein.',
        'array' => 'Das :attribute darf nicht mehr als :value-Elemente haben.',
    ],
    'max' => [
        'numeric' => 'Das :attribute darf nicht größer sein als :max.',
        'file' => 'Das :attribute darf nicht größer als :max Kilobyte sein.',
        'string' => 'Das :attribute darf nicht größer als :max Zeichen sein.',
        'array' => 'Das :attribute darf nicht mehr als :max Elemente haben.',
    ],
    'mimes' => 'Das :attribute muss eine Datei vom Typ: :values sein.',
    'mimetypes' => 'Das :attribute muss eine Datei vom Typ: :values sein.',
    'min' => [
        'numeric' => 'Das :attribute muss mindestens :min. sein.',
        'file' => 'Das :attribute muss mindestens :min Kilobyte groß sein.',
        'string' => 'Das :attribute muss mindestens :min Zeichen lang sein.',
        'array' => 'Das :attribute muss mindestens :min Elemente enthalten.',
    ],
    'not_in' => 'Das ausgewählte :Attribut ist ungültig.',
     'not_regex' => 'Das :attribute-Format ist ungültig.',
     'numeric' => 'Das :attribute muss eine Zahl sein.',
     'present' => 'Das :attribute-Feld muss vorhanden sein.',
     'regex' => 'Das :attribute-Format ist ungültig.',
     'required' => 'Das Feld :attribute ist erforderlich.',
     'required_if' => 'Das Feld :attribute ist erforderlich, wenn :other :value ist.',
     'required_unless' => 'Das Feld :attribute ist erforderlich, es sei denn, :other ist in :values.',
     'required_with' => 'Das Feld :attribute ist erforderlich, wenn :values vorhanden ist.',
     'required_with_all' => 'Das :attribute-Feld ist erforderlich, wenn :values vorhanden sind.',
     'required_without' => 'Das Feld :attribute ist erforderlich, wenn :values nicht vorhanden ist.',
     'required_without_all' => 'Das :attribute-Feld ist erforderlich, wenn keiner der :values vorhanden ist.',
     'same' => 'Das :attribute und :other müssen übereinstimmen.',
     'size' => [
         'numeric' => 'Das :attribute muss :size sein.',
         'file' => 'Das :attribute muss :size Kilobyte sein.',
         'string' => 'Das :attribute muss aus :size-Zeichen bestehen.',
         'array' => 'Das :attribute muss :size-Elemente enthalten.',
     ],
     'starts_with' => 'Das :attribute muss mit einem der folgenden Werte beginnen: :values',
     'string' => 'Das :attribute muss ein String sein.',
     'timezone' => 'Das :attribute muss eine gültige Zone sein.',
     'unique' => 'Das :attribute wurde bereits vergeben.',
     'uploaded' => 'Das :attribute konnte nicht hochgeladen werden.',
     'url' => 'Das :attribute-Format ist ungültig.',
     'uuid' => 'Das :attribute muss eine gültige UUID sein.',

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
            'rule-name' => 'benutzerdefinierte Nachricht',
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
