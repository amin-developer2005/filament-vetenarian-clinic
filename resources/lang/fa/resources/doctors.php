<?php

return [
    'label' => 'پزشکان',
    'plural_label' => 'پزشکان',
    'singular_label' => 'پزشک',
    'bread_crumb' => 'پزشکان',

    'navigations' => [
        'label'  => 'پزشکان',
        'group'  => 'مدیریت کاربران'
    ],

    'pages' => [
        'index' => [
            'actions' => [
                'create' => 'ایجاد پزشک جدید'
            ],
        ],
        'create' => [
            'record' => [
                'title' => 'ایجاد پزشک جدید'
            ],
            'form' => [
                'select' => [
                    'specialties' => [
                        'no_options_message' => 'تخصصی یافت نشد.'
                    ],
                    'clinics' => [
                        'no_options_message' => 'کلینیکی یافت نشد.'
                    ],
                ]
            ],
        ],
        'edit' => [
            'record' => [
                'title' => 'ویرایش پزشک'
            ],
        ],
    ],
    'schema' => [
        'form' => [
            'components' => [
                'name' => [
                    'label' => 'نام'
                ],
                'email' => [
                    'label' => 'ایمیل',
                    'validationMessages' => [
                        'unique' => 'ایمیل قبلا ثبت شده است.'
                    ],
                ],
                'phone' => [
                    'label' => 'شماره تماس'
                ],
                'specialties' => [
                    'label' => 'تخصص ها',
                    'no_options_message' => 'تخصصی یافت نشد.',
                    'createOptionForm' => [
                        'name' => 'نام تخصص'
                    ],
                    'createOptionModalHeading' => 'افزودن تخصص جدید',
                ],
                'clinics' => [
                    'label' => 'کلینیک ها',
                    'no_options_message' => 'کلینیکی یافت نشد.',
                    'createOptionForm' => [
                        'name' => 'نام کلینیک'
                    ],
                    'createOptionModalHeading' => 'افزودن کلینیک جدید',
                ],
                'password' => [
                    'label' => 'پسوورد'
                ],
            ]
        ],
    ],
    'table' => [
        'columns' => [
            'name' => [
                'label' => 'نام'
            ],
            'email' => [
                'label' => 'ایمیل'
            ],
            'phone' => [
                'label' => 'شماره تماس'
            ],
            'specialties' => [
                'label' => 'تخصص ها'
            ],
            'clinics' => [
                'label' => 'کلینیک ها'
            ],
            'email_status' => [
                'label' => 'وضعیت ایمیل'
            ],
            'created_at' => [
                'label' => 'تاریخ ساخت'
            ],
            'updated_at' => [
                'label' => 'تاریخ بروزرسانی'
            ],
        ],
        'filters' => [
            'clinics' => [
                'label' => ' کلینیک ',
            ],
            'email_status' => [
                'label' => 'وضعیت ایمیل ',
            ],
        ],
    ],

    'relationManagers' => [

        'schedules' => [

            'empty' => [

                'heading' => 'هنوز هیچ برنامه کاری برای این دامپزشک تعریف نشده است.',

                'description' => 'در حال حاضر هیچ برنامه زمانی برای این دامپزشک ثبت نشده است. پس از ایجاد اولین برنامه کاری، اطلاعات آن در این بخش نمایش داده خواهد شد.',

            ],
        ],
        'appointments' => [

            'empty' => [

                'heading' => 'هنوز هیچ نوبتی برای این دامپزشک ثبت نشده است.',

                'description' =>
                    'در حال حاضر هیچ مراجعه‌کننده‌ای برای این دامپزشک نوبت رزرو نکرده است. پس از ثبت اولین نوبت، اطلاعات آن در این بخش نمایش داده خواهد شد.',
            ],
        ],
    ],


];
