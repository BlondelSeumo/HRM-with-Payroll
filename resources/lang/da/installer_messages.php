<?php

return [

    /**
     *
     * Shared translations.
     *
     */
    'title' => 'Installationsprogram',
    'next' => 'Næste trin',
    'back' => 'Forrige',
    'finish' => 'Installer',
    'forms' => [
        'errorTitle' => 'Følgende fejl opstod:',
    ],

    /**
     *
     * Home page translations.
     *
     */
    'welcome' => [
        'templateTitle' => 'Velkommen',
        'title'   => 'Installationsprogram',
        'message' => 'Nem installation og opsætningsguide.',
        'next'    => 'Tjek krav',
    ],

    /**
     *
     * Requirements page translations.
     *
     */
    'requirements' => [
        'templateTitle' => 'Trin 1 | Serverkrav',
        'title' => 'Serverkrav',
        'next'    => 'Tjek tilladelser',
    ],

    /**
     *
     * Permissions page translations.
     *
     */
    'permissions' => [
        'templateTitle' => 'Trin 2 | Tilladelser',
        'title' => 'Tilladelser',
        'next' => 'Konfigurer miljø',
    ],

    /**
     *
     * Environment page translations.
     *
     */
    'environment' => [
        'menu' => [
            'templateTitle' => 'Trin 3 | Miljøindstillinger',
            'title' => 'Miljøindstillinger',
            'desc' => 'Vælg venligst, hvordan du vil konfigurere apps <code>.env</code>-filen.',
            'wizard-button' => 'Opsætning af formularguide',
            'classic-button' => 'Klassisk teksteditor',
        ],
        'wizard' => [
            'templateTitle' => 'Trin 3 | Miljøindstillinger | Guidet Wizard',
            'title' => 'Guidet <code>.env</code>-guide',
            'tabs' => [
                'environment' => 'Miljø',
                'database' => 'Database',
                'application' => 'Ansøgning'
            ],
            'form' => [
                'name_required' => 'Et miljønavn er påkrævet.',
                'app_name_label' => 'App navn',
                'app_name_placeholder' => 'App navn',
                'app_environment_label' => 'Appmiljø',
                'app_environment_label_local' => 'Lokalt',
                'app_environment_label_developement' => 'Udvikling',
                'app_environment_label_qa' => 'Qa',
                'app_environment_label_production' => 'Produktion',
                'app_environment_label_other' => 'Andet',
                'app_environment_placeholder_other' => 'Indtast dit miljø...',
                'app_debug_label' => 'App-fejlretning',
                'app_debug_label_true' => 'Sandt',
                'app_debug_label_false' => 'False',
                'app_log_level_label' => 'App-logniveau',
                'app_log_level_label_debug' => 'debug',
                'app_log_level_label_info' => 'info',
                'app_log_level_label_notice' => 'meddelelse',
                'app_log_level_label_warning' => 'advarsel',
                'app_log_level_label_error' => 'fejl',
                'app_log_level_label_critical' => 'kritisk',
                'app_log_level_label_alert' => 'advarsel',
                'app_log_level_label_emergency' => 'nødsituation',
                'app_url_label' => 'App-url',
                'app_url_placeholder' => 'App-url',
                'db_connection_label' => 'Databaseforbindelse',
                'db_connection_label_mysql' => 'mysql',
                'db_connection_label_sqlite' => 'sqlite',
                'db_connection_label_pgsql' => 'pgsql',
                'db_connection_label_sqlsrv' => 'sqlsrv',
                'db_host_label' => 'Databasevært',
                'db_host_placeholder' => 'Databasevært',
                'db_port_label' => 'Databaseport',
                'db_port_placeholder' => 'Databaseport',
                'db_name_label' => 'Databasenavn',
                'db_name_placeholder' => 'Databasenavn',
                'db_username_label' => 'Databasebrugernavn',
                'db_username_placeholder' => 'Databasebrugernavn',
                'db_password_label' => 'Database-adgangskode',
                'db_password_placeholder' => 'Database-adgangskode',

                'app_tabs' => [
                    'more_info' => 'Flere oplysninger',
                    'broadcasting_title' => 'Udsendelse, cachelagring, session, &amp; Kø',
                    'broadcasting_label' => 'Broadcast-driver',
                    'broadcasting_placeholder' => 'Broadcast-driver',
                    'cache_label' => 'Cachedriver',
                    'cache_placeholder' => 'Cachedriver',
                    'session_label' => 'Sessionsdriver',
                    'session_placeholder' => 'Sessionsdriver',
                    'queue_label' => 'Kødriver',
                    'queue_placeholder' => 'Kødriver',
                    'redis_label' => 'Redis-driver',
                    'redis_host' => 'Redis vært',
                    'redis_password' => 'Redis adgangskode',
                    'redis_port' => 'Redis Port',

                    'mail_label' => 'Mail',
                    'mail_driver_label' => 'Mail-driver',
                    'mail_driver_placeholder' => 'Mail-driver',
                    'mail_host_label' => 'Mail-vært',
                    'mail_host_placeholder' => 'Mail-vært',
                    'mail_port_label' => 'Mail Port',
                    'mail_port_placeholder' => 'Mail Port',
                    'mail_username_label' => 'Mailbrugernavn',
                    'mail_username_placeholder' => 'Mailbrugernavn',
                    'mail_password_label' => 'Mailadgangskode',
                    'mail_password_placeholder' => 'Mailadgangskode',
                    'mail_encryption_label' => 'Mail-kryptering',
                    'mail_encryption_placeholder' => 'Mail-kryptering',

                    'pusher_label' => 'Pusher',
                    'pusher_app_id_label' => 'Pusher App Id',
                    'pusher_app_id_palceholder' => 'Pusher App Id',
                    'pusher_app_key_label' => 'Pusher App Key',
                    'pusher_app_key_palceholder' => 'Pusher App Key',
                    'pusher_app_secret_label' => 'Pusher App Secret',
                    'pusher_app_secret_palceholder' => 'Pusher App Secret',
                ],
                'buttons' => [
                    'setup_database' => 'Opsætning af database',
                    'setup_application' => 'Opsætning af applikation',
                    'install' => 'Installer',
                ],
            ],
        ],
        'classic' => [
            'templateTitle' => 'Trin 3 | Miljøindstillinger | Classic Editor',
            'title' => 'Classic Environment Editor',
            'save' => 'Gem .env',
            'back' => 'Brug formularguiden',
            'install' => 'Gem og installer',
        ],
        'success' => 'Dine .env-filindstillinger er blevet gemt.',
        'errors' => 'Kan ikke gemme .env-filen. Opret den venligst manuelt.',
    ],

    'install' => 'Installere',

    /**
     *
     * Installed Log translations.
     *
     */
    'installed' => [
        'success_log_message' => 'Installationsprogrammet blev INSTALLERET på ',
    ],

    /**
     *
     * Final page translations.
     *
     */
    'final' => [
        'title' => 'Installationen er afsluttet',
        'templateTitle' => 'Installationen er afsluttet',
        'finished' => 'Applikationen er blevet installeret.',
        'migration' => 'Migration &amp; Seed Console output:',
        'console' => 'Programkonsol output:',
        'log' => 'Installationslogpost:',
        'env' => 'Endelig .env-fil:',
        'exit' => 'Klik her for at afslutte',
    ],

    /**
     *
     * Update specific translations
     *
     */
    'updater' => [
        /**
         *
         * Shared translations.
         *
         */
        'title' => 'Opdaterer',

        /**
         *
         * Welcome page translations for update feature.
         *
         */
        'welcome' => [
            'title'   => 'Velkommen til opdateringsprogrammet',
            'message' => 'Velkommen til opdateringsguiden.',
        ],

        /**
         *
         * Welcome page translations for update feature.
         *
         */
        'overview' => [
            'title'   => 'Oversigt',
            'message' => 'Der er 1 opdatering.|Der er :nummeropdateringer.',
            'install_updates' => "Installer opdateringer"
        ],

        /**
         *
         * Final page translations.
         *
         */
        'final' => [
            'title' => 'Færdig',
            'finished' => 'Applikationens database er blevet opdateret.',
            'exit' => 'Klik her for at afslutte',
        ],

        'log' => [
            'success_message' => 'Installationsprogrammet blev OPDATERET den ',
        ],
    ],
];
