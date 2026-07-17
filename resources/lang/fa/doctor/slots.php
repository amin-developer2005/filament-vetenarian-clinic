<?php

return [
    'label' => 'وقت های من',
    'plural_label' => 'وقت های من',
    'singular_label' => ' وقت من',
    'bread_crumb' => 'وقت های من',

    'navigations' => [
        'label' => 'وقت من',
        'group'  => 'مدیریت نوبت ها'
    ],

    'pages' => [
        'index' => [
            'actions' => [

            ],
        ],
        'create' => [
            'record' => [

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
            'created_at'    => 'تاریخ ساخت',
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
        'actions' => [
            'view' => [
                'modal' => [
                    'heading' => 'اطلاعات وقت',
                ],
            ],
        ],
        'emptyStateHeading' => 'هیچ برنامه زمانبدی برای شما وجود ندارد.',
        'emptyStateDescription' => 'برای شروع مدیریت رزروها، از بخش برنامه های کاری من یک برنامه زمانبندی جدید بسازید.',
    ],

];
