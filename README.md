# bta_password_hash
[Piwigo](https://piwigo.org/) plugin to change the password hashing to use the PHP native password hash API.  This is more secure than the [*Portable PHP password hashing framework*](https://www.openwall.com/phpass/) used in Piwigo by default since version 2.5.  This also allow "sharing" password hashes between applications that use bcrypt hashes. 

## Requirements

  * PHP version 5 (>= 5.5.0), PHP 7, or PHP 8.
  * Piwigo version 2.5+

## Installation
Download plugin code and copy to Piwigo's plugin directory in the **bta_password_hash** sub-directory.  Go to the Piwigo's pluging admin page and activate the **_BTA Password Hash_** plugin.  That's all.  Password hashes will be replaced in the database as users log-in. 
## Details
Hashes a password with the PHP *password_hash* function. 
Verifies a password, with the PHP *pass_verify* function.
 If the hash is 'old' (assumed MD5) or Piwigo hashing ($P$) the hash is updated in database using the Blowfish cipher [(bcrypt)](https://en.wikipedia.org/wiki/Bcrypt) algorithm with hashes prefixed with "$2y$".