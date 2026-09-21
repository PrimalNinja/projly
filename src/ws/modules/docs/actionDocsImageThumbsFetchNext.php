<?php

// fetch a series of image thumbs
function actionDocsImageThumbsFetchNext($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
	$strTableNameDocument = getTableNameEntity("document", false);
	$strTableNameDocumentType = getTableNameEntity("documenttype", false);
	
    $arrResult = array();

    // permission check
    if (!hasPermission($objConn_a, 'VW_DOCUMENT', __FUNCTION__, true)) { return false; }

    // parameters
    $strFetchCount = getJSONParameter($arrParameters_a, 'fetchcount');
    $strLastFetchedID = revertSecuredValue(getJSONParameter($arrParameters_a, 'lastfetched'), 'lastfetched', false);
	$strFilter = getJSONParameter($arrParameters_a, 'filter');

    $intFetchCount = intval($strFetchCount);
    if (($intFetchCount == 0) || ($intFetchCount > MAX_IMAGE_FETCH_COUNT)) 
	{
        $intFetchCount = MAX_IMAGE_FETCH_COUNT;
    }

    // initialisations
    $strClientID = $_SESSION['server_loggedin_clientid'];

	$strFilterSQL = "";
	if (strlen($strFilter) > 0)
	{
		$strFilterSQL = " and (ffe70fd7f1_401b_4c5c_b096_76f67d466982_code like '%~FILTER~%' or ffe70fd7f1_401b_4c5c_b096_76f67d466982_description like '%~FILTER~%' or ffe70fd7f1_401b_4c5c_b096_76f67d466982_tags like '%~FILTER~%' or ffe70fd7f1_401b_4c5c_b096_76f67d466982_filename like '%~FILTER~%')";
		$strFilterSQL = str_replace('~FILTER~', ff($strFilter), $strFilterSQL);
	}
	
    // fetch
    $strSQL = '';

	// note: checking for < because it is in descending order
    if (strlen($strLastFetchedID) > 0) 
	{
        $strSQL =
            "
	select d.id, d.code, d.description, d.filenamebase, d.ffe70fd7f1_401b_4c5c_b096_76f67d466982_tags tags, d.ffe70fd7f1_401b_4c5c_b096_76f67d466982_filename filename
	from ~TABLENAMEDOCUMENT~ d, ~TABLENAMEDOCUMENTTYPE~ dt
	where d.client_id = ~CLIENTID~ and d.is_committed = 'Y' and d.documenttype_id = dt.id and dt.code = 'IMG' and d.id < ~LASTFETCHEDID~ ~FILTERSQL~
	order by d.id desc limit ~FETCHLIMIT~
	";
    } 
	else 
	{
        $strSQL =
            "
	select d.id, d.code, d.description, d.filenamebase, d.ffe70fd7f1_401b_4c5c_b096_76f67d466982_tags tags, d.ffe70fd7f1_401b_4c5c_b096_76f67d466982_filename filename
	from ~TABLENAMEDOCUMENT~ d, ~TABLENAMEDOCUMENTTYPE~ dt
	where d.client_id = ~CLIENTID~ and d.is_committed = 'Y' and d.documenttype_id = dt.id and dt.code = 'IMG' ~FILTERSQL~
	order by d.id desc limit ~FETCHLIMIT~
	";
    }
	$strSQL = str_replace('~TABLENAMEDOCUMENT~', ff($strTableNameDocument), $strSQL);
	$strSQL = str_replace('~TABLENAMEDOCUMENTTYPE~', ff($strTableNameDocumentType), $strSQL);
    $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
    $strSQL = str_replace('~FETCHLIMIT~', ff($intFetchCount), $strSQL);
    $strSQL = str_replace('~LASTFETCHEDID~', ff($strLastFetchedID), $strSQL);
	$strSQL = str_replace('~FILTERSQL~', $strFilterSQL, $strSQL);	// no ff here because it is an already prepared SQL fragment
    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
    while ($arrRow = dbReadRecord($objResult)) 
	{
		$strDocumentRoot = getDocumentRootURLByDocumentID($objConn_a, $arrRow['id']);
        $strURL = getClusterPath($strDocumentRoot, $arrRow['filenamebase'], false, false) . $arrRow['filenamebase'] . '-';
        $strFilename = pathinfo($arrRow['filename'], PATHINFO_FILENAME);
        $strExtension = pathinfo($arrRow['filename'], PATHINFO_EXTENSION);
        $strURL .= $strFilename . '-thumb.' . $strExtension;

        $arrResult[] = array(
            "id" => secureEntityValue('DOCUMENT', $arrRow['id']),
            "code" => $arrRow['code'],
            "description" => $arrRow['description'],
			"tags" => $arrRow['tags'],
			"filename" => $arrRow['filename'],
            "url" => $strURL,
        );
    }
    dbCloseRecordset($objResult);

    return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
}
