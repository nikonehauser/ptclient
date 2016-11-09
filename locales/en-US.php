<?php

/*

(\w|\.|\%|,)[\r\n](\w|\.|\%|,)
$1 $2

 */

$lang = substr(basename(__FILE__), 0, 5);
$copyrightName = \Tbmt\Config::get('brand.copyright');
$brandName = \Tbmt\Config::get('brand.name');
$brandNameShort = \Tbmt\Config::get('brand.short');

$faqItems = include $lang.'-faq.php';
$mails = include $lang.'-mails.php';

return [
  'common' => [
    'brand_name' => $brandName,
    'brand_name_short' => 'BL',

    'member_types' => [
      0 => 'Customer',
      // 1 =>'Sub Promoter',
      2 => 'Promoter',
      3 => 'Organization Leader',
      4 => 'Director',
      5 => 'Sales Manager',
      6 => 'CEO',
      7 => 'Developer',
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
  'count' => [
    '1' => 'first',
    '2' => 'second',
    '3' => 'third',
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
    'member_num' => 'Customer number does not exist',
    'member_num_unpaid' => 'Customer has not paid yet',
    'age_of_18' => 'Must be 18 or older',
    'referrer_paiment_outstanding' => 'The customer exists but we didn´t receive the donation yet. Unfortunately you have to wait for this customer’s donation',
    'invitation_code_inexisting' => 'Invitation Code does not exist',
    'invitation_code_invalid' => 'Invalid invitation code',
    'invitation_code_used' => 'Invitation code already used',

    'sub_promoter_to_promoter' => 'Customer is no promoter',

    'login' => 'Invalid login credentials',

    'india_pincode' => 'This is no valid pin code from India.',
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
        'about' => 'About Us',
        'impressum' => 'Impressum',
        'account' => 'Profile',
      ],
      'navigation_sublinks' => [
        'projects' => [
          '1' => ['index', 'Video Explanation', 'video_explanation'],
        ],
        'member' => [
          'index' => 'A views things to know',
          'signup' => 'Sign up',
        ],
        'account' => [
          'index' => 'Information',
          'logout' => 'Logout',
        ],
        'about' => [
          'index' => 'About Us',
          'contact' => 'Contact',
          'faq' => 'FAQ',
          'terms' => 'Terms of Use',
        ],
      ],
      'member_login' => 'Customer Login',

      'copyright_text' => '© Copyright 2016 by '.$copyrightName.' All Rights Reserved.',

      'useful_link_contact' => 'Any questions? Just contact us!',
      'useful_link_faq' => 'Frequently asked questions',
      'useful_link_terms' => 'Terms of use',
      'useful_link_about_us' => 'About Us',
      'useful_link_impressum' => 'Impressum',
    ],

    /* VIEWS - MEMBER
    ---------------------------------------------*/
    'member' => [
      'btn' => [
        'signup' => 'Sign up now',
      ],
      'text' => [
        'Customer advertises customers',
      ],

      'system' => [
        'page_title' => 'Marketing System',
        'vs1' => 'Position customer',
        'vs2' => 'Position premium customer',
        'notice' => 'Notice',
        'signup_now' => 'Sign up now',
        'etc' => 'etc.',
        'intro1' => 'The meaning of the {0} will be explained in the following.',
        'intro1h' => ['marketing system'],
        'intro2' => 'To make it easy we will follow a detailed example',
        'intro3' => 'For a better understanding of our marketing system (multi-level-marketing-system) you must know that it has only two levels. The first level is the position “customer” and the second level is the position “premium customer”.',
        'you' => 'You',
        'head_explanation' => 'Explanation',
        'head_part' => 'Step',
        'head_illustration' => 'Illustration',



        'pdf_explanation' => 'There is also an illustrated explanation in an extra document. If you prefer to read a pdf document, ',
        'pdf_explanation_link' => 'just click here',

        '',

        '1' => 'As you are a customer of '.$brandName.' you will automatically be in a "customer" position in our marketing system.',
        '1h' => ['$_member_fee_$'],
        '1_1' => 'You = Customer',

        '2' => 'Now you recruit your first two new customers Durga and Kamal.',
        '3' => 'Thereby you will earn {0} each in the position "customer", hence altogether you earn {1}.',
        '3h' => ['$_lvl1_$', '$_2_times_lvl1_$'],
        '4' => 'When you have recruited two new customers successfully, you automatically are a "premium customer".',
        '5' => 'Now you recruit your third new customer, Jivan.',
        '6' => 'Thereby you will earn {0} in the position premium customer.',
        '6h' => ['$_lvl2_$'],
        '7' => 'For every additional customer you recruit in the future, you will also earn {0} each.',
        '7h' => ['$_lvl2_$'],
        '8' => 'Since Jivan now is a customer, he automatically is on the level "customer".',
        '9' => 'For example, if you can not recruit any more customers but Jivan can, his first two new customers are Rati and Lila.',
        '10' => '',
        '11' => 'Jivan thereby earns {0} for each recruited customer in his position "customer", thus altogether {1}.',
        '11h' => ['$_lvl1_$', '$_2_times_lvl1_$'],
        '12' => 'You earn {0} for each recruited customer in this case, thus altogether {1}.',
        '12h' => ['$_indirect_$', '$_2_times_indirect_$'],
        '13' => '{0}',
        '13h' => ['At this point you already earn money without being active yourself!', ],
        '14' => '{0} – you have already earned {1} {2}',
        '14h' => ['After just one week', '$_after_one_week_$', 'and almost entirely balanced your contribution!', ],
        '15' => ' Jivan will automatically be a "premium customer" with his first two successful recruitings.',
        '16' => 'As soon as you and Jivan are on the same level, you will not get paid any more for Jivan´s new customers.',
        '17' => 'However, now Rati and Lila are placed into your tree.',
        '18' => 'Because the first two new recruited customers (Rati and Lila) are assigned to the customer (you) that has advertised the recruiting customer (Jivan) in the first place.',
        '19' => 'After Rati and Lila have paid for their Happiness Guide, they both are on the "customer" level.',
        '20' => 'Now Rati and Lila each recruit their first two new customers, Devi, Kumar, Gita and Karan.',
        '21' => 'Thereby Rati and Lila earn {0} for each recruited customer and you get {1} each, thus altogether you earn {2}.',
        '21h' => ['$_lvl1_$', '$_indirect_$', '$_4_times_indirect_$'],
        '22' => 'Now Rati and Lila go from "customer" to "premium customer" after their first two successful recruitings.',
        '23' => 'From now on, you do not earn any more when Rati and Lila recruit new customers.',
        '24' => 'However, now Devi, Kumar, Gita and Karan are placed into your tree. As they have already paid for their Happiness Guide, they are all "customers".',
        '25' => 'Now Devi, Kumar, Gita and Karan recruited their first two customers each.',
        '26' => 'Thereby Devi, Kumar, Gita and Karan earn {0} for each customer and you earn {1} each, thus altogether you earn {2}.',
        '26h' => ['$_lvl1_$', '$_indirect_$', '$_8_times_indirect_$'],
        '27' => 'Now you did not only balance your purchase price for your own Happiness Guide and returned the investment for your better life but also earned money on top of that.',
        '28' => 'And how long did it take?',
        '29' => 'From experience, on average it takes one day, until a customer successfully recruits a new customer.',
        '30' => 'Therefore, this example would take 10 to 14 days.',
        '31' => 'If every future customer only recruits two new customers and takes one week on average, your earnings develop this way:',
        '31_week' => 'Week',
        '32' => '{0} you therefore already earned {1} {2}',
        '32h' => ['After approximately ten weeks', 'round about', '$_after_10_weeks_$'],
        '33' => '<p>Of course this marketing system will not work endlessly because the number of people is naturally limited. But there were and there still are, however, marketing systems worldwide and in different economic fields that have created a sheer endless number of prosperous people and are still creating new ones.</p><p>The only question for you is: Will you be one of these happy people or will you be the one who does not believe this could work?</p>Right now it is definitely working - you only need to join!
',
        '34' => 'With the customers who have decided to recruit new customers quickly on you will earn lots and lots of money.',
        '35' => 'Trustworthy mathematical calculations project that customers who became active in the first 12 months after starting the marketing system and at least recruited three new customers, earned a total of {0} to {1} in the first 12 months.',
        '35h' => ['$_lvl1_$', '$_lvl1_$'],
        '36' => '<p>There are no limits:</p> If only one customer recruits e.g. 10, 15 or 20 or even more customers, even higher earnings are possible.',
        '37' => 'However, we will also be customers who will just earn a meager amount caused by mediocre effort, for example {0}',
        '37r' => ['$_thousand_dollar_$'],
        '38' => 'Then there will also be customers who will only earn very few, for example only {0}',
        '38r' => ['$_2hundred_dollar_$'],
        '39' => 'And of course in the future, probably in a few years, we will also have cases of customers who will not earn anything in that marketing system. But with the Happiness Guide there are many advantages in life and maybe through this they earn much money in another field.',
        '40' => 'Due to the way the system works, you will earn less depending on how close the system is to ending. It is mostly unpredictable but someday the system will stagnate which will be when there are not any new customers. That day the marketing system will be closed, but maybe there will be a new way of successfully earning money!',
        '41' => 'The specific development of your personal profits can hardly be predicted. However, the possibilities result is depending on each customer’s personal effort.',
        '42' => 'If you believe that,',
        '43' => 'you can recruit at least three new customers',
        '44' => 'these customers again can recruit two new customers',
        '45' => 'these new customers each can recruit at least two new customers',
        '46' => 'Then your purchase price for your own Happiness Guide will be reimbursed within around 15 - 25 days and you additionally will have scored your first profit.',
        '47' => 'Furthermore, if you believe that over a course of only three months each new customer at least recruits three new customers, you will earn lots and lots of money with this and will be able to afford a whole new living standard.',
        '48' => 'You will be able to afford things you have never thought of before. You will be able to support your family, enjoy best medical care, buy a new car, do not need to spend time thinking about what to live from when you’re old, etc.',
        '49' => "This is the real and brilliant purpose of $brandName!",
        '50' => 'Take action now',
        '51' => 'You can register for a better life right here:',
      ],

      'index' => [
        'page_title' => 'A views things to know',

        'basic_idea' => 'Basic idea',
        'basic_idea_title' => [
          'Happiness Guide',
          'Basics',
        ],
        'basic_idea_text' => [
          'With the Happiness Guide you can change your work life, your health and be happier overall. In a 2-week cycle in eight steps our Happiness Guide makes you happy. Step by step you will learn how you make your life better.',
          'Everybody can buy the Happiness Guide. For only {member_fee} you get the instruction on how to become a happier person.'
        ],
        'basic_idea_highlights' => [
          null,
          ['onetime' => 'one-time', 'member_fee' => '$_member_fee_$'],
          ['passive_income' => 'passive income'],
          null,
        ],

        'become_member_title' => 'I want to be happy',
        'become_member_text' => 'If you also want to be a customer, please register on this homepage and purchase the Happiness Guide for {member_fee}. As soon as the payment has been transferred, you are a customer and you will receive the Happiness Guide for your ' . $brandName . '.',
        'become_member_highlights' => [
          'passive_income' => 'passive income',
          'member_fee' => '$_member_fee_$',
        ],


        'member_starting' => 'Why should I become a customer?',
        'member_starting_title' => [
          'Happiness Guide',
        ],
        'member_starting_text' => [
          'With the Happiness Guide you can change your work life, your health and be happier overall.',
        ],
        'member_starting_button' => [
          '',
        ],
      ],

      'signup' => [
        'page_title' => 'Sign up to get your Happiness Guide',
        'form_error' => 'Form has invalid fields.',
        'referral_member_num_help' => 'This is the customer number of the person who recruited you. This person will also receive a reward for your purchase. In order to join, you need somebody to invite you with their customer number.',
        'invitation_code_help' => 'This code is NOT required. You may have received a special invitation code from the person who invited you. This code may be linked with a bonus or similar things.',
        'form_labels' => [
          'contact' => 'Contact',
          'address' => 'Address',
          'bank_account' => 'Bank Account',
          'confirm' => 'Confirm',

          'referral_member_num' => 'Referrer customer number',
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
          'accept_valid_country' => 'I confirm that I am citizen of India',
          'not_accepted_countries' => '',

          'submit' => 'Submit',

          'bank_account_info' => 'We will never charge your bank account. Only the payments for your referrals will be paid into this account.',
          'email_info' => 'You will never receive any spam from us. We need your e-mail address to contact you in case there are problems with transferring money. If you do not have an e-mail address you can register here for free: ',
        ],
      ],

      'signupSuccess' => [
        'page_title' => 'Successfully signed up',
        'hello' => "Welcome to $brandName",
        'signup_text1' => "Thank you for signing up. You are one step away from becomming member of $brandName!",
        'signup_text2' => 'We have sent a confirmation link to your email address. You need to verify your email address by clicking on the link in the registration email. This is required to complete the registration.',
        'signup_text3' => 'This process guarantees valid email addresses for you and us. Because this is the only way how we can contact you if we run into problems transferring your bonus!'
      ],
    ],

    /* VIEWS - ACCOUNT
    ---------------------------------------------*/
    'account' => [
      'login' => [
        'page_title' => 'Customer login',
        'error' => 'Error',
        'password_reset' => 'Forgot your password?',
        'form_labels' => [
          'login' => 'Login',

          'num' => 'Customer Number',
          'pwd' => 'Password',

          'submit' => 'Submit',
        ],
      ],
      'index' => [
        'page_title' => 'Profile',
        'welcome' => 'Welcome {name}',
        'navigation_links' => [
          'index' => 'Information',
        ],
      ],
      'tabs' => [
        'index' => [
          'account' => 'Account',
          'your_num' => 'Your customer number',
          'warning' => 'Warning!',
          'tank_you' => 'Thank You!',
          'unpaid_text' => 'We have not received your donation yet!',
          'paid_text' => 'We have received your donation.',
          'address' => 'Address',
          'email' => 'Email',
          'email_none' => '-none-',
          'bank_account' => 'Payment data',
          'bank_recipient' => 'Recipient Name',
          // 'iban' => 'IBAN (International Bank Account Number)',
          'iban' => 'IBAN',
          // 'bic' => 'BIC (Bank Identifier Code)'
          'bic' => 'BIC',
          'member_type' => 'Customer type',
          'bonus_level' => 'Bonus Level',

          'change_pwd' => 'Change password',
          'change_profile' => 'Change Profile',

          'welc_not_1' => 'Welcome {name}',
          'welc_not_2' => 'This is your profile. You can find various information here',
          'welc_not_3' => 'IMPORTANT! Always remember your customer number! You will need it to login, recruit others etc.',

          'guides' => 'Your Happy Guides',
          'guides_not_available' => 'You have no guide enabled yet.',
          'guide_download_explanation' => 'Click on the names to download your guide(s)',
          'guide_name' => 'Happy Guide Nr. {number}',
        ],
        'invoice' => [
          'stats' => 'Statistics',
          'transactions' => 'Transactions (grouped by reason)',
          'oustanding_advertised_count' => 'Outstanding advertised customers count',
          'advertised_member_count' => 'Advertised customers count',
          'outstanding_total' => 'Total outstanding to be transferred',
          'transferred_total' => 'Total transferred',
          'custom' => '--custom--',
          'single_amount' => 'Single Amount',
          'quantity' => 'Quantity',
          'currency' => 'Currency',
          'total' => 'Total',
          'reason' => 'Reason',
          'transaction_reasons' => [
            0 => 'Commission Level 1',
            1 => 'Commission Level 2',
            2 => 'Bonus Level 2',
            3 => 'Bonus Director',
            4 => 'Bonus Organization Leader',
            5 => 'Bonus Promoter',
            6 => 'Bonus IT',
            7 => 'Bonus CEO',
            // 8 => 'Bonus CEO2',
            // 9 => 'Bonus lawyer',
            10 => 'Bonus Sub Promoter',
            11 => 'Bonus Sub Promoter Referrer',

            12 => 'Bonus Sales Management',
            13 => 'Bonus Executive',
            14 => 'Bonus Tariq Wani',
            15 => 'Bonus NGO',

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
          'restricted_to_lvl2' => 'You need to invite two customers to unlock invitations',

          'list_title' => 'Invitations',
          'hash' => 'Code',
          'creation_date' => 'Created',
          'accepted_date' => 'Accepted',
          'signup_success' => 'Successfully created invitation code.',
          'success' => 'Success!',

          'recipient_details' => 'Recipient details',
          'member_type' => 'Customer type',
          'member_num' => 'Customer number',
          'address' => 'Address',
          'email' => 'Email',
          'email_none' => '-none-',
        ],
        'bonus_payments' => [
          'form_title' => 'Create bonus payment for a customer',
          'recipient_num' => 'Customer number who receives this bonus',
          'amount' => 'Amount',
          'amount_in_currency' => 'Amount in {symbol} ({name})',
          'purpose' => 'Purpose',
          'validate_form' => 'Validate form',
          'submit' => 'Submit',

          'recipient_details' => 'Recipient details',
          'member_type' => 'Customer type',
          'member_num' => 'Customer number',
          'address' => 'Address',
          'email' => 'Email',
          'email_none' => '-none-',

          'list_title' => 'Bonus payments',
          'recipient_num_th' => 'Recipient customer number',
          'creation_date' => 'Created',
          'accepted_date' => 'Accepted',

          'signup_success' => 'Successfully created bonus payment.',
          'success' => 'Success!',
        ],
        'bonus_levels' => [
          'form_title' => 'Change bonus level for customer',
          'recipient_num' => 'Customer number who receives this bonus level',
          'amount_in_currency' => 'Amount in {symbol} ({name})',
          'validate_form' => 'Validate form',
          'submit' => 'Submit',

          'recipient_details' => 'Recipient details',
          'member_type' => 'Customer type',
          'bonus_level' => 'Bonus Level',
          'member_num' => 'Customer number',
          'address' => 'Address',
          'email' => 'Email',
          'email_none' => '-none-',
          'signup_success' => 'Successfully changed bonus level.',
          'success' => 'Success!',

          'list_title' => 'Bonus customers',
          'member_num' => 'Customer number',
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
          'num' => 'Your customer number',
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
      'change_profile' => [
        'page_title' => 'Change your profile',
        'error' => 'Error!',
        'success' => 'Success!',
        'success_msg' => 'Your profile information has been changed.',
        'form_labels' => [
          'firstName' => 'First name',
          'lastName' => 'Last name',
          'title' => 'Title',
          'email' => 'Email',
          'country' => 'Country',
          'zip_code' => 'Zip Code',
          'city' => 'City',
          'iban' => 'IBAN',
          'bic' => 'BIC',
          'bank_recipient' => 'Recipient',
          'submit' => 'Save',
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
        'why_poor_quote' => 'It is a fact that the parts of India that have been under British control for a long time are the least wealthy today.',
        'why_poor_quote_author' => 'Jawaharlal Nehru',
        'why_poor_quote_author_org' => 'First Prime Minister of India',

        'against_poverty_head' => 'We are against poverty',
        'against_poverty_sub' => 'We developed the Happiness Guide to make lives better',
        'we_foundation' => 'The Company',
        'we_foundation_description' => "$brandName has been founded for people who have few to no options to achieve a better life. This could be caused by reasons like economic and political circumstances.",

        'we_mission' => 'Our mission',
        'we_mission_description' => 'With the Happness Guide we want everyone to have important information about being happy. This way everybody can have a better life in the future.',

        'where_to_go_head' => 'where to go from here',

        'service_faq_title' => 'I have questions',
        'service_faq_desc' => 'No problem, just go to the FAQ section. You will find answers there',
        'service_faq_btn' => 'Go to FAQ',

        'service_contact_title' => 'I still have questions',
        'service_contact_desc' => 'That is still no problem! Just contact us and we will answer any question!',
        'service_contact_btn' => 'Contact us',

        'service_signup_title' => 'Yes I want it',
        'service_signup_desc' => 'Great! Just follow this link and fill in the registration form to get started!',
        'service_signup_btn' => 'Get our Happiness Guide',

        'video_explanation_head' => 'Our video presentation',
        'video_explanation' => 'We are working on the video, be prepared!',

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
          'phone' => 'Telephone',
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
      ],
    ],

    /* VIEWS - IMPRESSUM
    ---------------------------------------------*/
    'impressum' => [

      'index' => [
        'page_title' => 'Impressum',
      ],
    ],
  ],

    /* MAILS
    ---------------------------------------------*/
  'mail' => $mails
];

?>
