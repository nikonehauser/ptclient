<?php

/*

(\w|\.|\%|,)[\r\n](\w|\.|\%|,)
$1 $2

 */

$faqItems = include 'faq.php';

$brandName = \Tbmt\Config::get('brand.name');
$brandNameShort = \Tbmt\Config::get('brand.short');
$webPageName = 'Better living club';

return [
  'common' => [
    'brand_name' => $brandName,
    'brand_name_short' => 'BL',

    'member_types' => [
      'Donator',
      'Sub Promoter',
      'Promoter',
      'Marketing Leader',
      'Director',
      'Developer',
      'CEO',
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
    ],
  ],

    /* DATE FORMATS
    ---------------------------------------------*/
  'date_format_php' => [
    'default' => "m/d/Y",
    'short' => "M. d",
    'long' => "F d, Y",
  ],

  'datetime_format_php' => [
    'default' => "m/d/Y H:i",
    'short' => "M. d H:i",
    'long' => "F d, Y H:i",
  ],

  'time_format_php' => [
    'default' => "H:i",
    'short' => "H:i",
    'long' => "H:i",
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
    'INR' => 'Rupees',
  ],

    /* ERRORS
    ---------------------------------------------*/
  'error' => [
    'greater_zero' => 'Must be greater zero',
    'money_numeric' => 'Must be numeric greater or equal zero',
    'empty' => 'Can not be empty',
    'email' => 'Invalid email address',
    'int' => 'Invalid integer',
    'accept' => 'Must be accepted',
    'password' => 'Invalid password',
    'password_conditions' => '5 characters or more, at least one small letter and one digit',
    'password_unequal' => 'Passwords were not equal',

    'referral_member_num' => 'Referrer number does not exist',
    'member_num' => 'Donator number does not exist',
    'member_num_unpaid' => 'Donator has not paid yet',
    'age_of_18' => 'Must be 18 or older',
    'referrer_paiment_outstanding' => 'The donator exists but we didn´t receive the donation yet. Unfortunately you have to wait for this donator’s donation',
    'invitation_code_inexisting' => 'Invitation Code does not exist',
    'invitation_code_invalid' => 'Invalid invitation code',
    'invitation_code_used' => 'Invitation code already used',

    'sub_promoter_to_promoter' => 'Donator is no promoter',

    'login' => 'Invalid login credentials',
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
        'projects' => 'Home',
        'about' => 'About Us',
        'account' => 'Profile',
      ],
      'navigation_sublinks' => [
        'member' => [
          'index' => 'Donators recruit donators',
          'system' => 'Our marketing system',
          'signup' => 'Sign up',
        ],
        'account' => [
          'index' => 'Information',
          'invoice' => 'Invoice',
          'rtree' => 'Referral Tree',
          'htree' => 'Hierarchy Tree',
          'logout' => 'Logout',
        ],
        'about' => [
          'index' => 'About Us',
          'contact' => 'Contact',
          'faq' => 'FAQ',
          'terms' => 'Terms of Use',
        ],
      ],
      'member_login' => 'Donator Login',
      'copyright_name' => $brandName,

      'useful_link_contact' => 'Any questions? Just contact us!',
      'useful_link_faq' => 'Frequently asked questions',
      'useful_link_terms' => 'Terms of Use',
      'useful_link_about_us' => 'About us',
    ],

    /* VIEWS - MEMBER
    ---------------------------------------------*/
    'member' => [
      'btn' => [
        'signup' => 'Sign up now',
      ],
      'text' => [
        'Donator advertises donators',
      ],

      'system' => [
        'page_title' => 'Marketing System',
        'vs1' => 'Club donator level 1',
        'vs2' => 'Club donator level 2',
        'notice' => 'Notice',
        'signup_now' => 'Sign up now',
        'etc' => 'etc.',
        'intro1' => 'This page will explain our {0} in detail',
        'intro1h' => ['marketing system'],
        'intro2' => 'To make it easy we will follow a detailed example',
        'you' => 'You',
        'head_explanation' => 'Explanation',
        'head_part' => 'Step',
        'head_illustration' => 'Illustration',

        'pdf_explanation' => 'There is also an illustrated explanation in an extra document. If you prefer to read a pdf document, ',
        'pdf_explanation_link' => 'just click here',

        '1' => 'You become a donator through registering and paying the contribution of {0}. In our marketing system you will automatically be on club donator level 1.',
        '1h' => ['$_member_fee_$'],
        '1_1' => 'You = donator',

        '2' => 'Now you recruit your first two new donators Durga and Kamal.',
        '3' => 'Thereby you will earn {0} each on level 1, hence altogether you earn {1}.',
        '3h' => ['$_lvl1_$', '$_2_times_lvl1_$'],
        '4' => 'When you have recruited two new donators successfully, you automatically get club donator level 2.',
        '5' => 'Now you recruit your third new donator, Jivan.',
        '6' => 'Thereby you will earn {0} on level 2.',
        '6h' => ['$_lvl2_$'],
        '7' => 'For every additional donator you recruit in the future, you will also earn {0} each.',
        '7h' => ['$_lvl2_$'],
        '8' => 'Since Jivan is now a donator, he automatically is on club donator level 1.',
        '9' => 'Should you not be able to recruit any more donators',
        '10' => 'but Jivan recruits, just like you did before, his first two new donators, Rati and Lila.',
        '11' => ' Jivan thereby earns {0} for each recruited donator on level 1, thus altogether {1}.',
        '11r' => ['$_lvl1_$', '$_2_times_lvl1_$'],
        '12' => 'You earn {0} for each recruited donator in this case, thus altogether {1}.',
        '12h' => ['$_indirect_$', '$_2_times_indirect_$'],
        '13' => '{0}',
        '13h' => ['At this point you already earn money without being active yourself!', ],
        '14' => '{0} – you have already earned {1} {2}',
        '14h' => ['After just one week', '$_after_one_week_$', 'and almost entirely balanced your membership contribution!', ],
        '15' => ' Jivan automatically gets club donator level 2 with his first two successful recruitings.',
        '16' => 'As soon as you and Jivan have the same club donator level, you do not get paid when Jivan recruits new donators.',
        '17' => 'However, now Rati and Lila are placed into your tree.',
        '18' => 'Because the first two new recruited donators (Rati and Lila) are assigned to the donator (you) that has advertised the recruiting donator (Jivan)in the first place.',
        '19' => 'After Rati and Lila have paid their donation, they both are on club donator level 1.',
        '20' => 'Now Rati and Lila each recruit their first two new donators, Devi, Kumar, Gita and Karan.',
        '21' => 'Thereby Rati and Lila earn {0} for each recruited donator and you get {1} each, thus altogether you earn {2}.',
        '21h' => ['$_lvl1_$', '$_indirect_$', '$_4_times_indirect_$'],
        '22' => 'Now Rati and Lila get donator level 2 after their first two successful recruitings.',
        '23' => 'From now on, you do not earn anything when Rati and Lila recruit new donators.',
        '24' => 'However, now Devi, Kumar, Gita and Karan are placed into your tree. As they have already paid their donation, they are all on club donator level 1.',
        '25' => 'Now Devi, Kumar, Gita and Karan recruited their first two donators each.',
        '26' => 'Thereby Devi, Kumar, Gita and Karan earn {0} for each donator and you earn {1} each, thus altogether you earn {2}.',
        '26h' => ['$_lvl1_$', '$_indirect_$', '$_8_times_indirect_$'],
        '27' => 'You now have not only balanced your donation and returned your investment but also earned money on top of that.',
        '28' => 'And how long did it take?',
        '29' => 'From experience, it takes one day on average, until a donator recruits a new donator successfully.',
        '30' => 'Therefore, this example would take 10 to 14 days.',
        '31' => 'If every future donator only recruits two new donators and takes one week on average, your earnings develop as you can see in the following table:',
        '31_week' => 'Week',
        '32' => '{0} you therefore already earned {1} {2}',
        '32h' => ['After approximately ten weeks', 'more than', '$_after_10_weeks_$'],
        '33' => 'Of course this will not work infinitely, because the number of people is naturally limited. There were and are, however, marketing systems worldwide and in different economic fields that have created a sheer endless number of prosperous people and are still creating new ones.',
        '34' => 'With us, donators who have decided to recruit new donators, especially early on, you will earn lots and lots of money.',
        '35' => 'Trustworthy mathematical calculations project that donators who became active in the first 12 months after starting the marketing system and at least recruit three new donators, will earn a total of {0} to {1} in the first 12 months.',
        '35h' => ['$_lvl1_$', '$_lvl1_$'],
        '36' => 'There are no limits: If only one donator recruits e.g. 10, 15 or 20 or even more donators, naturally considerably higher earnings are possible.',
        '37' => 'However, we will also have donators who will just earn a meager amount caused by mediocre effort, for example a few thousand dollars.',
        '38' => 'Then we will also have donators who will only earn very few, for example only few hundreds of dollars.',
        '39' => 'And of course in the future, probably in a few years, we will also have cases of donators who will not earn anything. Through their contribution they have facilitated the earnings of the other donators and the development of several social projects and thus sponsored their {0} directly for the goods of their fellow people.',
        '39h' => ['$_member_fee_$'],
        '40' => 'Due to the way the system works, you will earn less depending on how near we are to the end of recruiting. It is mostly unpredictable but someday the system will stagnate as soon as there are no new donators. That day we will close the register and start to carry out beneficial projects.',
        '41' => 'The specific development of your personal profits can hardly be predicted. However, the possibilities result from each donator’s personal effort.',
        '42' => 'If you believe that,',
        '43' => 'you can recruit at least three new donators',
        '44' => 'these donators again can recruit two new donators',
        '45' => 'these new donators each can recruit at least two new donators',
        '46' => 'Then your donation will be reimbursed within around 15 - 25 days and you additionally will have scored your first profit.',
        '47' => 'Furthermore, if you believe that over a course of only three months each new donator at least recruits three new donators, you will earn lots and lots of money with us and will be able to afford a whole new living standard.',
        '48' => 'You will be able to afford things you have never thought of before. You will be able to support your family, enjoy best medical care, buy a new car, spend no time thinking about what to live from when you’re old, etc.',
        '49' => 'This is the purpose of the Betterliving Club!',
        '50' => 'Take action now',
        '51' => 'You can register for a better life right here:',
      ],

      'index' => [
        'page_title' => 'Donators advertise donators',

        'basic_idea' => 'Basic idea',
        'basic_idea_title' => [
          'Basics',
          'Passive income',
          'For those who deserve',
        ],
        'basic_idea_text' => [
          'Everybody can become a donator of the ' . $brandName . '. The {onetime} donation is only {member_fee}.',
          'Every donator can advertise new donators for the ' . $brandName . ' within the “donators recruit new donators” marketing system, thereby achieve a constant and above all {passive_income}.',
          'Citizens living in rich countries are not allowed to become donators of the ' . $brandName,
        ],
        'basic_idea_highlights' => [
          ['onetime' => 'one-time', 'member_fee' => '$_member_fee_$'],
          ['passive_income' => 'passive income'],
          null,
        ],

        'forbidden_countries' => 'Forbidden countries',
        'why_forbidden_countries' => 'Why these countries are forbidden',
        'forbidden_countries_text' => 'Each citizen of those countries has sufficient access to education, jobs, medical treatments and social welfare, thus we do not offer these citizens access to the Betterliving club.',

        'become_member_title' => 'I want to be donator',
        'become_member_text' => 'If you also want to be a donator, please register on this homepage and pay the membership fee of {member_fee} within 10 days. This will make you a donator of the ' . $brandName . ' and you can use our fantastic marketing system to achieve a permanent and {passive_income}.',
        'become_member_highlights' => [
          'passive_income' => 'passive income',
          'member_fee' => '$_member_fee_$',
        ],


        'member_starting' => 'Why should I donate',
        'member_starting_title' => [
          'Club donator level 1',
          'Marketing System',
          'Immediately start recruiting',
        ],
        'member_starting_text' => [
          'In our “Donators advertise new donators” marketing system you automatically are on club donator level 1.',
          'The meaning of the marketing system will be explained in the following.',
          'Right after you have finished your registration you can recruit new donators for the ' . $brandName . ' via this homepage.',
        ],
        'member_starting_button' => [
          'What does club donator level 1 mean?',
          'Explanation of the marketing system!',
          'Yes, I want to sign up!',
        ],

        'pdf_explanation_head' => 'Marketing system illustration',
        'pdf_explanation' => 'We also prepared an illustrated explanation in an extra document. If you prefer reading a pdf document, ',
        'pdf_explanation_link' => 'just click here',
      ],

      'signup' => [
        'page_title' => 'Sign up to be a donator',
        'form_error' => 'Form has invalid fields.',
        'referral_member_num_help' => 'This is the donator number of the person who is recruiting you. He also will receive the reward for your signup. It is required. You can not join us if nobody invited you with his donator number.',
        'invitation_code_help' => 'This code is NOT required. You may received a special invitation code from the person who is recruiting you. This code may be linked with a bonus or similar things.',
        'form_labels' => [
          'contact' => 'Contact',
          'address' => 'Address',
          'bank_account' => 'Bank Account',
          'confirm' => 'Confirm',

          'referral_member_num' => 'Referrer donator number',
          'invitation_code' => 'Invitation code (optional)',
          'firstName' => 'First name',
          'lastName' => 'Last name',
          'title' => 'Title',
          'email' => 'Email',
          'age' => 'Age',
          'country' => 'Country',
          'zip_code' => 'Zip Code',
          'city' => 'City',
          'country' => 'Country',
          'iban' => 'IBAN',
          'bic' => 'BIC',
          'bank_recipient' => 'Recipient',
          'password' => 'Your password',
          'password2' => 'Repeat password',

          'accept_agbs' => 'I have read and accepted the terms and conditions',
          'accept_valid_country' => 'I confirm that I am NOT a citizen of one of the following countries:',
          'not_accepted_countries' => '',

          'submit' => 'Submit',

          'bank_account_info' => 'We will never charge your bank account. But your payments for your referrals will be paid into this account.',
          'email_info' => 'You will never receive spam from us. We need your e-mail address to contact you in case there are problems with transferring money. If you do not have an e-mail address you can register here for free: ',
        ],
      ],

      'signupSuccess' => [
        'page_title' => 'Successfully signed up',
        'welcome' => 'Welcome to "' . $brandName . '".',
        'new_member_num' => 'Your personal donator number is {num}. Make sure to remember this number.',
        'info' => 'You will need it to invite new donators in order to help them and yourself.',
      ],
    ],

    /* VIEWS - ACCOUNT
    ---------------------------------------------*/
    'account' => [
      'login' => [
        'page_title' => 'Donator login',
        'error' => 'Error',
        'password_reset' => 'Forgot your password?',
        'form_labels' => [
          'login' => 'Login',

          'num' => 'Donator Number',
          'pwd' => 'Password',

          'submit' => 'Submit',
        ],
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
          'bonus_levels' => 'Bonus Levels',
          'dev_paying' => 'DevTest Paying'
        ],
      ],
      'tabs' => [
        'index' => [
          'account' => 'Account',
          'your_num' => 'Your donator number',
          'warning' => 'Warning!',
          'tank_you' => 'Thank You!',
          'unpaid_text' => 'We have not received your donation yet!',
          'paid_text' => 'We have received your donation.',
          'address' => 'Address',
          'email' => 'Email',
          'email_none' => '-none-',
          'bank_account' => 'Bank account',
          'bank_recipient' => 'Recipient Name',
          // 'iban' => 'IBAN (International Bank Account Number)',
          'iban' => 'IBAN',
          // 'bic' => 'BIC (Bank Identifier Code)'
          'bic' => 'BIC',
          'member_type' => 'Donator type',
          'bonus_level' => 'Bonus Level',

          'change_pwd' => 'Change password',
          'change_bank' => 'Change banking',

          'welc_not_1' => 'Welcome {name}',
          'welc_not_2' => 'This is your profile. You can find various information here',
          'welc_not_3' => 'IMPORTANT! Always remember your donator number! You will need it to login, recruit others etc.',
        ],
        'invoice' => [
          'stats' => 'Statistics',
          'transactions' => 'Transactions (grouped by reason)',
          'oustanding_advertised_count' => 'Outstanding advertised donators count',
          'advertised_member_count' => 'Advertised donators count',
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
            4 => 'Bonus marketing leader',
            5 => 'Bonus promoter',
            6 => 'Bonus IT',
            7 => 'Bonus CEO1',
            8 => 'Bonus CEO2',
            9 => 'Bonus lawyer',
            10 => 'Bonus sub promoter',

            1001 => 'Custom bonus payment',
            1002 => 'Remaining donation',
            1003 => 'Transfer to root system',

            2000 => 'Custom bonus level payment',
          ],
        ],
        'tree' => [
        ],
        'invitation' => [
          'form_title' => 'Create new invitation',
          'type' => 'Type',
          'free_signup' => 'Free registration',
          'promoter_num' => 'On the recommendation of promoter (Num)',
          'submit' => 'Submit',

          'list_title' => 'Invitations',
          'hash' => 'Code',
          'creation_date' => 'Created',
          'accepted_date' => 'Accepted',
          'signup_success' => 'Successfully created invitation code.',
          'success' => 'Success!',

          'recipient_details' => 'Recipient details',
          'member_type' => 'Donator type',
          'member_num' => 'Donator number',
          'address' => 'Address',
          'email' => 'Email',
          'email_none' => '-none-',
        ],
        'bonus_payments' => [
          'form_title' => 'Create bonus payment for a donator',
          'recipient_num' => 'Donator number who receives this bonus',
          'amount' => 'Amount',
          'amount_in_currency' => 'Amount in {symbol} ({name})',
          'purpose' => 'Purpose',
          'validate_form' => 'Validate form',
          'submit' => 'Submit',

          'recipient_details' => 'Recipient details',
          'member_type' => 'Donator type',
          'member_num' => 'Donator number',
          'address' => 'Address',
          'email' => 'Email',
          'email_none' => '-none-',

          'list_title' => 'Bonus payments',
          'recipient_num_th' => 'Recipient donator number',
          'creation_date' => 'Created',
          'accepted_date' => 'Accepted',

          'signup_success' => 'Successfully created bonus payment.',
          'success' => 'Success!',
        ],
        'bonus_levels' => [
          'form_title' => 'Change bonus level for donator',
          'recipient_num' => 'Donator number who receives this bonus level',
          'amount_in_currency' => 'Amount in {symbol} ({name})',
          'validate_form' => 'Validate form',
          'submit' => 'Submit',

          'recipient_details' => 'Recipient details',
          'member_type' => 'Donator type',
          'bonus_level' => 'Bonus Level',
          'member_num' => 'Donator number',
          'address' => 'Address',
          'email' => 'Email',
          'email_none' => '-none-',
          'signup_success' => 'Successfully changed bonus level.',
          'success' => 'Success!',

          'list_title' => 'Bonus donators',
          'member_num' => 'Donator number',
          'date' => 'Date',
        ],
        'dev_paying' => [
          'view_title' => 'DevTest paying'
        ]
      ],
    ],

    /* VIEWS - MANAGE
    ---------------------------------------------*/
    'manage' => [
      'password_reset' => [
        'page_title' => 'Password reset',
        'error' => 'Error!',
        'success' => 'Success!',
        'success_msg' => 'We have sent you an email with further instructions.',
        'form_labels' => [
          'num' => 'Your donator number',
          'submit' => 'Reset password',
        ],
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
        'success_msg' => 'We have sent you an email with further instructions.',
        'form_labels' => [
          'old_pwd' => 'Current password',
          'new_pwd' => 'New password',
          'new_repeat' => 'Repeat new password',
          'submit' => 'Change password',
        ],
      ],
      'change_bank' => [
        'page_title' => 'Change your banking',
        'error' => 'Error!',
        'success' => 'Success!',
        'success_msg' => 'We have successfully changed your banking information.',
        'form_labels' => [
          'iban' => 'IBAN',
          'bic' => 'BIC',
          'bank_recipient' => 'Recipient',
          'submit' => 'Speichern',
          'back' => 'Back',
        ],
      ],
    ],

    /* VIEWS - PROJECTS
    ---------------------------------------------*/
    'projects' => [
      'index' => [
        'page_title' => 'Projects',
        'about_us_title' => 'About us and our idea',
        'about_us_title2' => 'Help yourself AND others',
        'planed_projects_title' => 'Our planned projects for the future',
        'affected_countries_title' => 'Countries we want to help',

        'why_poor_head' => 'EVER WONDERED WHY INDIA BECAME SO POOR?',
        'why_poor_quote' => 'A significant fact is that the parts of India that have been under British control for a long time are the least wealthy today.',
        'why_poor_quote_author' => 'Jawaharlal Nehru',
        'why_poor_quote_author_org' => 'First Prime Minister of India',

        'against_poverty_head' => 'We are against poverty',
        'against_poverty_sub' => 'We developed this marketing system to change lives to the better',
        'we_foundation' => 'Foundation',
        'we_foundation_description' => 'The club has been founded for people who have few to no options to achieve a better life for themselves. This might be caused by various reasons like economic and political circumstances. .',
        'we_system' => 'Marketing System',
        'we_system_description' => 'On the one hand the club offers a direct way to help yourself through a marketing system and on the other hand it helps by supporting beneficial social projects.',

        'we_fee' => 'Donation',
        'we_fee_description' => 'Both concepts are financed by 100% from the donations of the club donators.',

        'where_to_go_head' => 'where to go from here',

        'service_ms_title' => 'Marketing System',
        'service_ms_desc' => 'Sounds great! Where can I get more information about this marketing system?',
        'service_ms_btn' => 'Click here!',

        'service_faq_title' => 'I have questions',
        'service_faq_desc' => 'No problem, just go to the FAQ section. You will find answers there',
        'service_faq_btn' => 'Go to FAQ',

        'service_contact_title' => 'I still have questions',
        'service_contact_desc' => 'Still no problem! Just contact us and we will answer any question!',
        'service_contact_btn' => 'Contact us',

        'service_signup_title' => 'I want to join',
        'service_signup_desc' => 'Great! Just follow this link and fill in the registration form to get started!',
        'service_signup_btn' => 'Go to registration',

        'pdf_explanation_head' => 'Marketing system illustration',
        'pdf_explanation' => 'There is also an illustrated explanation in an extra document. If you prefer reading a pdf document, ',
        'pdf_explanation_link' => 'just click here',

      ],
      'girls_schools' => [
        'page_title' => 'Grils schools',
      ],
    ],

    /* VIEWS - ABOUT
    ---------------------------------------------*/
    'about' => [

      'index' => [
        'page_title' => 'About Us',
      ],

      'contact' => [
        'page_title' => 'Contact',
        'form_title' => 'Contact form',
        'form_labels' => [
          'name' => 'Name',
          'email' => 'E-Mail',
          'subject' => 'Subject',
          'message' => 'Message',

          'submit' => 'Submit',
        ],
        'signup_success' => 'Successfully transferred message. Your request is being processed',
        'success' => 'Success!',
      ],

      'faq' => [
        'page_title' => 'FAQ',
        'welcome_faq' => 'Frequently {0} Questions',
        'welcome_faqh' => ['Asked'],

        'items' => $faqItems,
        /*
        'items' => [
          'When do the projects get started?',
          'As soon as the first donations are being made the planning process will begin. After the first two months of running the system, the projects will be started.',

          'Can I follow the progressing project?',
          'Of course! There will be a news feed on the Betterliving homepage which will be updated weekly.',

          'How long does the Betterliving club support the projects?',
          'There will be support from the club until the project is fully accomplished.',

          'When will I get paid?',
          'As soon as your donation (or your advertised donators’ donations) have been transferred to the club, your payments will be booked into your account. ',

          'What happens if I sign up but do not pay the donator fee?',
          'We expect your donation within 10 days. If no payment has been made during that time, you will not be able to receive any payments yourself.',

          'What do you need my email address for?',
          'In case you forget your password or there is any problem at all, we have a chance to contact you.',


          'How do I pay the donation?',
          'You will have to transfer the money. Usually, this transfer will take around 2-3 days.',

          'I still have questions',
          'You can email any question to this address: info@betterliving.social',

        ],
        */
      ],
    ],
  ],

    /* MAILS
    ---------------------------------------------*/
  'mail' => [
    'password_reset' => [
      'subject' => 'Password reset',
      'body' => "You have lost your $webPageName password. Sorry about that!\n
But don’t worry! You can use the following link within the next day to reset your password:\n
{link}\n
If you don’t use this link within 24 hours, it will expire.\n
Thanks,\n
Your friends at $webPageName",
    ],

    'signup_confirm' => [
      'subject' => 'Signup confirmation - Betterliving',
      'body' => "Hallo {fullname}
Thank you for registering for $webPageName!\n
It is great to have you with us.\n
It is the first step into a better live.\n
\n
This is your donator number: {num}\n
\n
You best note it down. You need this number to login into our webpage and to recruit other donators.\n
\n
If you have any questions, just do not hesitate and contact us!\n
\n
Best regards,\n
Your friends at $webPageName",
    ],
  ],
];

?>
