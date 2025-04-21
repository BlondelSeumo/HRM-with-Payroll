<?php

return [

    /**
     *
     * Shared translations.
     *
     */
    'title' => 'Installer',
    'next' => 'Nächster Schritt',
    'back' => 'Zurück',
    'finish' => 'Installieren',
    'forms' => [
        'errorTitle' => 'Die folgenden Fehler sind aufgetreten:',
    ],

    /**
     *
     * Home page translations.
     *
     */
    'welcome' => [
        'templateTitle' => 'Willkommen',
        'title'   => 'Installer',
        'message' => 'Einfacher Installations- und Einrichtungsassistent.',
        'next'    => 'Anforderungen prüfen',
    ],

    /**
     *
     * Requirements page translations.
     *
     */
    'requirements' => [
        'templateTitle' => 'Schritt 1 | Serveranforderungen',
        'title' => 'Serveranforderungen',
        'next'    => 'Berechtigungen prüfen',
    ],

    /**
     *
     * Permissions page translations.
     *
     */
    'permissions' => [
        'templateTitle' => 'Schritt 2 | Berechtigungen',
        'title' => 'Berechtigungen',
        'next' => 'Umgebung konfigurieren',
    ],

    /**
     *
     * Environment page translations.
     *
     */
    'environment' => [
        'menu' => [
            'templateTitle' => 'Schritt 3 | Umgebungseinstellungen',
            'title' => 'Umgebungseinstellungen',
            'desc' => 'Bitte wählen Sie aus, wie Sie die Apps-Datei <code>.env</code> konfigurieren möchten.',
            'wizard-button' => 'Formular-Assistent-Setup',
            'classic-button' => 'Klassischer Texteditor',
        ],
        'wizard' => [
            'templateTitle' => 'Schritt 3 | Umgebungseinstellungen | „Geführter Zauberer',
            'title' => 'Geführter <code>.env</code>-Assistent',
            'tabs' => [
                'environment' => 'Umgebung',
                'database' => 'Datenbank',
                'application' => 'Application'
            ],
            'form' => [
                'name_required' => 'Ein Umgebungsname ist erforderlich.',
                'app_name_label' => 'App-Name',
                'app_name_placeholder' => 'App-Name',
                'app_environment_label' => 'App-Umgebung',
                'app_environment_label_local' => 'Lokal',
                'app_environment_label_developement' => 'Entwicklung',
                'app_environment_label_qa' => 'Qa',
                'app_environment_label_production' => 'Produktion',
                'app_environment_label_other' => 'Andere',
                'app_environment_placeholder_other' => 'Betreten Sie Ihre Umgebung...',
                'app_debug_label' => 'App-Debug',
                'app_debug_label_true' => 'True',
                'app_debug_label_false' => 'False',
                'app_log_level_label' => 'App-Protokollebene',
                'app_log_level_label_debug' => 'debug',
                'app_log_level_label_info' => 'info',
                'app_log_level_label_notice' => 'notice',
                'app_log_level_label_warning' => 'Warnung',
                'app_log_level_label_error' => 'Fehler',
                'app_log_level_label_critical' => 'kritisch',
                'app_log_level_label_alert' => 'alert',
                'app_log_level_label_emergency' => 'Notfall',
                'app_url_label' => 'App-URL',
                'app_url_placeholder' => 'App-URL',
                'db_connection_label' => 'Datenbankverbindung',
                'db_connection_label_mysql' => 'mysql',
                'db_connection_label_sqlite' => 'sqlite',
                'db_connection_label_pgsql' => 'pgsql',
                'db_connection_label_sqlsrv' => 'sqlsrv',
                'db_host_label' => 'Datenbankhost',
                'db_host_placeholder' => 'Datenbankhost',
                'db_port_label' => 'Datenbankport',
                'db_port_placeholder' => 'Datenbankport',
                'db_name_label' => 'Datenbankname',
                'db_name_placeholder' => 'Datenbankname',
                'db_username_label' => 'Datenbankbenutzername',
                'db_username_placeholder' => 'Datenbankbenutzername',
                'db_password_label' => 'Datenbankpasswort',
                'db_password_placeholder' => 'Datenbankpasswort',

                'app_tabs' => [
                    'more_info' => 'Weitere Informationen',
                    'broadcasting_title' => 'Broadcasting, Caching, Sitzung &amp; Warteschlange',
                    'broadcasting_label' => 'Broadcast-Treiber',
                    'broadcasting_placeholder' => 'Broadcast-Treiber',
                    'cache_label' => 'Cache-Treiber',
                    'cache_placeholder' => 'Cache-Treiber',
                    'session_label' => 'Sitzungstreiber',
                    'session_placeholder' => 'Sitzungstreiber',
                    'queue_label' => 'Warteschlangentreiber',
                    'queue_placeholder' => 'Warteschlangentreiber',
                    'redis_label' => 'Redis-Treiber',
                    'redis_host' => 'Redis Host',
                    'redis_password' => 'Redis-Passwort',
                    'redis_port' => 'Redis-Port',

                    'mail_label' => 'Mail',
                    'mail_driver_label' => 'Mail Driver',
                    'mail_driver_placeholder' => 'Mail Driver',
                    'mail_host_label' => 'Mail-Host',
                    'mail_host_placeholder' => 'Mail-Host',
                    'mail_port_label' => 'Mail-Port',
                    'mail_port_placeholder' => 'Mail-Port',
                    'mail_username_label' => 'Mail-Benutzername',
                    'mail_username_placeholder' => 'Mail-Benutzername',
                    'mail_password_label' => 'Mail-Passwort',
                    'mail_password_placeholder' => 'Mail-Passwort',
                    'mail_encryption_label' => 'Mail-Verschlüsselung',
                    'mail_encryption_placeholder' => 'Mail-Verschlüsselung',

                    'pusher_label' => 'Pusher',
                    'pusher_app_id_label' => 'Pusher-App-ID',
                    'pusher_app_id_palceholder' => 'Pusher-App-ID',
                    'pusher_app_key_label' => 'Pusher App Key',
                    'pusher_app_key_palceholder' => 'Pusher App Key',
                    'pusher_app_secret_label' => 'Pusher App Secret',
                    'pusher_app_secret_palceholder' => 'Pusher App Secret',
                ],
                'buttons' => [
                    'setup_database' => 'Datenbank einrichten',
                    'setup_application' => 'Anwendung einrichten',
                    'install' => 'Installieren',
                ],
            ],
        ],
        'classic' => [
            'templateTitle' => 'Schritt 3 | Umgebungseinstellungen | „Klassischer Herausgeber',
            'title' => 'Klassischer Umgebungseditor',
            'save' => 'Speichern .env',
            'back' => 'Formularassistenten verwenden',
            'install' => 'Speichern und installieren',
        ],
        'success' => 'Ihre .env-Dateieinstellungen wurden gespeichert.',
        'errors' => 'Die .env-Datei konnte nicht gespeichert werden. Bitte erstellen Sie sie manuell.',
    ],

    'install' => 'Installieren',

    /**
     *
     * Installed Log translations.
     *
     */
    'installed' => [
        'success_log_message' => 'Installationsprogramm erfolgreich INSTALLIERT am ',
    ],

    /**
     *
     * Final page translations.
     *
     */
    'final' => [
        'title' => 'Installation abgeschlossen',
        'templateTitle' => 'Installation abgeschlossen',
        'finished' => 'Anwendung wurde erfolgreich installiert.',
        'migration' => 'Migration &amp; Ausgabe der Seed-Konsole:',
        'console' => 'Ausgabe der Anwendungskonsole:',
        'log' => 'Installationsprotokolleintrag:',
        'env' => 'Endgültige .env-Datei:',
        'exit' => 'Klicken Sie hier, um zu beenden',
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
        'title' => 'Updater',

        /**
         *
         * Welcome page translations for update feature.
         *
         */
        'welcome' => [
            'title'   => 'Willkommen beim Updater',
            'message' => 'Willkommen beim Update-Assistenten.',
        ],

        /**
         *
         * Welcome page translations for update feature.
         *
         */
        'overview' => [
            'title'   => 'Übersicht',
            'message' => 'Es gibt 1 Update.|Es gibt: Anzahl Updates.',
            'install_updates' => "Updates installieren"
        ],

        /**
         *
         * Final page translations.
         *
         */
        'final' => [
            'title' => 'Fertig',
            'finished' => 'Die Datenbank der Anwendung wurde erfolgreich aktualisiert.',
            'exit' => 'Klicken Sie hier, um zu beenden',
        ],

        'log' => [
            'success_message' => 'Installer erfolgreich AKTUALISIERT am ',
        ],
    ],
];
