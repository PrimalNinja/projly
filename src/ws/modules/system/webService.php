<?php

// globals
$strGlobalClientDB = "";
$strGlobalSessionID = "";		// used by IOS and Android
$strGlobalSessionToken = "";	// used by IOS and Android
$strGlobalDevicePushToken = "";	// used by IOS and Android

function getSessionID()
{
	global $strGlobalSessionID;

	$strResult = $strGlobalSessionID;
	
	if (strlen($strResult) == 0) 
	{
		$strResult = session_id();
	}
	
	return $strResult;
}

class webService
{
    private $m_objConn;

    // setup the database connection and session
    public function __construct()
    {
    }

    // clean up the database connection
    public function __destruct()
    {
        dbClose($this->m_objConn);
    }

	public function initDB()
	{
		global $strGlobalClientDB;

		// open the db
		if (ENABLE_CLIENTDATABASES == 'TRUE')
		{
			if (getSessionDB(__FUNCTION__) == "client")
			{
				dbClose($this->m_objConn);
				$this->m_objConn = dbOpen(DBCLIENTMAIN_HOSTNAME, DBCLIENTMAIN_LOGIN, DBCLIENTMAIN_PASSWORD, DBCLIENTMAIN_DATABASENAME);
			}
			else if (getSessionDB(__FUNCTION__) == "system")
			{
				dbClose($this->m_objConn);
				$this->m_objConn = dbOpen(DBSYSTEMMAIN_HOSTNAME, DBSYSTEMMAIN_LOGIN, DBSYSTEMMAIN_PASSWORD, DBSYSTEMMAIN_DATABASENAME);
			}
		}
		else
		{
			dbClose($this->m_objConn);
			$this->m_objConn = dbOpen(DBSYSTEMMAIN_HOSTNAME, DBSYSTEMMAIN_LOGIN, DBSYSTEMMAIN_PASSWORD, DBSYSTEMMAIN_DATABASENAME);
		}
		
		return $this->m_objConn;
	}
	
	public function newDB($strClientDB_a)
	{
		global $objConn;

		if (ENABLE_CLIENTDATABASES == 'TRUE')
		{
			// open the db
			if ($strClientDB_a == "client")
			{
				$objConn = dbOpen(DBCLIENTMAIN_HOSTNAME, DBCLIENTMAIN_LOGIN, DBCLIENTMAIN_PASSWORD, DBCLIENTMAIN_DATABASENAME);
			}
			else if ($strClientDB_a == "system")
			{
				$objConn = dbOpen(DBSYSTEMMAIN_HOSTNAME, DBSYSTEMMAIN_LOGIN, DBSYSTEMMAIN_PASSWORD, DBSYSTEMMAIN_DATABASENAME);
			}
		}
		else
		{
			$objConn = dbOpen(DBSYSTEMMAIN_HOSTNAME, DBSYSTEMMAIN_LOGIN, DBSYSTEMMAIN_PASSWORD, DBSYSTEMMAIN_DATABASENAME);
		}
		
		return $objConn;
	}
	
	public function initSession()
	{
        if (toBoolean(SESSION_IN_DATABASE)) 
		{
			$objSessionSystem = new SessionManager(DBSYSTEMTEMP_HOSTNAME, DBSYSTEMTEMP_LOGIN, DBSYSTEMTEMP_PASSWORD, DBSYSTEMTEMP_DATABASENAME);
		}
        session_name(SESSION_NAME);
        session_start();

        // initialise security model
        if (isset($_SESSION[SESSION_SECURITY])) 
		{
            // do nothing
        } 
		else 
		{
            $_SESSION[SESSION_SECURITY] = array();
        }
	}

//================================================================================

