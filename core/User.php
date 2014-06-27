<?php
  
  final class User {
    
    /**
     * Username length requirements. If the field length in the table changes,
     * the maximum should change to match the length of the field.
     **/
    const USERNAME_LENGTH_MINIMUM = 3;
    const USERNAME_LENGTH_MAXIMUM = 31;
    
    /**
     * Characters allowed to be in a username. This string is given
     * directly to php's preg_match() function. Be mindful that this
     * string may be displayed in more than just page content.
     **/
    const USERNAME_ALLOWED_CHARACTERS = "/^[A-Za-z0-9()}{\\[\\]._\\-]+$/s";
    
    /**
     * Characters allowed to be in a display name. This string is given
     * directly to php's preg_match() function. Be mindful that this
     * string may be displayed in more than just page content.
     **/
    const DISPLAYNAME_ALLOWED_CHARACTERS = "/^[ A-Za-z0-9()}{\\[\\]._\\-]+$/s";
    
    /**
     * The hashing algorithm to use. Ensure the users table in the
     * database allows for the exact length that the algorithm gives.
     * (ex.: sha256 yields 256 bits which means a binary(32) field.)
     **/
    const PASSWORD_HASH_ALGORITHM = PASSWORD_BCRYPT;

    /**
     * Cost of hashing, currently bcrypt
     **/
    const PASSWORD_HASH_COST = 10;
    
    /**
     * Password length requirements are irrespective of the database
     * design, since passwords are salted hashes.
     **/
    const PASSWORD_LENGTH_MINIMUM = 6;
    const PASSWORD_LENGTH_MAXIMUM = 48;
    
    /**
     * Password strength requirements. If these are all set to false,
     * then there are NO requirements. If these are all set to true,
     * then there are VERY STRICT requirements.
     *
     * PASSWORD_REQUIRES_SYMBOLS is given directly to php's preg_match().
     **/
    const PASSWORD_CANNOT_CONTAIN_USERNAME    = true;
    const PASSWORD_CANNOT_CONTAIN_DISPLAYNAME = true;
    const PASSWORD_CANNOT_CONTAIN_EMAIL       = true;
    const PASSWORD_REQUIRES_UPPERCASE_LETTERS = true;
    const PASSWORD_REQUIRES_LOWERCASE_LETTERS = true;
    const PASSWORD_REQUIRES_NUMBERS           = true;
    const PASSWORD_REQUIRES_SYMBOLS           = false;
    /*const PASSWORD_REQUIRES_SYMBOLS           = "/['\":;^£$%&*()}{\\[\\]@#~\\?><>,.\\/|=_+¬\\-]/";*/
    
    /**
     * User status bitfields.
     **/
    const STATUS_DISABLED_BY_SYSTEM          =     1; // System disabled the account.
    const STATUS_DISABLED_BY_STAFF           =     2; // A staff member disabled the account.
    const STATUS_DISABLED_BY_SELF            =     4; // The account owner disabled their own account.
    const STATUS_ACL_DOCUMENTS_READ          =     8; // Allows viewing documents.
    const STATUS_ACL_DOCUMENTS_WRITE         =    16; // Allows creating and modifying documents.
    const STATUS_ACL_NEWS_READ               =    32; // Allows viewing news posts.
    const STATUS_ACL_NEWS_WRITE              =    64; // Allows creating and modifying news posts.
    const STATUS_ACL_PACKETS_READ            =   128; // Allows viewing packets.
    const STATUS_ACL_PACKETS_WRITE           =   256; // Allows creating and modifying packets.
    const STATUS_ACL_SERVERS_READ            =   512; // Allows viewing servers.
    const STATUS_ACL_SERVERS_WRITE           =  1024; // Allows creating and modifying servers.
    const STATUS_ACL_LOGS_READ               =  2048; // Allows viewing logs.
    const STATUS_ACL_LOGS_WRITE              =  4096; // Allows creating and modifying logs.
    const STATUS_ACL_USERS_READ              =  8192; // Allows viewing private user data.
    const STATUS_ACL_USERS_WRITE             = 16384; // Allows creating and modifying private user data.
    
    /**
     * SQL column names for this object. Used in constructing new object.
     **/
    protected static $SQL_COLUMN_NAMES = [
      'uid',
      'email',
      'username',
      'display_name',
      // password_hash is excluded because it's a little more complex.
      'status',
      'registered_date',
      'verified_date',
      'verified_id',
    ];
    
    /**
     * Internal class variables used for storing info.
     **/
    private $oUserSession;
    private $iUId;
    private $sEmail;
    private $sUsername;
    private $sDisplayName;
    private $sPasswordHash;
    private $iStatus;
    private $sRegisteredDate;
    private $mVerifiedDate;
    private $iVerifiedId;
    
    public static function fFindUsersByEmail($sEmail) {
      if (!is_string($sEmail))
        throw new Exception('Email address is not of type string');
      $sQuery = 'SELECT `' . implode('`,`', self::$SQL_COLUMN_NAMES) . '`,'
          . '`password_hash` AS `password_hash` FROM `users` WHERE `email` = \''
        . BNETDocs::$oDB->fEscapeValue($sEmail)
        . '\' ORDER BY `uid` ASC;';
      $oSQLResult = BNETDocs::$oDB->fQuery($sQuery);
      if (!$oSQLResult || !($oSQLResult instanceof SQLResult))
        throw new Exception('An SQL query error occurred while finding users by email');
      $aUsers = array();
      while ($oUser = $oSQLResult->fFetchObject()) {
        $aUsers[] = new self($oUser);
      }
      return $aUsers;
    }
    
    public static function fFindUserByUsername($sUsername) {
      if (!is_string($sUsername))
        throw new Exception('Username is not of type string');
      $sQuery = 'SELECT `' . implode('`,`', self::$SQL_COLUMN_NAMES) . '`,'
          . '`password_hash` AS `password_hash` FROM `users` WHERE `username` = \''
        . BNETDocs::$oDB->fEscapeValue($sUsername)
        . '\' LIMIT 1;';
      $oSQLResult = BNETDocs::$oDB->fQuery($sQuery);
      if (!$oSQLResult || !($oSQLResult instanceof SQLResult))
        throw new Exception('An SQL query error occurred while finding user by username');
      if ($oSQLResult->iNumRows != 1)
        return false;
      return new self($oSQLResult->fFetchObject());
    }
    
    public static function fFindUserByVerifiedId($iVerifiedId) {
      $sQuery = 'SELECT `' . implode('`,`', self::$SQL_COLUMN_NAMES) . '`,'
          . '`password_hash` AS `password_hash` FROM `users` WHERE `verified_id` = \''
        . BNETDocs::$oDB->fEscapeValue($iVerifiedId)
        . '\' LIMIT 1;';
      $oSQLResult = BNETDocs::$oDB->fQuery($sQuery);
      if (!$oSQLResult || !($oSQLResult instanceof SQLResult))
        throw new Exception('An SQL query error occurred while finding user by verified id');
      if ($oSQLResult->iNumRows != 1)
        return false;
      return new self($oSQLResult->fFetchObject());
    }
    
    public function __construct() {
      $aFuncArgs = func_get_args();
      $iFuncArgs = count($aFuncArgs);
      if ($iFuncArgs == 1 && (is_numeric($aFuncArgs[0]) || is_object($aFuncArgs[0]))) {
        if (!is_object($aFuncArgs[0])) {
          // Create User object by result object. Need to get it by user id.
          $iUId = $aFuncArgs[0];
          $sQuery = 'SELECT `' . implode('`,`', self::$SQL_COLUMN_NAMES) . '`,'
            . '`password_hash` AS `password_hash` FROM `users`'
            . ' WHERE `uid` = \'' . BNETDocs::$oDB->fEscapeValue($iUId)
            . '\' LIMIT 1;';
          $oSQLResult = BNETDocs::$oDB->fQuery($sQuery);
          if (!$oSQLResult || !($oSQLResult instanceof SQLResult))
            throw new Exception('An SQL query error occurred while retrieving user by id');
          if ($oSQLResult->iNumRows != 1)
            throw new RecoverableException('There is no user by that id');
          $oResult = $oSQLResult->fFetchObject();
        } else {
          // Create User object by result object. Object already gotten, no SQL query needed.
          $oResult = $aFuncArgs[0];
        }
        // CAUTION: May have to typecast here. Tried to avoid it by using fetch object.
        $this->oUserSession    = null;
        $this->iUId            = $oResult->uid;
        $this->sEmail          = $oResult->email;
        $this->sUsername       = $oResult->username;
        $this->sDisplayName    = $oResult->display_name;
        $this->sPasswordHash   = $oResult->password_hash;
        $this->iStatus         = $oResult->status;
        $this->sRegisteredDate = $oResult->registered_date;
        $this->mVerifiedDate   = $oResult->verified_date;
        $this->iVerifiedId     = $oResult->verified_id;
      } else if ($iFuncArgs == 8) {
        $this->oUserSession    = null;
        $this->iUId            = null;
        $this->sEmail          = (string)$aFuncArgs[0];
        $this->sUsername       = (string)$aFuncArgs[1];
        $this->sDisplayName    = (string)$aFuncArgs[2];
        $this->sPasswordHash   = (string)$aFuncArgs[3];
        $this->iStatus         = (int)$aFuncArgs[4];
        $this->sRegisteredDate = (string)$aFuncArgs[5];
        $this->mVerifiedDate   = $aFuncArgs[6];
        $this->iVerifiedId     = (string)$aFuncArgs[7];
      } else if ($iFuncArgs == 9) {
        $this->oUserSession    = null;
        $this->iUId            = (int)$aFuncArgs[0];
        $this->sEmail          = (string)$aFuncArgs[1];
        $this->sUsername       = (string)$aFuncArgs[2];
        $this->sDisplayName    = (string)$aFuncArgs[3];
        $this->sPasswordHash   = (string)$aFuncArgs[4];
        $this->iStatus         = (int)$aFuncArgs[5];
        $this->sRegisteredDate = (string)$aFuncArgs[6];
        $this->mVerifiedDate   = $aFuncArgs[7];
        $this->iVerifiedId     = (string)$aFuncArgs[8];
      } else {
        throw new Exception('Wrong number of arguments given to constructor');
      }
    }
    
    public static function fCheckForm($sUsername, $sDisplayName, $sPasswordOne, $sPasswordTwo, $sEmailOne, $sEmailTwo, $bCheckUsername, $bCheckDisplayName, $bCheckPassword, $bCheckEmail) {
      $mResult   = false;
      if ($bCheckUsername && empty($sUsername)) {
        $mResult = "Your username cannot be blank.";
      } else if ($bCheckUsername && strlen($sUsername) < User::USERNAME_LENGTH_MINIMUM) {
        $mResult = "Your username must be at least " . User::USERNAME_LENGTH_MINIMUM . " characters.";
      } else if ($bCheckUsername && strlen($sUsername) > User::USERNAME_LENGTH_MAXIMUM) {
        $mResult = "Your username must be at most " . User::USERNAME_LENGTH_MAXIMUM ." characters.";
      } else if ($bCheckUsername && !preg_match(User::USERNAME_ALLOWED_CHARACTERS, $sUsername)) {
        $mResult = "Your username must not contain special characters.";
      } else if ($bCheckDisplayName && !preg_match(User::DISPLAYNAME_ALLOWED_CHARACTERS, $sUsername)) {
        $mResult = "Your display name must not contain special characters.";
      } else if ($bCheckPassword && $sPasswordOne != $sPasswordTwo) {
        $mResult = "The two passwords do not match.";
      } else if ($bCheckPassword && strlen($sPasswordOne) < self::PASSWORD_LENGTH_MINIMUM) {
        $mResult = "Your password must be at least " . self::PASSWORD_LENGTH_MINIMUM . " characters.";
      } else if ($bCheckPassword && strlen($sPasswordOne) > self::PASSWORD_LENGTH_MAXIMUM) {
        $mResult = "Your password must be at most " . self::PASSWORD_LENGTH_MAXIMUM . " characters.";
      } else if ($bCheckPassword && self::PASSWORD_CANNOT_CONTAIN_USERNAME && stripos($sPasswordOne, $sUsername) !== false) {
        $mResult = "Your password cannot contain your username.";
      } else if ($bCheckPassword && self::PASSWORD_CANNOT_CONTAIN_DISPLAYNAME && stripos($sPasswordOne, $sDisplayName) !== false) {
        $mResult = "Your password cannot contain your display name.";
      } else if ($bCheckPassword && self::PASSWORD_CANNOT_CONTAIN_EMAIL && stripos($sPasswordOne, $sEmailOne) !== false) {
        $mResult = "Your password cannot contain your email address.";
      } else if ($bCheckPassword && self::PASSWORD_REQUIRES_UPPERCASE_LETTERS && !preg_match('/[A-Z]/', $sPasswordOne)) {
        $mResult = "Your password must use at least one uppercase letter.";
      } else if ($bCheckPassword && self::PASSWORD_REQUIRES_LOWERCASE_LETTERS && !preg_match('/[a-z]/', $sPasswordOne)) {
        $mResult = "Your password must use at least one lowercase letter.";
      } else if ($bCheckPassword && self::PASSWORD_REQUIRES_NUMBERS && !preg_match('/[0-9]/', $sPasswordOne)) {
        $mResult = "Your password must use at least one numeric character.";
      } else if ($bCheckPassword && self::PASSWORD_REQUIRES_SYMBOLS && !preg_match(self::PASSWORD_REQUIRES_SYMBOLS, $sPasswordOne)) {
        $mResult = "Your password must use at least one symbol.";
      } else if ($bCheckEmail && strtolower($sEmailOne) != strtolower($sEmailTwo)) {
        $mResult = "The two email addresses do not match.";
      } else if ($bCheckEmail && !EmailRecipient::fValidateAgainstMX($sEmailOne)) {
        $mResult = "Your email address is not a valid address.";
      } else {
        $mResult = true;
      }
      return $mResult;
    }
    
    public function fCheckPassword($sTargetPassword) {
      $sCurrentPasswordHash = $this->fGetPasswordHash();
      return password_verify($sTargetPassword, $sCurrentPasswordHash);
    }
    
    public static function fGenerateVerifiedId() {
      mt_srand(microtime(true)*100000 + memory_get_usage(true));
      return md5(mt_rand(0, mt_getrandmax()) * 0xFFFFFFFF);
    }
    
    public function fGetDisplayName() {
      return $this->sDisplayName;
    }
    
    public function fGetEmail() {
      return $this->sEmail;
    }
    
    public function fGetPasswordHash() {
      return $this->sPasswordHash;
    }
    
    public static function fGetReadACLs($bLimited) {
      if (!$bLimited) {
        return (
          self::STATUS_ACL_DOCUMENTS_READ |
          self::STATUS_ACL_NEWS_READ |
          self::STATUS_ACL_PACKETS_READ |
          self::STATUS_ACL_SERVERS_READ |
          self::STATUS_ACL_LOGS_READ |
          self::STATUS_ACL_USERS_READ
        );
      } else {
        return (
          self::STATUS_ACL_DOCUMENTS_READ |
          self::STATUS_ACL_NEWS_READ |
          self::STATUS_ACL_PACKETS_READ |
          self::STATUS_ACL_SERVERS_READ |
          self::STATUS_ACL_LOGS_READ
        );
      }
    }
    
    public function fGetRegisteredDate() {
      return $this->sRegisteredDate;
    }
    
    public function fGetStatus() {
      return $this->iStatus;
    }
    
    public function fGetUId() {
      return $this->iUId;
    }
    
    public function fGetUsername() {
      return $this->sUsername;
    }
    
    public function fGetUserSession() {
      return $this->oUserSession;
    }
    
    public function fGetVerifiedDate() {
      return $this->mVerifiedDate;
    }
    
    public function fGetVerifiedId() {
      return $this->iVerifiedId;
    }
    
    public static function fGetWriteACLs($bLimited) {
      if (!$bLimited) {
        return (
          self::STATUS_ACL_DOCUMENTS_WRITE |
          self::STATUS_ACL_NEWS_WRITE |
          self::STATUS_ACL_PACKETS_WRITE |
          self::STATUS_ACL_SERVERS_WRITE |
          self::STATUS_ACL_LOGS_WRITE |
          self::STATUS_ACL_USERS_WRITE
        );
      } else {
        return (
          self::STATUS_ACL_DOCUMENTS_WRITE |
          self::STATUS_ACL_NEWS_WRITE |
          self::STATUS_ACL_PACKETS_WRITE |
          self::STATUS_ACL_SERVERS_WRITE
        );
      }
    }
    
    public static function fHashPassword($sPassword) {
      if (!is_string($sPassword))
        throw new Exception('Password is not of type string');
      $aOptions = ['cost' => self::PASSWORD_HASH_COST ];
      return password_hash(
        $sPassword,
        self::PASSWORD_HASH_ALGORITHM,
        $aOptions
      );
    }
    
    public function fHasReadACLs() {
      return ($this->fGetStatus() & self::fGetReadACLs(false));
    }
    
    public function fHasWriteACLs() {
      return ($this->fGetStatus() & self::fGetWriteACLs(false));
    }
    
    public function fResetVerifiedId() {
      $iVerifiedId = self::fGenerateVerifiedId();
      return $this->fSetVerifiedId($iVerifiedId);
    }
    
    public function fSave() {
      if (!isset($this->iUId) || is_null($this->iUId)) {
        $sQuery = 'INSERT INTO `users` ('
        . '`email`,'
        . '`username`,'
        . '`display_name`,'
        . '`password_hash`,'
        . '`status`,'
        . '`registered_date`,'
        . '`verified_date`,'
        . '`verified_id`'
        . ') VALUES (\''
        . BNETDocs::$oDB->fEscapeValue($this->sEmail) . '\',\''
        . BNETDocs::$oDB->fEscapeValue($this->sUsername) . '\',\''
        . BNETDocs::$oDB->fEscapeValue($this->sDisplayName) . '\','
        . (is_null($this->sPasswordHash) ?
          'NULL,\'' :
          '\'' . BNETDocs::$oDB->fEscapeValue($this->sPasswordHash) . '\',\'')
        . BNETDocs::$oDB->fEscapeValue($this->iStatus) . '\',\''
        . BNETDocs::$oDB->fEscapeValue($this->sRegisteredDate) . '\','
        . (is_null($this->mVerifiedDate) ?
          'NULL,\'' :
          '\'' . BNETDocs::$oDB->fEscapeValue($this->mVerifiedDate) . '\',\'')
        . BNETDocs::$oDB->fEscapeValue($this->iVerifiedId)
        . '\');';
      } else {
        $sQuery = 'UPDATE `users` SET '
        . '`email`=\'' . BNETDocs::$oDB->fEscapeValue($this->sEmail) . '\','
        . '`username`=\'' . BNETDocs::$oDB->fEscapeValue($this->sUsername) . '\','
        . '`display_name`=\'' . BNETDocs::$oDB->fEscapeValue($this->sDisplayName) . '\','
        . '`password_hash`=\'' . BNETDocs::$oDB->fEscapeValue($this->sPasswordHash) . '\','
        . '`status`=\'' . BNETDocs::$oDB->fEscapeValue($this->iStatus) . '\','
        . '`registered_date`=\'' . BNETDocs::$oDB->fEscapeValue($this->sRegisteredDate) . '\','
        . '`verified_date`=\'' . BNETDocs::$oDB->fEscapeValue($this->mVerifiedDate) . '\','
        . '`verified_id`=\'' . BNETDocs::$oDB->fEscapeValue($this->iVerifiedId) . '\' '
        . 'WHERE `uid`=\'' . BNETDocs::$oDB->fEscapeValue($this->iUId) . '\' LIMIT 1;';
      }
      if (BNETDocs::$oDB->fQuery($sQuery)) {
         return true;
      }
      else {
        return false;
      }
    }        
    
    public function fSetDisplayName($sDisplayName) {
      if (!is_string($sDisplayName))
        throw new Exception('Display Name is not of type string');
      if (empty($sDisplayName))
        throw new RecoverableException('Display Name is an empty string');
      if (BNETDocs::$oDB->fQuery('UPDATE `users` SET `display_name` = \''
        . BNETDocs::$oDB->fEscapeValue($sDisplayName)
        . '\' WHERE `uid` = \''
        . BNETDocs::$oDB->fEscapeValue($this->iUId)
        . '\' LIMIT 1;'
      )) {
        $this->sDisplayName = $sDisplayName;
        return true;
      } else
        return false;
    }
    
    public function fSetEmail($sEmail) {
      if (!is_string($sEmail))
        throw new Exception('Email address is not of type string');
      if (empty($sEmail))
        throw new RecoverableException('Email address is an empty string');
      if (BNETDocs::$oDB->fQuery('UPDATE `users` SET `email` = \''
        . BNETDocs::$oDB->fEscapeValue($sEmail)
        . '\' WHERE `uid` = \''
        . BNETDocs::$oDB->fEscapeValue($this->iUId)
        . '\' LIMIT 1;'
      )) {
        $this->sEmail = $sEmail;
        return true;
      } else
        return false;
    }
    
    public function fSetPassword($sPassword) {
      if (!is_string($sPassword))
        throw new Exception('Password is not of type string');
      $iPasswordLength = strlen($sPassword);
      if ($iPasswordLength < self::PASSWORD_LENGTH_MINIMUM && self::PASSWORD_LENGTH_MINIMUM > 0)
        throw new RecoverableException('Password is less than ' . self::PASSWORD_LENGTH_MINIMUM . ' characters');
      if ($iPasswordLength > self::PASSWORD_LENGTH_MAXIMUM && self::PASSWORD_LENGTH_MAXIMUM >= self::PASSWORD_LENGTH_MINIMUM)
        throw new RecoverableException('Password is more than ' . self::PASSWORD_LENGTH_MAXIMUM . ' characters');
      $sPasswordHash = self::fHashPassword($sPassword);
      if (BNETDocs::$oDB->fQuery('UPDATE `users` SET `password_hash` = \''
        . BNETDocs::$oDB->fEscapeValue($sPasswordHash)
        . '\' WHERE `uid` = \''
        . BNETDocs::$oDB->fEscapeValue($this->iUId)
        . '\' LIMIT 1;'
      )) {
        $this->sPasswordHash = $sPasswordHash;
        return true;
      } else
        return false;
    }
    
    public function fSetRegisteredDate($sRegisteredDate) {
      if (!is_string($sRegisteredDate))
        throw new Exception('Registered Date is not of type string');
      if (empty($sRegisteredDate))
        throw new RecoverableException('Registered Date is an empty string');
      if (BNETDocs::$oDB->fQuery('UPDATE `users` SET `registered_date` = \''
        . BNETDocs::$oDB->fEscapeValue($sRegisteredDate)
        . '\' WHERE `uid` = \''
        . BNETDocs::$oDB->fEscapeValue($this->iUId)
        . '\' LIMIT 1;'
      )) {
        $this->sRegisteredDate = $sRegisteredDate;
        return true;
      } else
        return false;
    }
    
    public function fSetStatus($iStatus) {
      if (!is_numeric($iStatus))
        throw new Exception('Status is not of type numeric');
      if (BNETDocs::$oDB->fQuery('UPDATE `users` SET `status` = \''
        . BNETDocs::$oDB->fEscapeValue($iStatus)
        . '\' WHERE `uid` = \''
        . BNETDocs::$oDB->fEscapeValue($this->iUId)
        . '\' LIMIT 1;'
      )) {
        $this->iStatus = $iStatus;
        return true;
      } else
        return false;
    }
    
    public function fSetUsername($sUsername) {
      if (!is_string($sUsername))
        throw new Exception('Username is not of type string');
      if (empty($sUsername))
        throw new RecoverableException('Username is an empty string');
      if (BNETDocs::$oDB->fQuery('UPDATE `users` SET `username` = \''
        . BNETDocs::$oDB->fEscapeValue($sUsername)
        . '\' WHERE `uid` = \''
        . BNETDocs::$oDB->fEscapeValue($this->iUId)
        . '\' LIMIT 1;'
      )) {
        $this->sUsername = $sUsername;
        return true;
      } else
        return false;
    }
    
    public function fSetUserSession($oUserSession) {
      if (!$oUserSession instanceof UserSession)
        throw new Exception('First argument is not of type UserSession class');
      $this->oUserSession = $oUserSession;
      return true;
    }
    
    public function fSetVerifiedDate($mVerifiedDate) {
      if (!is_string($mVerifiedDate) && !is_null($mVerifiedDate))
        throw new Exception('Verified Date is not of type string or null');
      if (is_string($mVerifiedDate) && empty($mVerifiedDate))
        throw new RecoverableException('Verified Date is an empty string');
      if (BNETDocs::$oDB->fQuery('UPDATE `users` SET `verified_date` = '
        . (is_string($mVerifiedDate) ? '\''
        . BNETDocs::$oDB->fEscapeValue($mVerifiedDate)
        . '\'' : 'NULL')
        . ' WHERE `uid` = \''
        . BNETDocs::$oDB->fEscapeValue($this->iUId)
        . '\' LIMIT 1;'
      )) {
        $this->mVerifiedDate = $mVerifiedDate;
        return true;
      } else 
        return false;
    }
    
    public function fSetVerifiedId($iVerifiedId) {
      if (BNETDocs::$oDB->fQuery('UPDATE `users` SET `verified_id` = \''
        . BNETDocs::$oDB->fEscapeValue($iVerifiedId)
        . '\' WHERE `uid` = \''
        . BNETDocs::$oDB->fEscapeValue($this->iUId)
        . '\' LIMIT 1;'
      )) {
        $this->iVerifiedId = $iVerifiedId;
        return true;
      } else
        return false;
    }
    
  }
  
  