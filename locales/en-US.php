<?php

/*

(\w|\.|\%|,)[\r\n](\w|\.|\%|,)
$1 $2

 */

$lang = substr(basename(__FILE__), 0, 5);
$copyrightName = \Tbmt\Config::get('brand.copyright');
$brandName = \Tbmt\Config::get('brand.name');
$brandNameShort = \Tbmt\Config::get('brand.short');

return [
  'common' => [
    'brand_name' => $brandName,
    'brand_name_short' => 'BL',

    'member_fee' => '__willBeLoadedinLocalizer__',

    'member_types' => [
      0 => 'Customer',
      // 1 =>'Sub Promoter',
      2 => 'Promoter',
      3 => 'Organization Leader',
      4 => 'Director',
      5 => 'Sales Manager',
      6 => 'CEO',
      7 => 'IT Speciallist',
    ],

    'country' => [
      'india' => 'India',
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

    'purchase_agreemensts' => 'You agree with our {terms}!',
    'terms' => 'Terms of Use',
    'privacy' => 'Data Privacy Notice',
    'cancelation_right' => 'Rights of Cancelation',

    'pdf_explanation' => 'There is also an illustrated explanation in an extra document. If you prefer to read a pdf document, ',
    'pdf_explanation_link' => 'just click here',
  ],

  'mails' => [
    'lvl2invitation' => 'Because of the special invitation you’re a “Premium Customer” already. That means you will earn {lvl2bonus} instead of {lvl1bonus} for each new client from the start.',
    'standardinvitation' => 'Plus, you only need three people to register and buy the Guide in order to have a passive income.',
    'free_invitation' => 'You will receive the Happiness Guide for free. Also: '
  ],


  'payment' => [
    'item_name' => 'Happiness Guide',
    'item_description' => 'Digital PDF download serie (consists of 8 chapters).',
    'transaction_description' => 'Happiness Guide (digital PDF download)',
    'payu_product_description' => 'Our Happiness Guide series. A digital download about getting happier in life. You will get 8 pieces, each one more step to wealth and health',
  ],


    /* DATE FORMATS
    ---------------------------------------------*/
  'count' => [
    '0' => '0',
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
    'vlong' => "l jS \of F Y",
  ],

  'datetime_format_php' => [
    'default' => "m/d/Y H:i",
    'short' => "M. d H:i",
    'long' => "F d, Y H:i",
    'vlong' => "l jS \of F Y H:i:s A",
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
    'email_exists' => 'Email address exists',
    'int' => 'Invalid integer',
    'accept' => 'Must be accepted',
    'password' => 'Invalid password',
    'password_conditions' => '5 characters or more, at least one small letter and one digit',
    'password_unequal' => 'Passwords were not equal',

    'referral_member_num' => 'Referrer number does not exist',
    'member_num' => 'Customer number does not exist',
    'member_email' => 'Email does not exist',
    'member_num_unpaid' => 'Customer has not paid yet',
    'age_of_18' => 'Must be 18 or older',
    'referrer_paiment_outstanding' => 'The customer exists but we didn´t receive the donation yet. Unfortunately you have to wait for this customer’s donation',
    'invitation_code_inexisting' => 'Invitation Code does not exist',
    'invitation_code_invalid' => 'Invalid invitation code',
    'invitation_code_used' => 'Invitation code already used',

    'sub_promoter_to_promoter' => 'Customer is no promoter',

    'login' => 'Invalid login credentials',

    'india_pincode' => 'This is no valid zip code from India.',
  ],

    /* VIEWS
    ---------------------------------------------*/
  'view' => [
    'common' => [
      'brand_name' => $brandName,
      'brand_name_short' => 'HYA',

      'email_us' => 'Email us: ',

      'navigation_links' => [
        'exp_videos' => 'Explanation videos',
        'member' => 'The Idea',
        'guide' => 'Happiness Guide',
        'projects' => 'Home',
        'about' => 'About Us',
        'signup' => 'Signup',
        'account' => 'Profile',
      ],

      /*

- Video explantation marketing system
- Pdf explantation marketing system
- Video explantation Happiness Guide
- Pdf explantation Happiness Guide
- Video explantation marketing system long version


       */
      'navigation_sublinks' => [
        'exp_videos' => [
          'index' => 'Login and pay',
          'whatsappinvite' => 'WhatsApp Invitation',
          'stepstosuccess' => 'Steps to Success',
          'fromto' => 'From first mail to premium customer in 3 days',
          'backoffice' => 'The Backoffice of Betterliving',
        ],
        'projects' => [
          '1' => ['index', 'Amazing Recommendation Marketing System Video', 'video_explanation'],
          '2' => ['index', 'Recommendation Marketing System PDF Explanation', 'pdf_explanation'],
          '3' => ['index', 'Happiness Guide Video Explanation', 'hg_video_explanation'],
          '4' => ['index', 'Happiness Guide PDF Explanation', 'hg_pdf_explanation']
        ],
        'member' => [
          'index' => 'Customers recommend customers',
          'system' => 'Our recommendation marketing system',
          'signup' => 'Sign up',
        ],
        'guide' => [
          'index' => 'The Happiness Guide',
          'howtopay' => 'How to pay the Happiness Guide',
        ],
        'account' => [
          'index' => 'Information',
          'invoice' => 'Invoice',
          'rtree' => 'Referral Tree',
          'htree' => 'Funds Level Tree',
          'btree' => 'Bonus Hierarchy',
          'logout' => 'Logout',
        ],
        'about' => [
          'index' => 'About Us',
          'contact' => 'Contact',
          'faq' => 'FAQ',
          'terms' => 'Terms of Use',
          'impressum' => 'Impressum',
        ],
      ],
      'member_login' => 'Customer Login',

      'copyright_text' => '© Copyright 2016 by '.$copyrightName.' All Rights Reserved.',

      'useful_link_1' => 'Useful',
      'useful_link_2' => 'Links',
      'useful_link_contact' => 'Any questions? Just contact us!',
      'useful_link_faq' => 'Frequently asked questions',
      'useful_link_terms' => 'Terms of use',
      'useful_link_about_us' => 'About Us',
      'useful_link_impressum' => 'Impressum',
    ],

    /* VIEWS - MEMBER
    ---------------------------------------------*/
    'exp_videos' => [
      'index' => [
        'page_title' => 'Explanation videos - Login and pay',
        'head' => 'Login and pay',
      ],
      'whatsappinvite' => [
        'page_title' => 'Explanation videos - WhatsApp Invitation',
        'head' => 'WhatsApp Invitation',
      ],
      'stepstosuccess' => [
        'page_title' => 'Explanation videos - Steps to Success',
        'head' => 'Steps to Success',

        'pdf_explanation_head' => 'Recommendation Marketing System',
        'pdf_explanation' => 'We also prepared an illustrated explanation in an extra document. If you prefer reading a pdf document, ',
        'pdf_explanation_link' => 'just click here',
      ],
      'fromto' => [
        'page_title' => 'Explanation videos - From first mail to premium customer',
        'head' => 'Login and pay',
      ],
      'backoffice' => [
        'page_title' => 'Explanation videos - The backoffice of Betterliving',
        'head' => 'The backoffice of Betterliving',
      ],
    ],

    'member' => [
      'btn' => [
        'signup' => 'Sign up now',
      ],
      'text' => [
        'Customer advertises customers',
      ],

      'system' => [
        'page_title' => 'Recommendation Marketing System',
        'vs1' => 'Position customer',
        'vs2' => 'Position premium customer',
        'notice' => 'Notice',
        'signup_now' => 'Sign up now',
        'etc' => 'etc.',
        'intro1' => 'The meaning of the {0} will be explained in the following.',
        'intro1h' => ['recommendation marketing system'],
        'intro2' => 'To make it easy we will follow a detailed example',
        'intro3' => 'For a better understanding of our recommendation marketing system you must know that it has only two positions. The first position is the “customer” and the second position is the “premium customer”.',
        'you' => 'You',
        'head_explanation' => 'Explanation',
        'head_part' => 'Step',
        'head_illustration' => 'Illustration',

        'names_yourself' => 'Yourself',

        'pdf_explanation' => 'There is also an illustrated explanation in an extra document. If you prefer to read a pdf document, ',
        'pdf_explanation_link' => 'just click here',
        'video_explanation' => 'Otherwise we have prepared a video. Just follow this ',
        'video_explanation_link' => 'link',

        '',

        '1' => 'As you are a customer of '.$brandName.' you will automatically be in a "customer" position in our recommendation marketing system.',
        '1h' => ['$_member_fee_$'],
        '1_1' => 'You = Customer',

        '2' => 'Now you recommend your first two new customers Durga and Kamal.',
        '3' => 'Thereby you will earn {0} each in the position "customer", hence altogether you earn {1}.',
        '3h' => ['$_lvl1_$', '$_2_times_lvl1_$'],
        '4' => 'When you have recommend two new customers successfully, you automatically are a "premium customer".',
        '5' => 'Now you recommend your third new customer, Jivan.',
        '6' => 'Thereby you will earn {0} in the position premium customer.',
        '6h' => ['$_lvl2_$'],
        '7' => 'For every additional customer you recommend in the future, you will also earn {0} each.',
        '7h' => ['$_lvl2_$'],
        '8' => 'Since Jivan now is a customer, he automatically is on the position "customer".',
        '9' => 'For example, if you can not recommend any more customers but Jivan can, his first two new customers are Rati and Lila.',
        '10' => '',
        '11' => 'Jivan thereby earns {0} for each recommended customer in his position "customer", thus altogether {1}.',
        '11h' => ['$_lvl1_$', '$_2_times_lvl1_$'],
        '12' => 'You earn {0} for each recommended customer in this case, thus altogether {1}.',
        '12h' => ['$_indirect_$', '$_2_times_indirect_$'],
        '13' => '{0}',
        '13h' => ['At this point you already earn money without being active yourself!', ],
        '14' => '{0} – you have already earned {1} {2}',
        '14h' => ['After just one week', '$_after_one_week_$', 'and almost entirely balanced your contribution!', ],
        '15' => ' Jivan will automatically be a "premium customer" with his first two successful recommendings.',
        '16' => 'As soon as you and Jivan are on the same position, you will not get paid any more for Jivan´s new customers.',
        '17' => 'However, now Rati and Lila are placed into your tree.',
        '18' => 'Because the first two new recommended customers (Rati and Lila) are assigned to the customer (you) that has advertised the recommending customer (Jivan) in the first place.',
        '19' => 'After Rati and Lila have paid for their Happiness Guide, they both are on the "customer" position.',
        '20' => 'Now Rati and Lila each recommend their first two new customers, Devi, Kumar, Gita and Karan.',
        '21' => 'Thereby Rati and Lila earn {0} for each recommended customer and you get {1} each, thus altogether you earn {2}.',
        '21h' => ['$_lvl1_$', '$_indirect_$', '$_4_times_indirect_$'],
        '22' => 'Now Rati and Lila go from "customer" to "premium customer" after their first two successful recommendings.',
        '23' => 'From now on, you do not earn any more when Rati and Lila recommend new customers.',
        '24' => 'However, now Devi, Kumar, Gita and Karan are placed into your tree. As they have already paid for their Happiness Guide, they are all "customers".',
        '25' => 'Now Devi, Kumar, Gita and Karan recommended their first two customers each.',
        '26' => 'Thereby Devi, Kumar, Gita and Karan earn {0} for each customer and you earn {1} each, thus altogether you earn {2}.',
        '26h' => ['$_lvl1_$', '$_indirect_$', '$_8_times_indirect_$'],
        '27' => 'Now you did not only balance your purchase price for your own Happiness Guide and returned the investment for your better life but also earned money on top of that.',
        '28' => 'And how long did it take?',
        '29' => 'From experience, on average it takes one day, until a customer successfully recommends a new customer.',
        '30' => 'Therefore, this example would take 10 to 14 days.',
        '31' => 'If every future customer only recommends two new customers and takes one week on average, your earnings develop this way:',
        '31_week' => 'Week',
        '32' => '{0} you therefore already earned {1} {2}',
        '32h' => ['After approximately ten weeks', 'round about', '$_after_10_weeks_$'],
        '33' => '<p>Of course this recommendation marketing system will not work endlessly because the number of people is naturally limited. But there were and there still are, however, recommendation marketing systems worldwide and in different economic fields that have created a sheer endless number of prosperous people and are still creating new ones.</p><p>The only question for you is: Will you be one of these happy people or will you be the one who does not believe this could work?</p>Right now it is definitely working - you only need to join!',
        '34' => 'With the customers who have decided to recommend new customers quickly on you will earn lots and lots of money.',
        '35' => 'Trustworthy mathematical calculations project that customers who became active in the first 12 months after starting the recommendation marketing system and at least recommend three new customers, earned a total of {0} to {1} in the first 12 months.',
        '35h' => ['$_lvl1_$', '$_lvl1_$'],
        '36' => '<p>There are no limits:</p> If only one customer recommends e.g. 10, 15 or 20 or even more customers, even higher earnings are possible.',
        '37' => 'However, we will also be customers who will just earn a meager amount caused by mediocre effort, for example {0}',
        '37r' => ['$_thousand_dollar_$'],
        '38' => 'Then there will also be customers who will only earn very few, for example only {0}',
        '38r' => ['$_2hundred_dollar_$'],
        '39' => 'And of course in the future, probably in a few years, we will also have cases of customers who will not earn anything in that recommendation marketing system. But with the Happiness Guide there are many advantages in life and maybe through this they earn much money in another field.',
        '40' => 'Due to the way the system works, you will earn less depending on how close the system is to ending. It is mostly unpredictable but someday the system will stagnate which will be when there are not any new customers. That day the recommendation marketing system will be closed, but maybe there will be a new way of successfully earning money!',
        '41' => 'The specific development of your personal profits can hardly be predicted. However, the possibilities result is depending on each customer’s personal effort.',
        '42' => 'If you believe that,',
        '43' => 'you can recommend at least three new customers',
        '44' => 'these customers again can recommend two new customers',
        '45' => 'these new customers each can recommend at least two new customers',
        '46' => 'Then your purchase price for your own Happiness Guide will be reimbursed within around 15 - 25 days and you additionally will have scored your first profit.',
        '47' => 'Furthermore, if you believe that over a course of only three months each new customer at least recommends three new customers, you will earn lots and lots of money with this and will be able to afford a whole new living standard.',
        '48' => 'You will be able to afford things you have never thought of before. You will be able to support your family, enjoy best medical care, buy a new car, do not need to spend time thinking about what to live from when you’re old, etc.',
        '49' => "This is the real and brilliant purpose of $brandName!",
        '50' => 'Take action now',
        '51' => 'You can register for a better life right here:',
      ],

      'index' => [
        'page_title' => 'Customers recommed customers',

        'basic_idea' => 'Basic idea',
        'basic_idea_title' => [
          'Happiness Guide',
          'Basics',
        ],
        'basic_idea_text' => [
          'With the Happiness Guide you can change your work life, your health and be happier overall. In a 2-week cycle in eight steps our Happiness Guide makes you happy. Step by step you will learn how you make your life better.',
          'Everybody in India can buy the Happiness Guide. For only {member_fee} you get the instruction on how to become a happier person.',
          'Every customer can become active easily (and without any fee or costs) within the “customers recommend customers” recommendation marketing system and earn a constant and {passive_income}.',
          'Only citizens of India are allowed to become customers of ' . $brandName.'.',
        ],
        'basic_idea_highlights' => [
          null,
          ['onetime' => 'one-time', 'member_fee' => '$_member_fee_$'],
          ['passive_income' => 'passive income'],
          null,
        ],

        'become_member_title' => 'I want to be customer',
        'become_member_text' => 'If you also want to be a customer, please register on this homepage and purchase the Happiness Guide for {member_fee}. As soon as the payment has been transferred, you are a customer and you will receive the Happiness Guide for your Betterliving. At this point you can use the fantastic recommendation marketing system to achieve your personal "Pension Plan".',
        'become_member_highlights' => [
          'member_fee' => '$_member_fee_$',
        ],


        'member_starting' => 'Why should I become a customer?',
        'member_starting_title' => [
          'Happiness Guide',
          'Your additional advantage as a customer',
          'Recommendation Marketing System',
          'Immediately start recommending',
        ],
        'member_starting_text' => [
          'With the Happiness Guide you can change your work life, your health and be happier overall.',
          'In our “customers recommend customers” recommendation marketing system you are in the position “customer” automatically.',
          'How the recommendation marketing system works will be explained in the following.',
          'Right after you have finished your registration you can recommend new customers for ' . $brandName . ' via this homepage.',
        ],
        'member_starting_button' => [
          '',
          'What does "customer" mean?',
          'Explanation of the recommendation marketing system',
          'Yes, I want to sign up!',
        ],

        'pdf_explanation_head' => 'Recommendation Marketing System',
        'pdf_explanation' => 'We also prepared an illustrated explanation in an extra document. If you prefer reading a pdf document, ',
        'pdf_explanation_link' => 'just click here',
      ],

      'signup' => [
        'page_title' => 'Sign up to be a customer',
        'form_error' => 'Form has invalid fields.',
        'token_missing_head' => 'You can not sign up',
        'token_missing' => 'Missing valid exclusive invitation code. Please visit the invitation link you got from your referrer and come back to this signup.',
        'referral_member_num_help' => 'This is the customer number of the person who recommended you. This person will also receive a reward for your purchase. In order to join, you need somebody to invite you with their customer number.',
        'invitation_code_help' => 'This code is NOT required. You may have received a special invitation code from the person who invited you.',

        'note_on_photos' => 'We will only need your image and your PAN Card internally for the payment of your recommendation fee and for the payment of the GST to the government. Since we strictly pay attention to legality, we have to comply with the legal regulations. Please pay attention to a good quality of your photos.',
        'passport_explanation' => 'Our bank needs for the transfer of your commission your pass photo. Please upload here a pass photo of you. Important: In the photo, your face must be clearly visible. Otherwise the bank cannot transfer your commission into your bank account.',
        'pan_explanation' => 'Our bank needs for the transfer of your commission your PAN card. Please upload here a photo from your PAN card. Important: In In the photo, all information from the PAN card must be clearly legible. Otherwise the bank cannot transfer your commission into your bank account.',

        'error_signup_maintenance' => 'We are sorry! Signup is in maintenance. Please come back later!',

        'form_labels' => [
          'contact' => 'Registration Form',
          'address' => 'Address',
          'passportandpan' => 'Pass / Pan Photo',
          'bank_account' => 'Bank Account',
          'bank_address' => 'Bank Address',
          'confirm' => 'Confirm',

          'referral_member_num_num_head' => 'संख्या:',
          'referral_member_num' => 'Congratulations, you were invited by:',
          'invitation_code' => 'Invitation code (optional)',
          'firstName' => 'First name',
          'lastName' => 'Last name',
          'title' => 'Title',
          'phone' => 'Cell / Home phone Number (advisable but optional)',
          'email' => 'Email',
          'age' => 'Age',
          'country' => 'Country',
          'zip_code' => 'Zip Code',
          'city' => 'City',
          'country' => 'Country',
          'iban' => 'Account Number',
          'bic' => 'IFSC code',
          'bank_recipient' => 'Recipient',
          'password' => 'Your password',
          'password2' => 'Repeat password',

          'passportfile' => 'Pass Photo',
          'panfile' => 'Pan Photo',

          'street' => 'Street and house/building number',
          'street_add' => 'Street addition (optional)',
          'bank_name' => 'Bank Name',
          'bank_street' => 'Bank Street',
          'bank_country' => 'Bank Country',
          'bank_zip_code' => 'Bank Zip Code',
          'bank_city' => 'Bank City',

          'correct_bank' => 'I confirm the correctness of this bank account and bank address. I understand that I am loosing any right of receiving commissions of '.$brandName.' due to invalid bank data',

          'accept_agbs' => 'I have read and accepted the terms and conditions',
          'accept_valid_country' => 'I confirm that I am resident of India',
          'not_accepted_countries' => '',

          'submit' => 'Submit',

          'find_ifsc_code_how_to' => 'How can I find my IFSC Code',

          'bank_account_info' => 'We will never charge your bank account. Only the payments for your referrals will be paid into this account.',
          'bank_account_info_2' => 'Please take good care and double check your your account data. We will not take responsibility for wrong transfers due to wrong account data.',
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
        'link_to_signup' => 'No account? Just follow this link to signup!',
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
          'invoice' => 'Invoice',
          'rtree' => 'Referrer Tree',
          'htree' => 'Funds Level Tree',
          'btree' => 'Bonus Hierarchy',
          'invitation' => 'Invitations',
          'bonus_payments' => 'Bonus Payments',
          'bonus_levels' => 'Bonus Levels',
          'dev_paying' => 'DevTest Paying',
          'total_invoice' => 'Total Invoice'
        ],
      ],
      'tabs' => [
        'index' => [
          'account' => 'Account',
          'payoutfailed' => 'The credit transfer failed!',
          'payoutfailed_reason' => 'The credit transfer failed for the following reason:',
          'payoutfailed_text1' => 'Please check your bank account information before contacting us. In most cases it is just a wrong character.',
          'payoutfailed_text2' => 'The following bank account was used:',
          'payoutfailed_text3' => 'Either way don\'t worry. Your reward is not lost. We will retry to transfer after you updated your bank account information!',
          'transfer_state_pending' => 'Your credit transfer state is pending. Your bank account information need to be checked.',
          'transfer_state_restored' => 'Your credit transfer state is back in active state. Your transfer will be done within the next period.',
          'gettingstarted' => 'Getting started',
          'purchasedtitle' => 'Welcome on board',
          'your_num' => 'Your customer number',
          'attention' => 'Attention!',
          'warning' => 'Warning!',
          'tank_you' => 'Thank You!',
          'unpaid_text' => 'You have not purchase our Happiness Guide yet!',
          'paid_text' => 'You purchased our Happiness Guide successfully.',
          'address' => 'Address',
          'email' => 'Email',
          'email_none' => '-none-',
          'phone' => 'Phone Number',
          'phone_none' => '-none-',
          'bank_account' => 'Bank account',
          'bank_address' => 'Bank address',
          'bank_recipient' => 'Recipient Name',
          // 'iban' => 'IBAN (International Bank Account Number)',
          'iban' => 'Account Number',
          // 'bic' => 'BIC (Bank Identifier Code)'
          'bic' => 'IFSC code',
          'member_type' => 'Customer type',
          'bonus_level' => 'Bonus Level',

          'street' => 'Street and house/building number',
          'street_add' => 'Street addition (optional)',
          'bank_name' => 'Bank Name',
          'bank_street' => 'Bank Street',
          'bank_country' => 'Bank Country',
          'bank_zip_code' => 'Bank Zip Code',
          'bank_city' => 'Bank City',

          'change_pwd' => 'Change password',
          'change_profile' => 'Change Profile',
          'change_photos' => 'Change Photos',

          'welc_not_1' => 'Welcome {name}',
          'welc_not_2' => 'Thank you for signing up! This is your profile. You will find all neccessary information here!',
          'welc_not_3' => 'IMPORTANT! Always remember your customer number! You will need it to login, recommend others etc.',

          'purchase_not_1' => 'Thank you for your purchase! You are owning the Happiness Guide now!',

          'guides' => 'Your Happiness Guides',
          'guides_not_available' => 'You have no guide enabled yet.',
          'guide_download_explanation' => 'Click on the names to download your guide(s)',
          'guide_name' => 'Happy Guide Nr. {number}',

          'purchase_now' => 'How to pay the Happiness Guide',

          'passportphoto' => 'Pass Photo',
          'panphoto' => 'Pan Photo',
          'no_image' => '- no image -',

          'images_missing' => 'We will only need your image and your PAN Card internally for the payment of your recommendation fee and for the payment of the GST to the government. Since we strictly pay attention to legality, we have to comply with the legal regulations. Please pay attention to a good quality of your photos.',
          'images_missing_attention' => 'ATTENTION - Images are Missing',
          'good_decision' => 'बहुत अच्छा निर्णय!',
          'invitation_link_comment' => 'This is your exclusive invitation link:',
          'additional_invitation_options' => 'Use this link to invite your friends and earn the systems rewards. You can share it with:',
          'additional_invitation_option_copy' => 'Copy',
          'additional_invitation_option_email' => 'Email',
          'important_header' => 'IMPORTANT',
          'this_is_customers_num' => 'This number {num} is your member number!',
          'this_is_customers_num_remember' => 'Remember it! You need this number to login!',
        ],
        'invoice' => [
          'stats' => 'Statistics',
          'transactions' => 'Transactions (grouped by reason)',
          'oustanding_advertised_count' => 'Outstanding advertised customers count',
          'advertised_member_count' => 'Advertised customers count',
          'outstanding_total' => 'Total earned',
          'transferred_total' => 'Total transferred of it',
          'custom' => '--custom--',
          'single_amount' => 'Single Amount',
          'quantity' => 'Quantity',
          'currency' => 'Currency',
          'total' => 'Total',
          'state' => 'State',
          'transfer_states' => [
            0 => 'Outstanding',
            1 => 'Reserved',
            2 => 'In transfer queue',
            3 => 'Transferred',
            4 => 'Failed',
          ],

          'button_total_invoice' => 'Show total invoice',

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
          'lvl2_signup' => 'Advance invitee to lvl 2',
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
          'num' => 'Your email address',
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
      'change_photos' => [
        'page_title' => 'Change your password',
        'error' => 'Error!',
        'success' => 'Success!',
        'success_msg' => 'Your photos has been updated.',

        'note_on_photos' => 'We will only need your image and your PAN Card internally for the payment of your recommendation fee and for the payment of the GST to the government. Since we strictly pay attention to legality, we have to comply with the legal regulations. Please pay attention to a good quality of your photos.',
        'passport_explanation' => 'Our bank needs for the transfer of your commission your pass photo. Please upload here a pass photo of you. Important: In the photo, your face must be clearly visible. Otherwise the bank cannot transfer your commission into your bank account.',
        'pan_explanation' => 'Our bank needs for the transfer of your commission your PAN card. Please upload here a photo from your PAN card. Important: In In the photo, all information from the PAN card must be clearly legible. Otherwise the bank cannot transfer your commission into your bank account.',

        'form_labels' => [
          'passportfile' => 'Pass Photo',
          'panfile' => 'Pan Photo',
          'submit' => 'Upload',
        ],
      ],
      'change_pwd' => [
        'page_title' => 'Change your photos',
        'error' => 'Error!',
        'success' => 'Success!',
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
          'FirstName' => 'First name',
          'LastName' => 'Last name',
          'Title' => 'Title',
          'Phone' => 'Phone',
          'Email' => 'Email',
          'Country' => 'Country',
          'ZipCode' => 'Zip Code',
          'City' => 'City',
          'Iban' => 'Account Number',
          'Bic' => 'IFSC code',
          'BankRecipient' => 'Recipient',
          'submit' => 'Save',
          'back' => 'Back',

          'address_title' => 'Address',

          'bank_account_title' => 'Bank account',
          'bank_address_title' => 'Bank address',

          'Street' => 'Street and house/building number',
          'StreetAdd' => 'Street addition (optional)',
          'BankName' => 'Bank Name',
          'BankStreet' => 'Bank Street',
          'BankCountry' => 'Bank Country',
          'BankZipCode' => 'Bank Zip Code',
          'BankCity' => 'Bank City',
        ],
      ],
    ],

    /* VIEWS - PROJECTS
    ---------------------------------------------*/
    'projects' => [
      'index' => [
        'page_title' => 'Projects',

        'slide_1' => 'Help yourself and other to a better and happier life',
        'slide_2_1' => 'Signup and become a customer of '.$brandName,
        'slide_2_2' => 'and get our Happiness Guide for a moderate purchase price',
        'slide_2_3' => 'and change immediately your and other\'s lives into a better',
        'slide_2_4' => 'and happier direction',

        'slide_3_1' => 'Say hello to your future with much more',
        'slide_3_2' => 'happiness and a passive income',

        'slide_4_1' => 'Our marketing system works for your happy life.',
        'slide_4_2' => 'It`s based on trustworthy mathematical calculations.',
        'slide_4_3' => 'Take action now and sign up for a better',
        'slide_4_4' => 'life for you and everybody who you love.',

        'message_head' => 'India, the largest democracy of 1.32 billion people',
        'message_text' => 'is also the biggest center of poverty in the world',

        'poor_teaser_1' => 'Top most reasons why india is so poor',
        'poor_teaser_2' => 'India has 363 million (or 29.5 percent) people under the poverty line based on a survey of 2011-12',
        'poor_teaser_3' => 'Unequal Distribution of Wealth',
        'poor_teaser_4' => 'Unfortunately, since departure of the colonial British in 1947 all economic development has taken place in the cities, while the majority of the population lives in the countryside.',
        'poor_teaser_5' => 'Illiteracy',
        'poor_teaser_6' => 'High level of illiteracy, particularly in the rural areas and among women, has been a crucial factor not only in perpetuating economic backwardness but also for high population growth',
        'poor_teaser_7' => 'Population',
        'poor_teaser_8' => 'India\'s population is currently growing annually at the rate of about 1.4 percent',

        'about_us_title' => 'About us and our idea',
        'about_us_title2' => 'Help yourself AND others',
        'planed_projects_title' => 'Our planned projects for the future',
        'affected_countries_title' => 'Countries we want to help',

        'why_poor_head' => 'EVER WONDERED WHY INDIA BECAME SO POOR?',
        'why_poor_quote' => 'It is a fact that the parts of India that have been under British control for a long time are the least wealthy today.',
        'why_poor_quote_author' => 'Jawaharlal Nehru',
        'why_poor_quote_author_org' => 'First Prime Minister of India',

        'against_poverty_head' => 'We are against poverty',
        'against_poverty_sub' => 'We developed the Happiness Guide and this recommendation marketing system to make lives better',
        'we_foundation' => 'The Company',
        'we_foundation_description' => "$brandName has been founded for people who have few to no options to achieve a better life. This could be caused by reasons like economic and political circumstances.",

        'we_mission' => 'Our mission',
        'we_mission_description' => 'With the Happness Guide we want everyone to have important information about being happy. This way everybody can have a better life in the future.',

        'we_system' => 'Recommendation Marketing System',
        'we_system_description' => "Our recommendation marketing system offers a huge opportunity to immediately start a better and happier life. No special education is required. With a little effort and just a little time all customers can earn a lot of money with a few recommendations. It really is the easiest system to earn money ever.",

        'we_fee' => 'Financing',
        'we_fee_description' => '100% of the recommendation marketing system is sponsored by our profit. So if the system profits, the customers profit.',

        'where_to_go_head' => 'where to go from here',

        'service_ms_title' => 'Recommendation Marketing System',
        'service_ms_desc' => 'Sounds great! Where can I get more information about this recommendation marketing system?',
        'service_ms_btn' => 'Click here!',

        'service_faq_title' => 'I have questions',
        'service_faq_desc' => 'No problem, just go to the FAQ section. You will find answers there',
        'service_faq_btn' => 'Go to FAQ',

        'service_contact_title' => 'I still have questions',
        'service_contact_desc' => 'That is still no problem! Just contact us and we will answer any question!',
        'service_contact_btn' => 'Contact us',

        'pdf_explanation_head' => 'Recommendation marketing system',
        'pdf_explanation' => 'There is also an illustrated explanation in an extra document. If you prefer reading a pdf document, ',
        'pdf_explanation_link' => 'just click here',

        'long_video_head' => 'The amazing recommendation marketing system of Betterliving India',
        'guide_video_head' => 'Happines Guide Video',
        'guide_illustration_head' => 'Happiness Guide ILLUSTRATION',

      ],
    ],

    /* VIEWS - ABOUT
    ---------------------------------------------*/
    'about' => [

      'index' => [
        'page_title' => 'About Us',
        'texts' => [
          '1' => 'We are proud to offer you this fantastic project in cooperation with Betterliving Management Private Ltd./Malta. This project is managed and produced by Betterliving Management Private Ltd./Malta.',
          '2' => 'The idea of Betterliving was created in 2015 by a circle of successful business people in the areas of health, marketing, sales and personal training in Germany and India.',
          '3' => 'In 2016 we decided that the idea – help the people in India with a professional Happiness Guide – starts in 2017.',
          '4' => 'Betterliving is not only a part of the names of our companies. Betterliving is our concept in the real sense of this word. We offer everybody a very simple way to a better life.',
          '5' => 'Become customer of Betterliving and use all your advantages from us and get also your personally and simple way to a much better life.',
        ]
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

        'items' => '__$faqItems__',
      ],

      'impressum' => [
        'page_title' => 'Impressum',
      ],

      'terms' => [
        'page_title' => 'Terms of Use',
        'texts' => '__$terms__'
      ],
    ],

    /* VIEWS - ADMINISTRATION
    ---------------------------------------------*/
    'admin' => [

      'index' => [
        'page_title' => 'Mark members paid',

        'recipient_num' => 'Search by customer number:',

        'list_title' => 'List of unpaid members',
        'member_name' => 'Member name',
        'member_num' => 'Customer number',
        'paid_date' => 'Haid Paid?',
        'signup_date' => 'Signup Date',
        'success' => 'Success',
        'success_msg' => 'Member is set paid now!'
      ],

      'members' => [
        'page_title' => 'Manage Members',

        'list_title' => 'Members',
        'search_member' => 'Search by Name/Num',
        'orderBy' => 'Order by',
        'limitBy' => 'Limit',
        'filterBy' => 'Filter',
      ],

      'imptrans' => [
        'page_title' => 'Import IndusInd Transfer Excel export',
        'importfile' => 'Excel File'
      ],
    ],

    /* VIEWS - GUIDE
    ---------------------------------------------*/
    'guide' => [

      'index' => [
        'page_title' => 'Happiness Guide',
        'product_title' => 'Happiness Guide',
        'product_description' => 'Our Happiness Guide series. A digital download about getting happier in life. You will get 8 pieces, each one more step to wealth and health',


        'paypal_register_text' => 'You don\'t have a PayPal Account?',
        'paypal_register_button' => 'Register PayPal Account for free',

        'product_tab_video' => 'Video',
        'product_tab_pdf' => 'PDF',
      ],

      'handleresult' => [
        'page_title' => 'Purchase Results',
      ],

      'howtopay' => [
        'page_title' => 'How to pay the Happiness Guide',

        'purchased_already' => 'You are already owning our Happiness Guide',
        'purchased_already_link' => 'Follow this link to your account to download our guide',
        'want_purchase' => 'Do you want to purchase our Happiness Guide?',
        'require_login' => 'Dont hesitate, you need to signup and login then',
        'signup_button' => 'Got to signup',
        'login_button' => 'Got to login',

      ],
    ],
  ],

    /* MAILS
    ---------------------------------------------*/
  'mail' => '__$mails__'
];

?>
