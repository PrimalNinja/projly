<?php

// fetch an image
// example URL: http://localhost/jsoncv/fetch.php?metadata=TEST1,TEST2
function actionPublicFetchMetaData($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
	$strTableNameLogic = getTableNameEntity("logic", false);
	$strTableNameLogicGroup = getTableNameEntity("logicgroup", false);

	$strFilename = '';
	if (!hasPermission($objConn_a, 'NOCHECK', __FUNCTION__, true)) {return false;}


	$strMetaData = $arrParameters_a[0]['value'];
	$arrMetaData = explode(",", $strMetaData);


	$strJavascriptCodeResult = '';

	foreach ($arrMetaData as $arrRowMetaData)
	{
	    $strSQL = "select l.code, l.jsondata from ~TABLENAMELOGIC~ l, ~TABLENAMELOGICGROUP~ lg where l.code = '~CODE~' and lg.code = 'FORM'";
	    $strSQL = str_replace('~TABLENAMELOGIC~', ff($strTableNameLogic), $strSQL);
	    $strSQL = str_replace('~TABLENAMELOGICGROUP~', ff($strTableNameLogicGroup), $strSQL);
		$strSQL = str_replace('~CODE~', ff($arrRowMetaData), $strSQL);

	    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
	    if ($arrRow = dbReadRecord($objResult))
	    {

	    	$strJSON =  $arrRow['jsondata'];
			$arrJSON = json_decode($strJSON, true);
			$arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSON, "g4ec1d17b-4aae-499c-8b4c-3639c690851b", "LOGIC");
			$strJavascriptCode  = $arrJSONField['p_value'];

			$strJavascriptCodeResult .= "// " . $arrRow['code'];
			$strJavascriptCodeResult .= "\r\n";
			$strJavascriptCodeResult .= "\r\n";
			$strJavascriptCodeResult .= $strJavascriptCode;
			$strJavascriptCodeResult .= "\r\n";
			$strJavascriptCodeResult .= "\r\n";
			$strJavascriptCodeResult .= "\r\n";
			$strJavascriptCodeResult .= "\r\n";
	    }
	    dbCloseRecordset($objResult);
	}

	header('Content-type: application/javascript');
	header('Content-Disposition: inline');
	$strFinalJS = '(function(){' . $strJavascriptCodeResult . '})();';
	
	echo $strFinalJS;
	die();
}
