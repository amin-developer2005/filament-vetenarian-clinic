<?php

return [
    'label' => 'دام‌ های من',
    'plural_label' => 'دام‌ های من',
    'singular_label' => 'دام من',
    'bread_crumb' => 'دام‌ های من',

    'navigations' => [
        'label'  => 'دام ‌ها',
    ],

    'pages' => [
        'index' => [
            'actions' => [
                'create' => 'افزودن حیوان دامی جدید'
            ],
        ],
        'create' => [
            'record' => [
                'title' => 'افزودن حیوان دامی جدید'
            ],
        ],
        'edit' => [
            'record' => [
                'title' => 'ویرایش دام'
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
