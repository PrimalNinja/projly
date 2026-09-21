<?php
function initialiseTransmission($objConn_a, $strSecurityToken_a, $strDataID_a, $strIntegrationOutboundID_a)
{    
    $strTableNameIntegrationOutbound = getTableNameEntity("integrationoutbound", false);
    $strTableNameIntegrationOutboundPlugin = getTableNameEntity("integrationoutboundplugin", false);
    $strTableNameIntegrationOutboundType = getTableNameEntity("integrationoutboundtype", false);
    $strTableNameServer = getTableNameEntity("server", false);

    $strResult = "";

    // these are required fields
    $strLogin = $_SESSION['server_loggedin_user'];
          
    if (dependencies('integration/createIntegrationTaskOutbound'))
    {
        dbBeginTrans($objConn_a, __FUNCTION__);

        $strSQL = "select id, client_id, g561d6970_cd14_4e7b_8ac4_460ae9f6a750_description description, jsondata from ~TABLENAMEINTEGRATIONOUTBOUND~ where id = ~INTEGRATIONOUTBOUNDID~";
        $strSQL = str_replace('~TABLENAMEINTEGRATIONOUTBOUND~', ff($strTableNameIntegrationOutbound), $strSQL);
        $strSQL = str_replace('~INTEGRATIONOUTBOUNDID~', ff($strIntegrationOutboundID_a), $strSQL);
        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

        if ($arrRow = dbReadRecord($objResult))
        {
            $strCode = 'TRANSMITSTEP2';
            $strDescription = 'Transmit Step 2';
            $strStatus = 'PENDING'; 
            $strIsProcessed = "N";
            $strIsInbound = "N";
            
            $strIntegrationOutboundID = $arrRow['id'];
            $strClientID = $arrRow['client_id'];
            $strIntegrationOutboundDescription = $arrRow['description'];
            $arrIntegrationOutboundJSONData = json_decode($arrRow['jsondata'], true);

            $strOutboundGUID = formValueGetBySectionCodeFieldCode($arrIntegrationOutboundJSONData, 'g561d6970-cd14-4e7b-8ac4-460ae9f6a750', 'OUTBOUNDGUID');

            $arrField = formFieldGetBySectionCodeFieldCode($arrIntegrationOutboundJSONData, 'g561d6970-cd14-4e7b-8ac4-460ae9f6a750', 'INTEGRATIONOUTBOUNDTYPE');
            $strIntegrationOutboundTypeID = $arrField['p_value'];
            $strIntegrationOutboundTypeDescription = $arrField['p_valuedescription'];

            $arrField = formFieldGetBySectionCodeFieldCode($arrIntegrationOutboundJSONData, 'g561d6970-cd14-4e7b-8ac4-460ae9f6a750', 'SERVER');
            $strServerID = $arrField['p_value'];
            $strServerDescription = $arrField['p_valuedescription'];

            $arrField = formFieldGetBySectionCodeFieldCode($arrIntegrationOutboundJSONData, 'g561d6970-cd14-4e7b-8ac4-460ae9f6a750', 'PARAMETERS');
            $strParameters = $arrField['p_value'];

            createIntegrationTaskOutbound($objConn_a, $strClientID, $strCode, $strDescription, $strStatus, $strIsProcessed, $strOutboundGUID, 
            $strIntegrationOutboundID, $strIntegrationOutboundDescription, $strIntegrationOutboundTypeID, $strIntegrationOutboundTypeDescription, 
            $strServerID, $strServerDescription, $strParameters);

            // update status of integrationoutbound
            $strSQL = "update ~TABLENAMEINTEGRATIONOUTBOUND~ set is_processed = 'Y' where id = ~INTEGRATIONOUTBOUNDID~";
            $strSQL = str_replace('~TABLENAMEINTEGRATIONOUTBOUND~', ff($strTableNameIntegrationOutbound), $strSQL);
            $strSQL = str_replace('~INTEGRATIONOUTBOUNDID~', ff($strIntegrationOutboundID), $strSQL);

            dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
        }
        
        dbCloseRecordset($objResult);

        if (dbEndTrans($objConn_a, __FUNCTION__))
        {
            $strResult = "Integration task created.";
        }
    }
    
    return $strResult;
}

//function transmitData($objConn_a, $strIntegrationTaskID_a)
/**
 * creates another integrationtask with code 'PREPAREENTITYDATA'. integration task created per json entity parameter
 * e.g [ { "entityname" : "GENDER", { "entityname" : "SUBURB" }] - two integration tasks will be created by this example.
 */
