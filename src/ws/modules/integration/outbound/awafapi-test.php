<?php

function initialiseTransmission($objConn_a, $strSecurityToken_a, $strDataID_a, $strIntegrationOutboundID_a)
{
    $strTableNameIntegrationOutbound = getTableNameEntity("integrationoutbound", false);
    $strTableNameIntegrationOutboundType = getTableNameEntity("integrationoutboundtype", false);
    $strTableNameServer = getTableNameEntity("server", false);
    $strTableNameServerProtocol = getTableNameEntity("serverprotocol", false);

    $strResult = "";

    $strServerID = "";
    $strIntegrationOutboundTypeID = "";
    $strGUID = "";


    $strSQL = "select server_id, integrationoutboundtype_id, g561d6970_cd14_4e7b_8ac4_460ae9f6a750_outboundguid guid from ~TABLENAMEINTEGRATIONOUTBOUND~ where id = ~INTEGRATIONOUTBOUNDID~";
    $strSQL = str_replace('~TABLENAMEINTEGRATIONOUTBOUND~', ff($strTableNameIntegrationOutbound), $strSQL);
    $strSQL = str_replace('~INTEGRATIONOUTBOUNDID~', ff($strIntegrationOutboundID_a), $strSQL);
    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

    if ($arrRow = dbReadRecord($objResult))
    {
        $strServerID = $arrRow['server_id'];
        $strIntegrationOutboundTypeID = $arrRow['integrationoutboundtype_id'];
        $strGUID = $arrRow['guid'];

        $strSQL = "select g407d9323_cadb_42f2_ac4d_ae6528520610_code returnvalue from ~TABLENAMEINTEGRATIONOUTBOUNDTYPE~ where id = ~INTEGRATIONOUTBOUNDTYPEID~";
        $strSQL = str_replace('~TABLENAMEINTEGRATIONOUTBOUNDTYPE~', ff($strTableNameIntegrationOutboundType), $strSQL);
        $strSQL = str_replace('~INTEGRATIONOUTBOUNDTYPEID~', ff($strIntegrationOutboundTypeID), $strSQL);
        $strIntegrationOutboundType = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
    }

    dbCloseRecordset($objResult);

    $strSQL = "select s.jsondata, p.ge9e3eb2e_0386_4df7_8d6d_5eb36073260e_code serverprotocol_code from ~TABLENAMESERVER~ s, ~TABLENAMESERVERPROTOCOL~ p where s.serverprotocol_id = p.id and s.id = ~SERVERID~";
	$strSQL = str_replace('~TABLENAMESERVER~', ff($strTableNameServer), $strSQL);
	$strSQL = str_replace('~TABLENAMESERVERPROTOCOL~', ff($strTableNameServerProtocol), $strSQL);
	$strSQL = str_replace('~SERVERID~', ff($strServerID), $strSQL);
	$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
	
	if ($arrRow = dbReadRecord($objResult))
	{               
        $arrJSONData = json_decode($arrRow['jsondata'], true);
		
		$strServerProtocol = $arrRow['serverprotocol_code'];
		$strServerPort = formValueGetBySectionCodeFieldCode($arrJSONData, 'gafb65c33-7df8-4b01-a919-4df125c08f0e', 'CONNECTIONPORT');
		$strServerURL = formValueGetBySectionCodeFieldCode($arrJSONData, 'gafb65c33-7df8-4b01-a919-4df125c08f0e', 'CONNECTIONURL');
		$strServerPath = formValueGetBySectionCodeFieldCode($arrJSONData, 'gafb65c33-7df8-4b01-a919-4df125c08f0e', 'CONNECTIONPATH');
		$strServerLogin = formValueGetBySectionCodeFieldCode($arrJSONData, 'gafb65c33-7df8-4b01-a919-4df125c08f0e', 'CONNECTIONLOGIN');
		$strServerPassword = formValueGetBySectionCodeFieldCode($arrJSONData, 'gafb65c33-7df8-4b01-a919-4df125c08f0e', 'CONNECTIONPASSWORD');
				
        // fetch proxy details and use it if enabled
        /*
		$strUseProxy = formValueGetBySectionCodeFieldCode($arrJSONData, 'gafb65c33-7df8-4b01-a919-4df125c08f0e', 'USEPROXY');
		$strProxyPort = formValueGetBySectionCodeFieldCode($arrJSONData, 'gafb65c33-7df8-4b01-a919-4df125c08f0e', 'PROXYPORT');
		$strProxyURL = formValueGetBySectionCodeFieldCode($arrJSONData, 'gafb65c33-7df8-4b01-a919-4df125c08f0e', 'PROXYURL');
		$strProxyLogin = formValueGetBySectionCodeFieldCode($arrJSONData, 'gafb65c33-7df8-4b01-a919-4df125c08f0e', 'PROXYLOGIN');
		$strProxyPassword = formValueGetBySectionCodeFieldCode($arrJSONData, 'gafb65c33-7df8-4b01-a919-4df125c08f0e', 'PROXYPASSWORD');
        */	

        $arrPostData = [
            [
                'name' => 'guid',
                'value' => $strGUID
            ],
            [
                'name' => 'inboundtype',
                'value' => $strIntegrationOutboundType
            ]
        ];

        $strFunction = 'integration_inbound';
        $arrParameters = $arrPostData;
            
        // create json request
        
        $objJSON = new stdClass();
        $objRequest = new stdClass();
        
        // $objRequest->clientversion = CLIENT_VERSION;
        //$objRequest->deviceidcookie = $this->m_strDeviceIDCookie; //''; //COOKIE_DEVICENAME;
        //$objRequest->callerid = CALLERID_PUBLIC;
        
        $objRequest->securitytoken = $strSecurityToken_a;
        $objRequest->dataid = $strDataID_a;
        $objRequest->function = $strFunction;
        $objRequest->parameters = $arrParameters;

        $objJSON->AWAFOS = $objRequest;
        
        $strPostFields = json_encode($objJSON);
            
        $intTimeout = 20;
        $strUserAgent = $_SERVER['HTTP_USER_AGENT'];
        $strWebServiceURL = $strServerURL . $strServerPath;
        //$strCookieFile = SERVER_TEMP_DIR . 'cookies/' . $this->m_strSecurityToken . '-mitsukibo-cookie';    		
               
        $objCurl = curl_init();
        curl_setopt($objCurl, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=UTF-8'));
        curl_setopt($objCurl, CURLOPT_URL, $strWebServiceURL);
        curl_setopt($objCurl, CURLOPT_REFERER, $strWebServiceURL);
        curl_setopt($objCurl, CURLOPT_HEADER, 0);
        curl_setopt($objCurl, CURLOPT_POST, true);
        curl_setopt($objCurl, CURLOPT_POSTFIELDS, $strPostFields);
        curl_setopt($objCurl, CURLOPT_USERAGENT, $strUserAgent);

        curl_setopt($objCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($objCurl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($objCurl, CURLOPT_CONNECTTIMEOUT, $intTimeout);
        curl_setopt($objCurl, CURLOPT_SSL_VERIFYPEER, false);

        
        $strResponse = curl_exec($objCurl);

        if (curl_errno($objCurl))
        {
            $strResponse = curl_error($objCurl); //"There is an error in requesting the server";
        }
         
        curl_close($objCurl);
        
        //return $strResponse;
        $strResult = $strResponse;	
    }
    
    
    dbCloseRecordset($objResult);

    return $strResult;
}

function prepareEntityData($objConn_a, $strIntegrationOutboundID_a)
{
    $blnResult = false;
    return $blnResult;
}

function transmitData($objConn_a, $strIntegrationOutboundID_a)
{
    $blnResult = false;
    return $blnResult;
}

function transmitEntityData($objConn_a, $strIntegrationOutboundID_a)
{   $blnResult = false;
    return $blResult;
}