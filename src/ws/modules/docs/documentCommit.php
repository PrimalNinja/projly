<?php

// to commit the document call this
function documentCommit($objConn_a, $strClientID_a, $strDocumentID_a)
{
	if (dependencies('entity/dataaccess/document'))
	{
		dbBeginTrans($objConn_a, __FUNCTION__);

		if (strlen($strClientID_a) > 0)
		{
			// non-batch version
			$arrDBFields = [["s", "is_committed", "Y"]];
			$arrCustomWhere = [["n", "client_id", $strClientID_a], 
							   ["n", "id", $strDocumentID_a]];
			update_document($objConn_a, [], $arrDBFields, "", $arrCustomWhere);
		}
		else
		{
			// batch version
			$arrDBFields = [["s", "is_committed", "Y"]];
			update_document($objConn_a, [], $arrDBFields, $strDocumentID_a, []);		
		}

		updateDocumentRepositoryDocumentCountDocumentID($objConn_a, $strDocumentID_a);
		
		dbEndTrans($objConn_a, __FUNCTION__);
	}
}