function transmitStep2($objConn_a, $strIntegrationTaskID_a)
{
    $strTableNameIntegrationTaskOutbound = getTableNameEntity("integrationtaskoutbound", false);

    $blnResult = true;

    if (dependencies('integration/createIntegrationTaskOutbound'))
    {
        $strSQL = "select client_id, integrationoutbound_id, jsondata from ~TABLENAMEINTEGRATIONTASKOUTBOUND~ where id = ~INTEGRATIONTASKID~";
        $strSQL = str_replace('~TABLENAMEINTEGRATIONTASKOUTBOUND~', ff($strTableNameIntegrationTaskOutbound), $strSQL);
        $strSQL = str_replace('~INTEGRATIONTASKID~', ff($strIntegrationTaskID_a), $strSQL);    
        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

        if ($arrRow = dbReadRecord($objResult))
        {
            $strClientID = $arrRow['client_id'];
            $strIntegrationOutboundID = $arrRow['integrationoutbound_id'];
            $strJSONData = $arrRow['jsondata'];

            $arrJSONData = json_decode($strJSONData, true);

            $strTableNameIntegrationOutbound = getTableNameEntity("integrationoutbound", false);
            $strSQL = "select jsondata returnvalue from ~TABLENAMEINTEGRATIONOUTBOUND~ where id = ~INTEGRATIONOUTBOUNDID~";
            $strSQL = str_replace('~TABLENAMEINTEGRATIONOUTBOUND~', ff($strTableNameIntegrationOutbound), $strSQL);
            $strSQL = str_replace('~INTEGRATIONOUTBOUNDID~', ff($strIntegrationOutboundID), $strSQL);

            $arrIntegrationOutboundJSONData = [];
            $strIntegrationOutboundJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
            $arrIntegrationOutboundJSONData = json_decode($strIntegrationOutboundJSONData, true);            
            $strParameters = formValueGetBySectionCodeFieldCode($arrIntegrationOutboundJSONData, 'g561d6970-cd14-4e7b-8ac4-460ae9f6a750', 'PARAMETERS');
            $arrParameters = [];
            
            if (strlen($strParameters) > 0)
            {
                $arrParameters = json_decode($strParameters, true);
            }
            
            // parameters are in array here. loop into the array of parameters
            foreach ($arrParameters as $arrItemParameter)
            {    
                $strOutboundGUID = formValueGetBySectionCodeFieldCode($arrJSONData, 'g0db1c356-65dc-4b1b-8fb9-ebb5fc3df916', 'OUTBOUNDGUID');

                $arrField = formFieldGetBySectionCodeFieldCode($arrJSONData, 'g0db1c356-65dc-4b1b-8fb9-ebb5fc3df916', 'INTEGRATIONOUTBOUND');
                $strIntegrationOutboundID = $arrField['p_value'];
                $strIntegrationOutboundDescription = $arrField['p_valuedescription'];

                $arrField = formFieldGetBySectionCodeFieldCode($arrJSONData, 'g0db1c356-65dc-4b1b-8fb9-ebb5fc3df916', 'INTEGRATIONOUTBOUNDPLUGIN');
                $strIntegrationOutboundPluginID = $arrField['p_value'];
                $strIntegrationOutboundPluginDescription = $arrField['p_valuedescription'];

                $arrField = formFieldGetBySectionCodeFieldCode($arrJSONData, 'g0db1c356-65dc-4b1b-8fb9-ebb5fc3df916', 'INTEGRATIONOUTBOUNDTYPE');
                $strIntegrationOutboundTypeID = $arrField['p_value'];
                $strIntegrationOutboundTypeDescription = $arrField['p_valuedescription'];

                $arrField = formFieldGetBySectionCodeFieldCode($arrJSONData, 'g0db1c356-65dc-4b1b-8fb9-ebb5fc3df916', 'SERVER');
                $strServerID = $arrField['p_value'];
                $strServerDescription = $arrField['p_valuedescription'];

                createIntegrationTaskOutbound($objConn_a, $strClientID, "TRANSMITSTEP3", "Transmit Step 3", "PENDING", "N", $strOutboundGUID, 
                $strIntegrationOutboundID, $strIntegrationOutboundDescription, $strIntegrationOutboundTypeID, $strIntegrationOutboundTypeDescription
                ,$strServerID, $strServerDescription, json_encode($arrItemParameter));
                
            }
        }

        dbCloseRecordset($objResult);
    }

    return $blnResult;
}


