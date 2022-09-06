<?php
/*
Plugin Name: BTA Password Hash
Version: 1.0.0
Description: Replace the default password hashing functions with PHP native functions.
Plugin URI: http://piwigo.org
Author: zenga11
Author URI: https://businesstechnologyassociates.com
*/

defined('PHPWG_ROOT_PATH') or die('Hacking attempt!');

define('BTA_PASSWORD_HASH_ID',      basename(dirname(__FILE__)));
define('BTA_PASSWORD_HASH_PATH' ,   PHPWG_PLUGINS_PATH . BTA_PASSWORD_HASH_ID . '/');

// Change config to use PHP password hash functions. 
global $conf; 
$conf['password_hash'] = 'bta_password_hash';
$conf['password_verify'] = 'bta_password_verify';

/**
 * Hashes a password with the PHP password_hash function
 * in PHP versions PHP 5 >= 5.5.0, PHP 7, PHP 8
 *
 * @param string $password plain text
 * @return string
 */
function bta_password_hash($password)
{

  return password_hash($password, PASSWORD_BCRYPT);
}


/**
 * Verifies a password, with the PHP pass_verify function.
 * If the hash is 'old' (assumed MD5) the hash is updated in database, used for
 * migration from Piwigo 2.4.
 * @since 2.5
 *
 * @param string $password plain text
 * @param string $hash may be md5, $P$ or $2y$ PHP hashed password
 * @param integer $user_id only useful to update password hash from md5 to PHP hashing
 * @return bool
 */
function bta_password_verify($password, $hash, $user_id=null)
{
  global $conf, $pwg_hasher;

  // If the password has not been hashed with the current algorithm.
  if ( strpos($hash, '$2y$') === 0 )
  {
  	return password_verify($password, $hash);
  }
  elseif ( strpos($hash, '$P$') === 0 ) 
  {
  	  if (empty($pwg_hasher))
	  {
	    require_once(PHPWG_ROOT_PATH.'include/passwordhash.class.php');
	
	    // We use the portable hash feature
	    $pwg_hasher = new PasswordHash(13, true);
	  }
	
	  $check = $pwg_hasher->CheckPassword($password, $hash);
  }
  else
  {
	$check = ( md5($password) == $hash);
  }

  if ($check)
	{
	  if (!isset($user_id) or $conf['external_authentification'])
	  {
	    return true;
	  }
	
	  // Rehash using new hash.
	  $hash = bta_password_hash($password);
	
	  single_update(
	    USERS_TABLE,
	    array('password' => $hash),
	    array('id' => $user_id)
	    );
	}

 return $check;

}


?>