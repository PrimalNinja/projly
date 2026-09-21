<?php

function fetchCMSContent($objConn_a, $strCMSCode_a)
{ 
    $strResult = "";

	if (dependencies('entity/dataaccess/cms'))
	{
		$arrCustomWhere = [["s", "code", $strCMSCode_a]];
		$strJSONData = fetchValue_cms($objConn_a, "jsondata", "", $arrCustomWhere);
		$arrJSONData = json_decode($strJSONData, true);

		$strResult = formValueGetBySectionTypeFieldCode($arrJSONData, "DATA", "CONTENT");
	}

    return $strResult;
}