//function prepareEntityData($objConn_a, $strIntegrationTaskID_a)
/**
 * create OUTBOUNDDATA from integration task with the code "PREPAREENTITYDATA"
 */
function transmitStep3($objConn_a, $strIntegrationTaskID_a)
{
    $blnResult = false;

    $strTableNameIntegrationTaskOutbound = getTableNameEntity("integrationtaskoutbound", false);
    $strTableNameOutboundData = getTableNameEntity("outbounddata", false);

    // these are required fields
    $strLogin = $_SESSION['server_loggedin_user'];
    //$strClientID = $_SESSION['server_loggedin_clientid']; 

    if (dependencies('integration/createIntegrationTaskOutbound'))
    {
        dbBeginTrans($objConn_a, __FUNCTION__);

        $strSQL = "select client_id, integrationoutbound_id, jsondata from ~TABLENAMEINTEGRATIONTASKOUTBOUND~ where id = ~INTEGRATIONTASKID~";
        $strSQL = str_replace('~TABLENAMEINTEGRATIONTASKOUTBOUND~', ff($strTableNameIntegrationTaskOutbound), $strSQL);
        $strSQL = str_replace('~INTEGRATIONTASKID~', ff($strIntegrationTaskID_a), $strSQL);    
        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

        if ($arrRow = dbReadRecord($objResult))
        {
            $strClientID = $arrRow['client_id'];
            $strIntegrationOutboundID = $arrRow['integrationoutbound_id'];
            $strJSONData = $arrRow['jsondata'];

            $arrJSONData = json_decode($strJSONData, true);

            $strTableNameIntegrationOutbound = getTableNameEntity("integrationoutbound", false);
            $strSQL = "select jsondata returnvalue from ~TABLENAMEINTEGRATIONOUTBOUND~ where id = ~INTEGRATIONOUTBOUNDID~";
            $strSQL = str_replace('~TABLENAMEINTEGRATIONOUTBOUND~', ff($strTableNameIntegrationOutbound), $strSQL);
            $strSQL = str_replace('~INTEGRATIONOUTBOUNDID~', ff($strIntegrationOutboundID), $strSQL);

            $arrIntegrationOutboundJSONData = [];
            $strIntegrationOutboundJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
            $arrIntegrationOutboundJSONData = json_decode($strIntegrationOutboundJSONData, true);            
            $strParameters = formValueGetBySectionCodeFieldCode($arrIntegrationOutboundJSONData, 'g561d6970-cd14-4e7b-8ac4-460ae9f6a750', 'PARAMETERS');
            $arrParameters = json_decode($strParameters, true);
        
            $strOutboundGUID = formValueGetBySectionCodeFieldCode($arrJSONData, 'g0db1c356-65dc-4b1b-8fb9-ebb5fc3df916', 'OUTBOUNDGUID');

            $arrField = formFieldGetBySectionCodeFieldCode($arrJSONData, 'g0db1c356-65dc-4b1b-8fb9-ebb5fc3df916', 'INTEGRATIONOUTBOUND');
            $strIntegrationOutboundID = $arrField['p_value'];
            $strIntegrationOutboundDescription = $arrField['p_valuedescription'];

            $arrField = formFieldGetBySectionCodeFieldCode($arrJSONData, 'g0db1c356-65dc-4b1b-8fb9-ebb5fc3df916', 'INTEGRATIONOUTBOUNDPLUGIN');
            $strIntegrationOutboundPluginID = $arrField['p_value'];
            $strIntegrationOutboundPluginDescription = $arrField['p_valuedescription'];

            $arrField = formFieldGetBySectionCodeFieldCode($arrJSONData, 'g0db1c356-65dc-4b1b-8fb9-ebb5fc3df916', 'INTEGRATIONOUTBOUNDTYPE');
            $strIntegrationOutboundTypeID = $arrField['p_value'];
            $strIntegrationOutboundTypeDescription = $arrField['p_valuedescription'];

            $arrField = formFieldGetBySectionCodeFieldCode($arrJSONData, 'g0db1c356-65dc-4b1b-8fb9-ebb5fc3df916', 'SERVER');
            $strServerID = $arrField['p_value'];
            $strServerDescription = $arrField['p_valuedescription'];

            $strIntegrationTaskID = createIntegrationTaskOutbound($objConn_a, $strClientID, "TRANSMITSTEP4", "Transmit Step 4", "PENDING", "N", $strOutboundGUID, 
            $strIntegrationOutboundID, $strIntegrationOutboundDescription, $strIntegrationOutboundTypeID, $strIntegrationOutboundTypeDescription, $strServerID, $strServerDescription, $strParameters);
                        
            $strEntityCode = $arrParameters[0]['source'];

            if (strlen($strEntityCode) > 0)
            {
                $strTableNameEntity = getTableNameEntity(strtoupper($strEntityCode), false);

                $strCode = "OUTBOUNDDATA";
                $strDescription = "Outbound Data";
                
                $strIsSent = "N";
                $strIsConfirmed = "N";
                    
                // these entity ID's are required and cannot be empty
                $strEntityID = getEntityID($objConn_a, "systemform");
                $strDataEntityID = getEntityID($objConn_a, "outbounddata");


                // this is the basic format for inserting entity table record. you can copy/paste this section whenever you insert entity table record
                $strSQL = "insert into ~TABLENAMEOUTBOUNDDATA~ (client_id, entity_id, dataentity_id, entitydata_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, is_sent, is_confirmed, integrationtaskoutbound_id)             
                            select ~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, id, code, description, '~ISENABLED~', ~CLIENTID~, jsondata, '~MODIFYUSER~', '~MODIFYDATETIME~', 
                            '~ISSENT~', '~ISCONFIRMED~', ~INTEGRATIONTASKID~ from ~TABLENAMEENTITY~ ";
                $strSQL = str_replace('~TABLENAMEOUTBOUNDDATA~', ff($strTableNameOutboundData), $strSQL);
                $strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);

                $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
                $strSQL = str_replace('~ISENABLED~', 'Y', $strSQL);
                $strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
                $strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
                $strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
                $strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
                
                //custom fields
                $strSQL = str_replace('~ISSENT~', $strIsSent, $strSQL);
                $strSQL = str_replace('~ISCONFIRMED~', $strIsConfirmed, $strSQL);
                $strSQL = str_replace('~INTEGRATIONTASKID~', $strIntegrationTaskID, $strSQL);
                dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);                
            }
            
        }

        dbCloseRecordset($objResult);
        $blnResult = dbEndTrans($objConn_a, __FUNCTION__);
    }

    return $blnResult;
}


