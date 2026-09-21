<?php

// print jobs can occur immediately or via a batch process, because of this, when in a batch process we actually create the documents later
// immediate printing leverages off the same behaviour as batch processes to save duplication of code.
// with batch processes (and immediate) the actual document is not created at the time of the user stating they want to print a new issue (for example) which is printing a newly generated document
// and not an existing document that happened to be within the cluster.  we do however create a .json file (using documentSimpleAdd) with the data in it that we want to create the document based upon and
// log a batch entry of 'PROCESS_GENERATEDOCUMENT' so that it knows that we want to generate it based on that .json file.
// when the PROCESS_GENERATEDOCUMENT function is called it then does the actual generation.
// documentOpen: For an actual generation to occur, it needs to open the document using documentOpen as it will modify the document.  The documentOpen function changes the status of the document so that other
// users that might also want to use the document can do so without conflicting.  At the end of the documentOpen when the document is modified (ie: in this case, the HTML file is created), we once again call
// documentCommit.
function printJobComplete($objConn_a, $strClientID_a, $strPrintJobID_a)
{
    $strTableNamePrintJob = getTableNameEntity("printjob", false);

    dbBeginTrans($objConn_a, __FUNCTION__);
	
	$strSQL = "select jsondata returnvalue from ~TABLENAMEPRINTJOB~ where client_id = ~CLIENTID~ and id = ~PRINTJOBID~";
	$strSQL = str_replace('~TABLENAMEPRINTJOB~', ff($strTableNamePrintJob), $strSQL);
    $strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~PRINTJOBID~', ff($strPrintJobID_a), $strSQL);
	$strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
	
	$arrJSONData = json_decode($strJSONData, true);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gb398c9db-2272-4748-907c-d9685b924329', "STATUS", STAT_COMPLETED);
	// UNCOMMENT BELOW LINE FOR TESTING
	//$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gb398c9db-2272-4748-907c-d9685b924329', "STATUS", STAT_READY);
	$strJSONData = json_encode($arrJSONData);

    $strSQL = "update ~TABLENAMEPRINTJOB~ set jsondata = '~JSONDATA~' where client_id = ~CLIENTID~ and id = ~PRINTJOBID~";
    $strSQL = str_replace('~TABLENAMEPRINTJOB~', $strTableNamePrintJob, $strSQL);
    $strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
    $strSQL = str_replace('~PRINTJOBID~', ff($strPrintJobID_a), $strSQL);
    dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	
	exposeEntityData($objConn_a, 'SYSTEMFORM', 'PRINTJOB', $strPrintJobID_a, $strJSONData);

    return dbEndTrans($objConn_a, __FUNCTION__);
}
