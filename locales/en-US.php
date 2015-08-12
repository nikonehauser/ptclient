<?php
$brandName = \Tbmt\Config::get('brand.name');
$brandNameShort = \Tbmt\Config::get('brand.short');

return [
  'common' => [
    'brand_name' => $brandName,
    'brand_name_short' => 'HYA',

    'member_types' => [
      'Member',
      'Sub Promoter',
      'Promoter',
      'Organization Leader',
      'Director',
      'Developer',
      'CEO'
    ],

    'forbidden_countries' => [
      'Norway',
      'Sweden',
      'Finland',
      'Denmark',
      'Ireland',
      'Iceland',
      'Great Britain',
      'Belgium',
      'Netherlands',
      'Luxembourg',
      'France',
      'Monaco',
      'Switzerland',
      'Germany',
      'Austria',
      'Canada',
      'USA',
    ]
  ],


    /* DATE FORMATS
    ---------------------------------------------*/
  'date_format_php' => [
    'default' => "m/d/Y",
    'short'   => "M. d",
    'long'    => "F d, Y",
  ],

  'datetime_format_php' => [
    'default' => "m/d/Y H:i",
    'short'   => "M. d H:i",
    'long'    => "F d, Y H:i",
  ],

  'time_format_php' => [
    'default' => "H:i",
    'short'   => "H:i",
    'long'    => "H:i",
  ],

  'currency_format' => [
    'dec_point' => '.',
    'thousands_sep' => ',',
    'decimals_count' => 2,
  ],

  'currency_symbol' => [
    'EUR' => '€',
    'USD' => '$',
    'INR' => '₹',
  ],

  'currency_name' => [
    'EUR' => 'Euro',
    'USD' => 'US Dollar',
    'INR' => 'Rupien',
  ],

    /* ERRORS
    ---------------------------------------------*/
  'error' => [
    'greater_zero' => 'Must be greater zero',
    'money_numeric' => 'Must be numeric greater or equal zero',
    'empty'  => 'Can not be empty',
    'email'  => 'Invalid email address',
    'int'    => 'Invalid integer',
    'accept' => 'Must be accepted',
    'password' => 'Invalid password',
    'password_conditions' => '5 characters or more, at least one small letter and one digit',
    'password_unequal' => 'Passwords were not equal',

    'referral_member_num' => 'Referrer number does not exist',
    'member_num' => 'Member number does not exist',
    'member_num_unpaid' => 'Member had not paid yet',
    'age_of_18' => 'Must be 18 or older',
    'referrer_paiment_outstanding' => 'The member exists but we didn´t receive the member fee yet. Unfornately you have to wait for this members full membership',
    'invitation_code_inexisting' => 'Invitation Code does not exist',
    'invitation_code_invalid' => 'Invalid invitation code',
    'invitation_code_used' => 'Invitation code already used',

    'sub_promoter_to_promoter' => 'Member is no promoter',

    'login' => 'Invalid login credentials'
  ],


    /* VIEWS
    ---------------------------------------------*/
  'view' => [
    'common' => [
      'brand_name' => $brandName,
      'brand_name_short' => 'HYA',

      'email_us' => 'Email us: ',

      'navigation_links' => [
        'member' => 'The Idea',
        'projects' => 'Projects',
        'about' => 'Contact',
        'account' => 'Profile'
      ],
      'navigation_sublinks' => [
        'member' => [
          'index' => 'Member recruit members',
          'system' => 'Our marketing system',
          'signup' => 'Signup',
        ],
        'projects' => [
          'index' => 'Social projects',
          'girls_schools'  => 'Schools for girls',
          'residential_home'  => 'Residential home',
          'vocational_education'  => 'Vocational education',
          'hospitals'  => 'Hospitals',
        ],
        'account' => [
          'index' => 'Information',
          'invoice' => 'Invoice',
          'rtree' => 'Referral Tree',
          'htree' => 'Hierarchy Tree',
          'logout' => 'Logout',
        ],
        'about' => [
          'index' => 'Contact',
          'faq' => 'FAQ',
        ]
      ],
      'member_login' => 'Member Login',
      'copyright_name' => $brandName,

      'useful_link_contact' => 'You have questions? Just contact us!',
      'useful_link_faq' => 'Frequently asked questions',
      'useful_link_terms' => 'Terms of Use',
      'useful_link_about_us' => 'About us'
    ],

    /* VIEWS - MEMBER
    ---------------------------------------------*/
    'member' => [
      'btn' => [
        'signup' => 'Signup now'
      ],
      'text' => [
        'Member advertises member'
      ],

      'system' => [
        'page_title' => 'Marketing System',
        'vs1' => 'Payment level 1',
        'vs2' => 'Payment level 2',
        'notice' => 'Notice',
        'signup_now' => 'Signup Now',
        'etc' => 'etc.',
        'intro1' => 'This page will explain our {0} in detail',
        'intro1h' => ['marketing system'],
        'intro2' => 'For better understanding we will follow a detailed example',
        'you' => 'You',
        'head_explanation' => 'Explanation',
        'head_part' => 'Part',
        'head_illustration' => 'Illustration',
        '1' => 'You become a member through registering and paying the contribution of {0}. In our marketing system, you are automatically on payment level 1.',
        '1h' => ['$_member_fee_$'],
        '1_1' => 'You = Member',

        '2' => 'Now you recruit your first two new members, Anna and Bernd.',
        '3' => 'Thereby you earn $5 each on level 1, hence altogether you earn {0}.',
        '3h' => ['$_2_times_lvl1_$'],
        '4' => 'As you have recruited two new members successfully, you automatically rise to payment level 2.',
        '5' => 'Now you recruit your third new member, Chris.',
        '6' => 'Thereby you earn a payment of {0} on level 2.',
        '6h' => ['$_lvl2_$'],
        '7' => 'For every additional member that you recruit in the future, you would also earn {0} each.',
        '7h' => ['$_lvl2_$'],
        '8' => 'Since Chris is now a member, he automatically is on payment level 1.',
        '9' => 'You now recruit – for example, because of a severe disease or because of an accident – no more members.',
        '10' => 'But Chris recruits, as you have done before, his first two new members, Dean and Emi.',
        '11' => 'Chris thereby earns a {0} payment for each recruited member on level 1, thus altogether {1}.',
        '11r' => ['$_lvl1_$', '$_2_times_lvl1_$'],
        '12' => 'You earn a payment of {0} for each recruited member in this case, thus altogether {1}.',
        '12h' => ['$_indirect_$', '$_2_times_indirect_$'],
        '13' => '{0}',
        '13h' => ['At this point you already earn payments without becoming active yourself!'],
        '14' => 'Already now – {0} – you have earned {1} {2}',
        '14h' => ['after just one week', '$_after_one_week_$', 'and almost entirely balanced your membership contribution!'],
        '15' => 'Chris rises automatically to payment level 2 after his first two successful recruitings.',
        '16' => 'As you and Chris now have the same payment level, you do not earn anything anymore when Chris recruits new members.',
        '17' => 'However, now Dean and Emi are placed into your tree.',
        '18' => 'This happens to each of our members. The first two new recruited members are assigned to the member that has once recruited the recruiting member.',
        '19' => 'After Dean and Emi have paid their membership fee, they both are on payment level 1.',
        '20' => 'Now Dean and Emi each recruit their first two new members, Frank, Gisele, Heinz and Ida.',
        '21' => 'Thereby Dean and Emi earn {0} for each recruited member and you earn {1} each, thus altogether you earn {2}.',
        '21h' => ['$_lvl1_$', '$_indirec_$', '$_4_times_indirect_$'],
        '22' => 'Now Dean and Emi rise to level 2 after their first two successful recruitings.',
        '23' => 'From now on, you do not earn anything when Dean and Emi recruit new members.',
        '24' => 'However, now Frank, Gisele, Heinz and Ida are placed into your tree. As they have already paid their contribution, they are all on payment level 1.',
        '25' => 'Now Frank, Gisele, Heinz and Ida recruited their first two members each.',
        '26' => 'Thereby Frank, Gisele, Heinz and ida earn {0} for each member and you earn {1} each, thus altogether you earn {2}.',
        '26h' => ['$_lvl1_$', '$_indirec_$', '$_8_times_indirect_$'],
        '27' => 'Now you have already not only balanced your membership fee and returned your investment, but also earned something on top of that.',
        '28' => 'And how long did it take?',
        '29' => 'From experience, it takes one day on average, until a member recruits a new member successfully.',
        '30' => 'Therefore, this example would take 10 to 14 days, for example.',
        '31' => 'If every future member only recruits two new members, but only needs one week on average, your earnings develop as you can see in the table:',
        '31_week' => 'Week',
        '32' => '{0} you therefore already earned {1} {2} and have helped yourself according to our club motto “Help Yourself”.',
        '32h' => ['After approximately ten weeks', 'more than', '$_after_10_weeks_$'],
        '33' => 'Of course this will not work indefinitely, because the number of people are naturally limited. There were and are, however, marketing systems worldwide and in different economic fields that have created a sheer endless number of prosperous people and are still creating new ones.',
        '34' => 'With us, members who have decided to recruit new members, especially  early on, will earn lots and lots of money.',
        '35' => 'Trustworthy mathematical calculations project that members who became active in the first 12 months after starting the marketing system and at least recruit three new members, will earn altogether $100,000 to $300,000 in the first 12 months.',
        '36' => 'There are no limits on the very top. If only one member recruits e.g. 10, 15 or 20 or even more members, naturally considerably higher earnings are possible.',
        '37' => 'However, we will also have members, who with mediocre effort will just earn a meager amount, for example a few thousand dollars.',
        '38' => 'Then we will also have members who will only earn very few, for example only few hundreds of dollars.',
        '39' => 'And of course in the future, probably in a few years, we will also have cases of members which will not earn anything. They have through their contribution facilitated the earnings of the other members and the development of several social projects and thus sponsored their $70 directly for the goods of their fellow people.',
        '40' => 'We also need to say that, due to the way the system works, you will earn less in dependence of how near we are to the end of recruiting. It is mostly unpredictable but someday the system will stagnate until there will be no new members. That day we will close the signup and start to realize benificial projects.',
        '41' => 'The specific development of your personal profits can hardly be predicted. However, the possibilities result from each member’s personal effort.',
        '42' => 'If you believe,',
        '43' => 'That you can recruit at least three new members',
        '44' => 'That these members again can recruit two new members',
        '45' => 'That these new members each can recruit at least two new members',
        '46' => 'Then you can rest assured that your contribution is reimbursed within 30 days and that you additionally will have scored your first profit.',
        '47' => 'Furthermore, if you believe that over a course of only three months each new member at least recruits three new members, then we can congratulate you because, if it happens like that, you will earn lots and lots of money with us and will be able to afford yourself a whole new quality of life.',
        '48' => 'You will be able to afford things which you have never thought of before. You will be able to support your family, enjoy best medical care, buy a new car, spend no time thinking about what to live from when you’re old, etc.',
        '49' => 'Exactly that is the whole purpose of our association!',
        '50' => 'Take action now',
        '51' => 'Here you can register for a better life:',
      ],

      'index' => [
        'page_title' => 'Members advertise members',

        'basic_idea' => 'Basic idea',
        'basic_idea_title' => [
          'Basics',
          'Passive income',
          'For those who deserve',
        ],
        'basic_idea_text' => [
          'Basically every human can become a member of the '.$brandName.'. The {onetime} contribution is only {member_fee}.',
          'Every member can recruit new members for the '.$brandName.' within the “Members recruit new members” marketing system, and thereby achieve a constant and, above all, {passive_income}.',
          'Citizens living in rich countries are not allowed to become a member of the '.$brandName,
        ],
        'basic_idea_highlights' => [
          ['onetime' => 'one-time', 'member_fee' => '$_member_fee_$'],
          ['passive_income' => 'passive income'],
          null
        ],

        'forbidden_countries' => 'Forbidden countries',
        'why_forbidden_countries' => 'Why forbid these countries',
        'forbidden_countries_text' => 'Each citizen of those countries has sufficient access to education, jobs, medical treatments and social welfare, thus we do not offer these citizens access to our self-help club.',

        'become_member_title' => 'I want to be member',
        'become_member_text' => 'If you also want to become a member, then please register here on our homepage and pay the membership fee of {member_fee} within 10 days. Then you are a member of the '.$brandName.' and you can use our fantastic marketing system to achieve a permanent and {passive_income}.',
        'become_member_highlights' => [
          'passive_income' => 'passive income',
          'member_fee' => '$_member_fee_$'
        ],


        'member_starting' => 'What will i get as member',
        'member_starting_title' => [
          'Payment Level 1',
          'Marketing System',
          'Immediately start recruiting',
        ],
        'member_starting_text' => [
          'In our “Members recruit new members” marketing system you are automatically integrated on payment level 1.',
          'We want to show you in the following explanation of our marketing system what this means for you.',
          'Directly after you have finished your registration, you can recruit new members for the '.$brandName.' via this homepage.',
        ],
        'member_starting_button' => [
          'What is payment level 1?',
          'Explain this marketing system!',
          'Yes, I want to signup!',
        ]
      ],

      'signup' => [
        'page_title' => 'Signup for membership',
        'form_error' => 'Form has invalid fields.',
        'form_labels' => [
          'contact' => 'Contact',
          'address' => 'Address',
          'bank_account' => 'Bank Account',
          'confirm' => 'Confirm',

          'referral_member_num' => 'Referrer Member Number',
          'invitation_code' => 'Invitation Code (optional)',
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
          'password' => 'Your password',
          'password2' => 'Repeat password',

          'accept_agbs' => 'I have read and accept the terms and conditions',
          'accept_valid_country' => 'I confirm that i am NOT citizen of one of following countries:',
          'not_accepted_countries' => '',

          'submit' => 'Submit',

          'bank_account_info' => 'We will never charge your bank account. But you will receive your provisions for your referalls at this account.',
          'email_info' => 'You will never receive spam from us. We need your E-Mail to contact you if for example we have failures to transfer your provisions. If you have no email you can register for free there: ',
        ]
      ],

      'signupSuccess' => [
        'page_title' => 'Successfully signed up',
        'welcome' => 'Welcome to "'.$brandName.'".',
        'new_member_num' => 'Your member number is {num}. Please note this number.',
        'info' => 'Use it to invite new members to help them and yourself.',
      ]
    ],


    /* VIEWS - ACCOUNT
    ---------------------------------------------*/
    'account' => [
      'login' => [
        'page_title' => 'Member Login',
        'error' => 'Error',
        'password_reset' => 'Password forgotten?',
        'form_labels' => [
          'login' => 'Login',

          'num' => 'Member Number',
          'pwd' => 'Password',

          'submit' => 'Submit'
        ]
      ],
      'index' => [
        'page_title' => 'Profile',
        'welcome' => 'Welcome {name}',
        'navigation_links' => [
          'index' => 'Information',
          'invoice' => 'Invoice',
          'rtree' => 'Referral Tree',
          'htree' => 'Hierarchy Tree',
          'invitation' => 'Invitations',
          'bonus_payments' => 'Bonus Payments',
          'bonus_levels' => 'Bonus Levels'
        ]
      ],
      'tabs' => [
        'index' => [
          'account' => 'Account',
          'your_num' => 'Your member number',
          'warning' => 'Warning!',
          'tank_you' => 'Thank You!',
          'unpaid_text' => 'We did not receive your member fee yet!',
          'paid_text' => 'We received your member fee.',
          'address' => 'Address',
          'email' => 'Email',
          'email_none' => '-none-',
          'bank_account' => 'Bank account',
          'bank_recipient' => 'Recipient Name',
          // 'iban' => 'IBAN (International Bank Account Number)',
          'iban' => 'IBAN',
          // 'bic' => 'BIC (Bank Identifier Code)'
          'bic' => 'BIC',
          'member_type' => 'Membership type',
          'bonus_level' => 'Bonus Level',

          'change_pwd' => 'Change password',

          'welc_not_1' => 'Welcome {name}',
          'welc_not_2' => 'This is you profile where you can find various information',
          'welc_not_3' => 'IMPORTANT! Note your member number! You will need it for login, recruting etc.',
        ],
        'invoice' => [
          'stats' => 'Statistics',
          'transactions' => 'Transactions (grouped by reason)',
          'oustanding_advertised_count' => 'Outstanding advertised members count',
          'advertised_member_count' => 'Advertised members count',
          'outstanding_total' => 'Total outstanding to be transferred',
          'transferred_total' => 'Total transferred',
          'custom' => '--custom--',
          'single_amount' => 'Single Amount',
          'quantity' => 'Quantity',
          'currency' => 'Currency',
          'total' => 'Total',
          'reason' => 'Reason',
          'transaction_reasons' => [
            0 => 'Advertised Lvl 1',
            1 => 'Advertised Lvl 2',
            2 => 'Bonus Lvl 2 Indirect',
            3 => 'Bonus director',
            4 => 'Bonus organization leader',
            5 => 'Bonus promoter',
            6 => 'Bonus IT',
            7 => 'Bonus CEO1',
            8 => 'Bonus CEO2',
            9 => 'Bonus lawyer',
            10 => 'Bonus sub promoter',

            1001 => 'Custom bonus payment',
            1002 => 'Remaining member fee',
            1003 => 'Transfer to root system',

            2000 => 'Custom bonus level payment',
          ]
        ],
        'tree' => [
        ],
        'invitation' => [
          'form_title' => 'Create new invitation',
          'type' => 'Type',
          'free_signup' => 'Free signup',
          'promoter_num' => 'On the recommendation of Promoter (Num)',
          'submit' => 'Submit',

          'list_title' => 'Invitations',
          'hash' => 'Code',
          'creation_date' => 'Created',
          'accepted_date' => 'Accepted',
          'signup_success' => 'Successfully created invitation code.',
          'success' => 'Success!',

          'recipient_details' => 'Recipient details',
          'member_type' => 'Membership type',
          'member_num' => 'Member number',
          'address' => 'Address',
          'email' => 'Email',
          'email_none' => '-none-',
        ],
        'bonus_payments' => [
          'form_title' => 'Create bonus payment for a member',
          'recipient_num' => 'Member number who receives this bonus',
          'amount' => 'Amount',
          'amount_in_currency' => 'Amount in {symbol} ({name})',
          'purpose' => 'Purpose',
          'validate_form' => 'Validate form',
          'submit' => 'Submit',

          'recipient_details' => 'Recipient details',
          'member_type' => 'Membership type',
          'member_num' => 'Member number',
          'address' => 'Address',
          'email' => 'Email',
          'email_none' => '-none-',

          'list_title' => 'Bonus payments',
          'recipient_num_th' => 'Recipient member number',
          'creation_date' => 'Created',
          'accepted_date' => 'Accepted',

          'signup_success' => 'Successfully created bonus payment.',
          'success' => 'Success!',
        ],
        'bonus_levels' => [
          'form_title' => 'Change bonus level for member',
          'recipient_num' => 'Member number who receives this bonus level',
          'amount_in_currency' => 'Amount in {symbol} ({name})',
          'validate_form' => 'Validate form',
          'submit' => 'Submit',

          'recipient_details' => 'Recipient details',
          'member_type' => 'Membership type',
          'bonus_level' => 'Bonus Level',
          'member_num' => 'Member number',
          'address' => 'Address',
          'email' => 'Email',
          'email_none' => '-none-',
          'signup_success' => 'Successfully changed bonus level.',
          'success' => 'Success!',

          'list_title' => 'Bonus members',
          'member_num' => 'Member number',
          'date' => 'Date',
        ]
      ]
    ],


    /* VIEWS - MANAGE
    ---------------------------------------------*/
    'manage' => [
      'password_reset' => [
        'page_title' => 'Password reset',
        'error' => 'Error!',
        'success' => 'Success!',
        'success_msg' => 'We have send you an email with further instructions.',
        'form_labels' => [
          'num' => 'Your member number',
          'submit' => 'Reset password',
        ]
      ],
      'do_reset_password' => [
        'page_title' => 'Password reset',
        'error' => 'Error!',
        'error_msg' => 'Invalid reset token',
        'success' => 'Success!',
        'success_msg' => 'Your new password is:',
      ],
      'change_pwd' => [
        'page_title' => 'Change your password',
        'error' => 'Error!',
        'success' => 'Success!',
        'success_msg' => 'We have send you an email with further instructions.',
        'form_labels' => [
          'old_pwd' => 'Current password',
          'new_pwd' => 'New password',
          'new_repeat' => 'Repeat new password',
          'submit' => 'Change password',
        ]
      ],
    ],


    /* VIEWS - PROJECTS
    ---------------------------------------------*/
    'projects' => [
      'index' => [
        'page_title' => 'Projects',
        'about_us_title' => 'About us and our idea',
        'about_us_title2' => 'Help yourself AND others',
        'planed_projects_title' => 'Our planeed Projects for self-help',
        'affected_countries_title' => 'Countries we want to help',
      ],
      'girls_schools' => [
        'page_title' => 'Grils schools'
      ]
    ],


    /* VIEWS - ABOUT
    ---------------------------------------------*/
    'about' => [

      'index' => [
        'page_title' => 'Contact',
        'form_title' => 'Contact form',
        'form_labels' => [
          'name' => 'Name',
          'email' => 'E-Mail',
          'subject' => 'Subject',
          'message' => 'Message',

          'submit' => 'Submit'
        ],
        'signup_success' => 'Successfully transferred message. We will hurry to process your request!',
        'success' => 'Success!',
      ],

      'faq' => [
        'page_title' => 'FAQ',
        'welcome_faq' => 'Frequently {0} Questions',
        'welcome_faqh' => ['Asked'],

        'items' => [
          'When does the projects get started',
          '...',

          'Can I follow the projects progress?',
          '...',

          'When does the projects get started',
          '...',

          'When will I get my provisions',
          '...',

          'What does happen if I sign up but do not pay the member fee',
          '...',

          'Why do you need an email address from myself?',
          '...',

          'I still have questions',
          '...',

          'How to pay the member fee?',
          '...',

          'I paid the member fee a view days ago but I still see the message that i did not paid yet?',
          '...',
        ]
      ],
    ]
  ],



    /* MAILS
    ---------------------------------------------*/
  'mail' => [
    'password_reset' => [
      'subject' => 'Password reset',
      'body' => "We heard that you lost your {web_page_name} password. Sorry about that!\n\r
But don’t worry! You can use the following link within the next day to reset your password:\n\r
{link}\n\r
If you don’t use this link within 24 hours, it will expire.\n\r
Thanks,\n\r
Your friends at {web_page_name}"
    ]
  ]
];

?>