//function transmitEntityData($objConn_a, $strIntegrationTaskID_a)
/**
 * send data from OUTBOUNDATA to another server
 */
function transmitStep4($objConn_a, $strIntegrationTaskID_a)
{   
    $strTableNameIntegrationTaskOutbound = getTableNameEntity("integrationtaskoutbound", false);
    $strTableNameIntegrationOutbound = getTableNameEntity("integrationoutbound", false);
    $strTableNameOutboundData = getTableNameEntity("outbounddata", false);

    $blnResult = true;
    $intTransmitLimit = 1;
    
    $strIntegrationOutboundID = "";
    $strIntegrationTaskJSONData = "";

    $strSQL = "select id, jsondata from ~TABLENAMEINTEGRATIONOUTBOUND~ where id in 
               (select integrationoutbound_id id from ~TABLENAMEINTEGRATIONTASKOUTBOUND~ where id = ~INTEGRATIONTASKID~)";
    $strSQL = str_replace('~TABLENAMEINTEGRATIONTASKOUTBOUND~', ff($strTableNameIntegrationTaskOutbound), $strSQL);
    $strSQL = str_replace('~TABLENAMEINTEGRATIONOUTBOUND~', ff($strTableNameIntegrationOutbound), $strSQL);
    $strSQL = str_replace('~INTEGRATIONTASKID~', ff($strIntegrationTaskID_a), $strSQL);    
    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

    if ($arrRow = dbReadRecord($objResult))
    { 
        $strIntegrationOutboundID = $arrRow['id'];
        $strIntegrationTaskJSONData = $arrRow['jsondata'];
        $arrIntegrationTaskJSONData = json_decode($strIntegrationTaskJSONData, true);
        $strIntegrationTaskParameters = formValueGetBySectionCodeFieldCode($arrIntegrationTaskJSONData, 'g561d6970-cd14-4e7b-8ac4-460ae9f6a750', "PARAMETERS");

        $arrIntegrationTaskParameters = json_decode($strIntegrationTaskParameters, true);

        if (isset($arrIntegrationTaskParameters['blocksize']) && intval($arrIntegrationTaskParameters['blocksize']) > 0)
        {
            $intTransmitLimit = intval($arrIntegrationTaskParameters['blocksize']);
        }
    }
    
    dbCloseRecordset($objResult);

    $strSQL = "select id, entitydata_id, code, description, jsondata from ~TABLENAMEOUTBOUNDDATA~ where integrationtaskoutbound_id = ~INTEGRATIONTASKOUTBOUNDID~ and is_sent = 'N' LIMIT ~LIMIT~";
    $strSQL = str_replace('~TABLENAMEOUTBOUNDDATA~', ff($strTableNameOutboundData), $strSQL);
    $strSQL = str_replace('~INTEGRATIONTASKOUTBOUNDID~', ff($strIntegrationTaskID_a), $strSQL);
    $strSQL = str_replace('~LIMIT~', ff($intTransmitLimit), $strSQL);
    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

    while ($arrRow = dbReadRecord($objResult))
    {         
        dbBeginTrans($objConn_a, __FUNCTION__);
              
        $strOutboundDataID = $arrRow['id'];   
        $strEntityDataID = $arrRow['entitydata_id'];
        $strCode = $arrRow['code'];
        $strDescription = $arrRow['description'];      
        $strJSONData = $arrRow['jsondata'];
        
        $strResponse = sendTransmitData($objConn_a, $strIntegrationOutboundID, $strIntegrationTaskParameters, $strEntityDataID, $strCode, $strDescription, $strJSONData);
                
        $strSQL = "update ~TABLENAMEOUTBOUNDDATA~ set is_sent = 'Y', is_confirmed = 'Y' where id = ~OUTBOUNDDATAID~";
        $strSQL = str_replace('~TABLENAMEOUTBOUNDDATA~', ff($strTableNameOutboundData), $strSQL);
        $strSQL = str_replace('~OUTBOUNDDATAID~', ff($strOutboundDataID), $strSQL);
        dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);   
        
        $blnResult = dbEndTrans($objConn_a, __FUNCTION__);
   }

    dbCloseRecordset($objResult);
    
    return $blnResult;
}

