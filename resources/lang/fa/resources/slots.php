<?php

return [
    'label' => 'وقت ها',
    'plural_label' => 'وقت ها',
    'singular_label' => 'وقت',
    'bread_crumb' => 'وقت ها',

    'navigations' => [
        'label' => 'وقت',
        'group'  => 'مدیریت نوبت‌ها'
    ],

    'pages' => [
        'index' => [
            'actions' => [
                'create' => 'ایجاد زمان بندی جدید',
            ],
        ],
        'create' => [
            'record' => [
                'title' => 'ایجاد زمان بندی جدید',
            ],
            'form' => [
                'select' => [
                    'schedule' => [
                        'no_options_message' => 'نویتی مرتبط با این برنامه زمان بندی یافت نشد.',
                    ],
                ],
            ],
        ],
        'edit' => [
            'record' => [
                'title' => 'ویرایش زمان بندی',
            ],
        ],
    ],
    'schema' => [
        'form' => [
            'components' => [
                'schedule' => [
                    'label' => 'برنامه کاری',
                    'no_options_message' => 'نویتی مرتبط با این برنامه زمان بندی یافت نشد.',
                    'createOptionForm' => [
                        'date' => 'تاریخ',
                        'owner_id' => 'مالک',
                    ],
                    'createOptionModalHeading' => 'افزودن نوبت جدید',
                ],
                'date' => [
                    'label' => 'تاریخ وقت',
                ],
                'start_time' => [
                    'label' => 'ساعت شروع',
                ],
                'end_time' => [
                    'label' => 'ساعت پایان',
                ],
                'status' => [
                    'label' => 'وضعیت',
                ],
            ],
        ],
    ],
    'table' => [
        'columns' => [
            'doctor'        => 'دامپزشک',
            'clinic'        => 'کلینیک',
            'date'          => 'تاریخ وقت',
            'start_time'    => 'ساعت شروع',
            'end_time'      => 'ساعت پایان',
            'status'       => 'وضعیت',
            'created_at'    => 'تاریخ ایجاد',
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
        'emptyStateHeading' => 'هیچ برنامه زمانبدی وجود ندارد.',
        'emptyStateDescription' => 'برای شروع مدیریت رزروها، یک برنامه زمانبندی جدید ایجاد کنید',
    ],

];
