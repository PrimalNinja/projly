<?php

function actionCoreDevicesFetchAll($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a) 
{ 
	$strTableNameClient = getTableNameEntity("client", false);
	$strTableNameDevice = getTableNameEntity("device", false);
	$strTableNameUser = getTableNameEntity("user", false);
    
    $arrResult = array();

    // permission check
    if (!hasPermission($objConn_a, 'NOCHECK', __FUNCTION__, true)) {return false;}
       
    // parameters
    $arrFilter = getJSONParameter($arrParameters_a, 'filter');
    $arrOrder = getJSONParameter($arrParameters_a, 'order');
    
	// initialisations
    $strClientID = $_SESSION['server_loggedin_clientid'];

    $arrFields = array();
    
    // get table count
    $strSQL = "select count(*) returnvalue from ~TABLENAMEDEVICE~ where client_id = ~CLIENTID~ " . dbBuildWhere('and', $arrFields, $arrFilter);
	$strSQL = str_replace('~TABLENAMEDEVICE~', ff($strTableNameDevice), $strSQL);
    $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
    $intRecordCount = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

    // fetch
    $strSQL = "select id, fff26af3dd_710e_4510_8153_058ed948e34e_user user, fff26af3dd_710e_4510_8153_058ed948e34e_description device from ~TABLENAMEDEVICE~ where client_id = ~CLIENTID~ " . dbBuildWhere('and', $arrFields, $arrFilter) . " limit 0," . NONAJAXGRIDLIMIT;
	$strSQL = str_replace('~TABLENAMEDEVICE~', ff($strTableNameDevice), $strSQL);
    $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
    
    $intRowNum = 1;

    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
    
    $arrResult = array();
    
    while ($arrRow = dbReadRecord($objResult)) 
	{
        $arrResult[] = array(
            "id" => secureEntityValue('DEVICE', $arrRow['id']),
            "user" => $arrRow['user'],
            "device" => $arrRow['device'],
            "rownum" => $intRowNum,
            "recordcount" => $intRecordCount,
            "limited" => NONAJAXGRIDLIMIT        
        );
		
        $intRowNum++;
    }
    
    dbCloseRecordset($objResult);
        
    return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
    
}
