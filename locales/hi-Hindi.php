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
      'india' => 'भारत',
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

    'purchase_agreemensts' => '{terms}',
    'terms' => 'आप हमारी उपयोग की शर्तों से सहमत हैं!',
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
    'password_conditions' => '5 अक्षर या अधिक, कम से कम एक छोटा अक्षर और एक अंक',
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
        'exp_videos' => 'स्पष्टीकरण वीडियो',
        'member' => 'विचार',
        'guide' => 'Happiness Guide',
        'projects' => 'होम',
        'about' => 'हमारे बारे में',
        'signup' => 'Signup',
        'account' => 'प्रोफाइल',
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
          'index' => 'प्रवेश करें और भुगतान करें',
          'whatsappinvite' => 'WhatsApp आमंत्रण',
          'stepstosuccess' => 'सफलता के लिए कदम',
          'fromto' => 'पहली मेल से प्रीमियम ग्राहक को 3 दिनों में',
          'backoffice' => 'Betterliving का बैक ऑफिस ',
        ],
        'projects' => [
          '1' => ['index', 'गज़ब का सिफारिश विपणन प्रणाली वीडियो', 'video_explanation'],
          '2' => ['index', 'सिफारिश विपणन प्रणाली पीडीएफ व्याख्या', 'pdf_explanation'],
          '3' => ['index', 'Happiness Guide वीडियो स्पष्टीकरण', 'hg_video_explanation'],
          '4' => ['index', 'Happiness Guide पीडीएफ व्याख्या', 'hg_pdf_explanation']
        ],
        'member' => [
          'index' => 'ग्राहक ही ग्राहकों को सलाह देते हैं',
          'system' => 'हमारी सिफारिश विपणन प्रणाली',
          'signup' => 'साइन अप करें',
        ],
        'guide' => [
          'index' => 'The Happiness Guide',
          'howtopay' => 'Happiness Guide भुगतान कैसे करें',
        ],
        'account' => [
          'index' => 'सूचना',
          'invoice' => 'चालान',
          'rtree' => 'रेफ़रल ट्री',
          'htree' => 'फंड स्तरीय ट्री',
          'btree' => 'बोनस पदानुक्रम',
          'logout' => 'लोग आउट',
        ],
        'about' => [
          'index' => 'हमारे बारे में',
          'contact' => 'संपर्क',
          'faq' => 'सामान्य प्रश्न',
          'terms' => 'उपयोग की शर्तें',
          'impressum' => 'Impressum',
        ],
      ],
      'member_login' => 'Customer Login',

      'copyright_text' => '© Copyright 2016 by '.$copyrightName.' All Rights Reserved.',

      'useful_link_1' => 'उपयोगी',
      'useful_link_2' => 'लिंक',
      'useful_link_contact' => 'कोई सवाल? बस हमसे संपर्क करें!',
      'useful_link_faq' => 'अक्सर पूछे जाने वाले प्रश्न',
      'useful_link_terms' => 'उपयोग की शर्तें',
      'useful_link_about_us' => 'हमारे बारे में',
      'useful_link_impressum' => 'Impressum',
    ],

    /* VIEWS - MEMBER
    ---------------------------------------------*/
    'exp_videos' => [
      'index' => [
        'page_title' => 'स्पष्टीकरण वीडियो - प्रवेश करें और भुगतान करें',
        'head' => 'प्रवेश करें और भुगतान करें',
      ],
      'whatsappinvite' => [
        'page_title' => 'स्पष्टीकरण वीडियो - WhatsApp आमंत्रण',
        'head' => 'WhatsApp आमंत्रण',
      ],
      'stepstosuccess' => [
        'page_title' => 'स्पष्टीकरण वीडियो - सफलता के लिए कदम',
        'head' => 'सफलता के लिए कदम',

        'pdf_explanation_head' => 'सिफारिश विपणन प्रणाली',
        'pdf_explanation' => 'हमने एक अतिरिक्त दस्तावेज़ में एक सचित्र व्याख्या भी तैयार की है। यदि आप एक pdf document पढ़ना पसंद करते हैं,',
        'pdf_explanation_link' => 'तो यहां क्लिक करें',
      ],
      'fromto' => [
        'page_title' => 'स्पष्टीकरण वीडियो - पहले मेल से premium customer तक',
        'head' => 'पहले मेल से premium customer तक',
      ],
      'backoffice' => [
        'page_title' => 'स्पष्टीकरण वीडियो - Betterliving का बैक ऑफिस',
        'head' => 'Betterliving का बैक ऑफिस',
      ],
    ],

    'member' => [
      'btn' => [
        'signup' => 'अभी साइन-अप करें',
      ],
      'text' => [
        'Customer advertises customers',
      ],

      'system' => [
        'page_title' => 'सिफारिश विपणन प्रणाली',
        'vs1' => 'Position customer',
        'vs2' => 'Position premium customer',
        'notice' => 'Notice',
        'signup_now' => 'अभी साइन-अप करें',
        'etc' => 'etc.',
        'intro1' => 'विपणन प्रणाली सिफारिश का अर्थ निम्नलिखित में समझाया जाएगा।',
        'intro1h' => ['सिफारिश विपणन प्रणाली'],
        'intro2' => 'इसे आसान बनाने के लिए हम एक विस्तृत उदाहरण का पालन करेंगे',
        'intro3' => 'हमारी सिफारिश विपणन प्रणाली की बेहतर समझ के लिए आपको पता होना चाहिए कि इसमें केवल दो स्थान हैं पहली स्थिति "customer" है और दूसरा स्थान "premium customer" है।',
        'you' => 'आप',
        'head_explanation' => 'Explanation',
        'head_part' => 'चरण',
        'head_illustration' => 'चित्रण',

        'names_yourself' => 'स्वयं',

        'pdf_explanation' => 'अतिरिक्त दस्तावेज में एक सचित्र व्याख्या भी है। यदि आप एक pdf दस्तावेज़ पढ़ना पसंद करते हैं, ',
        'pdf_explanation_link' => 'तो बस यहां क्लिक करें',
        'video_explanation' => 'अन्यथा हमने एक वीडियो तैयार किया है। बस इस लिंक का पालन ',
        'video_explanation_link' => 'करें',

        '',

        '1' => 'जैसा कि आप '.$brandName.' के ग्राहक हैं, आप हमारी सिफारिश मार्केटिंग सिस्टम में स्वचालित रूप से "customer" स्थिति में होंगे।',
        '1h' => ['$_member_fee_$'],
        '1_1' => 'You = Customer',

        '2' => 'अब आप अपने पहले दो नए ग्राहकों Durga और Kamal को सलाह देते हैं।',
        '3' => 'जिससे आप "customer" की स्थिति में प्रत्येक से {0} अर्जित करेंगे, इसलिए आप कुल {1} अर्जित करेंगे।',
        '3h' => ['$_lvl1_$', '$_2_times_lvl1_$'],
        '4' => 'जब आपने दो नए ग्राहकों को सफलतापूर्वक अनुशंसित किया है, तो आप स्वचालित रूप से एक "premium customer"हैं।',
        '5' => 'अब आप अपने तीसरे नए ग्राहक Jivan की सलाह देते हैं।',
        '6' => 'जिससे आप स्थिति premium customer में {0} अर्जित करेंगे।',
        '6h' => ['$_lvl2_$'],
        '7' => 'हर अतिरिक्त ग्राहक के लिए आप भविष्य में अनुशंसा करते हैं, तो आप भी {0} प्रत्येक अर्जित करेंगे।',
        '7h' => ['$_lvl2_$'],
        '8' => 'चूंकि Jivan अब एक ग्राहक है, वह स्वचालित रूप से स्थिति "customer" पर है',
        '9' => 'उदाहरण के लिए, यदि आप किसी और ग्राहक की सिफारिश नहीं कर सकते हैं, लेकिन Jivan के पास उसके पहले दो नए ग्राहक Rati और Lila हैं।',
        '10' => '',
        '11' => 'Jivan ने प्रत्येक ग्राहक के लिए अपनी "customer" की स्थिति में {0} अर्जित किये है, इस प्रकार कुल अर्जन {1}।',
        '11h' => ['$_lvl1_$', '$_2_times_lvl1_$'],
        '12' => 'इस मामले में प्रत्येक अनुशंसित ग्राहक के लिए आपको {0} रुपये कमाने होंगे, इस प्रकार कुल मिलाकर {1}।',
        '12h' => ['$_indirect_$', '$_2_times_indirect_$'],
        '13' => '{0}',
        '13h' => ['इस बिंदु पर आप पहले से ही स्वयं सक्रिय हुये बिना पैसा कमाते हैं!', ],
        '14' => '{0} – आपने पहले से ही {1} अर्जित किए हैं और आपके योगदान को लगभग पूरी तरह संतुलित कर दिया है!',
        '14h' => ['सिर्फ एक हफ्ते के बाद', '$_after_one_week_$', 'and almost entirely balanced your contribution!', ],
        '15' => 'Jivan अपनी पहली दो सफल अनुशंसाओं के साथ स्वतः "premium customer" होगा',
        '16' => 'जैसे ही आप और Jivan एक ही स्थिति में हैं, आपको Jivan के नए ग्राहकों के लिए और अधिक भुगतान नहीं किया जाएगा।',
        '17' => 'हालांकि, अब Rati और Lila को आपके ट्री में रखा गया है।',
        '18' => 'चूंकि पहले दो नए अनुशंसित ग्राहक (Rati और Lila) को ग्राहक (आप) को सौंपा गया है जिसने सिफारिश करके ग्राहक (Jivan) को पहली जगह में विज्ञापन किया है।',
        '19' => 'Rati और Lila ने अपनी Happiness Guide के लिए भुगतान करने के बाद, वे दोनों "customer" की स्थिति पर हैं।',
        '20' => 'अब Rati और Lila ने अपने पहले दो नए ग्राहकों, Devi, Kumar, Gita और Karan की सिफारिश की है।',
        '21' => 'इस प्रकार, Rati और Lila प्रत्येक अनुशंसित ग्राहक के लिए {0} अर्जित करते हैं और आपको प्रत्येक {1} रुपये मिलते हैं, इस प्रकार आप इनसे कुल से {2} कमाते हैं।',
        '21h' => ['$_lvl1_$', '$_indirect_$', '$_4_times_indirect_$'],
        '22' => 'अब Rati और Lila अपने पहले दो सफल अनुशंसाओं के बाद "customer" से "premium customer" में जाते हैं।',
        '23' => 'अब से, जब Rati और Lila नए ग्राहकों की अनुशंसा करते हैं तो आप उनसे और कमाते नहीं हैं।',
        '24' => 'हालांकि, अब Devi, Kumar, Gita और Karan को आपके ट्री में रखा गया है। जैसा कि उन्होंने पहले ही अपनी Happiness Guide के लिए भुगतान कर किया है, वे सभी "customers"हैं।',
        '25' => 'अब Devi, Kumar, Gita और Karan ने अपने पहले दो ग्राहकों की सिफारिश की।',
        '26' => 'इस प्रकार Devi, Kumar, Gita और Karan प्रत्येक ग्राहक से {0} अर्जित करते हैं और आप प्रत्येक {1} अर्जित करते हैं, इस प्रकार पूरी तरह से आप {2} कमाते हैं। ',
        '26h' => ['$_lvl1_$', '$_indirect_$', '$_8_times_indirect_$'],
        '27' => 'अब आपने न केवल अपनी Happiness Guide की खरीददारी की कीमत को वसूल लिया है और अपने बेहतर जीवन के लिए निवेश वापस जुटा लिया है, बल्कि उसके शीर्ष पर धन भी अर्जित किया।',
        '28' => 'और इसमे कितना समय लगा?',
        '29' => 'अनुभव से, औसतन इसमें एक दिन लगा है, जब तक कोई ग्राहक सफलतापूर्वक एक नए ग्राहक की सिफारिश नहीं करता।',
        '30' => 'इसलिए, इस उदाहरण में 10 से 14 दिन लग सकते हैं।',
        '31' => 'यदि हर भविष्य का ग्राहक केवल दो नए ग्राहकों की सिफारिश करता है और इसमें औसतन एक हफ्ते लगता हैं, तो आपकी कमाई इस तरह से होगी:',
        '31_week' => 'सप्ताह',
        '32' => '{0} you therefore already earned {1} {2}',
        '32' => 'लगभग दस हफ्तों के बाद आप ने पहले ही {2} कमा लिए',
        '32h' => ['After approximately ten weeks', 'round about', '$_after_10_weeks_$'],
        '33' => '<p>बेशक यह सिफारिश विपणन प्रणाली बेहिसाब काम नहीं करेगा क्योंकि लोगों की संख्या स्वाभाविक रूप से सीमित है। लेकिन वहां भी थे और अभी भी हैं, हालांकि, दुनिया भर में सिफारिश विपणन प्रणाली ने दुनिया भर में और विभिन्न आर्थिक क्षेत्रों में समृद्ध लोगों की एक बेहद अनगिनत संख्या बनाई है और अब भी नए लोगों का निर्माण कर रही है। </p><p>आपके लिए एकमात्र सवाल यह है कि: क्या आप इन खुशहाल लोगों में से एक हो सकते हैं या आप ऐसे व्यक्ति कहलना चाहेंगे जो इस बात पर विश्वास नहीं करता कि यह काम कर सकता है?</p>अभी यह निश्चित रूप से काम कर रहा है - आपको केवल शामिल होने की आवश्यकता है!',
        '34' => 'जिन ग्राहकों ने नए ग्राहकों को जल्दी से आप की सिफारिश करने का फैसला किया है, वे बहुत सारे और बहुत सारे पैसे कमा लेंगे।',
        '35' => 'भरोसेमंद गणितीय गणना परियोजना दर्शाती है कि वे ग्राहक जो सिफारिश के विपणन प्रणाली को शुरू करने के बाद पहले कम से कम 12 महीनों में सक्रिय हो गए थे और कम से कम तीन नए ग्राहकों की सिफारिश करते हैं, पहले 12 महीनों में कुल मिलाकर {0} से {1} कमाया।',
        '35h' => ['$_lvl1_$', '$_lvl1_$'],
        '36' => '<p>कोई सीमा नहीं है:</p> यदि केवल एक ग्राहक अनुशंसा करता है कि 10, 15 या 20 या अधिक ग्राहक, यहां तक कि अधिक कमाई संभव है।',
        '37' => 'हालांकि, हम उन ग्राहकों में से भी होंगे जो सामान्य प्रयास के कारण कम राशि कमाते हैं, उदाहरण के लिए {0}',
        '37r' => ['$_thousand_dollar_$'],
        '38' => 'फिर कुछ ऐसे भी ग्राहक होंगे जो केवल बहुत कम कमाएंगे, उदाहरण के लिए केवल {0}',
        '38r' => ['$_2hundred_dollar_$'],
        '39' => 'और निश्चित रूप से भविष्य में, संभवत: कुछ वर्षों में, हमारे पास ऐसे ग्राहकों के मामले भी होंगे जो कि सिफारिश विपणन प्रणाली में कुछ भी नहीं कमाएंगे। लेकिन Happiness Guide के साथ जीवन में कई फायदे हैं और शायद इसके माध्यम से वे दूसरे क्षेत्र में बहुत पैसा कमाते हैं।',
        '40' => 'जिस तरह से सिस्टम काम करता है उसके कारण, आप इस पर निर्भर करते हुए कम कमाएंगे कि सिस्टम कितनी करीब है। यह ज्यादातर अप्रत्याशित है लेकिन किसी दिन सिस्टम स्थिर होगा जो तब होगा जब कोई नया ग्राहक न हो। उस दिन सिफारिश मार्केटिंग प्रणाली बंद हो जाएगी, लेकिन संभवतः पैसे कमाने का एक नया तरीका होगा!',
        '41' => 'आपके व्यक्तिगत मुनाफे के विशिष्ट विकास की शायद ही भविष्यवाणी की जा सकती है। हालांकि, संभावनाओं का परिणाम प्रत्येक ग्राहक के व्यक्तिगत प्रयास पर निर्भर करता है।',
        '42' => 'अगर आपको लगता है कि,',
        '43' => 'आप कम से कम तीन नए ग्राहकों की सिफारिश कर सकते हैं',
        '44' => 'ये ग्राहक फिर से दो नए ग्राहकों की सिफारिश कर सकते हैं',
        '45' => 'ये नए ग्राहक कम से कम दो नए ग्राहकों की सिफारिश कर सकते हैं',
        '46' => 'फिर अपनी खुद की Happiness Guide के लिए आपके खरीद मूल्य की प्रतिपूर्ति लगभग 15-25 दिनों के भीतर हो जाएगी और इसके साथ ही आपको अपना पहला लाभ भी अर्जित हो जाएगा।',
        '47' => 'इसके अलावा, यदि आप मानते हैं कि केवल तीन महीने के दौरान प्रत्येक नए ग्राहक कम से कम तीन नए ग्राहकों की सिफारिश करते हैं, तो आप इस पर बहुत सारे और बहुत सा धन अर्जित करेंगे और पूरे नए जीवन स्तर को जुटाने में सक्षम होंगे।',
        '48' => 'आप उन चीजों को खरीदने में सक्षम होंगे जो आपने पहले कभी नहीं सोचा होगा। आप अपने परिवार की सहायता कर सकते हैं, सबसे अच्छी चिकित्सा देखभाल ले सकते हैं, नई कार खरीद सकते हैं, समय के बारे में सोचने की ज़रूरत नहीं है कि जब आप बूढ़े हो जायेंगे तब आप क्या जीएंगे, आदि।',
        '49' => "$brandName का यह असली और शानदार उद्देश्य है!",
        '50' => 'अब अमल करो',
        '51' => 'आप यहां बेहतर जीवन के लिए पंजीकरण कर सकते हैं:',
      ],

      'index' => [
        'page_title' => 'ग्राहक ही ग्राहकों को प्रशंसा करते देते हैं',

        'basic_idea' => 'मूल विचार',
        'basic_idea_title' => [
          'Happiness Guide',
          'मूल बातें',
        ],
        'basic_idea_text' => [
          'Happiness Guide के साथ आप अपने कामकाजी जीवन, अपने स्वास्थ्य को बदल सकते हैं और सम्पूर्ण रूप से खुश रह सकते हैं। 2-सप्ताह के आठ चरणों के चक्र में हमारी Happiness Guide आपको खुश कर देगी। प्रत्येक चरण के बाद आप सीखेंगे कि अपने जीवन को बेहतर कैसे बनाते हैं।',
          'भारत में हर कोई Happiness Guide खरीद सकता है। केवल {member_fee} के लिए आपको एक सुखी व्यक्ति बनने का निर्देश मिलता है।',
          'Every customer can become active easily (and without any fee or costs) within the “customers recommend customers” recommendation marketing system and earn a constant and {passive_income}.',
          'Only citizens of India are allowed to become customers of ' . $brandName.'.',
        ],
        'basic_idea_highlights' => [
          null,
          ['onetime' => 'one-time', 'member_fee' => '$_member_fee_$'],
          ['passive_income' => 'passive income'],
          null,
        ],

        'become_member_title' => 'मैं ग्राहक बनना चाहता हूं',
        'become_member_text' => 'यदि आप भी ग्राहक बनना चाहते हैं, तो कृपया इस होमपेज पर रजिस्टर करें और ₹ 3,950.00 के लिए Happiness Guide खरीदें। जैसे ही भुगतान का हस्तांतरण हो चुका है, आप एक ग्राहक हैं और आपको अपने बेहतर जीवन के लिए Happiness Guide प्राप्त होगी। इस बिंदु पर आप अपने व्यक्तिगत "पेंशन योजना" को प्राप्त करने के लिए शानदार सिफारिश विपणन प्रणाली का उपयोग कर सकते हैं',
        'become_member_highlights' => [
          'member_fee' => '$_member_fee_$',
        ],


        'member_starting' => 'मैं एक ग्राहक क्यों बनूँ?',
        'member_starting_title' => [
          'Happiness Guide',
          'एक ग्राहक के रूप में आपका अतिरिक्त लाभ',
          'सिफारिश विपणन प्रणाली',
          'तत्काल सिफारिश करना शुरू करें',
        ],
        'member_starting_text' => [
          'Happiness Guide के साथ आप अपना कामकाजी जीवन, अपने स्वास्थ्य को बदल सकते हैं और संपूर्ण रूप से खुश रह सकते हैं। ',
          'हमारी सिफारिश विपणन प्रणाली में आप "ग्राहक" उस स्थिति में होते हैं जब आप "ग्राहक ही ग्राहकों की सलाह देते हैं',
          'सिफारिश विपणन प्रणाली का काम कैसे किया जाए, निम्नलिखित में बताया जाएगा।',
          'अपने पंजीकरण समाप्त करने के ठीक बाद, आप इस होमपेज के माध्यम से बेहतर ग्राहकों के लिए नए ग्राहकों की सिफारिश कर सकते हैं।',
        ],
        'member_starting_button' => [
          '',
          '"ग्राहक" क्या अर्थ है?',
          'सिफारिश विपणन प्रणाली का स्पष्टीकरण',
          'हां, मैं साइन-अप करना चाहता हूं!',
        ],

        'pdf_explanation_head' => 'Recommendation Marketing System',
        'pdf_explanation' => 'We also prepared an illustrated explanation in an extra document. If you prefer reading a pdf document, ',
        'pdf_explanation_link' => 'just click here',
      ],

      'signup' => [
        'page_title' => 'Sign up to be a customer',
        'form_error' => 'Form has invalid fields.',
        'token_missing_head' => 'आप साइन अप नहीं कर सकते',
        'token_missing' => 'वैध अनन्य आमंत्रण कोड अनुपलब्ध है। कृपया अपने संदर्भ से प्राप्त आमंत्रण लिंक पर जाएं और इस साइनअप पर वापस आएं।',
        'referral_member_num_help' => 'This is the customer number of the person who recommended you. This person will also receive a reward for your purchase. In order to join, you need somebody to invite you with their customer number.',
        'invitation_code_help' => 'This code is NOT required. You may have received a special invitation code from the person who invited you.',

        'note_on_photos' => 'हम आपकी तस्वीरों का आंतरिक उपयोग नहीं करेंगे। हम कानूनन इनको एकत्र करना पड़ा है और जब हम अपने प्रावधानों का हस्तांतरण करते हैं तो उन्हें हमारे बैंक को भेजते हैं। हम उन तस्वीरों के बिना आपके प्रावधानों का भुगतान नहीं कर सकते।',
        'passport_explanation' => 'Our bank needs for the transfer of your commission your pass photo. Please upload here a pass photo of you. Important: In the photo, your face must be clearly visible. Otherwise the bank cannot transfer your commission into your bank account.',
        'pan_explanation' => 'Our bank needs for the transfer of your commission your PAN card. Please upload here a photo from your PAN card. Important: In In the photo, all information from the PAN card must be clearly legible. Otherwise the bank cannot transfer your commission into your bank account.',

        'error_signup_maintenance' => 'We are sorry! Signup is in maintenance. Please come back later!',

        'form_labels' => [
          'contact' => 'पंजीकरण फॉर्म',
          'address' => 'पता',
          'passportandpan' => 'पास / पैन फोटो',
          'bank_account' => 'बैंक खाता',
          'bank_address' => 'बैंक का पता',
          'confirm' => 'पुष्टि करें',

          'referral_member_num_num_head' => 'संख्या:',
          'referral_member_num' => 'बधाई हो, आपको इनके द्वारा आमंत्रित किया गया था:',
          'invitation_code' => 'निमंत्रण कोड (वैकल्पिक)',
          'firstName' => 'पहला नाम',
          'lastName' => 'अंतिम नाम',
          'title' => 'शीर्षक',
          'phone' => 'मोबाइल/घर का फोन नंबर (उचित लेकिन वैकल्पिक)',
          'email' => 'ईमेल',
          'age' => 'आयु',
          'country' => 'Country',
          'zip_code' => 'पिन कोड',
          'city' => 'शहर',
          'country' => 'देश',
          'iban' => 'खाता संख्या',
          'bic' => 'आईएफएससी कोड',
          'bank_recipient' => 'प्राप्त करने वाला',
          'password' => 'आपका पासवर्ड',
          'password2' => 'पासवर्ड दोहराएं',

          'passportfile' => 'Pass Photo',
          'panfile' => 'Pan Photo',

          'street' => 'गली और घर/ बिल्डिंग नंबर',
          'street_add' => 'मार्ग (वैकल्पिक)',
          'bank_name' => 'बैंक का नाम',
          'bank_street' => 'बैंक गली',
          'bank_country' => 'बैंक देश',
          'bank_zip_code' => 'बैंक पिन कोड',
          'bank_city' => 'बैंक शहर ',

          'correct_bank' => 'मैं इस बैंक खाते की शुद्धता और बैंक पते की पुष्टि करता हूँ। मैं समझता हूँ कि मैं अमान्य बैंक डेटा के कारण Betterliving के कमीशन प्राप्त करने का कोई अधिकार खो रहा हूँ।',

          'accept_agbs' => 'I have read and accepted the terms and conditions',
          'accept_valid_country' => 'मैं पुष्टि करता हूं कि मैं भारत के निवासी हूँ',
          'not_accepted_countries' => '',

          'submit' => 'जमा करें',

          'find_ifsc_code_how_to' => 'मैं अपना आईएफएससी कोड कैसे पा सकता हूं',

          'bank_account_info' => 'हम आपके बैंक खाते से कभी शुल्क नहीं लेंगे। केवल आपके रेफरल के लिए भुगतान इस खाते में दिए जाएंगे।',
          'bank_account_info_2' => 'कृपया ध्यान रखें करें और अपना खाता डेटा दोबारा जांचें। गलत खाता डेटा के कारण हम गलत स्थानान्तरण की जिम्मेदारी नहीं लेंगे।',
          'email_info' => 'आप हमें कभी भी स्पैम प्राप्त नहीं करेंगे। हमें आपके ई-मेल पते की आवश्यकता पड़ेगी अगर पैसे हस्तांतरित करने में समस्याएं आती हैं। यदि आपके पास ई-मेल पता नहीं है तो आप यहां नि: शुल्क पंजीकरण कर सकते हैं:',
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
        'page_title' => 'ग्राहक लॉगिन',
        'error' => 'Error',
        'password_reset' => 'क्या आप पासवर्ड भूल गए हैं?',
        'link_to_signup' => 'कोई खाता नहीं? बस इस लिंक का पालन करें',
        'form_labels' => [
          'login' => 'Login',

          'num' => 'ग्राहक संख्या',
          'pwd' => 'पासवर्ड',

          'submit' => 'Submit',
        ],
      ],
      'index' => [
        'page_title' => 'Profile',
        'welcome' => 'Welcome {name}',
        'navigation_links' => [
          'index' => 'सूचना',
          'invoice' => 'चालान',
          'rtree' => 'रेफ़रल ट्री',
          'htree' => 'फंड स्तरीय ट्री',
          'btree' => 'बोनस पदानुक्रम',
          'invitation' => 'निमंत्रण',
          'bonus_payments' => 'बोनस भुगतान',
          'bonus_levels' => 'बोनस स्तर',
          'dev_paying' => 'DevTest Paying',
          'total_invoice' => 'Total Invoice'
        ],
      ],
      'tabs' => [
        'index' => [
          'account' => 'लेखा',
          'payoutfailed' => 'The credit transfer failed!',
          'payoutfailed_reason' => 'The credit transfer failed for the following reason:',
          'payoutfailed_text1' => 'Please check your bank account information before contacting us. In most cases it is just a wrong character.',
          'payoutfailed_text2' => 'The following bank account was used:',
          'payoutfailed_text3' => 'Either way don\'t worry. Your reward is not lost. We will retry to transfer after you updated your bank account information!',
          'transfer_state_pending' => 'Your credit transfer state is pending. Your bank account information need to be checked.',
          'transfer_state_restored' => 'Your credit transfer state is back in active state. Your transfer will be done within the next period.',
          'gettingstarted' => 'Getting started',
          'purchasedtitle' => 'बोर्ड में आपका स्वागत है',
          'your_num' => 'आपका ग्राहक संख्या',
          'attention' => 'Attention!',
          'warning' => 'Warning!',
          'tank_you' => 'धन्यवाद!',
          'unpaid_text' => 'You have not purchase our Happiness Guide yet!',
          'paid_text' => 'अपने हमारी Happiness Guide को सफलतापूर्वक खरीद लिया है।',
          'address' => 'पता',
          'email' => 'ईमेल',
          'email_none' => '-कोई नहीं-',
          'phone' => 'फ़ोन नंबर',
          'phone_none' => '-कोई नहीं- ',
          'bank_account' => 'बैंक खाता',
          'bank_address' => 'बैंक का पता',
          'bank_recipient' => 'प्राप्तकर्ता का नाम',
          // 'iban' => 'IBAN (International Bank Account Number)',
          'iban' => 'खाता संख्या',
          // 'bic' => 'BIC (Bank Identifier Code)'
          'bic' => 'आईएफएससी कोड',
          'member_type' => 'ग्राहक प्रकार',
          'bonus_level' => 'Bonus Level',

          'street' => 'Street and house/building number',
          'street_add' => 'Street addition (optional)',
          'bank_name' => 'Bank Name',
          'bank_street' => 'Bank Street',
          'bank_country' => 'Bank Country',
          'bank_zip_code' => 'Bank Zip Code',
          'bank_city' => 'Bank City',

          'change_pwd' => 'पासवर्ड बदलें',
          'change_profile' => 'विवरणिका बदले',
          'change_photos' => 'Change Photos',

          'welc_not_1' => 'Welcome {name}',
          'welc_not_2' => 'Thank you for signing up! This is your profile. You will find all neccessary information here!',
          'welc_not_3' => 'IMPORTANT! Always remember your customer number! You will need it to login, recommend others etc.',

          'purchase_not_1' => 'Thank you for your purchase! You are owning the Happiness Guide now!',

          'guides' => 'आपकी Happiness Guides',
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
          'invitation_link_comment' => 'यह आपका अनन्य आमंत्रण लिंक है:',
          'additional_invitation_options' => 'अपने मित्रों को आमंत्रित करने के लिए इस लिंक का उपयोग करें और सिस्टम पुरस्कार कमाएं आप इसके साथ साझा कर सकते हैं:',
          'additional_invitation_option_copy' => 'प्रतिलिपि',
          'additional_invitation_option_email' => 'ईमेल',
          'important_header' => 'महत्त्वपूर्ण',
          'this_is_customers_num' => '{num} संख्या आपकी सदस्य संख्या है!',
          'this_is_customers_num_remember' => 'इसे याद रखिए! आप इस नंबर से लॉगिन कर सकते है!',
        ],
        'invoice' => [
          'stats' => 'आंकड़े',
          'transactions' => 'लेनदेन (समूहीकरण का आधार )',
          'oustanding_advertised_count' => 'बकाया विज्ञापित ग्राहक गिनती',
          'advertised_member_count' => 'विज्ञापित ग्राहक गिनती',
          'outstanding_total' => 'कुल कमाई',
          'transferred_total' => 'इसके कुल हस्तांतरण',
          'custom' => '--custom--',
          'single_amount' => 'Single Amount',
          'quantity' => 'मात्रा',
          'currency' => 'Currency',
          'total' => 'कुल',
          'state' => 'राज्य',
          'transfer_states' => [
            0 => 'बकाया',
            1 => 'Reserved',
            2 => 'In transfer queue',
            3 => 'Transferred',
            4 => 'Failed',
          ],

          'button_total_invoice' => 'कुल चालान दिखाएं',

          'reason' => 'कारण',
          'transaction_reasons' => [
            0 => 'कमीशन स्तर 1',
            1 => 'कमीशन स्तर 2',
            2 => 'बोनस स्तर 2',
            3 => 'बोनस Director',
            4 => 'बोनस Organization Leader',
            5 => 'बोनस Promoter',
            6 => 'बोनस IT',
            7 => 'बोनस CEO',
            // 8 => 'Bonus CEO2',
            // 9 => 'Bonus lawyer',
            10 => 'बोनस Sub Promoter',
            11 => 'बोनस Sub Promoter Referrer',

            12 => 'बोनस Sales Management',
            13 => 'बोनस Executive',
            14 => 'बोनस Tariq Wani',
            15 => 'बोनस NGO',

            1001 => 'Custom bonus payment',
            1002 => 'Remaining donation',
            1003 => 'Transfer to root system',

            2000 => 'Custom bonus level payment',
          ],
        ],
        'tree' => [
        ],
        'invitation' => [
          'form_title' => 'नया निमंत्रण बनाएं',
          'type' => 'प्रकार',
          'free_signup' => 'निःशुल्क पंजीकरण',
          'lvl2_signup' => 'Lvl 2 को अग्रिम आमंत्रण',
          'promoter_num' => 'On the recommendation of promoter (Num)',
          'submit' => 'Submit',
          'restricted_to_lvl2' => 'You need to invite two customers to unlock invitations',

          'list_title' => 'निमंत्रण',
          'hash' => 'कोड',
          'creation_date' => 'बनाया था',
          'accepted_date' => 'स्वीकृत',
          'signup_success' => 'Successfully created invitation code.',
          'success' => 'Success!',

          'recipient_details' => 'Recipient details',
          'member_type' => 'ग्राहक प्रकार',
          'member_num' => 'ग्राहक संख्या',
          'address' => 'पता',
          'email' => 'Email',
          'email_none' => '-none-',
        ],
        'bonus_payments' => [
          'form_title' => 'ग्राहक को बोनस भुगतान करें',
          'recipient_num' => 'बोनस प्राप्त करने वाले ग्राहकों की संख्या',
          'amount' => 'राशि ',
          'amount_in_currency' => 'राशि {symbol} ({name}) में ',
          'purpose' => 'उद्देश्य',
          'validate_form' => 'Validate form',
          'submit' => 'Submit',

          'recipient_details' => 'Recipient details',
          'member_type' => 'ग्राहक प्रकार',
          'member_num' => 'ग्राहक संख्या',
          'address' => 'पता',
          'email' => 'Email',
          'email_none' => '-none-',

          'list_title' => 'बोनस भुगतान',
          'recipient_num_th' => 'Recipient customer number',
          'creation_date' => 'बनाया था',
          'accepted_date' => 'Accepted',

          'signup_success' => 'Successfully created bonus payment.',
          'success' => 'Success!',
        ],
        'bonus_levels' => [
          'form_title' => 'ग्राहक के लिए बोनस का स्तर बदलें',
          'recipient_num' => 'इस बोनस स्तर को प्राप्त करने वाले की ग्राहक संख्या',
          'amount_in_currency' => 'राशि {symbol} ({name}) में ',
          'validate_form' => 'Validate form',
          'submit' => 'Submit',

          'recipient_details' => 'Recipient details',
          'member_type' => 'ग्राहक प्रकार',
          'bonus_level' => 'बोनस स्तर',
          'member_num' => 'ग्राहक संख्या',
          'address' => 'पता',
          'email' => 'Email',
          'email_none' => '-none-',
          'signup_success' => 'Successfully changed bonus level.',
          'success' => 'Success!',

          'list_title' => 'बोनस ग्राहक',
          'member_num' => 'ग्राहक संख्या',
          'date' => 'तिथि',
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

          'address_title' => 'पता',

          'bank_account_title' => 'Bank account',
          'bank_address_title' => 'बैंक का पता',

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

        'slide_1' => 'स्वयं की और दूसरों के जीवन को बेहतर और खुशहाल बनाने में मदद करें।',
        'slide_2_1' => 'साइन-अप करें और '.$brandName,
        'slide_2_2' => 'के एक ग्राहक बनें और एक सामान्य खरीद मूल्य के ',
        'slide_2_3' => 'लिए हमारी Happiness Guide प्राप्त करें और तुरंत ',
        'slide_2_4' => 'और आपके जीवन को बेहतर और खुश दिशा में बदल दें।',

        'slide_3_1' => 'Say hello to your future with much more',
        'slide_3_2' => 'happiness and a passive income',

        'slide_4_1' => 'Our marketing system works for your happy life.',
        'slide_4_2' => 'It`s based on trustworthy mathematical calculations.',
        'slide_4_3' => 'Take action now and sign up for a better',
        'slide_4_4' => 'life for you and everybody who you love.',

        'message_head' => 'भारत, 1.32 अरब लोगों का सबसे बड़ा लोकतंत्र',
        'message_text' => 'दुनिया में गरीबी का सबसे बड़ा केंद्र भी है',

        'poor_teaser_1' => 'भारत में इतनी खराब क्यों हैं शीर्ष कारण',
        'poor_teaser_2' => '2011-12 के सर्वेक्षण के अनुसार भारत में गरीबी रेखा के नीचे 363 मिलियन (या 29.5 प्रतिशत) लोग हैं',
        'poor_teaser_3' => 'धन का असमान वितरण',
        'poor_teaser_4' => 'दुर्भाग्य से, 1947 में औपनिवेशिक ब्रिटिशों के प्रस्थान के बाद से शहरों में सभी आर्थिक विकास हुआ है, जबकि अधिकांश आबादी ग्रामीण इलाकों में रहती है।',
        'poor_teaser_5' => 'निरक्षरता',
        'poor_teaser_6' => 'निरक्षरता का उच्च स्तर, विशेष रूप से ग्रामीण क्षेत्रों में और महिलाओं के बीच है, यह न केवल आर्थिक पिछड़ेपन को बनाए रखने में बल्कि उच्च जनसंख्या वृद्धि के लिए भी महत्वपूर्ण कारक है।',
        'poor_teaser_7' => 'जनसंख्या',
        'poor_teaser_8' => 'भारत की जनसंख्या वर्तमान में 1.4 प्रतिशत की दर से बढ़ रही है।',

        'about_us_title' => 'About us and our idea',
        'about_us_title2' => 'Help yourself AND others',
        'planed_projects_title' => 'Our planned projects for the future',
        'affected_countries_title' => 'Countries we want to help',

        'why_poor_head' => 'क्या कभी हैरानी हुई है कि भारत क्यों इतना गरीब बन गया?',
        'why_poor_quote' => 'यह एक तथ्य है कि भारत के कुछ हिस्सों को लंबे समय से ब्रिटिश नियंत्रण में रखा गया है, वे आज भी कम अमीर हैं।',
        'why_poor_quote_author' => 'Jawaharlal Nehru',
        'why_poor_quote_author_org' => 'First Prime Minister of India',

        'against_poverty_head' => 'हम गरीबी के खिलाफ हैं',
        'against_poverty_sub' => 'हमने जीवन को बेहतर बनाने के लिए Happiness Guide और इस सिफारिश विपणन प्रणाली को विकसित किया है।',
        'we_foundation' => 'कंपनी',
        'we_foundation_description' => "$brandName का निर्माण उनके लिए किया गया है, जिनके पास बेहतर जीवन प्राप्त करने के लिए कोई विकल्प नहीं हैं। यह आर्थिक और राजनीतिक परिस्थितियों के जैसे कारणों से हो सकता है।",

        'we_mission' => 'हमारा लक्ष्य',
        'we_mission_description' => 'Happness Guide के साथ हम चाहते हैं कि सभी को खुश रहने के बारे में महत्वपूर्ण जानकारी हो। इस तरह सभी भविष्य में बेहतर जीवन प्राप्त कर सकते हैं।',

        'we_system' => 'सिफारिश विपणन प्रणाली',
        'we_system_description' => "हमारी सिफारिश विपणन प्रणाली एक बेहतर और खुशहाल जीवन को तुरंत शुरू करने का एक बड़ा अवसर प्रदान करता है। कोई विशेष शिक्षा आवश्यक नहीं है। थोड़े से प्रयासों के साथ और थोड़े से समय के साथ सभी ग्राहक कुछ सिफारिशों के साथ बहुत पैसा कमा सकते हैं। यह वास्तव में पैसा कमाने के लिए सबसे आसान प्रणाली है",

        'we_fee' => 'फाइनेंसिंग',
        'we_fee_description' => 'सिफारिश मार्केटिंग सिस्टम 100% हमारे लाभ द्वारा प्रायोजित है। तो अगर सिस्टम को लाभ तो ग्राहक को लाभ।',

        'where_to_go_head' => 'यहाँ से कहाँ जाएं',

        'service_ms_title' => 'सिफारिश विपणन प्रणाली',
        'service_ms_desc' => 'बहुत अच्छा लगता है! इस सिफारिश विपणन प्रणाली के बारे में मुझे अधिक जानकारी कहां मिल सकती है?',
        'service_ms_btn' => 'Click here!',

        'service_faq_title' => 'मेरे कुछ सवाल हैं',
        'service_faq_desc' => 'कोई समस्या नहीं, बस FAQ अनुभाग पर जाएं आपको वहां उत्तर मिलेंगे',
        'service_faq_btn' => 'Go to FAQ',

        'service_contact_title' => 'मेरे पास अब भी सवाल हैं',
        'service_contact_desc' => 'यह अभी भी कोई समस्या नहीं है! बस हमसे संपर्क करें और हम किसी भी सवाल का जवाब देंगे!',
        'service_contact_btn' => 'Contact us',

        'pdf_explanation_head' => 'सिफारिश विपणन प्रणाली',
        'pdf_explanation' => 'अतिरिक्त दस्तावेज में एक सचित्र व्याख्या भी है। यदि आप एक पीडीएफ दस्तावेज़ पढ़ना पसंद करते हैं, ',
        'pdf_explanation_link' => 'तो यहां क्लिक करें',

        'long_video_head' => 'Betterliving India की आश्चर्यजनक सिफारिश विपणन प्रणाली',
        'guide_video_head' => 'Happiness Guide वीडियो',
        'guide_illustration_head' => 'Happiness Guide इलस्ट्रेशन',
      ],
    ],

    /* VIEWS - ABOUT
    ---------------------------------------------*/
    'about' => [

      'index' => [
        'page_title' => 'हमारे बारे में',
        'texts' => [
          '1' => 'हमें आपको Betterliving Management Private Ltd./Malta के सहयोग से इस शानदार परियोजना को पेश करने पर गर्व महसूस हो रहा हैं। इस परियोजना को Betterliving Management Private Ltd./Malta द्वारा प्रबंधित तथा प्रस्तुत किया गया है।',
          '2' => 'जर्मनी और भारत में स्वास्थ्य, विपणन, बिक्री और व्यक्तिगत प्रशिक्षण के क्षेत्र में सफल व्यवसाय के लोगों के एक समूह द्वारा 2015 में Betterliving का विचार का सर्जन किया गया था।',
          '3' => '2016 में हमने इस विचार फैसला किया कि - पेशेवर लोगों के साथ भारत में लोगों की मदद की जाए। Happiness Guide – का आरंभ 2017 में हुआ है।',
          '4' => 'Betterliving केवल हमारी कंपनियों के नाम का एक हिस्सा मात्र नहीं है। Betterliving हमारे विचार इस शब्द के वास्तविक अर्थ में है। हम सभी को बेहतर जीवन के लिए एक बहुत आसान तरीका बताते हैं।',
          '5' => 'Betterliving के ग्राहक बनें और हमारे सभी फायदे का उपयोग करें और एक बेहतर जीवन के लिए अपने लिए व्यक्तिगत और आसान तरीके भी जानें।',
        ]
      ],

      'contact' => [
        'page_title' => 'संपर्क',
        'form_title' => 'संपर्क प्रपत्र',
        'form_labels' => [
          'name' => 'नाम',
          'email' => 'ईमेल',
          'phone' => 'टेलीफोन',
          'subject' => 'विषय',
          'message' => 'संदेश',

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
        'member_num' => 'ग्राहक संख्या',
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
        'purchased_already' => 'You are already owning our Happiness Guide',
        'purchased_already_link' => 'Follow this link to your account to
        download our guide',
        'want_purchase' => 'Do you want to purchase our Happiness Guide?',
        'require_login' => 'Dont hesitate, you need to signup and login then',
        'signup_button' => 'Got to signup',
        'login_button' => 'Got to login',

        'paypal_register_text' => 'You don\'t have a PayPal Account?',
        'paypal_register_button' => 'Register PayPal Account for free',

        'product_tab_video' => 'Video',
        'product_tab_pdf' => 'PDF',

        'popup_purchase_loading_text' => 'Processing your purchase',
        'popup_purchase_success_text' => 'Purchase completed',
        'popup_purchase_cancel_text' => 'The purchase could not be completed',

        'popup_purchase_head' => 'Purchase Happines Guide',
        'popup_purchase_text1' => 'We are happy that you are following us to a better live!',
        'popup_purchase_text2' => 'Please read following notes carefully',
        'popup_purchase_text3' => 'You are about to purchase a {0}! You wont receive any physical objects!',
        'popup_purchase_text3_val' => ['digital download'],
        'popup_purchase_text4' => 'The format of our Happines Guide is PDF. You can find a free to use {0}.',
        'popup_purchase_text4_val' => ['pdf reader here'],
        'popup_purchase_text5' => 'You will regularly receive our Happines Guide per Email (PDF file attached).',
        'popup_purchase_text6' => 'You can download the PDF from within your login area as often as you like.',
        'popup_purchase_text7' => 'We guarantee an available {0}. But much likely longer. This is just a safety clause for us if we might close this service. So we can not guarantee lifetime download service.',
        'popup_purchase_text7_val' => ['download for at least 2 months'],
        'popup_purchase_text8' => 'The costs are {0}. Please refer to PayPal for eventual {1}. PayPal will charge this extra effort from you!',
        'popup_purchase_text8_val' => ['__', 'currency exchange rates'],
        'popup_purchase_text9' => 'You can find all these notes and many more in our {0}.',
        'popup_purchase_text9_val' => ['FAQ section'],
        'popup_purchase_text10' => 'If you have more questions, don\'t hesitate {0}',
        'popup_purchase_text10_val' => ['to contact us'],

        'popup_purchase_text11' => 'Since you are purchasing a digital download there is NO RIGHT OF CANCELATION',
        'popup_purchase_submit' => 'Complete Purchase Now',
        'popup_purchase_cancel' => 'Cancel Purchase',
        'popup_purchase_close' => 'Close',

        'extended_system_user_note' => 'NOTE: The system recognized your exclusive invitation. By purchasing our Happiness Guide you will get access to the recommendation marketing system.',
      ],

      'handleresult' => [
        'page_title' => 'Purchase Results',
      ],

      'howtopay' => [
        'page_title' => 'How to pay the Happiness Guide',
      ],
    ],
  ],

    /* MAILS
    ---------------------------------------------*/
  'mail' => '__$mails__'
];

?>
