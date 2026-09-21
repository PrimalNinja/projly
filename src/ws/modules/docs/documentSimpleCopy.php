<?php

// add a single part document in one go where the document already exists within a file
function documentSimpleCopy($objConn_a, $strClientID_a, $strUserID_a, $strDocumentTypeID_a, $strCode_a, $strDescription_a, $strFilename_a, $strSourcePath_a, $strMetaData_a, $strRepositoryCode_a, $strExpiryDate_a)
{
    $strDocumentID = '';

    if (dependencies('docs/documentAdd') &&
		dependencies('docs/documentCommit') &&
		dependencies('docs/documentComponentCopy')) 
	{
        dbBeginTrans($objConn_a, __FUNCTION__);

		$strRepositoryCode = $strRepositoryCode_a;
		if (strlen($strRepositoryCode) == 0)
		{
			if (getSystemClientID($objConn_a))
			{
				$strRepositoryCode = DR_SYSTEM;
			}
			else if (isClientReserved($strClientID_a))
			{
				$strRepositoryCode = DR_DOCUMENT;
			}
			else
			{
				$strRepositoryCode = DR_USER;
			}
		}

        $strDocumentID = documentAdd($objConn_a, $strClientID_a, $strUserID_a, $strDocumentTypeID_a, $strCode_a, $strDescription_a, $strFilename_a, $strMetaData_a, $strRepositoryCode, $strExpiryDate_a);
        documentComponentCopy($objConn_a, $strDocumentID, $strFilename_a, $strSourcePath_a);
        documentCommit($objConn_a, $strClientID_a, $strDocumentID);

        dbEndTrans($objConn_a, __FUNCTION__);
    }

    return $strDocumentID;
}
