<?php
  
  abstract class SQL {
    
    private $sHostname;
    private $sUsername;
    private $sPassword;
    private $sDatabase;
    private $iConnectTimeout;
    private $sCharacterSet;
    private $bAudit;
    
    public function __construct() {
      global $_CONFIG;
      $this->fSetHostname($_CONFIG['database']['hostname']);
      $this->fSetUsername($_CONFIG['database']['username']);
      $this->fSetPassword($_CONFIG['database']['password']);
      $this->fSetDatabase($_CONFIG['database']['name']);
      $this->fSetConnectTimeout($_CONFIG['database']['connect_timeout']);
      $this->fSetCharacterSet($_CONFIG['database']['character_set']);
      $this->fSetAudit($_CONFIG['database']['audit']);
      
      $aArgs = func_get_args();
      if (isset($aArgs[0])) $this->fSetHostname($aArgs[0]);
      if (isset($aArgs[1])) $this->fSetUsername($aArgs[1]);
      if (isset($aArgs[2])) $this->fSetPassword($aArgs[2]);
      if (isset($aArgs[3])) $this->fSetDatabase($aArgs[3]);
      if (isset($aArgs[4])) $this->fSetConnectTimeout($aArgs[4]);
      if (isset($aArgs[5])) $this->fSetCharacterSet($aArgs[5]);
      if (isset($aArgs[6])) $this->fSetAudit($aArgs[6]);
    }
    
    protected function fAuditQuery($sQuery) {
      $sTimestamp  = date('Y-m-d H:i:s O');
      $aTrace      = debug_backtrace();
      $sCallerFile = $aTrace[1]['file'];
      $sCallerLine = $aTrace[1]['line'];
      $sCallerFunc = $aTrace[1]['function'];
      $sCallerArgs = '"' . implode('","', $aTrace[1]['args']) . '"';
      unset($aTrace);
      
      if ($sCallerArgs == '""') $sCallerArgs = '';
      
      $sLogLine = "[$sTimestamp] $sCallerFile:$sCallerLine $sCallerFunc($sCallerArgs): $sQuery\n";
      
      global $_CONFIG;
      $sFile = $_CONFIG['paths']['base_dir'] . $_CONFIG['paths']['audit_dir'] . 'audited_queries.log';
      $oFile = fopen($sFile, 'a');
      fwrite($oFile, $sLogLine);
      fclose($oFile);
    }
    
    abstract public function fClose();
    abstract public function fConnect();
    abstract public function fErrorMessage();
    abstract public function fErrorNumber();
    abstract public function fEscapeValue($mValue);
    
    public function fGetAudit() {
      return $this->bAudit;
    }
    
    public function fGetCharacterSet() {
      return $this->sCharacterSet;
    }
    
    public function fGetConnectTimeout() {
      return $this->iConnectTimeout;
    }
    
    public function fGetDatabase() {
      return $this->sDatabase;
    }
    
    public function fGetHostname() {
      return $this->sHostname;
    }
    
    public function fGetPassword() {
      return $this->sPassword;
    }
    
    public function fGetUsername() {
      return $this->sUsername;
    }
    
    public function fSetAudit($bValue) {
      $this->bAudit = $bValue;
      return true;
    }
    
    public function fSetCharacterSet($sValue) {
      $this->sCharacterSet = $sValue;
      return true;
    }
    
    public function fSetConnectTimeout($iValue) {
      $this->iConnectTimeout = $iValue;
      return true;
    }
    
    public function fSetDatabase($sValue) {
      $this->sDatabase = $sValue;
      return true;
    }
    
    public function fSetHostname($sValue) {
      $this->sHostname = $sValue;
      return true;
    }
    
    public function fSetPassword($sValue) {
      $this->sPassword = $sValue;
      return true;
    }
    
    public function fSetUsername($sValue) {
      $this->sUsername = $sValue;
      return true;
    }
    
    abstract public function fQuery($sQuery);
    
  }
  