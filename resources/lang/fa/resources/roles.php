<?php

return [
    'label' => 'نقش ها',
    'plural_label' => 'نقش ها',
    'bread_crumb' => 'نقش ها',

    'navigations' => [
        'label'  => 'نقش ها',
        'group'  => 'تنظیمات'
    ],

    'pages' => [
        'index' => [
            'actions' => [
                'create' => 'ایجاد نقش جدید'
            ],
        ],
        'create' => [
            'record' => [
                'title' => 'ایجاد نقش جدید'
            ],
        ],
        'edit' => [
            'record' => [
                'title' => 'ویرایش نقش'
            ],
        ],
        'view' => [
            'label' => ''
        ],
    ],
    'form' => [
        'name' => [
            'label' => 'نام نقش',
        ],
        'description' => [
            'label' => 'توضیحات'
        ],
    ],
    'table' => [
        'columns' => [
            'name' => [
                'label' => 'نام نقش',
            ],
            'description' => [
                'label' => 'توضیحات'
            ],
            'created_at' => [
                'label' => 'مدت زمان ساخت نقش'
            ],
        ],
        'filters' => [
            'name' => [
                'label' => 'نقشی را جهت فیلتر انتخاب کنید',
            ],
        ],
    ],
];
