<?php

// from http://www.devshed.com/c/a/PHP/Storing-PHP-Sessions-in-a-Database/
class SessionManagerPhP7
{
    public $m_intLifeTime;
	public $m_strSessionID = "";
    public $m_strHostname = "";
    public $m_strLogin = "";
    public $m_strPassword = "";
    public $m_strDatabase = "";
	
	private $m_blnAllowSessionExpiry = true;	// turn session expiry on or off

    // setup the session manager callbacks
    function __construct($strHostname_a, $strLogin_a, $strPassword_a, $strDatabase_a)
    {
        // Read the maxlifetime setting from PHP
        $this->m_intLifeTime = get_cfg_var("session.gc_maxlifetime");
		$this->m_strSessionID = "";
        $this->m_strHostname = $strHostname_a;
        $this->m_strLogin = $strLogin_a;
        $this->m_strPassword = $strPassword_a;
        $this->m_strDatabase = $strDatabase_a;

        session_set_save_handler(
            array(&$this, "open"),
            array(&$this, "close"),
            array(&$this, "read"),
            array(&$this, "write"),
            array(&$this, "destroy"),
            array(&$this, "gc")
        );
    }

    // open the session
    public function open($strSavePath_a, $strSessionName_a)
    {
        return true;
    }

    // close the session
    public function close()
    {
        return true;
    }

    // read the session
    public function read($strSessionID_a)
    {
		global $strGlobalSessionID;
		$strSessionID = $strGlobalSessionID;
		if (strlen($strSessionID) == 0) 
		{
			$strSessionID = $strSessionID_a;
		}
		//file_put_contents("d:\dev\session.log", " sessions.php read " . $strSessionID . ":" . "\n", FILE_APPEND);	// DEBUGSESSION
		
		//logDebug("read session with: " . $strSessionID, '');
        $intTime = time();

		$strSQL = "";
		if ($this->m_blnAllowSessionExpiry)
		{
			$strSQL = "select session_data returnvalue from t_sessions where session_id = '~SESSIONID~' and expires > ~EXPIRETIME~";
			$strSQL = str_replace('~SESSIONID~', ff($strSessionID), $strSQL);
			$strSQL = str_replace('~EXPIRETIME~', ff($intTime), $strSQL);
		}
		else
		{
			$strSQL = "select session_data returnvalue from t_sessions where session_id = '~SESSIONID~'";
			$strSQL = str_replace('~SESSIONID~', ff($strSessionID), $strSQL);
		}

        $objConn = dbOpen($this->m_strHostname, $this->m_strLogin, $this->m_strPassword, $this->m_strDatabase);
        $strResult = dbReadValueNoDebug($objConn, $strSQL, __FUNCTION__);
        dbClose($objConn);

        return $strResult;
    }

    // write the session
    public function write($strSessionID_a, $strSessionData_a)
    {
		global $strGlobalSessionID;
		global $strGlobalSessionToken;
		$this->m_strSessionID = $strGlobalSessionID;
		if (strlen($this->m_strSessionID) == 0) 
		{
			$this->m_strSessionID = $strSessionID_a;
		}
		//file_put_contents("d:\dev\session.log", " sessions.php write " . $this->m_strSessionID . ":" . $_SESSION['server_loggedin_client'] . "\n", FILE_APPEND);	// DEBUGSESSION
		
		//logDebug("write session with: " . $this->m_strSessionID, '');
        $intTime = time() + $this->m_intLifeTime;

        //$objConn = dbOpen($this->m_strHostname, $this->m_strLogin, $this->m_strPassword, $this->m_strDatabase);

        // $strSQL = "delete from t_sessions where session_id = '~SESSIONID~'";
        // $strSQL = str_replace('~SESSIONID~', ff($strSessionID_a), $strSQL);
        // dbExecuteSQLNoDebug($objConn, $strSQL);

        // $strSQL = "insert into t_sessions (session_id, session_data, expires) values ('~SESSIONID~', '~SESSIONDATA~', ~EXPIRETIME~)";
        $strSQL = "replace t_sessions (session_id, session_token, session_data, expires, lifetime) values ('~SESSIONID~', '~SESSIONTOKEN~', '~SESSIONDATA~', ~EXPIRETIME~, ~LIFETIME~)";
        $strSQL = str_replace('~SESSIONID~', ff($this->m_strSessionID), $strSQL);
        $strSQL = str_replace('~SESSIONTOKEN~', ff($strGlobalSessionToken), $strSQL);
        $strSQL = str_replace('~SESSIONDATA~', ff($strSessionData_a), $strSQL);
        $strSQL = str_replace('~EXPIRETIME~', ff($intTime), $strSQL);
        $strSQL = str_replace('~LIFETIME~', ff($this->m_intLifeTime), $strSQL);
		//file_put_contents("d:\dev\session.log", " sessions.php write " . $this->m_strSessionID . ":" . $strSQL . "\n", FILE_APPEND);	// DEBUGSESSION

        $objConn = dbOpen($this->m_strHostname, $this->m_strLogin, $this->m_strPassword, $this->m_strDatabase);
        dbExecuteSQLNoDebug($objConn, $strSQL);
        dbClose($objConn);

        return true;
    }

    // cleanup the session
    public function destroy($strSessionID_a)
    {
		global $strGlobalSessionID;
		$this->m_strSessionID = $strGlobalSessionID;
		if (strlen($this->m_strSessionID) == 0) 
		{
			$this->m_strSessionID = $strSessionID_a;
		}
		//file_put_contents("d:\dev\session.log", " sessions.php destroy " . $this->m_strSessionID . ":" . $_SESSION['server_loggedin_client'] . "\n", FILE_APPEND);	// DEBUGSESSION
		
		//logDebug("destroy session with: " . $this->m_strSessionID, '');
        $strSQL = "delete from t_sessions where session_id = '~SESSIONID~'";
        $strSQL = str_replace('~SESSIONID~', ff($this->m_strSessionID), $strSQL);

        $objConn = dbOpen($this->m_strHostname, $this->m_strLogin, $this->m_strPassword, $this->m_strDatabase);
        dbExecuteSQLNoDebug($objConn, $strSQL);
        dbClose($objConn);
//debug('destroy');
        return true;
    }

    // cleanup the session completely
    public function gc()
    {
		if ($this->m_blnAllowSessionExpiry)
		{
			$intTime = time();

			//$strSQL = "delete from t_sessions where expires < UNIX_TIMESTAMP()";
			$strSQL = "delete from t_sessions where session_id = '~SESSIONID~' and expires < ~EXPIRETIME~";
			$strSQL = str_replace('~SESSIONID~', ff($this->m_strSessionID), $strSQL);
			$strSQL = str_replace('~EXPIRETIME~', ff($intTime), $strSQL);
			$objConn = dbOpen($this->m_strHostname, $this->m_strLogin, $this->m_strPassword, $this->m_strDatabase);
			dbExecuteSQLNoDebug($objConn, $strSQL);
			dbClose($objConn);
		}

//debug('gc');
        return true;
    }
}