    // process an action
    public function processAction($strSecurityToken_a, $strDataID_a, $strFunction_a, $arrParameters_a, $strDeviceIDCookie_a, $blnIsAndroidOrIOSNative_a)
    {
		global $strGlobalClientDB;
        global $g_strCurrentFunction;

        $strResult = "";

        $strSecurityToken = $strSecurityToken_a;
        $strDataID = $strDataID_a;
        $strFunction = strtolower($strFunction_a);
        $arrParameters = $arrParameters_a;
        $strDeviceIDCookie = $strDeviceIDCookie_a;

        $g_strCurrentFunction = $strFunction;

        if (toBoolean(DEBUG_PARAMETER_OUTPUT)) 
		{
            echo ("param securitytoken:" . $strSecurityToken . "<br>");
            echo ("param dataid:" . $strDataID . "<br>");
            echo ("param deviceidcookie:" . $strDeviceIDCookie . "<br>");
            echo ("param function:" . $strFunction . "<br>");
        }
		
//file_put_contents("d:\dev\session.log", " webService.php 2:" . $strFunction . "\n", FILE_APPEND);	// DEBUGSESSION
        logDebug('========== START processAction SECURITY CHECK', 'S');
        if ($strFunction == ACTION_PUBLIC_LOGOUT) 
		{
            if (dependencies('public/actionPublicLogout')) 
			{
				// NOTE: do NOT move these out of public, as the ANDROID and IOS apps rely on them in public
                logDebug('service: public_logout', '');
//file_put_contents("d:\dev\session.log", " webService.php 3:" . $strFunction . "\n", FILE_APPEND);	// DEBUGSESSION
                $strResult = actionPublicLogout($this->m_objConn, $strSecurityToken, $strDataID, $arrParameters, $strDeviceIDCookie);
            }
		} 
		else if ($strFunction == ACTION_PUBLIC_RETURN) 
		{
            if (dependencies('public/actionPublicReturn')) 
			{
				// NOTE: do NOT move these out of public, as the ANDROID and IOS apps rely on them in public
                logDebug('service: public_return', '');
//file_put_contents("d:\dev\session.log", " webService.php 3:" . $strFunction . "\n", FILE_APPEND);	// DEBUGSESSION
                $strResult = actionPublicReturn($this->m_objConn, $strSecurityToken, $strDataID, $arrParameters, $strDeviceIDCookie);
            }
        } 
		else 
		{
            // public functions first before the security check
            if (($strFunction == ACTION_PUBLIC_AUTHENTICATION_CHECK) && (ALLOW_STAY_LOGGEDIN == 'TRUE')) 
			{
                if (dependencies('public/actionPublicAuthenticationCheck')) 
				{
					// NOTE: do NOT move these out of public, as the ANDROID and IOS apps rely on them in public
                    logDebug('service: public_authenticationcheck', '');
                    $strResult = actionPublicAuthenticationCheck($this->m_objConn, $strSecurityToken, $strDataID, $arrParameters, $strDeviceIDCookie);
                }
            } 
			else if ($strFunction == ACTION_PUBLIC_CONFIRMATION) 
			{
                if (dependencies('public/actionPublicConfirmation')) 
				{
                    logDebug('service: public_confirmation', '');
                    $strResult = actionPublicConfirmation($this->m_objConn, $strSecurityToken, $strDataID, $arrParameters, $strDeviceIDCookie);
                }
            } 
			else if ($strFunction == ACTION_PUBLIC_DOCUMENTDOWNLOAD) 
			{
                if (dependencies('public/actionPublicDocumentDownload')) 
				{
                    logDebug('service: public_documentdownload', '');
                    $strResult = actionPublicDocumentDownload($this->m_objConn, $strSecurityToken, $strDataID, $arrParameters, $strDeviceIDCookie);
                }
            } 
			else if ($strFunction == ACTION_PUBLIC_FETCHIMAGE) 
			{
                if (dependencies('public/actionPublicFetchImage')) 
				{
                    logDebug('service: public_fetchimage', '');
                    $strResult = actionPublicFetchImage($this->m_objConn, $strSecurityToken, $strDataID, $arrParameters, $strDeviceIDCookie);
                }
            } 
			else if ($strFunction == ACTION_PUBLIC_FETCHMETADATA) 
			{
                if (dependencies('system/actionPublicFetchMetaData')) 
				{
                    logDebug('service: public_fetchmetadata', '');
                    $strResult = actionPublicFetchMetaData($this->m_objConn, $strSecurityToken, $strDataID, $arrParameters, $strDeviceIDCookie);
                }
            } 
			else if ($strFunction == ACTION_PUBLIC_GETDATABASENAME) 
			{
                if (dependencies('public/actionPublicGetDatabaseName')) 
				{
                    logDebug('service: public_getdatabasename', '');
                    $strResult = actionPublicGetDatabaseName($this->m_objConn, $strSecurityToken, $strDataID, $arrParameters, $strDeviceIDCookie);
                }
            } 
			else if ($strFunction == ACTION_PUBLIC_LOGIN) 
			{
                if (dependencies('public/verifyClientDB,public/actionPublicLogin')) 
				{
					// NOTE: do NOT move these out of public, as the ANDROID and IOS apps rely on them in public
                    logDebug('service: public_login', '');
					$blnClientDB = verifyClientDB($this->m_objConn, $strSecurityToken, $strDataID, $arrParameters, $strDeviceIDCookie);
					if ($blnClientDB)
					{
						logSecurity('DB is client', '');
						self::initDB();	// since we are now in the clientDB, then re-initialise the DB before actually loggingin
					}
					else
					{
						logSecurity('DB is system', '');
					}
                    $strResult = actionPublicLogin($this->m_objConn, $strSecurityToken, $strDataID, $arrParameters, $strDeviceIDCookie);
                }
            } 
			else if ($strFunction == ACTION_PUBLIC_PASSWORDRESET) 
			{
                if (dependencies('public/actionPublicPasswordReset')) 
				{
                    logDebug('service: public_passwordreset', '');
                    $strResult = actionPublicPasswordReset($this->m_objConn, $strSecurityToken, $strDataID, $arrParameters, $strDeviceIDCookie);
                }
            } 
			else if ($strFunction == ACTION_PUBLIC_REGISTER) 
			{
                if (dependencies('public/actionPublicRegister')) 
				{
                    logDebug('service: public_register', '');
                    $strResult = actionPublicRegister($this->m_objConn, $strSecurityToken, $strDataID, $arrParameters, $strDeviceIDCookie);
                }
            } 
			else if ($strFunction == ACTION_PUBLIC_REGISTRATIONFETCH) 
			{
                if (dependencies('public/actionPublicRegistrationFetch')) 
				{
                    logDebug('service: public_registrationfetch', '');
                    $strResult = actionPublicRegistrationFetch($this->m_objConn, $strSecurityToken, $strDataID, $arrParameters, $strDeviceIDCookie);
                }
            } 
			else if ($strFunction == ACTION_PUBLIC_VERIFICATION) 
			{
                if (dependencies('public/actionPublicVerification')) 
				{
                    logDebug('service: public_verification', '');
                    $strResult = actionPublicVerification($this->m_objConn, $strSecurityToken, $strDataID, $arrParameters, $strDeviceIDCookie);
                }
            } 
			else if ($strFunction == ACTION_PUBLIC_FETCHWIKI) 
			{
                if (dependencies('public/actionPublicFetchWiki')) 
				{
                    logDebug('service: public_fetchwiki', '');
                    $strResult = actionPublicFetchWiki($this->m_objConn, $strSecurityToken, $strDataID, $arrParameters, $strDeviceIDCookie);
                }
            } 
			else 
			{
				$blnContinue = false;
				
                // security check
                $strStoredSecurityToken = '';
                if (isset($_SESSION['server_loggedin_token'])) 
				{
                    $strStoredSecurityToken = $_SESSION['server_loggedin_token'];
                }
				
//file_put_contents("d:\dev\session.log", " webService.php tokens:" . $strFunction . ":" . $strSecurityToken_a . ":" . $strStoredSecurityToken . "\n", FILE_APPEND);	// DEBUGSESSION
                if (($strSecurityToken_a == $strStoredSecurityToken) && (strlen($strSecurityToken_a) > 0)) 
				{
                    // do nothing
					$blnContinue = true;
                } 
				else 
				{
					//debug($strSecurityToken_a . ":" . $strStoredSecurityToken . ":" . getSessionID());
					//die();		// this stops a reset loop
					clearSession();

					$blnContinue = false;
					
					//if (strlen($strStoredSecurityToken) > 0)
					//{
						logDebug('Security Check: force logout', '');
						return forceLogout('Security Token Mismatch', __FUNCTION__);
					//}
                }

				if ($blnContinue)
				{
					logDebug('========== START processAction MODULE ENTRY POINTS', 'S');
					$blnDispatched = false;
					//module entry points
					$arrModules = explode(',', SERVERMODULES);
					foreach ($arrModules as $strModule) {
						if (file_exists('modules/' . $strModule . '/inc-entry.php'))
						{
							require_once 'modules/' . $strModule . '/inc-entry.php';
						}
						else
						{
							logDebug('========== MODULE MISSING:' . $strModule, '');
						}
					}
					if ($blnDispatched) 
					{
						logDebug('function dispatched: ' . $strFunction, '');
					} 
					else 
					{
						logDebug('function not dispatched: ' . $strFunction, '');
					}
					logDebug('========== END processAction MODULE ENTRY POINTS', 'E');
				}
				else
				{
					if ($blnIsAndroidOrIOSNative_a)	
					{
						// JC TODO: Note, when USE_CLIENTSIDE_SESSIONS is true, sometimes things come in here and cause issues but the issue for now is we are 
						// 				  not distinguishing between native IOS or IPhone vs USE_CLIENTSIDE_SESSIONS - whatever the fix is we don't want to break mobile Apps.
						//				  Perhaps a fix is to make the client handle no response better when it is PHP?
						
						// do nothing, as no need to redirect
//file_put_contents("d:\dev\session.log", " webService.php 4:" . $strFunction . "\n", FILE_APPEND);	// DEBUGSESSION
					}
					else
					{
//file_put_contents("d:\dev\session.log", " webService.php 5:" . $strFunction . "\n", FILE_APPEND);	// DEBUGSESSION
						$arrResponse = array("newtab"=> false, "url"=>"logout.php");
						return createJSONResponse('', RESPONSE_URL, '', $arrResponse);
						//return createJSONResponse('', RESPONSE_URL, '', 'logout.php');
					}
				}
            }
        }
        logDebug('========== END processAction SECURITY CHECK', 'E');

        return $strResult;
    }