function sendTransmitData($objConn_a, $strIntegrationOutboundID_a, $strIntegrationTaskData_a, $strEntityDataID_a, $strCode_a, $strDescription_a,  $strInboundData_a)
{
    $strTableNameIntegrationOutbound = getTableNameEntity("integrationoutbound", false);
    $strTableNameIntegrationOutboundType = getTableNameEntity("integrationoutboundtype", false);
    $strTableNameServer = getTableNameEntity("server", false);
    $strTableNameServerProtocol = getTableNameEntity("serverprotocol", false);

    $strResult = "";

    $strClientID = "";
    $strServerID = "";
    $strIntegrationOutboundTypeID = "";
    $strGUID = "";

    $strSecurityToken = ""; // how to pass this?
    $strDataID = ""; // how to pass this?

    $strSQL = "select client_id, server_id, integrationoutboundtype_id, g561d6970_cd14_4e7b_8ac4_460ae9f6a750_outboundguid guid from ~TABLENAMEINTEGRATIONOUTBOUND~ where id = ~INTEGRATIONOUTBOUNDID~";
    $strSQL = str_replace('~TABLENAMEINTEGRATIONOUTBOUND~', ff($strTableNameIntegrationOutbound), $strSQL);
    $strSQL = str_replace('~INTEGRATIONOUTBOUNDID~', ff($strIntegrationOutboundID_a), $strSQL);
    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

    if ($arrRow = dbReadRecord($objResult))
    {
        $strClientID = $arrRow['client_id']; // passing it to other server cuz other server client id is blank. just for testing.
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
            ],
            [
                'name' => 'integrationtaskdata',
                'value' => $strIntegrationTaskData_a
            ],
            // [
            //     'name' => 'entitydataid',
            //     'value' => $strEntityDataID_a
            // ],
            [
                'name' => 'code',
                'value' => $strCode_a
            ],
            [
                'name' => 'description',
                'value' => $strDescription_a
            ],
            [
                'name' => 'inbounddata',
                'value' => $strInboundData_a
            ] //,
            // [
            //     'name' => 'client_id',
            //     'value' => $strClientID
            // ]
        ];

        $strFunction = 'integration_inbound';
        $arrParameters = $arrPostData;
            
        // create json request
        
        $objJSON = new stdClass();
        $objRequest = new stdClass();
        
        // $objRequest->clientversion = CLIENT_VERSION;
        //$objRequest->deviceidcookie = $this->m_strDeviceIDCookie; //''; //COOKIE_DEVICENAME;
        //$objRequest->callerid = CALLERID_PUBLIC;
        
        $objRequest->securitytoken = $strSecurityToken;
        $objRequest->dataid = $strDataID;
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