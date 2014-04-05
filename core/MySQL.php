<?php
  
  class MySQL extends SQL {
    
    protected $oLink;
    
    public function __construct() {
      parent::__construct();
      
      $this->oLink = mysqli_init();
    }
    
    public function fClose() {
      $this->oLink->close();
    }
    
    public function fConnect() {
      $sHostname = $this->fGetHostname();
      $iPort = 3306;
      $iPos = strpos($sHostname, ':');
      if ($iPos) {
        $iPort = (int)substr($sHostname, 0, $iPos - 1);
        $sHostname = substr($sHostname, $iPos + 1);
      }
      $this->oLink->options(MYSQLI_OPT_CONNECT_TIMEOUT, $this->fGetConnectTimeout());
      $bConnectedState = $this->oLink->real_connect(
        $sHostname,
        $this->fGetUsername(),
        $this->fGetPassword(),
        $this->fGetDatabase(),
        $iPort
      );
      if ($bConnectedState) {
        $this->oLink->set_charset($this->fGetCharacterSet());
      }
      return $bConnectedState;
    }
    
    public function fErrorMessage() {
      return $this->oLink->error;
    }
    
    public function fErrorNumber() {
      return $this->oLink->errno;
    }
    
    public function fEscapeValue($mValue) {
      return $this->oLink->real_escape_string($mValue);
    }
    
    public function fQuery($sQuery) {
      if ($this->fGetAudit()) $this->fAuditQuery($sQuery);
      $mResult = $this->oLink->query($sQuery, MYSQLI_STORE_RESULT);
      if (!$mResult && $this->oLink->errno == 1142)
        throw new RecoverableException('Cannot execute SQL statement because access was denied.');
      if ($mResult instanceof mysqli_result)
        return new MySQLResult($mResult);
      else
        return $mResult;
    }
    
  }
  