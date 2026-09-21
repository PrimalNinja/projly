<?php

// list file formats
function actionImportFileFormatsList($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    $strTableNameFileFormat = getTableNameEntity('fileformat', false);
    $strTableNameFileType = getTableNameEntity('filetype', false);
    $strTableNameFormatType = getTableNameEntity('formattype', false);

    $arrResult = array();

    // permission check
    if (!hasPermission($objConn_a, 'VW_FF', __FUNCTION__, true)) {return false;}

    // parameters
    $arrFilter = getJSONParameter($arrParameters_a, 'filter');
    $arrOrder = getJSONParameter($arrParameters_a, 'order');

    // paging
    $intOffset = intval(getJSONParameter($arrParameters_a, 'offset'));

    // limit
    $intLimit = intval(getJSONParameter($arrParameters_a, 'limit'));

    if ($intLimit == 0 || $intLimit > NONAJAXGRIDLIMIT ) 
	{
       $intLimit = NONAJAXGRIDLIMIT;
    }
    else if ($intLimit < 0) 
	{
       $intLimit = 1; // make it one. negative limit returns no record
    }

    // for mapping and security purposes
    $arrFields = array();
    $arrFields['type'] = 'ft.description';
    $arrFields['code'] = 'ff.code';
    $arrFields['description'] = 'ff.description';
    $arrFields['enabled'] = 'ff.is_enabled';
	$arrFields['isdefault'] = 'ff.is_default';
    $arrFields['canuseredit'] = 'fmt.is_userdefined';

    // initialisations
    $strClientID = $_SESSION['server_loggedin_clientid'];

    $intRecordCount = 0;

    $strSQL = "
select count(*) returnvalue
from ~TABLENAMEFILEFORMAT~ ff, ~TABLENAMEFILETYPE~ ft, ~TABLENAMEFORMATTYPE~ fmt
where ft.id = ff.filetype_id and fmt.id = ff.formattype_id and ff.client_id = ~CLIENTID~ and (ft.gc5913c98_7aff_4753_9b43_6f388e7b707f_applications = '' or ft.gc5913c98_7aff_4753_9b43_6f388e7b707f_applications is null or ft.gc5913c98_7aff_4753_9b43_6f388e7b707f_applications like '%~APPCODE~%')

" . dbBuildWhere('and', $arrFields, $arrFilter) . dbBuildOrderBy($arrFields, $arrOrder);
    $strSQL = str_replace('~TABLENAMEFILEFORMAT~', ff($strTableNameFileFormat), $strSQL);
    $strSQL = str_replace('~TABLENAMEFILETYPE~', ff($strTableNameFileType), $strSQL);
    $strSQL = str_replace('~TABLENAMEFORMATTYPE~', ff($strTableNameFormatType), $strSQL);
    $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
	$strSQL = str_replace('~APPCODE~', ff(APP_CODE), $strSQL);
    $intRecordCount = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

    // fetch
    $strSQL =
    "
select ff.id id, ff.code, ff.description description, ft.description type, ff.is_enabled, ff.is_default, fmt.is_userdefined
from ~TABLENAMEFILEFORMAT~ ff, ~TABLENAMEFILETYPE~ ft, ~TABLENAMEFORMATTYPE~ fmt
where ft.id = ff.filetype_id and ff.client_id = ~CLIENTID~ and fmt.id = ff.formattype_id and (ft.gc5913c98_7aff_4753_9b43_6f388e7b707f_applications = '' or ft.gc5913c98_7aff_4753_9b43_6f388e7b707f_applications is null or ft.gc5913c98_7aff_4753_9b43_6f388e7b707f_applications like '%~APPCODE~%') 

" . dbBuildWhere('and', $arrFields, $arrFilter) . dbBuildOrderBy($arrFields, $arrOrder) . " limit ~OFFSET~,~LIMIT~";

    $strSQL = str_replace('~TABLENAMEFILEFORMAT~', ff($strTableNameFileFormat), $strSQL);
    $strSQL = str_replace('~TABLENAMEFILETYPE~', ff($strTableNameFileType), $strSQL);
    $strSQL = str_replace('~TABLENAMEFORMATTYPE~', ff($strTableNameFormatType), $strSQL);
    $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
	$strSQL = str_replace('~APPCODE~', ff(APP_CODE), $strSQL);
    $strSQL = str_replace('~OFFSET~', $intOffset, $strSQL);
    $strSQL = str_replace('~LIMIT~', $intLimit, $strSQL);

    $intRowNum = 1;

    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
    while ($arrRow = dbReadRecord($objResult)) 
	{
        $arrResult[] = array(
            "id" => secureEntityValue('FILEFORMAT', $arrRow['id']),
            "rownum" => $intRowNum,
            "type" => $arrRow['type'],
            "code" => $arrRow['code'],
            "description" => $arrRow['description'],
            "enabled" => $arrRow['is_enabled'],
            "isdefault" => $arrRow['is_default'],
            "canuseredit" => $arrRow['is_userdefined'],
            "recordcount" => $intRecordCount,
            "limited" => $intLimit
        );
        $intRowNum++;
    }
    dbCloseRecordset($objResult);

    return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
}