<?php

// update a document
function documentUpdate($objConn_a, $strClientID_a, $strDocumentID_a, $strNotes_a, $strTags_a)
{
	if (dependencies('entity/dataaccess/document'))
	{
		$arrFormFields = [["NOTES", $strNotes_a, ""], 
						  ["TAGS", $strTags_a, ""]];
		
		$arrCustomWhere = [["n", "client_id", $strClientID_a], 
						   ["n", "id", $strDocumentID_a]];
		update_document($objConn_a, $arrFormFields, [], "", $arrCustomWhere);
	}
}
