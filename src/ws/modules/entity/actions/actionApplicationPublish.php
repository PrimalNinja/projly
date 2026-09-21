<?php
function actionApplicationPublish($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{	
    $strTableNameApplication = getTableNameEntity("application", false);

	$strResult = '';
    $strResponseMessage = '';
    
    $strApplicationID = revertSecuredValue(getJSONParameter($arrParameters_a, 'id'), 'id', true);

    $strLogin = $_SESSION['server_loggedin_user']; 

    $strSQL = "select g375c1fc8_2001_4235_bb44_ec10fd82afc8_description description, jsondata from ~TABLENAMEAPPLICATION~ where id = ~APPLICATIONID~";
    $strSQL = str_replace('~TABLENAMEAPPLICATION~', ff($strTableNameApplication), $strSQL);
    $strSQL = str_replace('~APPLICATIONID~', ff($strApplicationID), $strSQL);
    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

    if ($arrRow = dbReadRecord($objResult))
    {   
        $strApplicationDescription = $arrRow['description'];
        $arrJSONData = json_decode($arrRow['jsondata'], true);

        $objDate = new DateTime();  
        $strDate = $objDate->format("Y-m-d");

        $arrDocuments = formValueGetBySectionCodeFieldCode($arrJSONData, 'g375c1fc8-2001-4235-bb44-ec10fd82afc8', "JSTESTDOCUMENT");
        $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g375c1fc8-2001-4235-bb44-ec10fd82afc8', "JSLOGICDOCUMENT", $arrDocuments);
        $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g375c1fc8-2001-4235-bb44-ec10fd82afc8', "PUBLISHDATE", $strDate);

        $strJSONData = json_encode($arrJSONData);

        $strSQL = "update ~TABLENAMEAPPLICATION~ set jsondata = '~JSONDATA~', modifyuser = '~MODIFYUSER~', modifydatetime = '~MODIFYDATETIME~' where id = ~APPLICATIONID~";
        $strSQL = str_replace('~TABLENAMEAPPLICATION~', ff($strTableNameApplication), $strSQL);
        $strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
        $strSQL = str_replace('~APPLICATIONID~', ff($strApplicationID), $strSQL);
        $strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
        $strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
        dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

        exposeEntityData($objConn_a, 'SYSTEMFORM', 'APPLICATION', $strApplicationID, $strJSONData);

        $strResponseMessage = "Application (" . $strApplicationDescription . ") has been published!";
    }
    

	$strResult = createJSONResponse($strDataID_a, RESPONSE_OK, $strResponseMessage, array());
	
	return $strResult;
}