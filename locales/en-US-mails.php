<?php

return [



    'transfer_failed' => [
      'subject' => 'IMPORTANT - Transfer failed! Please check your account data.',
      'body' => "Hello {fullname},

unfortunately we could not transfer your bonus. Our transfer service returned the
following error:

----------------
{transfer_error}
----------------

But you do not need to worry. It is not lost!
Just go to your account and double check your account data.
After that we will try to transfer your rewards again.

If your account data are correct than just contact us. We will take care
of that!
",
    ],




    'password_reset' => [
      'subject' => 'Password reset',
      'body' => "You have lost your $brandName password. Sorry about that!
But don’t worry! You can use the following link within the next day to reset your password:
{link}
Note: Link expires within 24 hours.",
    ],




    'email_validation' => [
      'subject' => 'Registration Email Validation',
      'body' => "Hello {fullname},
thank you for signing up to $brandName!
Just open the following link in your favourite web browser to complete your registration:
{link}
Note: Link expires within 24 hours.",
    ],





/**
 * #1_1
 * Der neue Spender erhält eine umfassende Begrüßungsemail, diese mail bezieht
 * sich auf kostenlose einladungen.
 *
 * params:
 *   fullname,
 *   member_id,
 *   member_type,
 *   referrer_fullname,
 *   video_link,
 *   signup_link,
 *   after6weeksamount
 *
 */
  'free_signup_confirm' => [

    'subject' => 'Welcome to Betterliving',

    'body' => "Dear {fullname},

Welcome to Betterliving – You’re now registered at Betterliving.social!
{referrer_fullname} recommended you and wants you to profit from Betterliving.
We’re happy you followed his recommendation.

You were invited as a special guest!
You are \"{member_type}\". So you can earn even more than normal customers!

Most probably {referrer_fullname} has already told you it is possible to earn money with Betterliving.
Please watch the video
{video_link}
for more information. As soon as you recommend friends to Betterliving you
will receive commissions from Betterliving.
From your third recommendation on, the commission will go up to +400%.
The video will show you how high your income can become.
Let it surprise you and let all your friends know about this opportunity.
What would you do if you and your friends owned commissions of more than {after6weeksamount} Dollar?
If you have any idea about how you would spend money and you
grudge your friends the same, make sure you tell them about Betterliving.
Just share the video
{video_link}
and let them know you are on board as well.
Don’t forget to give them your personal costumer number \"{member_id}\".
Because you can only be identified and get commissions if they will fill in
this costumer number when they are signing up for Betterliving.
So what are you waiting for?
Enjoy the opportunity of making a fortune while being social!
Maybe you don’t know how to let your friends know about this.
In this case just copy the following and send it to all your best friends via email:
__________________________________________
Dear friend,
a short time ago I was told about the opportunity to make money by using the Happiness Guide.
It’s super easy and just genius.
I would like to let you know about this opportunity and how you can get a lot of money as well.
Just watch this video
http://betterliving.social?mod=projects&act=index#video_explanation
for me. I have already donated to Betterliving and I’d be happy if you did so, too.
In order to join, just click here:
{signup_link}
___________________________________________

We wish you the very best and hope you’ll get rich soon!"

  ],


/**
 * #1
 * Der neue Spender erhält eine umfassende Begrüßungsemail mit Anweisungen, wohin er welchen Betrag bis wann überweisen soll.
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

    'body' => "Dear {fullname}

Welcome to Betterliving. {recruiter} recommended you and wants you to profit from Betterliving.
We’re happy you followed {recruiter}’s recommendation.
In order to make you a part of Betterliving and to offer you all opportunities it is necessary for you to purchase the Happiness Guide.
Please transfer {fmt_member_fee} to the following bank account until {duedate}:

{bankaccount}

Always indicate your personal customer number \"{member_id}\" as intended purpose for the transfer.
As soon as we have received your payment, you are going to get to know
helpful secrets about how to make a fortune with $brandName."
  ],





/**
 * #2_1
 * Der Tippgeber dieses neuen Spenders erhält auch eine Email, diese mail bezieht
 * sich auf kostenlose einladungen.
 *
 * params:
 *   fullname,
 *   member_id,
 *   recruited_fullname,
 *   video_link,
 *
 */
  'new_free_recruitment_congrats' => [

    'subject' => 'The next step to make a fortune with Betterliving',

    'body' => "Dear {fullname}

Congratulations on your successful invitation!
We have been informed that you invited {recruited_fullname} to Betterliving.
{recruited_fullname} has successfully signed up.
We wish you the best at finding new customers for Betterliving.
Just share the video
{video_link}
with your friends and tell everybody about the great opportunity of making money by being social.
It’s enough if every customer recommends three more customers.
You can show {recruited_fullname} how easy it is.
That way you can support each other in making a fortune.
Take good care that your friends use your costumer number \"{member_id}\"
when they are signing up."

  ],





/**
 * #2
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

    'body' => "Dear {fullname}

Congratulations on your successful {recommendation_count} recommendation!
We have been informed that you invited {recruited_fullname} to Betterliving.
{recruited_fullname} has successfully signed up for Betterliving.
Most probably you have already told him to purchase the Happiness Guide within 7 days, until the {duedate}.
This is important for you as you will get commissions for their purchase.
If possible, please ask him whether the donation has already been made.
For sure you have seen the video
{video_link}
about how to make a fortune with Betterliving.
It’s enough if every customer recommends three more customers.
You can show {recruited_fullname} how easy it is.
That way you can support each other in making a fortune.
We wish you the best at finding new customers for the Happiness Guide.
Just share the video
{video_link}
with your friends and tell everybody about the great opportunity of making money by being social.
Take good care that your friends use your customer number \"{member_id}\"
when they are signing up."

  ],





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
 * Der neue registrierte Spender erhält eine Email, in der das Datum seiner Registrierung genannt wird und wir bis dato keinen Geldeingang verzeichnen konnten. Dann wird er daran erinnert, sein Kauf unter Angabe seiner ID spätestens innerhalb von sieben Tagen zu leisten. Hier wird noch einmal kurz auf die tollen Projekte der NGO und recht ausführlich auf die Verdienstmöglichkeit für ihn und seine Freunde, Bekannten und Verwandte hingewiesen.
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
 * #7
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

    'body' => "Dear {fullname},

thanks for purchasing the Happiness Guide!
Now here is our secret which maybe isn’t a secret anymore for you:

Maybe {referrer_fullname} has already told you it is possible to earn money with Betterliving.
Please watch the video
{video_link}
for more information. As soon as you recommend two friends to Betterliving you
will receive commissions from Betterliving.
From your third recommendation on the commission will go up to +400%.
The video will show you how high your income can be.
Let it surprise you and let all your friends know about this opportunity.
What would you do if you and your friends owned commissions of more than {after6weeksamount} dollar?
If you have any idea about how you would spend money and you
grudge your friends the same, make sure you tell them about Betterliving.
Just share the video
{video_link}
and let them know you are on board as well.
Don’t forget to give them your personal customer number \"{member_id}\".
Because you can only be identified and get commissions if they fill in
your number when they are signing up for Betterliving.
So what are you waiting for?
Enjoy the opportunity of making a fortune!
Maybe you don’t know how to let your friends know about this.
In this case just copy the following and send it to all your best friends via email:
__________________________________________
Dear friend,
a short time ago I was told about the opportunity to make money by purchasing the Happiness Guide.
It’s super easy and just genius.
I would like to let you know about this opportunity and how you can make a lot of money as well.
Just watch this video
{video_link}
for me. I have already purchased it and I’d be happy if you did so, too.
In order to join, just click here:
{signup_link}
___________________________________________

We wish you the very best and hope you’ll be rich soon!"

  ],





/**
 * #8
 * Der Tippgeber dieses Spenders erhält auch eine Email. Hiermit wird er informiert, dass seine Empfehlung (namentlich genannt) ihre Spende geleistet hat und er einen Betrag in Höhe von …. INR in der nächsten Abrechnung hierfür vergütet erhält. Zudem wird ihm zum …ten erfolgreichen Spender gratuliert und das weitere entsprechende Szenarium aufgezeigt.
 *
 * params:
 *   fullname,
 *   recruited_fullname,
 *   recruited_firstname,
 *   video_link,
 *   memberfee_amount,
 *   member_id
 *
 *
 *  TODO
 */
  'fee_income_referrer' => [

    'subject' => 'The next step to your personal fortune ',

    'body' => "Dear {fullname},

your recommended customer {recruited_fullname} has purchased the Happiness Guide.
Thank you for sharing the opportunity with {recruited_firstname}.
You will get a commission for {recruited_firstname}’s purchase.
You will receive {provision_amount} Dollar for {recruited_firstname}’s purchase.",

    'level1_addition' =>
"You want to earn even more? Make sure you find three friends who join Betterliving.
This will make you a premium customer. That means your commission will go up +400% to {adv2amount} Dollar!
This is proved in the explanation video
{video_link}
at 4:09.
You are going to see that in your upcoming invoice as well.
From the third recommended customer who signs up using your personal customer number \"{member_id}\"
and purchases the Happiness Guide the door to your private wellbeing will be wide open.
We’re wishing best of luck finding new customers and have fun spending the commissions you will receive."

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
From now on you will receive {adv2amount} Dollar for every future customer.
So what do you need to know now?
You know you have received {adv1amount} Dollar for the first two customers.
Betterliving always pays a commission of {adv2amount} Dollar for each customer,
so as a premium costumer you will always get the difference of {advindirectamount} Dollar
for the first two customers of your recommended friends.
If you take a look at 4:22 – 4:33 in the video
{video_link}
you can replace “Jivan” with {recruited_fullname}.
If you want to make sure you make a lot of money in a short time, here’s how it works:
Well firstly, you can always go on finding new customers.
You will always receive {adv2amount} Dollar each.
Secondly, and this is even more interesting, show all your recommended customers how to become premium costumers as well.
The video
{video_link}
shows what will happen when {recruited_fullname} finds two friends who also recommend two others at 4:40.
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