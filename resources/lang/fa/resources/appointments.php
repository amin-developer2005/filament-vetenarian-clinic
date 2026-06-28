<?php

return [
    'label'         => 'نوبت‌ ها',
    'plural_label'  => 'نوبت ‌ها',
    'singular_label'=> 'نوبت',
    'bread_crumb' => 'دام‌ها',

    'navigations' => [
        'label'  => 'نوبت ‌ها',
        'group'  => 'مدیریت نوبت‌ها'
    ],

    'pages' => [
        'index' => [
            'actions' => [
                'create' => 'رزرو نوبت جدید'
            ],
        ],
        'create' => [
            'record' => [
                'title' => 'افزودن حیوان دامی جدید'
            ],
        ],
        'edit' => [
            'record' => [
                'title' => 'ویرایش نوبت'
            ],
        ],
    ],

    'schema' => [
        'form' => [
            'components' => [
                'clinic_id' => [
                    'label' => 'کلینیک',
                ],
                'owner_id' => [
                    'label' => 'صاحب دام',
                    'no_options_message' => ' مالکی در کلینیک انتخاب شده وجود ندارد.',
                ],
                'animal_id' => [
                    'label' => 'دام / حیوان',
                    'panel' => [
                        'admin' => [
                            'no_options_message' => 'مالک دارای حیوان نیست.',
                        ],
                        'owner' => [
                            'no_options_message' => 'شما هنوز حیوانی ثبت نکرده اید.',
                        ],
                    ],

                ],
                'selected_date' => [
                    'label' => 'تاریخ نوبت',
                ],
                'doctor_id' => [
                    'label' => 'دامپزشک',
                    'no_options_message' => 'هیچ پزشکی در این تاریخ فعال نیست.',
                ],
                'slot_id' => [
                    'label' => 'وقت خالی',
                    'no_options_message' => 'وقت خالی برای این پزشک در تاریخ انتخاب‌شده وجود ندارد.',
                ],
                'status' => [
                    'label' => 'وضعیت',
                ],
                'description' => [
                    'label' => 'توضیحات',
                ],
                'sections' => [
                    'info' => 'اطلاعات نوبت',
                    'medical_info' => 'اطلاعات پزشکی',
                ],
            ],
            'steps' => [
                '1' => 'انتخاب کلینیک و مالک',
                '2' => 'تاریخ و پزشک',
                '3' => 'انتخاب زمان و توضیحات',
            ],
        ],
    ],

    'table' => [
        'columns' => [
            'clinic'     => 'کلینیک',
            'animal'     => 'دام',
            'owner'      => 'صاحب',
            'doctor'     => 'دامپزشک',
            'date'       => 'تاریخ',
            'time'       => 'ساعت',
            'status'     => 'وضعیت',
            'created_at' => 'تاریخ ثبت',
        ],
        'filters' => [
            'clinic'     => 'فیلتر کلینیک',
            'status'     => 'فیلتر وضعیت',
            'owner'      => 'فیلتر صاحب دام',
            'created_at' => 'تاریخ ثبت رزرو',
            'doctor'     =>  'فیلتر دکتر',
            'from_booked_date' => 'از تاریخ',
            'to_booked_date'   => 'تا تاریخ',
        ],
        'actions' => [
            'confirm' => [
                'label' => 'تایید نوبت',
                'notification' => 'نوبت با موفقیت تایید شد.',
            ],
            'check_in' => [
                'label' => 'پذیرش بیمار',
                'notification' => 'بیمار با موفقیت پذیرش شد.',
            ],
            'start_visit' => [
                'label' => 'شروع ویزیت',
                'notification' => 'ویزیت با موفقیت شروع شد.',
            ],
            'reject' => [
                'label' => 'رد نوبت',
                'notification' => 'نوبت رد شد و اسلات آزاد گردید.',
            ],
            'cancel' => [
                'label' => 'لغو نوبت',
                'notification' => 'نوبت لغو شد و اسلات آزاد گردید.',
            ],
            'complete' => [
                'label' => 'اتمام نوبت',
                'notification' => 'نوبت با موفقیت به اتمام رسید.',
            ],
        ],
        'emptyState' => [
            'heading' => 'نوبتی برای شما پیدا نشد.',
            'description' => 'شما هنوز نوبتی رزرو نکرده اید.',
        ],
    ],
];
