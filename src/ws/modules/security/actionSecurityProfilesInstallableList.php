<?php

// list installable profiles
function actionSecurityProfilesInstallableList($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
	$strTableNameProfile = getTableNameEntity("profile", false);

    $arrResult = array();

    // permission check
    if (!hasPermission($objConn_a, 'INS_PROFILE', __FUNCTION__, true)) {return false;}

    // parameters
    $arrFilter = getJSONParameter($arrParameters_a, 'filter');
    $arrOrder = getJSONParameter($arrParameters_a, 'order');

    // for mapping and security purposes
    $arrFields = array();
    $arrFields['description'] = 'description';
    $arrFields['admin'] = 'f9a0f134f_54b8_4e11_81b3_02d4f51dadd0_isadmin';
    $arrFields['default'] = 'f9a0f134f_54b8_4e11_81b3_02d4f51dadd0_isdefault';
    $arrFields['enabled'] = 'is_enabled';

    // initialisations
    $blnSystemArea = $_SESSION['server_loggedin_system'];
    $strClientIDTo = $_SESSION['server_loggedin_clientid'];
    $strClientIDFrom = "";
	
    if ($blnSystemArea) 
	{
        // install from the default area
        $strClientIDFrom = getDefaultClientID($objConn_a);
    } 
	else 
	{
        // install from the system area
        $strClientIDFrom = getSystemClientID($objConn_a);
    }

    // get table count
    $strSQL = "select count(*) returnvalue from ~TABLENAMEPROFILE~ where is_sysadmin = 'N' and client_id = ~CLIENTIDFROM~ and description not in (select description from ~TABLENAMEPROFILE~ where client_id = ~CLIENTIDTO~)" . dbBuildWhere('and', $arrFields, $arrFilter);
	$strSQL = str_replace('~TABLENAMEPROFILE~', ff($strTableNameProfile), $strSQL);
    $strSQL = str_replace('~CLIENTIDFROM~', ff($strClientIDFrom), $strSQL);
    $strSQL = str_replace('~CLIENTIDTO~', ff($strClientIDTo), $strSQL);
    $intRecordCount = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

    // fetch
    $strSQL =
    "
select id, description, f9a0f134f_54b8_4e11_81b3_02d4f51dadd0_isadmin admin, f9a0f134f_54b8_4e11_81b3_02d4f51dadd0_isdefault isdefault, is_enabled enabled
from ~TABLENAMEPROFILE~
where is_sysadmin = 'N' and client_id = ~CLIENTIDFROM~ and description not in (select description from ~TABLENAMEPROFILE~ where client_id = ~CLIENTIDTO~)

" . dbBuildWhere('and', $arrFields, $arrFilter) . dbBuildOrderBy($arrFields, $arrOrder) . " limit 0," . NONAJAXGRIDLIMIT;
	$strSQL = str_replace('~TABLENAMEPROFILE~', ff($strTableNameProfile), $strSQL);
    $strSQL = str_replace('~CLIENTIDFROM~', ff($strClientIDFrom), $strSQL);
    $strSQL = str_replace('~CLIENTIDTO~', ff($strClientIDTo), $strSQL);

    $intRowNum = 1;

    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
    while ($arrRow = dbReadRecord($objResult)) {
        $arrResult[] = array(
            "id" => secureEntityValue('PROFILE', $arrRow['id']),
            "rownum" => $intRowNum,
            "description" => $arrRow['description'],
            "admin" => $arrRow['admin'],
            "default" => $arrRow['isdefault'],
            "enabled" => $arrRow['enabled'],
            "recordcount" => $intRecordCount,
            "limited" => NONAJAXGRIDLIMIT
        );
        $intRowNum++;
    }
    dbCloseRecordset($objResult);

    return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
}
