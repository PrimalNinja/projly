<?php

/**
 * @param $arrFormFields_a
 * FormField format
 * [
 * 	[ fieldname, fieldvalue, fieldvaluedescription ]
 * ]
 * 
 * datatypes
 * n - numeric not nullable
 * nn - numeric nullable
 * s - string not nullable
 * sn - string nullable
 * d - date not nullable
 * dn - date nullable


 * @param $arrDBFields_a
 * DBField format
 * [
 * 	[ datatype, fieldname, fieldvalue ]
 * ]
 * 
 * datatypes
 * n - numeric not nullable
 * nn - numeric nullable
 * s - string not nullable
 * sn - string nullable
 * d - date not nullable
 * dn - date nullable
 */

// default add without having to get the template beforehand
function add_reportsection_field($objConn_a, $arrFormFields_a, $arrDBFields_a)
{
	$arrJSONData = getFormTemplate_reportsection_field($objConn_a);
	return addbulk_reportsection_field($objConn_a, $arrFormFields_a, $arrDBFields_a, $arrJSONData);
}

// bulk add method where template is fetched beforehand
function addbulk_reportsection_field($objConn_a, $arrFormFields_a, $arrDBFields_a, $arrJSONData_a)
{
	$strTableNameREPORTSECTION_FIELD = getTableNameEntity("reportsection_field", false);	

	$strResult = "";
	
	$strClientID = $_SESSION['server_loggedin_clientid'];
	$strEntityID = getEntityID($objConn_a, "systemform");
	$strDataEntityID = getEntityID($objConn_a, "reportsection_field");
	$strCode = "REPORTSECTION_FIELD";
	$strDescription = "REPORTSECTION_FIELD";

	$arrJSONData = $arrJSONData_a;

	// formTransferSectionValues($arrJSONData_a, "", $arrJSONData, $strFormSection);        
	// transfer arrFormFields_a to $arrJSONData
	foreach ($arrFormFields_a as $arrField)
	{
		$strFieldName  = strtoupper($arrField[0]);
		$strFormSection = "gfae1e5b0-bfcc-49bd-bad7-996129013235r";
			
		// optional other section is provided
		if (strpos($strFieldName, '.') !== false)
		{
			$arrTemp = explode(".", $strFieldName);
			$strFormSection = $arrTemp[0];
			$strFieldName  = $arrTemp[1];
		}
		
		$strFieldValue = $arrField[1];
		$strValueDescription = '';
		if (count($arrField) >= 3)
		{
			$strValueDescription = $arrField[2];
		}

		if (strlen($strValueDescription) > 0)
		{
			$arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, $strFormSection, $strFieldName, $strFieldValue, $strValueDescription);
		}
		else
		{
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, $strFormSection, $strFieldName, $strFieldValue);
		}
	}
	
    $strJSONData = json_encode($arrJSONData);

	$strModifyUser = $_SESSION['server_loggedin_user'];
	$strModifyDateTime = getDateTime();

	dbBeginTrans($objConn_a, __FUNCTION__);
        
    $strSQLFields = "client_id, entity_id, dataentity_id, code, description, is_enabled, jsondata, data_client_id, modifyuser, modifydatetime";
    $strSQLValues = "~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', 'Y', '~JSONDATA~', ~DATACLIENTID~, '~MODIFYUSER~', '~MODIFYDATETIME~'";

    if (is_array($arrDBFields_a) && count($arrDBFields_a) > 0)
    {
        foreach ($arrDBFields_a as $arrField)
        {
			$strDataType   = strtolower($arrField[0]);
			$strFieldName  = $arrField[1];
			$strFieldValue = $arrField[2];
            
            $strSQLFields .= ', ' . $strFieldName;
            
            if ($strDataType === 'n')
            {
                $strSQLValues .= ', ' . ff($strFieldValue);
            }
            else if ($strDataType === 'nn')
            {
                $strSQLValues .= ', ' . ffn($strFieldValue);
            }
            else if ($strDataType === 'sn' || $strDataType === 'dn')
            {
                $strFieldValue = ffn($strFieldValue);

                if ($strFieldValue !== 'null')
                {
                    $strSQLValues .= ', ' . "'" .  $strFieldValue . "'";   
                }
                else
                {
                    $strSQLValues .= ', ' . $strFieldValue;
                }
            }            
            else
            {
                $strSQLValues .= ', ' . "'" .  ff($strFieldValue) . "'";
            }
        }
    }

    $strSQL = "insert into ~TABLENAMEREPORTSECTION_FIELD~ (~FIELDS~) values (~VALUES~)";
    $strSQL = str_replace('~FIELDS~', $strSQLFields, $strSQL);
    $strSQL = str_replace('~VALUES~', $strSQLValues, $strSQL);
	$strSQL = str_replace('~TABLENAMEREPORTSECTION_FIELD~', ff($strTableNameREPORTSECTION_FIELD), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
	$strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
	$strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
	$strSQL = str_replace('~CODE~', ff($strCode), $strSQL);
	$strSQL = str_replace('~DESCRIPTION~', ff($strDescription), $strSQL);
	$strSQL = str_replace('~DATACLIENTID~', ff($strClientID), $strSQL);
	$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
	$strSQL = str_replace('~MODIFYUSER~', ff($strModifyUser), $strSQL);
	$strSQL = str_replace('~MODIFYDATETIME~', $strModifyDateTime, $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	$strResult = dbLastInsertID($objConn_a);

	exposeEntityData($objConn_a, "SYSTEMFORM", "REPORTSECTION_FIELD", $strResult, $strJSONData);
	
	dbEndTrans($objConn_a, __FUNCTION__);
	
	return $strResult;
}

function delete_reportsection_field($objConn_a, $strID_a, $varCustomWhere_a)
{
	$strTableNameREPORTSECTION_FIELD = getTableNameEntity("reportsection_field", false);	

	dbBeginTrans($objConn_a, __FUNCTION__);

	$strWhere = buildWhere_reportsection_field($strID_a, $varCustomWhere_a);

	$strSQL = "delete from ~TABLENAMEREPORTSECTION_FIELD~ where ~WHERE~";
	$strSQL = str_replace('~TABLENAMEREPORTSECTION_FIELD~', ff($strTableNameREPORTSECTION_FIELD), $strSQL);
    $strSQL = str_replace('~WHERE~', $strWhere, $strSQL);	
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	
	return dbEndTrans($objConn_a, __FUNCTION__);
}

function deleteExtension_reportsection_field($objConn_a, $strID_a, $varCustomWhere_a)
{
	$strTableNameREPORTSECTION_FIELDExtension = getTableNameEntityExtension("reportsection_field");	

	dbBeginTrans($objConn_a, __FUNCTION__);

	$strWhere = buildWhere_reportsection_field($strID_a, $varCustomWhere_a);

	$strSQL = "delete from ~TABLENAMEREPORTSECTION_FIELDEXTENSION~ where ~WHERE~";
	$strSQL = str_replace('~TABLENAMEREPORTSECTION_FIELDEXTENSION~', ff($strTableNameREPORTSECTION_FIELDExtension), $strSQL);
    $strSQL = str_replace('~WHERE~', $strWhere, $strSQL);	
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	
	return dbEndTrans($objConn_a, __FUNCTION__);
}

// note: update only exposes if ID is provided
function update_reportsection_field($objConn_a, $arrFormFields_a, $arrDBFields_a, $strID_a, $varCustomWhere_a)
{
	$strTableNameREPORTSECTION_FIELD = getTableNameEntity("reportsection_field", false);	

    $strResult = "";
       
	$strModifyUser = $_SESSION['server_loggedin_user'];
	$strModifyDateTime = getDateTime();
	$strJSONData = '';

	// this expose code should be more clean
	$blnExpose = false;
	if (is_array($varCustomWhere_a))
	{
		if ((count($varCustomWhere_a) == 0) && (strlen($strID_a) > 0))
		{
			$blnExpose = true;
		}
	}
	else
	{
		if (strlen($strID_a) > 0)
		{
			$blnExpose = true;
		}
	}

	$strWhere = buildWhere_reportsection_field($strID_a, $varCustomWhere_a);

	if ($blnExpose)
	{
		$strSQL = "select jsondata returnvalue from ~TABLENAMEREPORTSECTION_FIELD~ where ~WHERE~";
		$strSQL = str_replace('~TABLENAMEREPORTSECTION_FIELD~', ff($strTableNameREPORTSECTION_FIELD), $strSQL);    
		$strSQL = str_replace('~WHERE~', $strWhere, $strSQL);
		$strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		
		$arrJSONData = json_decode($strJSONData, true);

		// transfer arrFormFields_a to $arrJSONData
		foreach ($arrFormFields_a as $arrField)
		{
			$strFieldName  = strtoupper($arrField[0]);
			$strFormSection = "gfae1e5b0-bfcc-49bd-bad7-996129013235r";
			
			// optional other section is provided
			if (strpos($strFieldName, '.') !== false)
			{
				$arrTemp = explode(".", $strFieldName);
				$strFormSection = $arrTemp[0];
				$strFieldName  = $arrTemp[1];
			}
			
			$strFieldValue = $arrField[1];
			$strValueDescription = '';
			if (count($arrField) >= 3)
			{
				$strValueDescription = $arrField[2];
			}

			if (strlen($strValueDescription) > 0)
			{
				$arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, $strFormSection, $strFieldName, $strFieldValue, $strValueDescription);
			}
			else
			{
				$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, $strFormSection, $strFieldName, $strFieldValue);
			}
		}

		$strJSONData = json_encode($arrJSONData);

		$strSQLFields = "jsondata = '~JSONDATA~', modifyuser = '~MODIFYUSER~', modifydatetime = '~MODIFYDATETIME~'";
	}
	else
	{
		$strSQLFields = "modifyuser = '~MODIFYUSER~', modifydatetime = '~MODIFYDATETIME~'";
	}

	dbBeginTrans($objConn_a, __FUNCTION__);
	        
    if (is_array($arrDBFields_a) && count($arrDBFields_a) > 0)
    {
        foreach ($arrDBFields_a as $arrField)
        {
			$strDataType   = strtolower($arrField[0]);
			$strFieldName  = $arrField[1];
			$strFieldValue = $arrField[2];
			
            if ($strDataType === 'n')
            {
                $strSQLFields .= ', ' . $strFieldName . "=" . ff($strFieldValue);
            }
            else if ($strDataType === 'nn')
            {
                $strSQLFields .= ', ' . $strFieldName . "=" . ffn($strFieldValue);
            }
            else if ($strDataType === 'sn' || $strDataType === 'dn')
            {
                $strFieldValue = ffn($strFieldValue);

                if ($strFieldValue !== 'null')
                {
                    $strSQLFields .= ', ' . $strFieldName . "=" . "'" . $strFieldValue . "'";  
                }
                else
                {
                    $strSQLFields .= ', ' . $strFieldName . "=" . $strFieldValue;
                }
            }            
            else
            {
                $strSQLFields .= ', ' . $strFieldName . "=" . "'" . ff($strFieldValue) . "'";
            }
        }
    }

	$strSQL = "update ~TABLENAMEREPORTSECTION_FIELD~ set ~UPDATEFIELDS~ where ~WHERE~";
	$strSQL = str_replace('~UPDATEFIELDS~', $strSQLFields, $strSQL);
	$strSQL = str_replace('~TABLENAMEREPORTSECTION_FIELD~', ff($strTableNameREPORTSECTION_FIELD), $strSQL);
	$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
	$strSQL = str_replace('~MODIFYUSER~', ff($strModifyUser), $strSQL);
    $strSQL = str_replace('~MODIFYDATETIME~', $strModifyDateTime, $strSQL);
    $strSQL = str_replace('~WHERE~', $strWhere, $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	
	if ($blnExpose)
	{
		exposeEntityData($objConn_a, "SYSTEMFORM", "REPORTSECTION_FIELD", $strID_a, $strJSONData);
	}
	
	return dbEndTrans($objConn_a, __FUNCTION__);
}

