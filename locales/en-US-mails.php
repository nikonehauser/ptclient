<?php

return [



    'password_reset' => [
      'subject' => 'Password reset',
      'body' => "You have lost your $brandName password. Sorry about that!
But don’t worry! You can use the following link within the next day to reset your password:
{link}
If you don’t use this link within 24 hours, it will expire.",
    ],



    'email_validation' => [
      'subject' => 'Registration Email Validation',
      'body' => "Hello {fullname},
thank you for signing up for $brandName!
Just open the following link in your favourite web browser to complete your registration:
{link}",
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
In order to make you a part of Betterliving and to offer you all opportunities it is necessary for you to donate.
Please transfer your donation of {fmt_member_fee} to the following bank account until {duedate}:

{bankaccount}

Always indicate your personal ID {member_id} as intended purpose for the transfer.
As soon as we have received your donation we are going to tell
you some helpful secrets about how to make a fortune with $brandName."
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
Most probably you have already told him to transfer their donation within 7 days, until the {duedate}.
This is important for you as you will get commissions for their donation.
If possible, please ask him whether the donation has already been made.
For sure you have seen the video
{video_link}
about how to make a fortune with Betterliving.
It’s enough if every donator recommends three more donators.
You can show {recruited_fullname} how easy it is.
That way you can support each other in making a fortune.
We wish you the best at finding new donators for Betterliving.
Just share the video
{video_link}
with your friends and tell everybody about the great opportunity of making money by being social.
Take good care that your friends use your ID {member_id}
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
after you successfully signed up to be a donator on www.betterliving.soical.
Unfortunately we did not receive your donation within the deadline of 7 days.
If the donation has already been transferred, this email can be ignored.
In case you haven’t been able to donate because of personal issues
you can do so as soon as possible.
Please make sure you will have donated by {duedate_second}.
These are our bank account details:

{bankaccount}

Always indicate your personal ID {member_id} as intended purpose for the transfer.
Please keep in mind that we are in need of your donation in order to carry out
social projects for the neediest in this country and on top you will
be able to revalue your own life!
Because if you are a donator and recommend three others,
you can receive monthly payments of several million Rupees
using our unique marketing system.
It’s not only your life that will be a lot better and more comfortable in the future.
Just offer everyone you know and love, say your family and friends,
the opportunity to make a donation and to profit from our unbelievable
marketing system by only making three recommendations.
In this video
{video_link}
for you and your loved ones our marketing system is explained.
We hope you won’t miss your chance to make your life a lot easier than
before by making a small donation and recommending Betterliving
to only three people who do the same.
Us, the team of Betterliving, thank you for your donation in advance
and wish you a carefree and pleasant future life with your high passive income of Betterliving."

  ],





/**
 * #4
 * Der neue registrierte Spender erhält eine Email, in der das Datum seiner Registrierung genannt wird und wir bis dato keinen Geldeingang verzeichnen konnten. Dann wird er daran erinnert, sein Spende unter Angabe seiner ID spätestens innerhalb von sieben Tagen zu leisten. Hier wird noch einmal kurz auf die tollen Projekte der NGO und recht ausführlich auf die Verdienstmöglichkeit für ihn und seine Freunde, Bekannten und Verwandte hingewiesen.
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

    'subject' => 'Your recommended donator {recruited_fullname} has not made their donation yet',

    'body' => "Dear {fullname},

on {recruited_signup_date} {recruited_fullname} successfully signed up on
www.betterliving.social because of your recommendation.

Unfortunately {recruited_fullname} has not transferred their donation yet.
Please get in touch with {recruited_fullname}. Maybe {recruited_fullname} didn’t
get our messages or there are still questions we definitely have answers for.
Possibly {recruited_firstname} has already made their donation but it just has not
entered our bank account yet, in this case everything is fine.
If this is not the case please make sure {recruited_firstname} will donate as soon as possible.
Here are our account details:

{bankaccount}

The personal ID {member_id} should always be given as intended purpose for the transfer.
Thank you for the support, we hope you will successfully find new donators for us and your passive income."

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
after you successfully signed up to be a donator on www.betterliving.soical.
Unfortunately we did not receive your donation within the deadline of 7 days.
If the donation has already been transferred, this email can be ignored.
In case you haven’t been able to donate because of personal issues
you can do so as soon as possible.
Please make sure you will have donated by {duedate_second}.
Here are our bank account details:

{bankaccount}

Always indicate your personal ID {member_id} as intended
purpose for the transfer.

Here is some very important information for you:
If you miss out on donating within the deadline listed above,
you cannot receive commissions for your recommended donators.
That’s not it. You can see in our explanation video at 4:25 what happens
if you are a prime partner of Betterliving. Being a prime partner is easy;
all you have to do is recommend three donators.
From then on you will receive a commission of 1200 Rupees
for the first two donators of all your future recommendations.
As you can see in the video, this can rise up to be a pretty high income in a short time.
Don’t miss your chance to make a lot of money by forgetting to donate.
So please transfer your donation for Betterliving as soon as you can!

Keep in mind that we are in need of your donation in order to carry out social
projects for the neediest in this country and on top you will be able to revalue your own life!

It’s not only your life which will be a lot better and more comfortable in the future.
Just offer everyone you know and love, say your family and friends,
the opportunity to make a donation and to profit from our
unbelievable marketing system by only making three recommendations.
In this video
{video_link}
for you and your loved ones our marketing system is explained.
We hope you don’t miss your chance to make your life a lot easier than before
by making a small donation and recommending Betterliving to only three people who do the same.
Us, the team of Betterliving, thank you for your donation in advance
and wish you a carefree and pleasant future life with your high passive income of Betterliving."

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
  'fee_reminder_with_advertisings' => [

    'subject' => 'Your recommended donator {recruited_fullname} has not made their donation',

    'body' => "Dear {fullname},

on {recruited_signup_date} {recruited_fullname} successfully signed up
on www.betterliving.social after your recommendation.
Unfortunately {recruited_firstname} has not transferred their donation yet.
Please get in touch with {recruited_firstname}.
Maybe {recruited_firstname} didn’t get our messages or there are still questions we definitely have an answer for.
Possibly {recruited_firstname} has already made their donation
but it just has not entered our bank account yet, in this case everything is fine.
If this is not the case please make sure {recruited_firstname} will donate as soon as possible.
Here are our account details:

{bankaccount}

The personal ID {member_id} should always be given as intended purpose for the transfer.
By the way, {recruited_fullname} has already been diligent and has successfully recommended more donators.
Now all {recruited_firstname} has to do is make their donation within 7 days so you will get payed!
Thank you for the support, we hope you will successfully find new donators for us and your passive income."

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
 *   signup_link
 *
 */
  'fee_income' => [

    'subject' => 'The secret of Betterliving',

    'body' => "Dear {fullname},

today we have received your donation, thank you!
You are helping our co-operating NGOs in India in order to carry out social projects for Indian people.
You will be informed about several projects in the next weeks and months.
If you would like to suggest a project, please let us know at projects@betterliving.social.
Now here is our secret which maybe isn’t a secret anymore for you:

Maybe {referrer_fullname} has already told you it is possible to earn money with Betterliving.
Please watch the video
{video_link}
for more information. As soon as you recommend two friends to Betterliving you
will receive commissions from Betterliving.
From your third recommendation on the commission will go up to +400%.
The video will show you how high your income can be.
Let it surprise you and let all your friends know about this opportunity.
What would you do if you and your friends owned commissions of more than 300000 Rupees?
And you will not only have a lot more money than you do now;
your donation makes many social projects possible which benefit Indian citizens.
If you have any idea about how you would spend money and you
grudge your friends the same, make sure you tell them about Betterliving.
Just share the video
{video_link}
and let them know you are on board as well.
Don’t forget to give them your personal ID {member_id}.
Because you can only be identified and get commissions if they fill in
your ID when they are signing up to Betterliving.
So what are you waiting for?
Enjoy the opportunity of making a fortune by being social!
Maybe you don’t know how to let your friends know about this.
In this case just copy the following and send it to all your best friends via email:
__________________________________________
Dear friend,
a short time ago I was told about the opportunity to make money by collecting donations for social projects.
It’s super easy and just genius.
I would like to let you know about this opportunity and how you can get a lot of money as well.
Just watch this video
{video_link}
for me. I have already donated to Betterliving and I’d be happy if you did so, too.
In order to join, just click here:
{signup_link}
___________________________________________

We wish you the very best and hope you’ll get rich soon!"

  ],





/**
 * #8
 * Der Tippgeber dieses Spenders erhält auch eine Email. Hiermit wird er informiert, dass seine Empfehlung (namentlich genannt) ihre Spende geleistet hat und er einen Betrag in Höhe von …. INR in der nächsten Abrechnung hierfür vergütet erhält. Zudem wird ihm zum …ten erfolgreichen Spender gratuliert und das weitere entsprechende Szenarium aufgezeigt.
 *
 * params:
 *   fullname,
 *   recruited_fullname,
 *   recruited_firstname,
 *   video_link
 *
 *
 *  TODO
 */
  'fee_income_referrer' => [

    'subject' => 'The next step to your personal fortune ',

    'body' => "Dear {fullname},

your recommended donator {recruited_fullname} has made a donation of 5500 Rupees.
Thank you for sharing the opportunity with {recruited_firstname}.
Now Betterliving can carry out beneficial projects in India.
Your advantage is great as well!
You should know by now that you will get a commission for {recruited_firstname}’s donation.
You will receive {provision_amount} Rupees for {recruited_firstname}’s donation.
You want to earn even more? Make sure you find three friends who join Betterliving.
This will make you a prime member. That means your commission will go up +400% to 1500 Rupees!
This is proved in the explanation video
{video_link}
at 4:09.
You are going see that in your upcoming invoice as well.
From the third recommended donator who signs up using your personal ID
and donates within the deadline the door to your private wellbeing will be wide open.
We’re wishing best of luck finding new donators and have fun spending the commissions you will receive. "

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
By recommending your second donator you have become a prime partner of Betterliving.
From now on you will receive 1500 Rupees for every future donator.
So what do you need to know now?
You know you have received 300 Rupees for the first two donators.
Betterliving always pays a commission of 1500 Rupees for each donator,
so as a prime partner you will always get the difference of 1200 Rupees
for the first two donators of your recommended friends.
If you take a look at 4:22 – 4:33 in the video
{video_link}
you can replace “Jivan” with {recruited_fullname}.
If you want to make sure you make a lot of money in a short time, here’s how it works:
Well firstly, you can always go on finding new donators.
You will always receive 1500 Rupees each.
Secondly, and this is way more interesting, show all your recommended donators how to become prime partners as well.
The video
{video_link}
shows what will happen when {recruited_fullname} finds two friends who also recommend two others at 4:40.
After six weeks you will find a total amount of 300000 Rupees in your bank account.

So in order to make lots of money, all you have to do is:
  1. Get three friends to join Betterliving, so you’ll become a prime partner
  2. Show them how to be prime partners as well
  3. Find more friends who donate to Betterliving

With every person you help becoming a prime partner you multiply your commission.
On top of that Betterliving will be able to carry out many social projects with their partners in India
which will benefit Indian citizens.
Your friends will have a great income being prime partners and you have
done well in making a fortune for you and your family.
Good luck!"

  ],






  ];

?>