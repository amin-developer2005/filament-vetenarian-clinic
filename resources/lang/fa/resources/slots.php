<?php

return [
    'label' => 'زمان بندی ها',
    'plural_label' => 'زمان بندی ها',
    'singular_label' => 'زمان بندی',
    'bread_crumb' => 'زمان بندی ها',

    'navigations' => [
        'label'  => 'زمان بندی',
    ],

    'pages' => [
        'index' => [
            'actions' => [
                'create' => 'ایجاد زمان بندی جدید'
            ],
        ],
        'create' => [
            'record' => [
                'title' => 'ایجاد زمان بندی جدید'
            ],
            'form' => [
                'select' => [
                    'schedule' => [
                        'no_options_message' => 'نویتی مرتبط با این برنامه زمان بندی یافت نشد.'
                    ],
                ]
            ],
        ],
        'edit' => [
            'record' => [
                'title' => 'ویرایش زمان بندی'
            ],
        ],
    ],
    'schema' => [
        'form' => [
            'components' => [
                'start' => [
                    'label' => 'ساعت شروع نوبت'
                ],
                'end' => [
                    'label' => 'ساعت پایان نویت',
                    'validationMessages' => [
                        'unique' => 'ایمیل قبلا ثبت شده است.'
                    ],
                ],
                'schedule' => [
                    'label' => 'نوبت',
                    'no_options_message' => 'نویتی مرتبط با این برنامه زمان بندی یافت نشد.',
                    'createOptionForm' => [
                        'date'     => 'تاریخ',
                        'owner_id' => 'مالک',
                    ],
                    'createOptionModalHeading' => 'افزودن نوبت جدید',
                ],
                'status' => [
                    'label' => 'وضعیت'
                ],
            ]
        ],
    ],
    'table' => [
        'columns' => [
            'start' => [
                'label' => 'ساعت شروع نوبت'
            ],
            'end' => [
                'label' => 'ساعت پایان نویت',
            ],
            'schedule' => [
                'label' => 'نوبت',
            ],
            'status' => [
                'label' => 'وضعیت'
            ],
            'created_at' => [
                'label' => 'تاریخ ساخت'
            ],
            'updated_at' => [
                'label' => 'تاریخ بروزرسانی'
            ],
        ],
        'filters' => [
            'start' => [
                'label' => 'ساعت شروع زمانبندی',
            ],
            'end' => [
                'label' => 'ساعت پایان زمانبندی',
            ],
            'status' => [
                'label' => 'وضعیت زمانبندی',
            ],
        ],
        'emptyStateHeading'     => 'هیچ برنامه زمانبدی وجود ندارد.',
        'emptyStateDescription' => 'برای شروع مدیریت رزروها، یک برنامه زمانبندی جدید ایجاد کنید',
    ],

];
