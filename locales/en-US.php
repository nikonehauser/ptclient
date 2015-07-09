<?php
return [
  'common' => [
    'brand_name' => 'Help Yourself Association',
    'brand_name_short' => 'HYA',
  ],

  'error' => [
    'empty'  => 'Can not be empty',
    'email'  => 'Invalid email address',
    'int'    => 'Invalid integer',
    'accept' => 'Must be accepted',

    'referer_num' => 'Referer num does not exist',
    'age_of_18' => 'Must be 18 or older',
    'referer_paiment_outstanding' => 'The member exists but we didn´t receive the member fee yet. Unfornately you have to wait for this members full membership',
  ],

  'view' => [
    'common' => [
      'navigation_links' => [
        'member' => 'Become Member',
        'projects' => 'Projects',
        'about' => 'About'
      ],
    ],
    'home' => [
      'text' => [
        'Der Verein wurde für Menschen gegründet'
      ]
    ],
    'member' => [
      'btn' => [
        'signup' => 'Signup now'
      ],
      'text' => [
        'Member advertises member'
      ],

      'signup' => [
        'form_labels' => [
          'contact' => 'Contact',
          'address' => 'Address',
          'bank_account' => 'Bank Account',
          'confirm' => 'Confirm',

          'referer_num' => 'Referer Member Number',
          'firstName' => 'First name',
          'lastName' => 'Last name',
          'title' => 'Title',
          'email' => 'Email',
          'age' => 'Age',
          'country' => 'Country',
          'city' => 'City',
          'country' => 'Country',
          'iban' => 'IBAN',
          'bic' => 'BIC',
          'bank_recipient' => 'Recipient',

          'accept_agbs' => 'I have read and accept the terms and conditions',
          'accept_valid_country' => 'I confirm that i am not citizen of one of following countries',

          'submit' => 'Submit'
        ]
      ],

      'signup_success' => [
        'welcome' => 'Welcome to "Help Yourself Association".',
        'new_member_num' => 'Your member number is {num}. Please note this number.',
        'info' => 'Use it to invite new members to help them and yourself.',
      ]
    ],
    'projects' => [
      'text' => [
        'Social projects to help yourself'
      ]
    ],
    'about' => [
      'text' => [
        'Contact:'
      ]
    ]
  ]
];

?>