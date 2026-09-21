<?php
 
function resetDocumentRepository($objConn_a, $strClientID_a, $strDocumentRepositoryID_a) 
{ 
	$strTableNameDocumentRepository = getTableNameEntity("documentrepository", false);
	
	$blnResult = false;

	$strLogin = $_SESSION['server_loggedin_user'];
	
	dbBeginTrans($objConn_a, __FUNCTION__);
	
	$strSQL = "select jsondata returnvalue from ~TABLENAMEDOCUMENTREPOSITORY~ where id = ~DOCUMENTREPOSITORYID~";
	$strSQL = str_replace('~TABLENAMEDOCUMENTREPOSITORY~', ff($strTableNameDocumentRepository), $strSQL);
	$strSQL = str_replace('~DOCUMENTREPOSITORYID~', ff($strDocumentRepositoryID_a), $strSQL);
	$strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
	
	$arrJSONData = json_decode($strJSONData, true);
	
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g13c0b91f-45bd-492c-8b47-ce82b1757837", "DOCUMENTCOUNT", "");
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g13c0b91f-45bd-492c-8b47-ce82b1757837", "STORAGEUSED", "");
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g13c0b91f-45bd-492c-8b47-ce82b1757837", "DELETESTORAGESIZE", "");

	$strJSONData = json_encode($arrJSONData);

	$strSQL = "update ~TABLENAMEDOCUMENTREPOSITORY~ set jsondata = '~JSONDATA~' where id = ~DOCUMENTREPOSITORYID~";
	$strSQL = str_replace('~TABLENAMEDOCUMENTREPOSITORY~', ff($strTableNameDocumentRepository), $strSQL);
	$strSQL = str_replace('~DOCUMENTREPOSITORYID~', ff($strDocumentRepositoryID_a), $strSQL);
	$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	
	exposeEntityData($objConn_a, 'SYSTEMFORM', 'DOCUMENTREPOSITORY', $strDocumentRepositoryID_a, $strJSONData);
			
	$blnResult = dbEndTrans($objConn_a, __FUNCTION__);
	
	return $blnResult;
}