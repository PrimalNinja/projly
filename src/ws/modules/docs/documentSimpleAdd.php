<?php

// add a single part document in one go where the document is stored in the variable objComponent_a
function documentSimpleAdd($objConn_a, $strClientID_a, $strUserID_a, $strDocumentTypeID_a, $strCode_a, $strDescription_a, $strComponentFilename_a, $strFilename_a, $objComponent_a, $strMetaData_a, $strRepositoryCode_a, $strExpiryDate_a)
{
    $strDocumentID = '';

    if (dependencies('docs/documentAdd') &&
		dependencies('docs/documentCommit') &&
		dependencies('docs/documentComponentAdd')) 
	{
        dbBeginTrans($objConn_a, __FUNCTION__);

        $strDocumentID = documentAdd($objConn_a, $strClientID_a, $strUserID_a, $strDocumentTypeID_a, $strCode_a, $strDescription_a, $strFilename_a, $strMetaData_a, $strRepositoryCode_a, $strExpiryDate_a);
        documentComponentAdd($objConn_a, $strDocumentID, $strComponentFilename_a, $objComponent_a);
        documentCommit($objConn_a, $strClientID_a, $strDocumentID);

        dbEndTrans($objConn_a, __FUNCTION__);
    }

    return $strDocumentID;
}
