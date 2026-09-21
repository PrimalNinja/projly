<?php

// fetch suburbs
function actionCoreSuburbSearchBySuburb($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
	$strTableNameSuburb = getTableNameEntity("suburb", false);
	
    $arrResult = array();

    // permission check
	if (!hasPermission($objConn_a, 'VW_SUBURB', __FUNCTION__, true)) {return false;}

    // parameters
    $arrFilter = getJSONParameter($arrParameters_a, 'filter');
    $arrOrder = getJSONParameter($arrParameters_a, 'order');

    // for mapping and security purposes
    // $arrFieldsSoundex = array();	// uncomment for soundex
    // $arrFieldsSoundex['suburb'] = 'soundex(suburb)';	// uncomment for soundex
    // $arrFieldsSoundex['state'] = 'state';	// uncomment for soundex
    // $arrFieldsSoundex['postcode'] = 'postcode';	// uncomment for soundex
    // $arrFieldsSoundex['country'] = 'soundex(country)';	// uncomment for soundex

    $arrFields = array();
    $arrFields['suburb'] = 'suburb';
    $arrFields['state'] = 'state';
    $arrFields['postcode'] = 'postcode';
    $arrFields['country'] = 'country';

    // fetch
	$strSQL = "";
    // $strSQL =	// uncomment for soundex
    // "
// select distinct id, suburb, state, postcode, country
// from ~TABLENAMESUBURB~
// union
// " . dbBuildWhere('where', $arrFieldsSoundex, $arrFilter);

    $strSQL .=
    "
select id, suburb, state, postcode, country
from ~TABLENAMESUBURB~

" . dbBuildWhere('where', $arrFields, $arrFilter) . dbBuildOrderBy($arrFields, $arrOrder) . " limit 0," . NONAJAXGRIDLIMIT;
	$strSQL = str_replace('~TABLENAMESUBURB~', ff($strTableNameSuburb), $strSQL);

    $intRowNum = 1;

    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
    while ($arrRow = dbReadRecord($objResult)) 
	{
        $arrResult[] = array(
            "id" => secureEntityValue('SUBURB', $arrRow['id']),
            "rownum" => $intRowNum,
            "suburb" => $arrRow['suburb'],
            "state" => $arrRow['state'],
            "postcode" => $arrRow['postcode'],
            "country" => $arrRow['country'],
        );
		
        $intRowNum++;
    }
    dbCloseRecordset($objResult);

    return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
}