    // check if the server is disabled before processing an action
    public function processActionWithDisabledCheck($strSecurityToken_a, $strDataID_a, $strFunction_a, $arrParameters_a, $strClientVersion_a, $strDeviceIDCookie_a)
    {
		global $strGlobalSessionID;
		global $strGlobalSessionToken;

        $strResult = "";

		// if we are using android or ios then use the client-side token for the server sessionid
		$blnIsAndroidOrIOSNative = ((InStr(strtoupper($strClientVersion_a), "(ANDROID)") >= 0) || (InStr(strtoupper($strClientVersion_a), "(IOS)") >= 0) || (USE_CLIENTSIDE_SESSIONS == "TRUE"));
		$blnUseWSToken = $blnIsAndroidOrIOSNative;
		
		// note: if the below line is uncommented, the security token is the sessionid
		// 		 and the session can be re-established
		$blnUseWSToken = true;

		if ($blnUseWSToken)
		{     
			$strGlobalSessionID = $strSecurityToken_a;  
			logDebug("sessionid from request: " . $strGlobalSessionID, '');
		}
		$strGlobalSessionToken = $strSecurityToken_a;
		//file_put_contents("d:\dev\session.log", " webService.php function " . $strFunction_a . ":" . "\n", FILE_APPEND);	// DEBUGSESSION
		//file_put_contents("d:\dev\session.log", " webService.php 1 " . $strGlobalSessionID . ":" . "\n", FILE_APPEND);	// DEBUGSESSION

		self::initSession();
		self::initDB();

		logDebugSession();

        $strServerDisabled = systemSettingGetNoDebug($this->m_objConn, SETTING_KEY_SERVERDISABLED) == BOOLTRUE;
        $strSchemaVersion = systemSettingGetNoDebug($this->m_objConn, SETTING_KEY_SCHEMAVERSION);
        if ($strServerDisabled == true) 
		{
            $strServerDisabledReason = systemSettingGetNoDebug($this->m_objConn, SETTING_KEY_SERVERDISABLEDREASON);
            $strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, $strServerDisabledReason, array());
        } 
		else 
		{
            if (intval($strSchemaVersion, 10) != intval(DBSYSTEM_REQUIREDSCHEMAVERSION, 10)) 
			{
                logDebug('Schema version expected by client: ' . $strSchemaVersion . ', schema version in use: ' . DBSYSTEM_REQUIREDSCHEMAVERSION, '');
                $strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, INVALID_SCHEMAVERSION, array());
            } 
			else 
			{
                $strResult = self::processAction($strSecurityToken_a, $strDataID_a, $strFunction_a, $arrParameters_a, $strDeviceIDCookie_a, $blnIsAndroidOrIOSNative);
            }
        }

        return $strResult;
    }

    // JSON version of the webservice (future other versions could support XML), parse the JSON and process the action
    public function serviceJson($strConfirm_a, $strVerify_a, $strImageID_a, $strURLToken_a, $strURLDocumentID_a, $strMetaData_a, $strWiki_a, $strDownload_a, $blnJSONP_a, $strJSON_a, $strCallback_a)
    {
		global $strGlobalDevicePushToken;

        $strResult = "";

        if (strlen($strConfirm_a) > 0) 
		{
            // handle email registrations based on a token

            $strSecurityToken = $strConfirm_a;
            $strClientVersion = "";
            $strDeviceIDCookie = "";
            $strCallerID = "";
            $strDataID = "";
            $strFunction = ACTION_PUBLIC_CONFIRMATION;
            $arrParameters = array();

            //debug(print_r($arrParameters, true));

            // put some logic in here to deal with caller version and callerid if necessary
            $strResult = self::processActionWithDisabledCheck($strSecurityToken, $strDataID, $strFunction, $arrParameters, $strClientVersion, $strDeviceIDCookie);
        } 
		else if (strlen($strVerify_a) > 0) 
		{
            // handle email registrations based on a token

            $strSecurityToken = $strVerify_a;
            $strClientVersion = "";
            $strDeviceIDCookie = "";
            $strCallerID = "";
            $strDataID = "";
            $strFunction = ACTION_PUBLIC_VERIFICATION;
            $arrParameters = array();

            // put some logic in here to deal with caller version and callerid if necessary
            $strResult = self::processActionWithDisabledCheck($strSecurityToken, $strDataID, $strFunction, $arrParameters, $strClientVersion, $strDeviceIDCookie);
        } 
        else if (strlen($strMetaData_a) > 0) 
		{
            // handle metadata fetching based on a token

            $strSecurityToken = $strMetaData_a;
            $strClientVersion = "";
            $strDeviceIDCookie = "";
            $strCallerID = "";
            $strDataID = "";
            $strFunction = ACTION_PUBLIC_FETCHMETADATA;
            $arrParameters = array();
            array_push($arrParameters, array("name" => "metadata", "value" => $strMetaData_a));

            //debug(print_r($arrParameters, true));

            // put some logic in here to deal with caller version and callerid if necessary
            $strResult = self::processActionWithDisabledCheck($strSecurityToken, $strDataID, $strFunction, $arrParameters, $strClientVersion, $strDeviceIDCookie);
        } 
		else 
		{
            // normal webserver security applies to below

            if (strlen($strURLToken_a) > 0) 
			{
				if (strlen($strImageID_a) > 0) 
				{
					// handle image fetching based on a token

					$strSecurityToken = $strURLToken_a;
					$strClientVersion = "";
					$strDeviceIDCookie = "";
					$strCallerID = "";
					$strDataID = "";
					$strFunction = ACTION_PUBLIC_FETCHIMAGE;
					$arrParameters = array();
					array_push($arrParameters, array("name" => "id", "value" => $strImageID_a));
					array_push($arrParameters, array("name" => "download", "value" => $strDownload_a));

					//debug(print_r($arrParameters, true));

					// put some logic in here to deal with caller version and callerid if necessary
					$strResult = self::processActionWithDisabledCheck($strSecurityToken, $strDataID, $strFunction, $arrParameters, $strClientVersion, $strDeviceIDCookie);
				} 
				else if (strlen($strURLDocumentID_a) > 0) 
				{
                    // handle cluster file downloads based on a token

                    $strSecurityToken = $strURLToken_a;
                    $strClientVersion = "";
                    $strDeviceIDCookie = "";
                    $strCallerID = "";
                    $strDataID = "";
                    $strFunction = ACTION_PUBLIC_DOCUMENTDOWNLOAD;
                    $arrParameters = array();
                    array_push($arrParameters, array("name" => "id", "value" => $strURLDocumentID_a));
                    array_push($arrParameters, array("name" => "download", "value" => $strDownload_a));

                    //debug(print_r($arrParameters, true));

                    // put some logic in here to deal with caller version and callerid if necessary
                    $strResult = self::processActionWithDisabledCheck($strSecurityToken, $strDataID, $strFunction, $arrParameters, $strClientVersion, $strDeviceIDCookie);
                }
				else if (strlen($strWiki_a) > 0)
				{
                    // handle wiki based on a token

                    $strSecurityToken = $strURLToken_a;
                    $strClientVersion = "";
                    $strDeviceIDCookie = "";
                    $strCallerID = "";
                    $strDataID = "";
                    $strFunction = ACTION_PUBLIC_FETCHWIKI;
                    $arrParameters = array();
                    array_push($arrParameters, array("name" => "wiki", "value" => $strWiki_a));

                    //debug(print_r($arrParameters, true));

                    // put some logic in here to deal with caller version and callerid if necessary
                    $strResult = self::processActionWithDisabledCheck($strSecurityToken, $strDataID, $strFunction, $arrParameters, $strClientVersion, $strDeviceIDCookie);
				}
            } 
			else 
			{
                if ($blnJSONP_a) 
				{
                    $strJSON = $strJSON_a;
                } 
				else 
				{
                    // read our JSON from the request stream
                    $strJSON = file_get_contents('php://input');
                }
//echo('1:' . $strJSON . "<hr>");				
                $arrJSON = json_decode($strJSON, true);
//echo('2:' . print_r($arrJSON, true) . "<hr>");				
                //logDebug(print_r($arrJSON, true), '');

                $strSecurityToken = $arrJSON['AWAFOS']['securitytoken'];
                $strClientVersion = $arrJSON['AWAFOS']['clientversion'];

				$strDeviceIDCookie = "";			// NOTE: do NOT move these out of public, as the ANDROID and IOS apps rely on them in public
				if (array_key_exists('computeridcookie', $arrJSON['AWAFOS']))
				{
					$strDeviceIDCookie = $arrJSON['AWAFOS']['computeridcookie'];
				}
				if (strlen($strDeviceIDCookie) == 0)
				{
					if (array_key_exists('deviceidcookie', $arrJSON['AWAFOS']))
					{
						$strDeviceIDCookie = $arrJSON['AWAFOS']['deviceidcookie'];
					}
				}
				
                $strCallerID = $arrJSON['AWAFOS']['callerid'];
                $strDataID = $arrJSON['AWAFOS']['dataid'];
                $strFunction = $arrJSON['AWAFOS']['function'];
                $arrParameters = $arrJSON['AWAFOS']['parameters'];
//die('3:' . $strFunction);
				// NOTE: do NOT move these out of public, as the ANDROID and IOS apps rely on them in public
				$strGlobalDevicePushToken = getJSONParameter($arrParameters, 'devicetoken');

                // put some logic in here to deal with caller version and callerid if necessary
                $strResult = self::processActionWithDisabledCheck($strSecurityToken, $strDataID, $strFunction, $arrParameters, $strClientVersion, $strDeviceIDCookie);

                if ($blnJSONP_a) 
				{
                    $strResult = $strCallback_a . '(' . $strResult . ');';
                }
            }
        }

        return $strResult;
    }
}
