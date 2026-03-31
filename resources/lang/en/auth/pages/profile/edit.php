<?php

return [
    'label' => 'Edit your profile',
    'slug' => 'edit-profile',

    'navigations' => [
        'label' => 'Edit profile',
        'group' => 'Settings',
    ],

    'notifications' => [
        'email_change_verification_sent' => [
            'title' => 'Email address change request sent',
            'body' => 'A request to change your email address has been sent to :email. Please check your email to verify the change.',
        ],
        'updated' => [
            'title' => 'Your profile has been updated.',
        ],
    ],
    'actions' => [
        'update' => [
            'label' => 'Save changes',
        ],
        'cancel' => [
            'label' => 'Cancel',
        ]
    ],
    'form' => [
        'avatar' => [
            'label' => 'Avatar',
        ],
        'first_name' => [
            'label' => 'First name',
        ],
        'surname' => [
            'label' => 'Surname',
        ],
        'username' => [
            'label' => 'User Name',
        ],
        'email' => [
            'label' => 'Email Address',
        ],
        'mobile' => [
            'label' => 'Mobile',
        ],
        'birth_date' => [
            'label' => 'Birth Date',
        ],
        'gender' => [
            'label' => 'Gender',
        ],
        'address' => [
            'label' => 'Address',
        ],
        'password' => [
            'label' => 'Password',
            'validation_attribute' => 'password',
        ],
        'password_confirmation' => [
            'label' => 'Confirm new Password',
            'validation_attribute' => 'password confirmation',
        ],
        'current_password' => [
            'label' => 'Current Password',
            'validation_attribute' => 'current password',
            'below_content' => 'For security, please confirm your password to continue.',
        ],
    ],
];
