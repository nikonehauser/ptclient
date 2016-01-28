<?php

return [



    'password_reset' => [
      'subject' => 'Password reset',
      'body' => "You have lost your $webPageName password. Sorry about that!
But don’t worry! You can use the following link within the next day to reset your password:
{link}
If you don’t use this link within 24 hours, it will expire.",
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
 *   bankaccount
 *
 *
 */
  'signup_confirm' => [

    'subject' => 'Welcome to Betterliving',

    'body' => "Dear {fullname}

Welcome to Betterliving. {recruiter} recommended you and wants you to profit from Betterliving.
We’re happy you followed {recruiter}’s recommendation.
In order to make you a part of Betterliving and to offer you all opportunities it is necessary for you to donate.
Please transfer your donation of {fmt_member_fee} to the following bank account until (Date + 7 days):

{bankaccount}

Always indicate your personal ID {member_id} as intended purpose for the transfer.
As soon as we have received your donation we are going to tell
you some helpful secrets about how to make a fortune with Better Living."
  ],





/**
 * #2
 * Der Tippgeber dieses neuen Spenders erhält auch eine Email. Hier wird er über diese Registrierung namentlich informiert. Zudem wird ihm zur …ten erfolgreichen Registrierung gratuliert und das weitere entsprechende Szenarium aufgezeigt.
 *
 * params:
 *   fullname,
 *   member_id,
 *   recruiter,
 *   fmt_member_fee,
 *   bankaccount
 *
 *
 */
  'new_recruitment_congrats' => [

    'subject' => 'A further step to make a fortune with Betterliving ',

    'body' => "Dear {fullname}

Congratulations on your successful {recommendation_count} recommendation!
We have been informed that you invited {recruited_fullname} to Betterliving.
{recruited_fullname} has successfully signed up for Betterliving.
Most probably you have already told him to transfer their donation within 7 days, until the (date + 7 days).
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






  ];

?>