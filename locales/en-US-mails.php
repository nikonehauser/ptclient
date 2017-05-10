<?php

return [




    'password_reset' => [
      'subject' => 'Password reset',
      'body' => "You have lost your $brandName password. Sorry about that!
But don’t worry! You can use the following link within the next day to reset your password:
[link]({link})
Note: Link expires within 24 hours.",
    ],




/**
 * #1
 *
 **/
    'email_validation' => [
      'subject' => 'Registration Validation',
      'body' =>
"Hello {fullname},

thank you for signing up to $brandName!

Just open the following link in your favourite web browser to complete your registration:

[Betterliving Email Validation]({link})

Note: Link expires within 24 hours.",

    ],


/**
 * #2
 * Der neue Customer, auch Customer in einer Bonusstufe erhält eine umfassende Begrüßungsemail mit Anweisungen, wohin er welchen Betrag bis wann überweisen soll.
 *
 * params:
 *   fullname,
 *   member_id,
 *   recruiter,
 *   fmt_member_fee,
 *   bankaccount,
 *   duedate
 *
 */
  'signup_confirm' => [

    'subject' => 'Welcome to Betterliving',

    'body' => "Dear {fullname},

welcome to Betterliving. {recruiter} recommended you and wants you to profit from Betterliving.
We’re happy you followed {recruiter}’s recommendation.

In order to make you a part of Betterliving and to offer you all opportunities it is necessary for you to purchase the Happiness Guide.

Please pay the price of {fmt_member_fee} via PayPal now, otherwise we cannot confirm your registration.

To get hold of the Happiness Guide, just click on the button “Purchase your Happiness Guide now”.
After that, click on “PayPal” and complete the payment of {fmt_member_fee}.
In case you do not own a PayPal account, please register on their website [PayPal](https://paypal.com).

As soon as we have received your payment, you are going to get to know
helpful secrets about how to make a fortune with $brandName.

This is your client identification number (ID): \"{member_id}\".

If you want to log in into your account at Betterliving in the future you have to visit our website http://www.betterliving.social.

There you have to push the button \"Customer Login\".

Then you enter your email address and your personal password and push \"SUBMIT\".",
  ],





/**
 * #3
 * Der neue Spender erhält eine Email. Hierin wird sich einerseits für die Spende bedankt und andererseits darauf hingewiesen, dass wenn er weitere Spender gewinnt, er nicht nur die Projekte der NGO fördert, sondern er und alle von ihm empfohlenen Spender auch sehr viel Geld verdienen können.
 *
 * params:
 *   fullname,
 *   member_id,
 *   referrer_fullname,
 *   video_link,
 *   signup_link,
 *   after6weeksamount
 *
 */
  'fee_income' => [

    'subject' => 'The secret of Betterliving',

    'body' =>

"Dear {fullname},

thanks for purchasing the Happiness Guide!

{referrer_fullname} has probably told you about making a lot of money very easily with Betterliving by recommending it to other people.

That is why they want you to register and buy the Happiness Guide for {fmt_member_fee}.
We are glad you followed the recommendation!

Please watch the video [Marketingsystem of Betterliving]({video_link}) for more information.

As you can see, you will receive commissions as soon as you successfully recommend Betterliving to your friends or anybody in your life.

From the third successful recommendation your bonus will increase to over 500%!

Plus, you only need three people to register and buy the Guide in order to have a passive income.

Make use of this fantastic marketing system today and tell all your friends about this genius opportunity.

What would you do if you and your friends earned {after6weeksamount} after six weeks already?

If you do not know how to spend your money: Trust me, as soon as you have got it, you will know what beautiful things you want to spend it on.

Show this video [Marketingsystem of Betterliving]({video_link}) as many people as possible.

And do not forget to tell them you’re in already!

So what are you waiting for?

Enjoy the opportunity not only to make a fortune for yourself, but also to give your loved one the same opportunity!

Maybe you don’t know how to let your friends know about this.

In this case just copy the following and send it to all your best friends via email:
__________________________________________
Dear friend,

a short time ago I was told about the opportunity to make money by using the Happiness Guide.

It’s super easy and just genius.

I would like to let you know about this opportunity and how you can get a lot of money as well.

Just watch this video [Marketingsystem of Betterliving]({video_link}) for me. I am already customer by Betterliving and I’d be happy if you did so, too.

In order to join, just click here:
[Betterliving Signup]({signup_link})
___________________________________________

This is your client identification number (ID): \"{member_id}\".

Remember it to log into your account.

That is where your private details are saved; just like the bank account you want us to transfer your commissions to. You can always update those.

On top, you can take a look at all of the commissions you’ve already received, the ones you are going to get in the future and an overview of all of your recommendations.

Long story short: This is where you can find any information about your success in this fantastic marketing system.

Of course you can also find the download links to your Happiness Guides here. The first part is ready to download already. Over the next few months, you will be able to download all of the missing parts to complete the Happiness Guide – until you own all eight parts.

We wish you the very best and hope you’ll get rich soon!"


  ],


/**
 * #3 for tbmtproduct
 *
 * params:
 *   fullname,
 *   member_id,
 *   referrer_fullname,
 *   video_link,
 *   signup_link,
 *   after6weeksamount
 *
 */
  'fee_income_tbmt_product' => [

    'subject' => 'The secret of Betterliving',

    'body' =>
"Dear {fullname},

We are happy to welcome you to Betterliving.

You have purchased your Happiness Guide today, thank you!

That’s why the first part of your Happiness Guide is ready for you to download.

This is your client identification number (ID): \"{member_id}\".

Keep your number in a safe place; you will need it to log in on [www.betterliving.social](http://www.betterliving.social).

In order to download the first part of your Happiness Guide, just log in now.

After the first part, there will be the following parts ready for you to download every two weeks. This will go on until you own the complete Guide with all eight parts.

We are going to send you an email to remind you of the download as soon as a new part is there for you.

You will definitely become more successful in life with the Happiness Guides, health, wealth and luck will be yours.

From the bottom of our hearts, us – team Betterliving – wish you the very best and, of course, lots of success with your Happiness Guide.

May all your wishes come true."

  ],


/**
 * #4
 * #5
 * #6
 *
 * params:
 *   fullname,
 *   member_id,
 *   referrer_fullname,
 *   video_link,
 *   signup_link,
 *   after6weeksamount
 *   member_type_name
 *   member_type_bonus
 *
 */
  'invitation_fee_income' => [

    'subject' => 'Welcome to Betterliving – You are now a special member at www.get-a-better.life.social!',

    'body' =>

"Dear {fullname},

thanks for purchasing the Happiness Guide!

{referrer_fullname} has probably told you about making a lot of money very easily with Betterliving by recommending it to other people.

That is why they want you to register and buy the Happiness Guide for {fmt_member_fee}.
We are glad you followed the recommendation!

By the way, {referrer_fullname} has invited you to be a special client!

Because of their personal invitation, you have been registered in the position \"{member_type_name}\".

That means: {free_invitation} For every client we get because of your recommendation, you will receive a bonus amount on top of the money you will make with our official marketing system. All about that is in the following video: [Marketingsystem of Betterliving]({video_link}).

This video shows how your income with Betterliving is going to evolve. And again: This is what you will earn on top of the bonus as \"{member_type_name}\".

As you can see, you will receive commissions as soon as you successfully recommend Betterliving to your friends or anybody in your life.

From the third successful recommendation your bonus will increase to over 500%!

{lvl2text}

Make use of this fantastic marketing system today and tell all your friends about this genius opportunity.

What would you do if you and your friends earned {after6weeksamount} after six weeks already?

If you do not know how to spend your money: Trust me, as soon as you have got it, you will know what beautiful things you want to spend it on.

Show this video [Marketingsystem of Betterliving]({video_link}) as many people as possible.

And do not forget to tell them you’re in already!

So what are you waiting for?

Enjoy the opportunity not only to make a fortune for yourself, but also to give your loved one the same opportunity!

Maybe you don’t know how to let your friends know about this.

In this case just copy the following and send it to all your best friends via email:
__________________________________________
Dear friend,

a short time ago I was told about the opportunity to make money by using the Happiness Guide.

It’s super easy and just genius.

I would like to let you know about this opportunity and how you can get a lot of money as well.

Just watch this video [Marketingsystem of Betterliving]({video_link}) for me. I am already customer by Betterliving and I’d be happy if you did so, too.

In order to join, just click here: [Betterliving Signup]({signup_link})
___________________________________________

This is your client identification number (ID): \"{member_id}\".

Remember it to log into your account.

That is where your private details are saved; just like the bank account you want us to transfer your commissions to. You can always update those.

On top, you can take a look at all of the commissions you’ve already received, the ones you are going to get in the future and an overview of all of your recommendations.

Long story short: This is where you can find any information about your success in this fantastic marketing system.

Of course you can also find the download links to your Happiness Guides here. The first part is ready to download already. Over the next few months, you will be able to download all of the missing parts to complete the Happiness Guide – until you own all eight parts.

We wish you the very best and hope you’ll get rich soon!

If you want to log in into your account at Betterliving in the future you have to visit our website www.betterliving.social.
There you have to push the button \"Customer Login\".

Then you enter your email address or your client identification number (ID): \"{member_id}\" and your personal password and push \"SUBMIT\"."


  ],





/**
 * #7
 * Der Tippgeber dieses neuen Spenders erhält auch eine Email. Hier wird er über diese Registrierung namentlich informiert. Zudem wird ihm zur …ten erfolgreichen Registrierung gratuliert und das weitere entsprechende Szenarium aufgezeigt.
 *
 * params:
 *   fullname,
 *   member_id,
 *   recommendation_count,
 *   recruited_fullname,
 *   video_link,
 *   duedate
 *
 */
  'new_recruitment_congrats' => [

    'subject' => 'The next step to make a fortune with Betterliving',

    'body' =>

"**Congratulations on your successful {recommendation_count} recommendation!**

Dear {fullname},

{recruited_fullname} has registered at Betterliving because of your recommendation.

Contact {recruited_fullname} and help them get their first 10 people to join Betterliving. If they can do it, you can earn thousands of Euros just by that.

As you can see, it is not difficult to recommend Betterliving successfully and earn a lot of money by doing so.

The more people you recommend, the faster you will have a passive income.

So tell 10 people about it today, tomorrow and the day after and you can become rich – guaranteed! There’s no other way.

Just take another look at the video: [Marketingsystem of Betterliving]({video_link})

So what are you waiting for?

Enjoy the opportunity of making a fortune!

Maybe you don’t know how to let your friends know about this.

In this case just copy the following and send it to all – really all - your best friends via email:
__________________________________________
Dear friend,

a short time ago I was told about the opportunity to make money by using the Happiness Guide.

It’s super easy and just genius.

I would like to let you know about this opportunity and how you can get a lot of money as well.

Just watch this video [Marketingsystem of Betterliving]({video_link}) for me. I am already customer by Betterliving and I’d be happy if you did so, too.

In order to join, just click here: [Betterliving Signup]({signup_link})
___________________________________________

We wish you the very best and hope you’ll be rich soon!
"

  ],





/**
 * #8
 *
 * params:
 *   fullname,
 *   recruited_fullname,
 *   recruited_firstname,
 *   provision_amount,
 *   adv2amount,
 *   video_link,
 *   paid_recommendation_count,
 *   min_payout_amount,
 *   profile_url
 *
 */
  'fee_income_referrer_first' => [

    'subject' => 'The next step to your personal fortune',

    'body' =>

"Dear {fullname},

your {paid_recommendation_count} recommended customer {recruited_fullname} has purchased the Happiness Guide.

Thank you for sharing the opportunity with {recruited_firstname}.

You will get a commission for {recruited_firstname}’s purchase.

You will receive {provision_amount} for {recruited_firstname}’s purchase.

You want to earn even more? Make sure you find two friends who join Betterliving.

This will make you a premium customer. That means your commission will go up +500% to {adv2amount} and secures a passive income for the future!

This is proved in the explanation video [Marketingsystem of Betterliving]({video_link}) at 1:35.

You are going to see that in your upcoming invoice as well.

From your third recommended customer who signs up and purchases the Happiness Guide the door to your private wellbeing will be wide open.

We’re wishing best of luck finding new customers and have fun spending the commissions you will receive.

Please recheck whether your bank account details are correct. You can do so by logging in to your [profile]({profile_url}) with your ID and check your information. By clicking “change profile” you can correct your bank account details.


Please note that we only transfer commissions at a minimum of {min_payout_amount}. For more information, have a look at our terms of use.",

  ],





/**
 * #9
 *
 * params:
 *   fullname,
 *   adv2amount,
 *   adv1amount,
 *   advindirectamount,
 *   recruited_firstname,
 *   provision_amount,
 *   recruited_fullname,
 *   video_link,
 *   after6weeksamount,
 *   min_payout_amount
 *
 */
  'fee_income_referrer_second' => [

    'subject' => 'The next step to your personal fortune',

    'body' =>

"Dear {fullname},

CONGRATULATIONS! You made it.

By recommending your second customer you have become a premium customer of Betterliving.

You will receive {provision_amount} for {recruited_firstname}’s purchase.

From now on you will receive {adv2amount} for every future customer.

So what do you need to know now?

You know you have received {adv1amount} for the first two customers.

Betterliving always pays a commission of {adv2amount} for each customer, so as a premium costumer you will always get the difference of {advindirectamount} for the first two customers of your recommended friends.

If you take a look at 2:50 in the video [Marketingsystem of Betterliving]({video_link})

You can replace “Jivan” with your next recommended Customer.

If you want to make sure you make a lot of money in a short time, here’s how it works:

Well firstly, you can always go on finding new customers.

You will always receive {adv2amount} each.

Secondly, and this is even more interesting, show all your recommended customers how to become premium costumers as well.

The video [Marketingsystem of Betterliving]({video_link}) shows what will happen when your next recommended Customer finds two friends who also recommend two others at 4:00

After six weeks you will find a total amount of {after6weeksamount} in your bank account.

In order to make lots of money, all you have to do is:
1. Make three friends join Betterliving to become a premium customer and receive a passive income
2. Show your three friends how to become premium clients themselves
3. Find even more friends to join

You can multiply your commission with each of your friends when they become a premium customer, too. Your friends will have a great income being premium costumers and you have done well in making a fortune for you and your family.

Good luck!

Please note that we only transfer commissions at a minimum of {min_payout_amount}. For more information, have a look at our terms of use."


  ],





/**
 * #10
 *
 * params:
 *   fullname,
 *   paid_recommendation_count,
 *   recruited_fullname,
 *   recruited_firstname,
 *   provision,
 *   min_payout_amount
 *
 */
  'fee_income_referrer_premium' => [

    'subject' => 'Your {paid_recommendation_count} Step to your personal Fortune',

    'body' =>

"Dear {fullname},

your {paid_recommendation_count} recommended customer {recruited_fullname} has purchased the Happiness Guide.

Thank you for sharing the opportunity with {recruited_firstname}.

You will get a commission for {recruited_firstname}’s purchase.

{provision}

We’re wishing best of luck finding new customers and have fun spending the commissions you will receive.

Please note that we only transfer commissions at a minimum of {min_payout_amount}. For more information, have a look at our terms of use.",

  ],





/**
 * #11
 *
 * params:
 *   fullname,
 *   hg_count,
 *   member_id,
 *
 */
  'hg_available' => [

    'subject' => 'The "{hg_count}" part of your Happiness Guide is ready to download!',

    'body' =>

"Dear {fullname},

today the \"{hg_count}\" part of your happiness guide is ready to download.

Please log in with your personal client ID {member_id}. After doing so you can download the new part of the Happiness Guide!

Have fun reading it!

May all your personal dreams come true.",

  ],





/**
 * #12
 *
 * params:
 *
 */
  'invoice' => [

    'subject' => 'Invoice: {invoice_number}',

    'body' =>

"{fullname}<br>
{address}<br>
<br>
<br>
<h1>Invoice</h1>
<br>
<br>
<br>
<strong>Invoice Number:</strong> {invoice_number}<br>
<strong>Invoice Date:</strong> {invoice_date}<br>
<strong>Customer Number:</strong> {customer_number}<br>
<br>
<table class=\"invoiceTable\" border=1>
  <tr>
    <th>Pos</th>
    <th>Product</th>
    <th>VAT</th>
    <th>Single Price</th>
    <th>Quantity</th>
    <th>Total Price</th>
  </tr>

  <tr>
    <td>1</td>
    <td>Happiness Guide</td>
    <td>0 %</td>
    <td>{membership_fee}</td>
    <td>1</td>
    <td>{membership_fee}</td>
  </tr>
</table><br>
The total amount has been paid via PayPal.<br>
<br>
<br>
<br>
<hr>
<br>
<center style=\"font-size: smaller;\">
Betterliving For Everyone Ltd<br>
SWQ 3141 Malta, Swieqi - Noel Muscat & Co - Triq L-Imghazel <br>
Registration number: C 76973 - VAT registrations number: MT23625003<br>
www.betterliving.social - info@betterliving.social<br>
</center>
<br>
<hr>
<br>
",

  ],


/*****************************************************************
###################################################################
###################################################################
###################################################################
###################################################################
###################################################################
###################################################################
###################################################################
###################################################################
###################################################################
###################################################################
###################################################################
###################################################################
###################################################################
###################################################################
###################################################################
###################################################################
###################################################################
###################################################################
###################################################################
###################################################################
*******************************************************************/







/**
 * #3
 * Der neue registrierte Spender erhält eine Email, in der das Datum seiner Registrierung genannt wird und wir bis dato keinen Geldeingang verzeichnen konnten. Dann wird er daran erinnert, sein Spende unter Angabe seiner ID spätestens innerhalb von sieben Tagen zu leisten. Hier wird noch einmal kurz auf die tollen Projekte der NGO und recht ausführlich auf die Verdienstmöglichkeit für ihn und seine Freunde, Bekannten und Verwandte hingewiesen.
 *
 * params:
 *   fullname,
 *   member_id,
 *   signup_date,
 *   duedate_second,
 *   bankaccount,
 *   video_link
 *
 */
  'fee_reminder' => [

    'subject' => 'Don’t miss the chance for your better life',

    'body' => "Dear {fullname},

we have already welcomed you to Betterliving on {signup_date}
after you successfully signed up to be a customer on www.betterliving.soical.
Unfortunately we did not receive your payment within 7 days.
If you have already transferred the full amount, this email can be ignored.
In case you haven’t been able to do so because of personal issues
you can make up for it as soon as possible.
Please make sure you are done by {duedate_second}.
These are the bank account details:

{bankaccount}

Always indicate your personal customer number \"{member_id}\" as intended purpose for the transfer.
Because if you are a customer and recommend three others,
you can receive very good monthly payments
using this unique marketing system.
It’s not only your life that will be a lot better and more comfortable in the future.
Just offer everyone you know and love, your family and friends,
the opportunity to profit from our unbelievable
marketing system by only making three recommendations.
In this video
{video_link}
for you and your loved ones our marketing system is explained.
We hope you won’t miss your chance to make your life a lot easier than
before by purchasing the Happiness Guide and recommending Betterliving
to only three people who do the same.
We wish you a carefree and pleasant future life with your great passive income of Betterliving."

  ],





/**
 * #4
 * Nach Zahlung einse Customers in einer Bonusstufe erhält er eine Email, in der Begrüßung wird, eine Musteremail zum Einladen von Bekannten, Hinweis auf seine Bonusstufe, ID für Teilnehmerlogin.
 *
 * params:
 *   fullname,
 *   member_id,
 *   recruited_signup_date,
 *   recruited_fullname,
 *   recruited_firstname,
 *   bankaccount
 *
 */
  'fee_reminder_referrer' => [

    'subject' => 'Your recommended customer {recruited_fullname} has not made their purchase yet',

    'body' => "Dear {fullname},

on {recruited_signup_date} {recruited_fullname} successfully signed up on
www.betterliving.social because of your recommendation.

Unfortunately {recruited_fullname} has not purchased the Happiness Guide yet.
Please get in touch with {recruited_fullname}. Maybe {recruited_fullname} didn’t
get our messages or there are still questions we definitely have answers for.
Possibly {recruited_firstname} has already got it but the payment has not
entered our bank account yet, in this case everything is fine.
If this is not the case please make sure {recruited_firstname} makes their purchase as soon as possible.
Here are our account details:

{bankaccount}

The customer number \"{member_id}\" should always be given as intended purpose for the transfer.
Thank you for the support, we hope you will successfully find new customers for us and your passive income."

  ],





/**
 * #5
 * Der neue registrierte Spender erhält eine Email, in der das Datum seiner Registrierung genannt wird und wir bis dato keinen Geldeingang verzeichnen konnten. Dann wird er daran erinnert, sein Spende unter Angabe seiner ID spätestens innerhalb von sieben Tagen zu leisten. Hier wird noch einmal kurz auf die tollen Projekte der NGO und recht ausführlich auf die Verdienstmöglichkeit für ihn und seine Freunde, Bekannten und Verwandte hingewiesen.
 *
 * params:
 *   fullname,
 *   member_id,
 *   signup_date,
 *   duedate_second,
 *   bankaccount,
 *   video_link,
 *
 */
  'fee_reminder_with_advertisings' => [

    'subject' => 'Don’t miss the opportunity for your better life',

    'body' => "Dear {fullname},

we could already welcome you to Betterliving on {signup_date}
after you successfully signed up to be a customer on www.betterliving.soical.
Unfortunately you did not purchase the Happiness Guide within 7 days.
If you already have, this email can be ignored.
In case you haven’t been able to purchase it because of personal issues
please do so by{duedate_second}.
Here are the bank account details:

{bankaccount}

Always indicate your customer number \"{member_id}\" as intended
purpose for the transfer.

Here is some very important information for you:
If you miss out on purchasing within the deadline listed above,
you cannot receive commissions for your recommended customers.
That’s not it. You can see in our explanation video at 4:25 what happens
if you are a \"premium customer\" of Betterliving. Being a \"premium customer\" is easy;
all you have to do is recommend three customers.
From then on you will receive a commission of {advindirectamount} Dollars
for the first two customers of all your future recommendations.
As you can see in the video, this can rise up to be a pretty high income in a short time.
Don’t miss your chance to make a lot of money.

It’s not only your life which will be a lot better and more comfortable in the future.
Just offer everyone you know and love, your family and friends,
the opportunity to purchase the Happiness Guide and to profit from this
unbelievable marketing system by only making three recommendations.
In this video
{video_link}
for you and your loved ones our marketing system is explained.
We hope you don’t miss your chance to make your life a lot easier than before
with the Happiness Guide and recommending it to only three people.

We wish you a carefree and pleasant future life with your high passive income of Betterliving."

  ],





/**
 * #6
 * Der Tippgeber dieses Spenders erhält auch eine Email, in der er aufgefordert wird, sich diesem Spender, der sich am …. Registriert hat, noch einmal anzunehmen und auf die Zahlung der Spende hinzuwirken.
 *
 * params:
 *   fullname,
 *   member_id,
 *   recruited_signup_date,
 *   recruited_fullname,
 *   recruited_firstname,
 *   bankaccount,
 *
 */
  'fee_reminder_referrer_with_advertisings' => [

    'subject' => 'Your recommended customer {recruited_fullname} has not made their purchase',

    'body' => "Dear {fullname},

on {recruited_signup_date} {recruited_fullname} successfully signed up
on www.betterliving.social after your recommendation.
Unfortunately {recruited_firstname} has not purchased the Happiness Guide yet.
Please get in touch with {recruited_firstname}.
Maybe {recruited_firstname} didn’t get our messages or there are still questions we definitely have an answer for.
Possibly {recruited_firstname} has already got it
but it has not entered our bank account yet, in this case everything is fine.
If this is not the case please make sure {recruited_firstname} will get the Happiness Guide as soon as possible.
Here are the account details:

{bankaccount}

The personal customer number \"{member_id}\" should always be given as intended purpose for the transfer.
By the way, {recruited_fullname} has already been diligent and has successfully recommended more customers.
Now all {recruited_firstname} has to do is make their purchase within 7 days so you will get paid!
Thank you for the support, we hope you will successfully find new customers for us and your passive income."

  ],





/**
 * #9
 * funds level upgrade
 *
 * params:
 *   fullname,
 *   recruited_fullname,
 *   video_link
 *
 */
  'funds_level_upgrade' => [

    'subject' => 'The next step to your personal fortune ',

    'body' => "Dear {fullname},

CONGRATULATIONS! You made it.
By recommending your second customer you have become a premium customer of Betterliving.
You will receive € 4.00 for {recruited_fullname´s}. purchase.
From now on you will receive {adv2amount} Dollar for every future customer.
So what do you need to know now?
You know you have received {adv1amount} Dollar for the first two customers.
Betterliving always pays a commission of {adv2amount} Dollar for each customer,
so as a premium costumer you will always get the difference of {advindirectamount} Dollar
for the first two customers of your recommended friends.
If you take a look at 2:50 in the video
{video_link}
you can replace “Jivan” with {recruited_fullname}.
If you want to make sure you make a lot of money in a short time, here’s how it works:
Well firstly, you can always go on finding new customers.
You will always receive {adv2amount} Dollar each.
Secondly, and this is even more interesting, show all your recommended customers how to become premium costumers as well.
The video
{video_link}
shows what will happen when {recruited_fullname} finds two friends who also recommend two others at 4:00.
After six weeks you will find a total amount of {after6weeksamount} Dollar in your bank account.

In order to make lots of money, all you have to do is:
  1. Get three friends to join Betterliving and get the Happiness Guide, so you’ll become a premium costumer
  2. Show them how to be premium costumers as well
  3. Find more friends who purchase the Happiness Guide.

With every person you help becoming a premium costumer you multiply your commission.
Your friends will have a great income being premium costumers and you have
done well in making a fortune for you and your family.
Good luck!"

  ],






  ];

?>