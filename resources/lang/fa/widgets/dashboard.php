<?php


return [
    'appointment_trend' => [
        'heading' => 'روند نوبت‌ها',
        'description' => 'نوبت‌های ایجاد شده در طول زمان',
        'filters' => [
            'today' => 'امروز',
            'last_7_days' => '۷ روز گذشته',
            'last_30_days' => '۳۰ روز گذشته',
            'last_3_months' => '۳ ماه گذشته',
            'this_year' => 'امسال',
        ],
        'datasets' => [
            'appointments' => 'نوبت‌ها',
        ],
    ],

    'greeting' => 'خوش آمدید',

    /* ── Appointment statistics ───────────────────────── */

    'stats' => [

        'total' => [
            'label'       => 'کل نوبت‌ها',
            'description' => 'مجموع نوبت‌های ثبت‌شده امروز',
        ],

        'pending' => [
            'label'       => 'در انتظار',
            'description' => 'نوبت‌های در انتظار تأیید',
        ],

        'confirmed' => [
            'label'       => 'تأیید شده',
            'description' => 'آماده برای ویزیت',
        ],

        'completed' => [
            'label'       => 'تکمیل شده',
            'description' => 'ویزیت‌های انجام‌شده',
        ],

        'cancelled' => [
            'label'       => 'لغو شده',
            'description' => 'نوبت‌های لغو‌شده',
        ],

        'no_show' => [
            'label'       => 'عدم حضور',
            'description' => 'بیمار حاضر نشده',
        ],

        'free_slots' => [
            'label'       => 'وقت های های آزاد من',
            'description' => 'وقت های های آزاد امروز',
        ],
        'reserved_slots' => [
            'label'       => 'وقت های رزرو شده من',
            'description' => 'وقت های رزرو شده امروز',
        ],
    ],

    /* ── Schedule summary ─────────────────────────────── */

    'schedule' => [
        'title'            => 'برنامه امروز',
        'first_appointment' => 'اولین نوبت',
        'last_appointment'  => 'آخرین نوبت',
        'working_hours'     => 'مدت زمان ساعات کاری امروز',
    ],

    'empty_schedule' => [
        'panel' => [
            'admin' => [
                'title' => 'نوبتی برای امروز ثبت نشده',
                'description' => 'امروز هیچ نوبتی برای این کلینیک ثبت نشده است.',
            ],
            'doctor' => [
                'title' => 'امروز نوبتی با شما رزرو نشده ',
                'description' => 'امروز هیچ نوبتی برای برنامه کاری های کاری شما رزرو نشده است.',
            ],
            'owner' => [
                'title' => 'امروز شما نوبتی شما ندارید. ',
                'description' => 'امروز شما هیچ نوبتی رزرو نکردید.',
            ],
            'staff' => [
                'title' => 'نوبتی برای امروز ثبت نشده',
                'description' => 'امروز هیچ نوبتی برای این کلینیک ثبت نشده است.',
            ],
        ]
    ],

    /* ── Quick information ────────────────────────────── */

    'quick_info' => [
        'title'  => 'اطلاعات سریع',
        'clinic' => 'کلینیک فعلی',
        'role'   => 'نقش کاربری',
        'tenant' => 'شعبه',
    ],
];
