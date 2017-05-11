<?php

namespace Tbmt;

$loginMessage = '';
$resetMessage = '';
$resetMailMessage = '';

function renderSimpleLogin() {
  global $loginMessage, $resetMessage, $resetMailMessage;

  $params = array_merge([
    'basePath'    => '',
    'windowtitle' => '',
    'loginMessage' => $loginMessage,
    'resetMessage' => $resetMessage,
    'resetMailMessage' => $resetMailMessage,
  ]);

  echo (new view\Login())->render($params);
  exit();
}

Session::start();
$isAllowed = false;
$login = Session::getLogin();
$nonce = isset($_REQUEST['nonce']) ? $_REQUEST['nonce'] : null;

if ( $nonce ) {
  /* =============================================================== */
  /* Check for valid nonce redirection
  ================================================================ */
  $nonce = \NonceQuery::create()->findOneByNonce($nonce);
  if ( !$nonce ) {
    // nonce exists but is not valid -> exit
    renderSimpleLogin();
    exit();
  }

  $now = time();
  if ( $now > $nonce->getDate() ) {
    // nonce is to old -> exit
    renderSimpleLogin();
    exit();
  }

  // terminate current session if exists
  $login = Session::getLogin();
  if ( $login ) {
    Session::terminate();
    Session::start();
  }

  // Login user of the nonce
  Session::setLogin($nonce->getMember());
  $nonce->delete();

  // redirect to account
  header('Location: '.Router::toModule('account'));

} else if ( $login ) {
  // Valid login exists
  $isAllowed = true;

} else if ( isset($_REQUEST['CoPOEStGHS0EAJP5ijX7'])
            && $_REQUEST['CoPOEStGHS0EAJP5ijX7'] === 'true' ) {
  /* =============================================================== */
  /* login form
  ================================================================ */
  $member = Session::login($_REQUEST['name'], $_REQUEST['password']);
  if ( $member && $member->isExtended() )
    $isAllowed = true;
  else {
    $loginMessage = 'Invalid login data';
  }

} else if ( isset($_REQUEST['mod'], $_REQUEST['act'], $_REQUEST['hash'])
            && $_REQUEST['mod'] === 'member'
            && $_REQUEST['act'] === 'confirm_email_registration' ) {
  /* =============================================================== */
  /* reset password form
  ================================================================ */

  $emailValidation = \EmailValidation::validateHash($_REQUEST['hash']);
  if ( $emailValidation )
    $isAllowed = true;

} else if ( isset($_REQUEST['MQYmYS5YFuhGQOXJxtHh'])
            && $_REQUEST['MQYmYS5YFuhGQOXJxtHh'] === 'true' ) {
  /* =============================================================== */
  /* reset password form
  ================================================================ */

  $member = \MemberQuery::create()
    ->filterByDeletionDate(null, \Criteria::ISNULL)
    ->findOneByEmail(trim($_REQUEST['email']));
  if ( $member && $member->isExtended() ) {
    MailHelper::sendPublicPasswordResetLink($member);
    $resetMailMessage = 'Success, check your mails!';
  } else {
    $resetMessage = 'Unknown email address!';
  }

} else if ( isset($_REQUEST['3591f374b308cb3932260b45d5709a4c'])
            && $_REQUEST['3591f374b308cb3932260b45d5709a4c'] === 'true' ) {

  /* =============================================================== */
  /* reset password link
  ================================================================ */
  $data = \Tbmt\Arr::initMulti($_REQUEST, [
    'num' => TYPE_STRING,
    'exp' => TYPE_STRING,
    'hash' => TYPE_STRING,
  ]);

  $newPassword = false;
  if ( !empty($data['num']) && !empty($data['exp']) && !empty($data['hash']) ) {
    $member = \Member::getByNum($data['num']);

    if ( $member &&
        $member->getNum() == $data['num'] &&
        Cryption::validatePasswordResetToken(
          $data['num'],
          $data['exp'],
          $member->getEmail(),
          $data['hash']
        ) &&
        intval($data['exp']) + 3600 * 24 >= time() ) {
      $newPassword = bin2hex(mcrypt_create_iv(8, MCRYPT_DEV_URANDOM));

      $member->setPassword($newPassword);
      $member->save();
    }
  }

  if ( $newPassword === false ) {
    echo '<center><h1 stlye="color:red;">Password reset link is invalid.</h1></center>';
  } else {
    $memberNum = $member->getNum();
    $loginLink = \Tbmt\Router::toModule('account');

echo <<<END
'<center>
  <h1 stlye="color:green;">Success! This is your new password:</h1>

  <p>$newPassword</p>
  <br>
  <br>
  <h3>Just Remember, this is your member number:</h3>
  <p>$memberNum</p>

  <br>
  <br>
  <a href="$loginLink">Go back to login form</a>
</center>'
END;
  }

  exit;

// confirm_email_registration&hash=0da8630becc89eef7c7953257e66dddb1483658024
} else if ( Session::hasValidToken() && Session::getLogin() ) {
  /* =============================================================== */
  /* Valid session with token exists
  ================================================================ */
  $isAllowed = true;
}

/* =============================================================== */
/* If token is given check for its validity
/* By INTENTION, this will override any previous valid token
================================================================ */
$token = isset($_REQUEST['tkn']) ? $_REQUEST['tkn'] : null;
if ( $token ) {
  $res = \Member::getByHash($token, false);
  if ( $res && $res instanceof \Member && $res->isExtended() ) {
    $isAllowed = true;
    Session::setValidToken($token);
  }
}

if ( $isAllowed !== true )  {
  renderSimpleLogin();
  exit();
}

