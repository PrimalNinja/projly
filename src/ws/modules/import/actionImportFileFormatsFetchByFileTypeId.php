<?php

// fetch file formats
function actionImportFileFormatsFetchByFileTypeId($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    $strResult = "";

	$arrResult = array();

    $strTableNameFileFormat = getTableNameEntity('fileformat', false);
    $strTableNameFileFormatTemplate = getTableNameEntity('fileformattemplate', false);

	// permission check
	if (!hasPermission($objConn_a, 'VW_FILEFORMAT', __FUNCTION__, true)) {return false;}

	// parameters
	$strFileTypeID = revertSecuredValue(getJSONParameter($arrParameters_a, 'filetype_id'), 'filetype_id', true);

	// initialisations
	//$strClientID = $_SESSION['server_loggedin_clientid'];

	// fetch
	$strSQL = "select ff.id, g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_code code, g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_description description from ~TABLENAMEFILEFORMAT~ ff, ~TABLENAMEFILEFORMATTEMPLATE~ fft where fft.id=ff.fileformattemplate_id and ff.is_enabled = 'Y' and g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_is_import = 'Y' and fft.filetype_id = ~FILETYPEID~ order by g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_description";
	$strSQL = str_replace('~TABLENAMEFILEFORMAT~', ff($strTableNameFileFormat), $strSQL);
	$strSQL = str_replace('~TABLENAMEFILEFORMATTEMPLATE~', ff($strTableNameFileFormatTemplate), $strSQL);
	#$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
	$strSQL = str_replace('~FILETYPEID~', ff($strFileTypeID), $strSQL);

	$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
	while ($arrRow = dbReadRecord($objResult)) 
	{
		$arrResult[] = array(
			"id" => secureEntityValue('FILEFORMAT', $arrRow['id']),
			"code" => $arrRow['code'],
			"description" => $arrRow['description']
		);
	}
	dbCloseRecordset($objResult);

	$strResult = createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);

    return $strResult;
}