function fetch_reportsection_field($objConn_a, $strDBFields_a, $strID_a, $varCustomWhere_a, $strOrderBy_a = "")
{
	$strTableNameREPORTSECTION_FIELD = getTableNameEntity("reportsection_field", false);	

	$strWhere = buildWhere_reportsection_field($strID_a, $varCustomWhere_a);
	
	$strDBFields = "id, jsondata";
	if (strlen($strDBFields_a) > 0)
	{
		$strDBFields .= ", " . $strDBFields_a;
	}

	$strSQL = "select ~DBFIELDS~ from ~TABLENAMEREPORTSECTION_FIELD~ where ~WHERE~";
	$strSQL = str_replace('~TABLENAMEREPORTSECTION_FIELD~', ff($strTableNameREPORTSECTION_FIELD), $strSQL);
    $strSQL = str_replace('~DBFIELDS~', $strDBFields, $strSQL);
    $strSQL = str_replace('~WHERE~', $strWhere, $strSQL);
	
	if (strlen($strOrderBy_a) > 0)
	{
		$strSQL .= " order by " . $strOrderBy_a;
	}

	return dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
}

function fetchValue_reportsection_field($objConn_a, $strFieldName_a, $strID_a, $varCustomWhere_a)
{
	$strTableNameREPORTSECTION_FIELD = getTableNameEntity("reportsection_field", false);	

	$strWhere = buildWhere_reportsection_field($strID_a, $varCustomWhere_a);

	$strSQL = "select ~FIELDNAME~ returnvalue from ~TABLENAMEREPORTSECTION_FIELD~ where ~WHERE~";
	$strSQL = str_replace('~TABLENAMEREPORTSECTION_FIELD~', ff($strTableNameREPORTSECTION_FIELD), $strSQL);
	$strSQL = str_replace('~FIELDNAME~', ff($strFieldName_a), $strSQL);
    $strSQL = str_replace('~WHERE~', $strWhere, $strSQL);
	return dbReadValue($objConn_a, $strSQL, __FUNCTION__);
}

