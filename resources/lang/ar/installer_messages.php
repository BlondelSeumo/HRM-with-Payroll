<?php

return [

    /**
     *
     * Shared translations.
     *
     */
    'title' => 'المثبت',
    'next' => 'الخطوة التالية',
    'back' => 'سابق',
    'finish' => 'ثَبَّتَ',
    'forms' => [
        'errorTitle' => 'وقعت الأخطاء التالية:',
    ],

    /**
     *
     * Home page translations.
     *
     */
    'welcome' => [
        'templateTitle' => 'مرحباً',
        'title'   => 'المثبت',
        'message' => 'معالج التثبيت والإعداد السهل.',
        'next'    => 'التحقق من المتطلبات',
    ],

    /**
     *
     * Requirements page translations.
     *
     */
    'requirements' => [
        'templateTitle' => 'الخطوة 1 | متطلبات الخادم',
        'title' => 'متطلبات الخادم',
        'next'    => 'تحقق من الأذونات',
    ],

    /**
     *
     * Permissions page translations.
     *
     */
    'permissions' => [
        'templateTitle' => 'الخطوة 2 | الأذونات',
        'title' => 'الأذونات',
        'next' => 'تكوين البيئة',
    ],

    /**
     *
     * Environment page translations.
     *
     */
    'environment' => [
        'menu' => [
            'templateTitle' => 'الخطوة 3 | إعدادات البيئة',
            'title' => 'إعدادات البيئة',
            'desc' => 'الرجاء تحديد الطريقة التي تريد بها تكوين ملف التطبيقات <code>.env</code>.',
            'wizard-button' => 'إعداد معالج النموذج',
            'classic-button' => 'محرر النصوص الكلاسيكي',
        ],
        'wizard' => [
            'templateTitle' => 'الخطوة 3 | إعدادات البيئة | معالج موجه',
            'title' => 'معالج <code>.env</code> الموجه',
            'tabs' => [
                'environment' => 'بيئة',
                'database' => 'قاعدة البيانات',
                'application' => 'طلب'
            ],
            'form' => [
                'name_required' => 'مطلوب اسم البيئة.',
                'app_name_label' => 'اسم التطبيق',
                'app_name_placeholder' => 'اسم التطبيق',
                'app_environment_label' => 'بيئة التطبيق',
                'app_environment_label_local' => 'محلي',
                'app_environment_label_developement' => 'تطوير',
                'app_environment_label_qa' => 'سؤال',
                'app_environment_label_production' => 'إنتاج',
                'app_environment_label_other' => 'آخر',
                'app_environment_placeholder_other' => 'أدخل بيئتك...',
                'app_debug_label' => 'تصحيح أخطاء التطبيق',
                'app_debug_label_true' => 'حقيقي',
                'app_debug_label_false' => 'خطأ شنيع',
                'app_log_level_label' => 'مستوى سجل التطبيق',
                'app_log_level_label_debug' => 'تصحيح',
                'app_log_level_label_info' => 'معلومات',
                'app_log_level_label_notice' => 'يلاحظ',
                'app_log_level_label_warning' => 'تحذير',
                'app_log_level_label_error' => 'خطأ',
                'app_log_level_label_critical' => 'شديد الأهمية',
                'app_log_level_label_alert' => 'يُحذًِر',
                'app_log_level_label_emergency' => 'طارئ',
                'app_url_label' => 'عنوان URL للتطبيق',
                'app_url_placeholder' => 'عنوان URL للتطبيق',
                'db_connection_label' => 'اتصال قاعدة البيانات',
                'db_connection_label_mysql' => 'mysql',
                'db_connection_label_sqlite' => 'com.sqlite',
                'db_connection_label_pgsql' => 'com.pgsql',
                'db_connection_label_sqlsrv' => 'sqlsrv',
                'db_host_label' => 'مضيف قاعدة البيانات',
                'db_host_placeholder' => 'مضيف قاعدة البيانات',
                'db_port_label' => 'منفذ قاعدة البيانات',
                'db_port_placeholder' => 'منفذ قاعدة البيانات',
                'db_name_label' => 'اسم قاعدة البيانات',
                'db_name_placeholder' => 'اسم قاعدة البيانات',
                'db_username_label' => 'اسم مستخدم قاعدة البيانات',
                'db_username_placeholder' => 'اسم مستخدم قاعدة البيانات',
                'db_password_label' => 'كلمة مرور قاعدة البيانات',
                'db_password_placeholder' => 'كلمة مرور قاعدة البيانات',

                'app_tabs' => [
                    'more_info' => 'مزيد من المعلومات',
                    'broadcasting_title' => 'البث، والتخزين المؤقت، والجلسة، &amp; طابور',
                    'broadcasting_label' => 'سائق البث',
                    'broadcasting_placeholder' => 'سائق البث',
                    'cache_label' => 'سائق ذاكرة التخزين المؤقت',
                    'cache_placeholder' => 'سائق ذاكرة التخزين المؤقت',
                    'session_label' => 'سائق الجلسة',
                    'session_placeholder' => 'سائق الجلسة',
                    'queue_label' => 'سائق قائمة الانتظار',
                    'queue_placeholder' => 'سائق قائمة الانتظار',
                    'redis_label' => 'سائق ريديس',
                    'redis_host' => 'مضيف ريديس',
                    'redis_password' => 'كلمة مرور ريديس',
                    'redis_port' => 'ميناء ريديس',

                    'mail_label' => 'بريد',
                    'mail_driver_label' => 'سائق البريد',
                    'mail_driver_placeholder' => 'سائق البريد',
                    'mail_host_label' => 'مضيف البريد',
                    'mail_host_placeholder' => 'مضيف البريد',
                    'mail_port_label' => 'ميناء البريد',
                    'mail_port_placeholder' => 'ميناء البريد',
                    'mail_username_label' => 'اسم مستخدم البريد',
                    'mail_username_placeholder' => 'اسم مستخدم البريد',
                    'mail_password_label' => 'كلمة مرور البريد',
                    'mail_password_placeholder' => 'كلمة مرور البريد',
                    'mail_encryption_label' => 'تشفير البريد',
                    'mail_encryption_placeholder' => 'تشفير البريد',

                    'pusher_label' => 'انتهازي',
                    'pusher_app_id_label' => 'معرف التطبيق انتهازي',
                    'pusher_app_id_palceholder' => 'معرف التطبيق انتهازي',
                    'pusher_app_key_label' => 'مفتاح التطبيق انتهازي',
                    'pusher_app_key_palceholder' => 'مفتاح التطبيق انتهازي',
                    'pusher_app_secret_label' => 'سر التطبيق انتهازي',
                    'pusher_app_secret_palceholder' => 'سر التطبيق انتهازي',
                ],
                'buttons' => [
                    'setup_database' => 'قاعدة بيانات الإعداد',
                    'setup_application' => 'تطبيق الإعداد',
                    'install' => 'ثَبَّتَ',
                ],
            ],
        ],
        'classic' => [
            'templateTitle' => 'الخطوة 3 | إعدادات البيئة | المحرر الكلاسيكي',
            'title' => 'محرر البيئة الكلاسيكية',
            'save' => 'حفظ .env',
            'back' => 'استخدم معالج النماذج',
            'install' => 'حفظ وتثبيت',
        ],
        'success' => 'تم حفظ إعدادات ملف .env الخاص بك.',
        'errors' => 'غير قادر على حفظ ملف .env، يرجى إنشائه يدويًا.',
    ],

    'install' => 'ثَبَّتَ',

    /**
     *
     * Installed Log translations.
     *
     */
    'installed' => [
        'success_log_message' => 'تم تثبيت برنامج التثبيت بنجاح ',
    ],

    /**
     *
     * Final page translations.
     *
     */
    'final' => [
        'title' => 'انتهى التثبيت',
        'templateTitle' => 'انتهى التثبيت',
        'finished' => 'تم تثبيت التطبيق بنجاح.',
        'migration' => 'الهجرة &amp; مخرجات وحدة تحكم البذور:',
        'console' => 'مخرجات وحدة تحكم التطبيق:',
        'log' => 'إدخال سجل التثبيت:',
        'env' => 'ملف .env النهائي:',
        'exit' => 'انقر هنا للخروج',
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
        'title' => 'المحدث',

        /**
         *
         * Welcome page translations for update feature.
         *
         */
        'welcome' => [
            'title'   => 'مرحبا بكم في المحدث',
            'message' => 'مرحبًا بك في معالج التحديث.',
        ],

        /**
         *
         * Welcome page translations for update feature.
         *
         */
        'overview' => [
            'title'   => 'ملخص',
            'message' => 'يوجد تحديث واحد.|يوجد :number التحديثات.',
            'install_updates' => "تثبيت التحديثات"
        ],

        /**
         *
         * Final page translations.
         *
         */
        'final' => [
            'title' => 'انتهى',
            'finished' => 'تم تحديث قاعدة بيانات التطبيق بنجاح.',
            'exit' => 'انقر هنا للخروج',
        ],

        'log' => [
            'success_message' => 'تم تحديث برنامج التثبيت بنجاح ',
        ],
    ],
];
