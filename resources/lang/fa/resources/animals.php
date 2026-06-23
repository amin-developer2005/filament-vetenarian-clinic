<?php

return [
    'label' => 'دام‌ ها',
    'plural_label' => 'دام‌ ها',
    'singular_label' => 'دام',
    'bread_crumb' => 'دام ‌ها',

    'navigations' => [
        'label'  => 'دام ‌ها',
    ],

    'pages' => [
        'index' => [
            'actions' => [
                'create' => 'ایجاد حیوان دامی جدید'
            ],
        ],
        'create' => [
            'record' => [
                'title' => 'ایجاد حیوان دامی جدید'
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
                'owner_id' => [
                    'label' => 'صاحب دام',
                    'no_options_message' => 'مالکی یافت نشد.',
                ],
                'clinics' => [
                    'label' => 'کلینیک ها',
                    'no_options_message' => 'کلینیکی یافت نشد.',
                    'createOptionForm' => [
                        'name' => 'نام کلینیک'
                    ],
                    'createOptionModalHeading' => 'افزودن کلینیک جدید',
                ],
                'name' => [
                    'label' => 'نام دام',
                ],
                'species' => [
                    'label' => 'گونه',
                ],
                'breed' => [
                    'label' => 'نژاد',
                ],
                'gender' => [
                    'label' => 'جنسیت',
                ],
                'date_of_birth' => [
                    'label' => 'تاریخ تولد',
                ],
                'avatar' => [
                    'label' => 'تصویر',
                ],
            ]
        ],
    ],
    'table' => [
        'columns' => [
            'avatar'       => 'تصویر',
            'name'         => 'نام',
            'owner'        => 'صاحب',
            'clinics'      => 'کلینیک ها',
            'species'      => 'گونه',
            'date_of_birth'=> 'تاریخ تولد',
            'created_at'   => 'تاریخ ساخت دام',
        ],
        'filters' => [
            'owner'   => 'فیلتر صاحب',
            'species' => 'فیلتر گونه',
        ],
    ],
    'genders' => [
        'male' => 'نر',
        'female' => 'ماده',
    ],
    'species' => [
        'dog' => 'سگ',

        'cat' => 'گربه',

        'bird' => 'پرنده',

        'rabbit' => 'خرگوش',

        'horse' => 'اسب',

        'cow' => 'گاو',

        'sheep' => 'گوسفند',

        'goat' => 'بز',

        'chicken' => 'مرغ',
    ],
];