function buildWhere_reportsection_field($strID_a, $varCustomWhere_a)
{
	$strResult = "";
	
	if (is_array($varCustomWhere_a))
	{
		foreach ($varCustomWhere_a as $arrField)
		{
			$strDataType   = strtolower($arrField[0]);
			$strFieldName  = $arrField[1];
			$strFieldValue = $arrField[2];

			if (strlen($strResult) > 0)
			{
				$strResult .= " and ";
			}
			
            if ($strDataType === 'n')
            {
                $strResult .= $strFieldName . "=" . ff($strFieldValue);
            }
            else if ($strDataType === 'nn')
            {
                $strResult .= $strFieldName . "=" . ffn($strFieldValue);
            }
            else if ($strDataType === 'sn' || $strDataType === 'dn')
            {
                $strFieldValue = ffn($strFieldValue);

                if ($strFieldValue !== 'null')
                {
                    $strResult .= $strFieldName . "=" . "'" . $strFieldValue . "'";  
                }
                else
                {
                    $strResult .= $strFieldName . "=" . $strFieldValue;
                }
            }            
            else
            {
                $strResult .= $strFieldName . "=" . "'" . ff($strFieldValue) . "'";
            }
		}
	}
	else
	{
		$strResult = $varCustomWhere_a;
	}

    if (strlen($strResult) == 0)
    {
		if (strlen($strID_a) > 0)
		{
			$strResult = "id = " . ff($strID_a);
		}
		else
		{
			$strResult = "1 = 0";
		}
    }
	
	return $strResult;
}

function getFormTemplate_reportsection_field($objConn_a)
{
	return formTemplateGetFromDBByEntityCode($objConn_a, "REPORTSECTION_FIELD");
}