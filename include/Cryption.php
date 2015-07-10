<?php

namespace Tbmt;

class Cryption {

  const ALGORITHM = 'sha1';

  static private $salt = 'oFBt9r10L623QAFGy9qo';

  /**
   * Returns the password hash encrypted using the specified salt.
   *
   * Do not use this function directly unless you know what you are doing.
   * Use {@link getPasswordHash()} and {@link verifyPassword()} instead.
   *
   * @return string The hexadecimal representation of the hash.
   * @see getPasswordHash()
   */
  static public function encryptPassword($password, $salt) {
    return hash_hmac(self::ALGORITHM, $password, $salt.':'.self::$salt);
  }

  /**
   * Returns a JSON string encoding the encrypted password and the used salt.
   *
   * @param string $password The password to encrypt.
   * @param string $salt Optional. The salt to use.
   * @return string
   * @see encryptPassword()
   * @see verifyPassword()
   */
  static public function getPasswordHash($password, $salt = null) {
    if ( $salt === null )
      $salt = bin2hex(mcrypt_create_iv(8, MCRYPT_DEV_URANDOM)).time();

    $x = json_encode(array($salt, self::encryptPassword($password, $salt)));
    //throw new Exception($x);
    return $x;
  }

  /**
   * Compares the supplied password against the hash.
   * This is the counterpart to {@link getPasswordHash()}.
   *
   * @param string $passwordHash The JSON-encoded password hash with its salt.
   * @return bool Returns TRUE if the password matches the hash, otherwise
   *     FALSE.
   * @see getPasswordHash()
   */
  static public function verifyPassword($password, $passwordHash) {
    $hashData = json_decode($passwordHash, true);
    if ( !isset($hashData[0], $hashData[1]) or !is_array($hashData) )
      throw new Exception('Invalid password hash found in database.');

    $salt     = $hashData[0];
    $expected = $hashData[1];

    return ( self::encryptPassword($password, $salt) === $expected );
  }
}

?>