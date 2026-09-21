<?php

// list installable file formats
function actionImportFileFormatInstallableList($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
	$strTableNameFileFormat = getTableNameEntity("fileformat", false);

    $arrResult = array();

    // permission check
    if (!hasPermission($objConn_a, 'INS_FILEFORMAT', __FUNCTION__, true)) {return false;}

    // parameters
    $arrFilter = getJSONParameter($arrParameters_a, 'filter');
    $arrOrder = getJSONParameter($arrParameters_a, 'order');

    // for mapping and security purposes
    $arrFields = array();
    $arrFields['code'] = 'code';
    $arrFields['description'] = 'description';
    $arrFields['enabled'] = 'is_enabled';
	$arrFields['fileformattemplate'] = 'g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_fileformattemplate';
	$arrFields['formattype'] = 'g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_formattype';
	$arrFields['headerrows'] = 'g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_headerrows';
	$arrFields['is_import'] = 'g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_is_import';
	$arrFields['is_export'] = 'g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_is_export';
	$arrFields['is_default'] = 'g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_is_default';

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
    $strSQL = "
select count(*) returnvalue 
from ~TABLENAMEFILEFORMAT~ where client_id = ~CLIENTIDFROM~ and description not in (select description from ~TABLENAMEFILEFORMAT~ where client_id = ~CLIENTIDTO~)

" . dbBuildWhere('and', $arrFields, $arrFilter);
	$strSQL = str_replace('~TABLENAMEFILEFORMAT~', ff($strTableNameFileFormat), $strSQL);
    $strSQL = str_replace('~CLIENTIDFROM~', ff($strClientIDFrom), $strSQL);
    $strSQL = str_replace('~CLIENTIDTO~', ff($strClientIDTo), $strSQL);
    $intRecordCount = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

    //debug($strClientIDFrom);
    //debug($strClientIDTo);

    // fetch
    $strSQL =
    "
select id, code, description, is_enabled,
g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_fileformattemplate fileformattemplate, g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_fileformattype fileformattype, 
g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_headerrows headerrows, g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_is_import is_import, g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_is_export is_export, g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_is_default is_default
from ~TABLENAMEFILEFORMAT~
where client_id = ~CLIENTIDFROM~ and description not in (select description from ~TABLENAMEFILEFORMAT~ where client_id = ~CLIENTIDTO~)

" . dbBuildWhere('and', $arrFields, $arrFilter) . dbBuildOrderBy($arrFields, $arrOrder) . " limit 0," . NONAJAXGRIDLIMIT;

	$strSQL = str_replace('~TABLENAMEFILEFORMAT~', ff($strTableNameFileFormat), $strSQL);
    $strSQL = str_replace('~CLIENTIDFROM~', ff($strClientIDFrom), $strSQL);
    $strSQL = str_replace('~CLIENTIDTO~', ff($strClientIDTo), $strSQL);

    $intRowNum = 1;

    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
    while ($arrRow = dbReadRecord($objResult)) 
	{
        $arrResult[] = array(
            "id" => secureEntityValue('FILEFORMAT', $arrRow['id']),
            "rownum" => $intRowNum,
            "description" => $arrRow['description'],
            "code" => $arrRow['code'],
            "enabled" => $arrRow['is_enabled'],
			"fileformattemplate" => $arrRow['fileformattemplate'],
			"formattype" => $arrRow['fileformattype'],
			"headerrows" => $arrRow['headerrows'],
			"is_import" => $arrRow['is_import'],
			"is_export" => $arrRow['is_export'],
			"is_default" => $arrRow['is_default'],
            "recordcount" => $intRecordCount,
            "limited" => NONAJAXGRIDLIMIT
        );
        $intRowNum++;
    }
    dbCloseRecordset($objResult);

    return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
}
