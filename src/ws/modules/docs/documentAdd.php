<?php

// also see notes at the top of printJobComplete.php (all these notes should move to a wiki)

// normal usage:
// the cluster supports compound documents, that is documents made up of multiple files, such as an html file with it's images.
// documentAdd: to store such documents in the cluster we first call documentAdd which returns a documentid which we need to complete the overall document add process
// documentComponentAdd: now having a documentid we can now add the components of our compound document one at a time using the returned documentid using the documentComponentAdd function
// documentCommit: having added all the components, we close the document with documentCommit

// simpledocument usage:
// documentSimpleAdd: for documents that consist of only a single file we have a wrapper for the normal usage to do everything in a single go, the function is called documentSimpleAdd
// documentSimpleCopy: similar to documentSimpleAdd but when we want to copy a single-file document that already exists as a file from elsewhere to the cluster

// modifying documents:
// documentOpen: open a document which changes the status of it
// documentClusterCopy or documentClusterSave: use these to add components to the document if necessary or even rewrite/replace the existing components
// documentCommit: having finished modifying the document, we close the document with documentCommit

// add a document but not the body (a group of components) yet, the document will be in an uncommitted state (logically) but committed in the database
function documentAdd($objConn_a, $strClientID_a, $strUserID_a, $strDocumentTypeID_a, $strCode_a, $strDescription_a, $strFilename_a, $strMetaData_a, $strRepositoryCode_a, $strExpiryDate_a)
{
	$strDocumentID = "";
	
	if (dependencies('entity/dataaccess/client') &&
		dependencies('entity/dataaccess/document') &&
		dependencies('entity/dataaccess/documentrepository') &&
		dependencies('entity/dataaccess/documenttype') &&
		dependencies('entity/dataaccess/user'))
	{
		$strUserID = $_SESSION['server_loggedin_userid'];
		if (strlen($strUserID_a) > 0)
		{
			$strUserID = $strUserID_a;
		}

		$strTags = str_replace('.', ' ', $strDescription_a);
		$strTags = str_replace('-', ' ', $strTags);

		$strIsTagless = 'N';
		if (strlen(trim($strTags))==0)
		{
			$strIsTagless = 'Y';
		}

		$strDocumentRepositoryID = fetchValue_documentrepository($objConn_a, "id", "", [["s", "code", $strRepositoryCode_a]]);
		$strDocumentRepositoryDescription = fetchValue_documentrepository($objConn_a, "description", $strDocumentRepositoryID, "");
		$strCreatedByClient = fetchValue_client($objConn_a, "description", $strClientID_a, "");
		$strCreatedByUser = fetchValue_user($objConn_a, "description", $strUserID, "");
		$strDocumentType = fetchValue_documenttype($objConn_a, "description", $strDocumentTypeID_a, "");

		$arrFormFields = [["CODE", $strCode_a, ""],
						  ["DESCRIPTION", $strDescription_a, ""],
						  ["ISENABLED", "Y", ""],
						  ["DOCUMENTTYPE", $strDocumentTypeID_a, $strDocumentType],
						  ["DOCUMENTREPOSITORY", $strDocumentRepositoryID, $strDocumentRepositoryDescription],
						  ["CREATEDBYCLIENT", $strCreatedByClient, ""],
						  ["CREATEDBYUSER", $strCreatedByUser, ""],
						  ["CREATEDATETIME", getDateTime(), ""],
						  ["TAGS", $strTags, ""],
						  ["DOCUMENTDATE", getDateOnly(), ""],
						  ["EXPIRYDATE", $strExpiryDate_a, ""],
						  ["FILENAME", $strFilename_a, ""],
						  ["FILES", array(), ""],
						  ["STORAGEUSED", "0", ""]];

		$arrDBFields =   [["n", "user_id", $strUserID],
						  ["n", "documenttype_id", $strDocumentTypeID_a],
						  ["s", "metadata", $strMetaData_a],
						  ["s", "is_tagless", $strIsTagless]];

		$strDocumentID = add_document($objConn_a, $arrFormFields, $arrDBFields);

		// allocate a base filename and store it
		$arrDBFields = [["s", "filenamebase", getFilenameGUID() . '-' . $strDocumentID]];
		update_document($objConn_a, [], $arrDBFields, $strDocumentID, []);
	}

    return $strDocumentID;
}
