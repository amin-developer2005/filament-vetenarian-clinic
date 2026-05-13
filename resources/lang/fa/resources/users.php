<?php

return [
    'label' => 'کاربران',
    'plural_label' => 'کاربران',
    'singular_label' => 'کاربر',
    'bread_crumb' => 'کاربران',

    'navigations' => [
        'label'  => 'کاربران',
        'group'  => 'تنظیمات'
    ],

    'pages' => [
        'index' => [
            'actions' => [
                'create' => 'ایجاد کاربر جدید'
            ],
        ],
        'create' => [
            'record' => [
                'title' => 'ایجاد کاربر جدید'
            ],
            'form' => [
                'select' => [
                    'roles' => [
                        'no_options_message' => 'نقشی یافت نشد.'
                    ],
                    'clinics' => [
                        'no_options_message' => 'کلینیکی یافت نشد.'
                    ],
                ]
            ],
        ],
        'edit' => [
            'record' => [
                'title' => 'ویرایش کاربر'
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
                'roles' => [
                    'label' => 'نقش ها',
                    'no_options_message' => 'نقشی یافت نشد.',
                    'createOptionForm' => [
                        'name' => 'نام نقش'
                    ],
                    'createOptionModalHeading' => 'افزودن نقش جدید',
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
            'roles' => [
                'label' => 'نقش ها'
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
            'roles' => [
                'label' => 'یک نقش جهت فیلتر کاربران انتخاب کنید.',
            ],
            'clinics' => [
                'label' => 'یک کلینیک جهت فیلتر کاربران انتخاب کنید.',
            ],
            'email_status' => [
                'label' => 'وضعیت ایمیل را جهت فیلتر کاربران انتخاب کنید.',
            ],
        ],
    ],

];
