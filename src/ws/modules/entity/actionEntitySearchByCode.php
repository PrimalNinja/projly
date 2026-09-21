<?php

function actionEntitySearchByCode($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a) 
{ 
	$strTableNameEntity = getTableNameEntity('entity', false);
	
    $arrResult = array();

    // permission check
	if (!hasPermission($objConn_a, 'DEVELOPER', __FUNCTION__, true)) {return false;}

    // parameters
    $arrFilter = getJSONParameter($arrParameters_a, 'filter');

    $arrFields = array();
    $arrFields['code'] = 'code';

	// initialisations
	
    // fetch
	$strSQL = "";
	$strSQL =
	"
	select distinct id, code
	from ~TABLENAMEENTITY~

	" . dbBuildWhere('where', $arrFields, $arrFilter) . " limit 0," . NONAJAXGRIDLIMIT;
	$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);

    $intRowNum = 1;

    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
    while ($arrRow = dbReadRecord($objResult)) {
        $arrResult[] = array(
            "rownum" => $intRowNum,
            "code" => $arrRow['code']
        );
        $intRowNum++;
    }
    dbCloseRecordset($objResult);

    return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
    
}
