<?php
function processImportedSuburbs($objConn_a, $strTableTemp_a, $strImportedFile_a, $strFilename_a, $strOriginalFilename_a,$objFileFormat_a, $strClientID_a)
{
	$strTableNameSuburb = getTableNameEntity("suburb", false);

	$arrResult = array("result"=> STAT_ERROR, "error"=>"Upload Suburbs File Processing Error", "tagtype"=>"", "tag"=> "");

    if (dependencies('utils/import') && dependencies('docs/documentSimpleCopy')) {
        $strLogin = 'upload'; // future somehow workout the user (have it added to the metadata)
        $strUserID = '';

		// get the entity ids
		$strEntityID = getEntityID($objConn_a, "systemform");
		$strDataEntityID = getEntityID($objConn_a, "suburb");

        // get the field values (these are used when selecting from the temporary tables)
        $strSurburbField = getFileFormatFieldValue($objFileFormat_a, "suburb");
        $strStateField = getFileFormatFieldValue($objFileFormat_a, "state");
        $strPostcodeField = getFileFormatFieldValue($objFileFormat_a, "postcode");
        $strCountryField = getFileFormatFieldValue($objFileFormat_a, "country");

        // begin trans before we change our live tables with the data from the temporary table
        dbBeginTrans($objConn_a, __FUNCTION__);

        // truncate and clear all suburbs before importing suburb data
        $strSQL = "delete from ~TABLENAMESUBURB~";
		$strSQL = str_replace('~TABLENAMESUBURB~', ff($strTableNameSuburb), $strSQL);
        dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

        // create the new suburbs
        $strSQL = "insert into ~TABLENAMESUBURB~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, suburb, state, postcode, country) ";
        $strSQL .= "select distinct ~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', 'Y', ~DATACLIENTID~, null, '~MODIFYUSER~', '~MODIFYDATETIME~', ~SUBURB~, ~STATE~, ~POSTCODE~, ~COUNTRY~ from ~DATABASETEMP~.~TABLENAMETEMP~";
		$strSQL = str_replace('~TABLENAMESUBURB~', ff($strTableNameSuburb), $strSQL);
        $strSQL = str_replace('~DATABASETEMP~', DBSYSTEMTEMP_DATABASENAME, $strSQL);
        $strSQL = str_replace("~TABLENAMETEMP~", ff($strTableTemp_a), $strSQL);
		$strSQL = str_replace("~CLIENTID~", $strClientID_a, $strSQL);
		$strSQL = str_replace("~ENTITYID~", $strEntityID, $strSQL);
		$strSQL = str_replace("~DATAENTITYID~", $strDataEntityID, $strSQL);
		$strSQL = str_replace("~CODE~", $strPostcodeField, $strSQL);  // using postcode for now as we generally don't use this field on the lister etc.
		$strSQL = str_replace("~DESCRIPTION~", $strSurburbField, $strSQL);  // using suburb for now as we generally don't use this field on the lister etc.
		$strSQL = str_replace("~DATACLIENTID~", $strClientID_a, $strSQL);
		$strSQL = str_replace("~MODIFYUSER~", ff($strLogin), $strSQL);
		$strSQL = str_replace("~MODIFYDATETIME~", getDateTime(), $strSQL);
        $strSQL = str_replace("~SUBURB~", $strSurburbField, $strSQL);
        $strSQL = str_replace("~STATE~", $strStateField, $strSQL);
        $strSQL = str_replace("~POSTCODE~", $strPostcodeField, $strSQL);
        $strSQL = str_replace("~COUNTRY~", $strCountryField, $strSQL);
        dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

        // read the document type
        $strDocumentTypeID = getDocumentTypeIDByCode($objConn_a, DOCUMENTTYPE_SUBURBS);

        // add the original document along with the error list as notes
        $strCode = '';
        $strDescription = $strOriginalFilename_a;
        $strMetaData = '{ "originalfilename":"' . $strOriginalFilename_a . '" }';
        $strDocumentID = documentSimpleCopy($objConn_a, $strClientID_a, $strUserID, $strDocumentTypeID, $strCode, $strDescription, $strOriginalFilename_a, TEMP_PENDING_PATH . $strFilename_a, $strMetaData, "", "");


        if (dbEndTrans($objConn_a, __FUNCTION__))
        {
			$arrResult["result"] = STAT_COMPLETED;
			$arrResult["error"] = "";
			$arrResult["tagtype"] = "documentid";
			$arrResult["tag"] = $strDocumentID;
        }
    }

    return $arrResult;
}
