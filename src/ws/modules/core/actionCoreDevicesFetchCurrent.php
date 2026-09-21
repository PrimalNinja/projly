<?php

function actionCoreDevicesFetchCurrent($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a) 
{ 
	$strTableNameDevice = getTableNameEntity("device", false);
    $strTableNameUserFormDevice = getTableNameEntity("userform_device", false);
    $strTableNameSystemForm = getTableNameEntity("systemform", false);
    
    $arrResult = array();

    // permission check
    if (!hasPermission($objConn_a, 'TODO', __FUNCTION__, true)) {return false;}
              
    // parameters
    $strFormEntityCode = getJSONParameter($arrParameters_a, 'formentity');

	// initialisations
    $strClientID = $_SESSION['server_loggedin_clientid'];
    $strUserID = $_SESSION['server_loggedin_userid'];
    $strDeviceID = $_SESSION['server_deviceid'];
    $strCurrentDeviceDescription = dbGetDescriptionFromID($objConn_a, $strTableNameDevice, $strDeviceID, __FUNCTION__);

    $arrFields = array();
     
    $strSQL = "select id returnvalue from ~TABLENAMESYSTEMFORM~ where code = '~ENTITYCODE~'";
    $strSQL = str_replace('~TABLENAMESYSTEMFORM~', ff($strTableNameSystemForm), $strSQL);
    $strSQL = str_replace('~ENTITYCODE~', ffeu($strFormEntityCode), $strSQL);
    $strSystemFormID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

    // get table count
    $strSQL = "select count(*) returnvalue from ~TABLENAMEDEVICE~ where client_id = ~CLIENTID~ and user_id = ~USERID~ and is_current = 'Y'";
	$strSQL = str_replace('~TABLENAMEDEVICE~', ff($strTableNameDevice), $strSQL);
    $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
    $strSQL = str_replace('~USERID~', ff($strUserID), $strSQL);
    $intRecordCount = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

    // fetch
    $strSQL = "select id,  fff26af3dd_710e_4510_8153_058ed948e34e_user user, fff26af3dd_710e_4510_8153_058ed948e34e_description device from ~TABLENAMEDEVICE~ where client_id = ~CLIENTID~ and user_id = ~USERID~ and is_current = 'Y' limit 0," . NONAJAXGRIDLIMIT;
	$strSQL = str_replace('~TABLENAMEDEVICE~', ff($strTableNameDevice), $strSQL);
    $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
    $strSQL = str_replace('~USERID~', ff($strUserID), $strSQL);

    $intRowNum = 1;

    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
    
    $arrResult = array();
    
    while ($arrRow = dbReadRecord($objResult)) 
    {
        $strSelected = "N";
        $strUserFormDeviceID = "";

        if (strlen($strSystemFormID) > 0)
        {
            $strSQL = "select id returnvalue from ~TABLENAMEUSERFORMDEVICE~ where client_id = ~CLIENTID~ and user_id = ~USERID~ and device_id = ~DEVICEID~ and systemform_id = ~SYSTEMFORMID~";
            $strSQL = str_replace('~TABLENAMEUSERFORMDEVICE~', ff($strTableNameUserFormDevice), $strSQL);
            $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
            $strSQL = str_replace('~USERID~', ff($strUserID), $strSQL);
            $strSQL = str_replace('~DEVICEID~', ff($arrRow['id']), $strSQL);
            $strSQL = str_replace('~SYSTEMFORMID~', ff($strSystemFormID), $strSQL);
            $strUserFormDeviceID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
        }
        
        if (strlen($strUserFormDeviceID) > 0)
        {
            $strSelected = "Y";
        }

        if ($strCurrentDeviceDescription === $arrRow['device'])
        {
            $strDevice = '<strong>(This)</strong>&nbsp;' . $arrRow['device'];
        }
        else
        {
            $strDevice = $arrRow['device'];
        }
               
        $arrResult[] = array(
            "id" => secureEntityValue('DEVICE', $arrRow['id']),
            "user" => $arrRow['user'],
            "device" => $strDevice,
            "isselected" => $strSelected,
            "rownum" => $intRowNum,
            "recordcount" => $intRecordCount,
            "limited" => NONAJAXGRIDLIMIT        
        );
		
        $intRowNum++;
    }
    
    dbCloseRecordset($objResult);
        
    return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
}
