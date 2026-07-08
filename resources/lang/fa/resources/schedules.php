<?php

return [
    'label' => 'برنامه‌های کاری',
    'plural_label' => 'برنامه‌های کاری',
    'singular_label' => 'برنامه کاری',
    'bread_crumb' => 'برنامه‌های کاری',

    'navigations' => [
        'label'  => 'برنامه‌های کاری',
        'group'  => 'مدیریت نوبت ها'
    ],

    'pages' => [
        'index' => [
            'actions' => [
                'create' => 'افزودن برنامه کاری جدید'
            ],
        ],
        'create' => [
            'record' => [
                'title' => 'افزودن برنامه کاری جدید'
            ],
            'form' => [
                'select' => [
                    'doctor' => [
                        'no_options_message' => 'دکتری یافت نشد.'
                    ],
                    'clinic' => [
                        'no_options_message' => 'کلینیکی یافت نشد.'
                    ],
                ]
            ],
        ],
        'edit' => [
            'record' => [
                'title' => 'ویرایش برنامه کاری'
            ],
        ],
    ],
    'schema' => [
        'form' => [
            'components' => [
                'doctor' => [
                    'label' => 'دامپزشک',
                    'no_options_message' => 'دکتری یافت نشد.',
                ],
                'clinic' => [
                    'label' => 'کلینیک',
                    'no_options_message' => 'کلینیکی یافت نشد.',
                ],
                'start_date' => [
                    'label' => 'تاریخ شروع'
                ],
                'end_date' => [
                    'label' => 'تاریخ پایان'
                ],
                'days_of_week' => [
                    'label' => 'روزهای هفته',
                    'days' => [
                        'saturday'  => 'شنبه',
                        'sunday'    => 'یکشنبه',
                        'monday'    => 'دوشنبه',
                        'tuesday'   => 'سه‌شنبه',
                        'wednesday' => 'چهارشنبه',
                        'thursday'  => 'پنج‌شنبه',
                        'friday'    => 'جمعه',
                    ],
                ],
                'time_start' => [
                    'label' => 'ساعت شروع'
                ],
                'time_end' => [
                    'label' => 'ساعت پایان'
                ],
                'slot_duration' => [
                    'label' => 'مدت زمان هر اسلات (دقیقه)',
                    'minutes' => 'دقیقه',
                ],
            ]
        ],
    ],
    'table' => [
        'columns' => [
            'doctor' => [
                'label' => 'دامپزشک'
            ],
            'clinics' => [
                'label' => ' کلینیک ها'
            ],
            'start_date' => [
                'label' => 'تاریخ شروع'
            ],
            'end_date' => [
                'label' => 'تاریخ پایان'
            ],
            'days_of_week' => [
                'label' => 'روزهای هفته'
            ],
            'time_start' => [
                'label' => 'ساعت شروع'
            ],
            'time_end' => [
                'label' => 'ساعت پایان'
            ],
            'slot_duration' => [
                'label' => 'مدت اسلات'
            ],
            'created_at' => [
                'label' => 'تاریخ ساخت'
            ],
            'updated_at' => [
                'label' => 'تاریخ بروزرسانی'
            ],
        ],
        'filters' => [
            'doctor' => [
                'label' => 'یک دامپزشک جهت فیلتر برنامه‌ها انتخاب کنید.',
            ],
            'clinic' => [
                'label' => 'یک کلینیک جهت فیلتر برنامه‌ها انتخاب کنید.',
            ],
            'start_date' => [
                'label' => 'فیلتر بر اساس تاریخ شروع',
            ],
            'end_date' => [
                'label' => 'فیلتر بر اساس تاریخ پایان',
            ],
        ],
    ],

    'relationManagers' => [
        'slots' => [
            'title' => 'وقت های برنامه کاری دکتر'
        ],
    ],
];
