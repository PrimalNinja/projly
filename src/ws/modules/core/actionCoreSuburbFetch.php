<?php

// fetch suburbs
function actionCoreSuburbFetch($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
	$strTableNameSuburb = getTableNameEntity("suburb", false);
	
    $arrResult = array();

    // permission check
	if (!hasPermission($objConn_a, 'VW_SUBURB', __FUNCTION__, true)) {return false;}

    // parameters
    $strSuburbID = revertSecuredValue(getJSONParameter($arrParameters_a, 'id'), 'id', true);

    // fetch
    $strSQL =
        "
select id, suburb, state, postcode, country
from ~TABLENAMESUBURB~
where id = ~SUBURBID~
";
	$strSQL = str_replace('~TABLENAMESUBURB~', ff($strTableNameSuburb), $strSQL);
    $strSQL = str_replace('~SUBURBID~', ff($strSuburbID), $strSQL);

    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
    if ($arrRow = dbReadRecord($objResult)) 
	{
        $arrResult[] = array(
            "id" => secureEntityValue('SUBURB', $arrRow['id']),
            "suburb" => $arrRow['suburb'],
            "state" => $arrRow['state'],
            "postcode" => $arrRow['postcode'],
            "country" => $arrRow['country']
        );
    }
    dbCloseRecordset($objResult);

    return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
}
