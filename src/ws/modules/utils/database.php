<?php

$g_blnExecuteError = false;
$g_intErrorCode = 0;
$g_intNestedTransactions = 0;
$g_intLastAffected = 0;
$g_strCustomError = "";
$g_strLastStatement = "";
$g_strSuccessMessage = "";
$g_strErrorDescription = "";

// transactions support nesting
// returns true if successful, false if not
function dbBeginTrans($objConn_a, $strCaller_a = "")
{
    $blnResult = true;

    global $g_blnExecuteError;
    global $g_intErrorCode;
    global $g_intNestedTransactions;
	global $g_strErrorDescription;

	if ($g_intNestedTransactions == 0) 
	{
		if (strlen(DBSYSTEM_ISOLATIONLEVEL) > 0) 
		{
			$blnX = dbExecuteSQL($objConn_a, "set session transaction isolation level " . DBSYSTEM_ISOLATIONLEVEL, $strCaller_a); // note: set the isolation level as configured
			if ($blnX == false) {$blnResult = false;}
		}
		$blnX = dbExecuteSQL($objConn_a, "set autocommit=0", $strCaller_a); // note: no database changes will really take effect unless within a dbBeginTrans and dbEndTrans
		if ($blnX == false) { $blnResult = false; }

		if ($blnResult) 
		{
			$blnX = dbExecuteSQL($objConn_a, "start transaction", $strCaller_a);
			if ($blnX == false) {$blnResult = false;}
		}

		$g_blnExecuteError = !$blnResult;

		if ($blnResult) 
		{
			$g_intErrorCode = 0;
			$g_strErrorDescription = "";
			dbClearCustomError();
			dbClearSuccessMessage();
		}
	} 
	else 
	{
		//logDebug($strCaller_a . ': ' . getSessionDB(__FUNCTION__) . ':' . 'nested start transaction: ' . $g_intNestedTransactions, '');
		//logSQL(LOG_DEBUG_SQL_FILE, false, 'nested start transaction: ' . $g_intNestedTransactions, '', 0);
		logSQL($objConn_a->logFile, false, $strCaller_a . ': ' . getSessionDB(__FUNCTION__) . ':' . 'nested start transaction: ' . $g_intNestedTransactions, '', 0);
	}

	$g_intNestedTransactions++;

    return $blnResult;
}

// bind blob
function dbBindB($objStatement_a, $strParam_a, $str_a)
{
	$objStatement_a->bindParam($strParam_a, $str_a, PDO::PARAM_LOB);
}

// bind string
function dbBindS($objStatement_a, $strParam_a, $str_a)
{
	$objStatement_a->bindParam($strParam_a, $str_a, PDO::PARAM_STR);
}

// bind integer
function dbBindI($objStatement_a, $strParam_a, $int_a)
{
	$objStatement_a->bindParam($strParam_a, $int_a, PDO::PARAM_INT);
}

// build an order by clause from an array of order fields
// returns an order by clause
function dbBuildOrderBy($arrFields_a, $arrOrder_a, $strAppendFields_a = "")
{
    $strResult = '';
    $blnFirstField = true;

//logDebug("F:" . print_r($arrFields_a, true), '');
//logDebug("O:" . print_r($arrOrder_a, true), '');

    if (is_array($arrOrder_a)) 
	{
        foreach ($arrOrder_a as $objField) 
		{
            $strOrderField = $objField['field'];
            $strAscending = $objField['ascending'];
			
			$strFieldDef = $arrFields_a[$strOrderField];

			$arrFieldDef = explode("/", $strFieldDef);
			$strField = $arrFieldDef[0];
			$strHint = '';
			if (count($arrFieldDef) > 1)
			{
				$strHint = $arrFieldDef[1];
			}

			// get alternate table prefix from whitelist
			$strTablePrefix = '';
			if (InStr($strField, '.') >= 0)
			{
				$arrField = explode('.', $strField);
				$strTablePrefix = $arrField[0] . '.';
				$strField = $arrField[1];
			}

			$blnIgnore = ($strHint == 'ignore');
			$blnBoolean = ($strHint == 'boolean');
			$blnNumeric = ($strHint == 'numeric');

			$strFieldPart = '~TABLEPREFIX~~FIELD~';
			$strFieldPart = str_replace('~TABLEPREFIX~', $strTablePrefix, $strFieldPart);
			$strFieldPart = str_replace('~FIELD~', $strField , $strFieldPart);

            if ($blnIgnore == false) 
			{
				if (!$blnFirstField) 
				{
					$strResult .= ', ';
				}

				if ($strFieldPart != null) 
				{
					if ($strAscending == '1') 
					{
						$strResult .= $strFieldPart;
					} 
					else 
					{
						$strResult .= $strFieldPart . ' desc';
					}
				}

				$blnFirstField = false;
            }
        }
		
		if ((strlen($strAppendFields_a) > 0) && (strlen($strResult) > 0)) 
		{
			$strResult .= ', ';
		}
		$strResult .= $strAppendFields_a;

        if (strlen($strResult) > 0) 
		{
            $strResult = ' order by ' . $strResult;
        }
    }

    return $strResult;
}

// build a where clause from an array of criteria
// returns a where clause
function dbBuildWhere($strType_a, $arrFields_a, $arrFilter_a, $strTablePrefix_a = "")
{
    $strResult = '';
    $blnFirstField = true;
	$strTablePrefix = $strTablePrefix_a;

//logDebug("F:" . print_r($arrFields_a, true), '');
//logDebug("F2:" . print_r($arrFilter_a, true), '');
    if (is_array($arrFilter_a)) 
	{
        $blnFilterAll = false;
        
        foreach ($arrFilter_a as $arrFilter) 
		{ 
            if ($arrFilter['field'] == 'all') 
			{ 
                $blnFilterAll = true;
                $strFilterValue = ff($arrFilter['value']);
            }
        }
        
        if ($blnFilterAll ) 
		{ 
            if (strlen($strFilterValue) > 0) 
			{
                $strFilter = "(";

                $intI = 0;
                foreach ($arrFields_a as $strFieldDef) 
				{ 
                    if ($intI > 0) 
					{ 
                        $strFilter .= " or ";
                    }
					
					$arrFieldDef = explode("/", $strFieldDef);
					$strField = $arrFieldDef[0];
					$strHint = '';

					if (count($arrFieldDef) > 1)
					{
						$strHint = $arrFieldDef[1];
					}

					// get alternate table prefix from whitelist
					if (InStr($strField, '.') >= 0)
					{
                        $arrField = explode('.', $strField);
                        $strTablePrefix = $arrField[0] . '.';
                        $strField = $arrField[1];
					}
	
					$blnBoolean = ($strHint == 'boolean');
					$blnNumeric = ($strHint == 'numeric');

					$strFieldPart = '';
					if ($blnBoolean)
					{
						$strFieldPart = "~TABLEPREFIX~~FIELD~ = '~VALUE~'";
					}
					if ($blnNumeric)
					{
						$strFieldPart = "~TABLEPREFIX~~FIELD~ = ~VALUE~";
					}
					else 
					{
						$strFieldPart = "~TABLEPREFIX~~FIELD~ like '%~VALUE~%'";                
					}
					$strFieldPart = str_replace('~TABLEPREFIX~', $strTablePrefix , $strFieldPart);
                    $strFieldPart = str_replace('~FIELD~', $strField , $strFieldPart);
                    $strFieldPart = str_replace('~VALUE~', $strFilterValue , $strFieldPart);
					
					$strFilter .= $strFieldPart;

                    $intI++;
                }

                $strFilter .= ")";

                $strResult = $strFilter;
            }
        }
        else 
		{
//logDebug("filter:" . print_r($arrFilter_a, true), "");
            foreach ($arrFilter_a as $objField) 
			{
                $blnFilterWildcards = true;

                $strFilterField = $objField['field'];
                $strFilterValue = $objField['value'];
                if (isset($objField['wildcards'])) 
				{
                    $strFilterWildcards = $objField['wildcards'];
                    $blnFilterWildcards = toBoolean($strFilterWildcards);
                }

//logDebug("filterfield:" . $strFilterField, "");
                $strFieldDef = $arrFields_a[$strFilterField];

				$arrFieldDef = explode("/", $strFieldDef);
				$strField = $arrFieldDef[0];
				$strHint = '';
				if (count($arrFieldDef) > 1)
				{
					$strHint = $arrFieldDef[1];
				}
				
				// get alternate table prefix from whitelist
				if (InStr($strField, '.') >= 0)
				{ 
                    $arrField = explode('.', $strField);
                    $strTablePrefix = $arrField[0] . ".";
                    $strField = $arrField[1];
				}
//logDebug("field:" . $strField . ", hint:" . $strHint, "");
                $blnSoundEx = ($strHint == 'soundex');
                $blnIgnore = ($strHint == 'ignore');
				$blnBoolean = ($strHint == 'boolean');
				$blnNumeric = ($strHint == 'numeric');
				$blnExact = ($strHint == 'exact');
	
                if ($blnIgnore == false) 
				{
                    if (($strFilterField != null) && ($strFilterValue != null)) 
					{
                        if ($blnSoundEx) 
						{
                            // do nothing
                        } 
						else 
						{
                            if (($blnFilterWildcards) && ($blnExact == false) && ($blnBoolean == false) && ($blnNumeric == false)) 
							{
                                // add automatic wildcards, remove the next line if don't need it
                                $strFilterValue = '*' . $strFilterValue . '*';
                            }
                        }

                        if (!$blnFirstField) 
						{
                            $strResult .= ' and ';
                        }
                        $strComparitor = '=';

                        if ($blnSoundEx) 
						{
                            // if using soundex, remove the wildcards
                            $strFilterValue = str_replace('*', '', $strFilterValue);
                            $strFilterValue = "soundex('" . ff($strFilterValue) . "')";

                            if ($strField != null) 
							{
                                $strResult .= "~TABLEPREFIX~" . $strField . " ~COMPARITOR~ ~VALUE~";
                            }

                            $strResult = str_replace('~COMPARITOR~', $strComparitor, $strResult);
                            $strResult = str_replace('~VALUE~', $strFilterValue, $strResult);
                        } 
						else 
						{
                            if ($blnBoolean) 
							{
                                $strComparitor = '=';
                                if ($strField != null) 
								{
                                    $strResult .= "~TABLEPREFIX~" . $strField . " ~COMPARITOR~ '~VALUE~'";
                                }
                            } 
							else if ($blnNumeric) 
							{
                                $strComparitor = '=';
                                if ($strField != null) 
								{
                                    $strResult .= "~TABLEPREFIX~" . $strField . " ~COMPARITOR~ ~VALUE~";
                                }
                            } 
							else if ($blnExact) 
							{
                                $strComparitor = '=';
                                if ($strField != null) 
								{
                                    $strResult .= "~TABLEPREFIX~" . $strField . " ~COMPARITOR~ '~VALUE~'";
                                }
							}
							else 
							{
                                if (InStr($strFilterValue, '*') >= 0) 
								{
                                    $strFilterValue = str_replace('*', '%', $strFilterValue);
                                    $strComparitor = 'like';
                                }

                                if ($strField != null) 
								{
                                    $strResult .= "~TABLEPREFIX~" . $strField . " ~COMPARITOR~ '~VALUE~'";
                                }
                            }

							$strResult = str_replace('~TABLEPREFIX~', $strTablePrefix , $strResult);
                            $strResult = str_replace('~COMPARITOR~', $strComparitor, $strResult);
                            $strResult = str_replace('~VALUE~', ff($strFilterValue), $strResult);
                        }

                        $blnFirstField = false;
                    }
                }
            }
        }
    }
    else if (strlen($arrFilter_a) > 0) 
	{ 
        
        $intI = 0;
        
        $strFilterValue = $arrFilter_a;
        $strFilter = "(";
            
        foreach ($arrFields_a as $strFieldDef) 
		{ 
            if ($intI > 0) 
			{ 
                $strFilter .= " or ";
            }
                                
			$arrFieldDef = explode("/", $strFieldDef);
			$strField = $arrFieldDef[0];
			$strHint = '';

			if (count($arrFieldDef) > 1)
			{
				$strHint = $arrFieldDef[1];
			}

			$blnBoolean = ($strHint == 'boolean');
			$blnNumeric = ($strHint == 'numeric');

			$strFieldPart = '';

			if ($blnBoolean)
			{
				$strFieldPart = "~TABLEPREFIX~~FIELD~ = '~VALUE~'";
			}

			if ($blnNumeric)
			{
				$strFieldPart = "~TABLEPREFIX~~FIELD~ = ~VALUE~";
			}
			else
			{
				$strFieldPart = "~TABLEPREFIX~~FIELD~ like '%~VALUE~%'";
			}

			$strFieldPart = str_replace('~TABLEPREFIX~', $strTablePrefix , $strFieldPart);
            $strFieldPart = str_replace('~FIELD~', $strField , $strFieldPart);
            $strFieldPart = str_replace('~VALUE~', $strFilterValue , $strFieldPart);
			
			$strFilter .= $strFieldPart;
            
            $intI++;
        }

        $strFilter .= ")";
        
        $strResult = $strFilter;
    }
    
    if (strlen($strResult) > 0) 
	{
        $strResult = ' ' . $strType_a . ' ' . $strResult;
    }

    return $strResult;
}

// close a database connection
function dbClose($objConn_a)
{
	if ($objConn_a)
	{
		$objConn_a->PDO = null;
		$objConn_a = null;
	}
}

// close a recordset
function dbCloseRecordset($objRecordset_a)
{
    if ($objRecordset_a != null) 
	{
        $objRecordset_a->closeCursor();
        $objRecordset_a = null;
    }
}

// create a counter
// returns true if successful, false if not
function dbCounterCreate($objConn_a, $strCounterKey_a)
{
    $strSQL = "insert into ~TABLENAMESYSTEM~ (code, value) values ('~CODE~', '1')";
	$strSQL = str_replace("~TABLENAMESYSTEM~", CORE_SYSTEM, $strSQL);
    $strSQL = str_replace('~CODE~', ff($strCounterKey_a), $strSQL);
    return dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
}

// get a counter value
// returns a system setting
function dbCounterGetCurrentValue($objConn_a, $strCounterKey_a)
{
    return systemSettingGet($objConn_a, $strCounterKey_a);
}

// allocate a new counter value
// returns next counter value
function dbCounterGetNextValue($objConn_a, $strCounterKey_a)
{
    $dblResult = 0;

    dbBeginTrans($objConn_a, __FUNCTION__);

    $strCounter = systemSettingGet($objConn_a, $strCounterKey_a);
    $dblResult = floatval($strCounter);
    $dblCounter = $dblResult + 1;

    if ($dblCounter == 0) 
	{
        logError(true, 'failed to allocate counter value: ' . $strCounterKey_a);
    }

    systemSettingPut($objConn_a, $strCounterKey_a, $dblCounter);

    dbEndTrans($objConn_a, __FUNCTION__);

    return $dblResult;
}

// returns a database dbConnection if successful
function dbCreate($strHostname_a, $strLogin_a, $strPassword_a, $strDatabase_a)
{
    $blnCreated = false;
    $objResult = null;

    try
    {
        if (toBoolean(DBSYSTEM_CREATEDROP)) 
		{
            $objResult = dbOpen($strHostname_a, $strLogin_a, $strPassword_a);
            $blnCreated = dbExecuteSQL($objResult, "create database " . $strDatabase_a);
            dbClose($objResult);
            $objResult = null;
        } 
		else 
		{
            $blnCreated = true;
        }

        if ($blnCreated) 
		{
            $objResult = dbOpen($strHostname_a, $strLogin_a, $strPassword_a, $strDatabase_a);
        }
    } 
	catch (PDOException $e) 
	{
        $blnCreated = false;
        $objResult = null;
        $strError = $e->getMessage();
    }

    return $objResult;
}

// returns true if successful, false if not
function dbDrop($strHostname_a, $strLogin_a, $strPassword_a, $strDatabase_a)
{
    $blnResult = true;

    try
    {
        if (toBoolean(DBSYSTEM_CREATEDROP)) 
		{
            $objConn = dbOpen($strHostname_a, $strLogin_a, $strPassword_a);
            $blnResult = dbExecuteSQL($objConn, "drop database " . $strDatabase_a);
            dbClose($objConn);
        } 
		else 
		{
            // just remove all the tables as we don't have permissions
            $objConn = dbOpen($strHostname_a, $strLogin_a, $strPassword_a, $strDatabase_a);
            $blnResult = dbPurge($objConn);
            dbClose($objConn);
        }
    } 
	catch (PDOException $e) 
	{
        $blnResult = false;
        $strError = $e->getMessage();
    }

    return $blnResult;
}

// returns true if successful, false if not
function dbDropTable($objConn_a, $strTable_a, $blnIfExists_a)
{
	$blnResult = true;
	
	if ($blnIfExists_a)
	{
		$strSQL = "drop table if exists ~TABLE~";
		$strSQL = str_replace("~TABLE~", $strTable_a, $strSQL);
		$blnResult = dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	}
	else
	{
		$strSQL = "drop table ~TABLE~";
		$strSQL = str_replace("~TABLE~", $strTable_a, $strSQL);
		$blnResult = dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	}

    return $blnResult;
}

function dbDropForeignKeys($objConn_a, $strTable_a, $strField_a)
{
	// constraints
	$strSQL = "show create table ~TABLENAME~";
	$strSQL = str_replace("~TABLENAME~", $strTable_a, $strSQL);
	$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
	$arrColumns = $objResult->fetchAll();
	$strCreate = preg_replace("/AUTO_INCREMENT=[\w]*./", '', $arrColumns[0][1]);
	$arrLines = explode("\n", $strCreate);
	
	foreach ($arrLines as $strLine)
	{
		$strLine = trim($strLine);
		if (strLeft($strLine, 11, "") == "CONSTRAINT ")
		{
			$arrParts = explode("`", $strLine);
			$strConstraintName = $arrParts[1];
			$strFieldName = $arrParts[3];
		
			if ($strFieldName == $strField_a)
			{
				$strSQL = "alter table ~TABLENAME~ drop foreign key ~CONSTRAINTNAME~";
				$strSQL = str_replace('~TABLENAME~', $strTable_a, $strSQL);
				$strSQL = str_replace('~CONSTRAINTNAME~', $strConstraintName, $strSQL);
				dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
			}
		}
	}
	dbCloseRecordset($objResult);
	
	// foreign keys
	$strSQL = "show indexes in ~TABLENAME~ where Column_name = '~FIELDNAME~'";
	$strSQL = str_replace("~TABLENAME~", $strTable_a, $strSQL);
	$strSQL = str_replace("~FIELDNAME~", $strField_a, $strSQL);
	$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
	while ($arrRow = dbReadRecord($objResult)) 
	{
		$strFK = $arrRow['Key_name'];
		
		$strSQL = "alter table ~TABLENAME~ drop foreign key ~FK~";
		$strSQL = str_replace('~TABLENAME~', $strTable_a, $strSQL);
		$strSQL = str_replace('~FK~', $strFK, $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	}
	dbCloseRecordset($objResult);
	dbClearErrors();
}

// commit a transaction support nesting
function dbEndTrans($objConn_a, $strCaller_a = "")
{
    $blnResult = true;

    global $g_blnExecuteError;
    global $g_intErrorCode;
    global $g_intNestedTransactions;
	global $g_strErrorDescription;

	$g_intNestedTransactions--;

	if ($g_intNestedTransactions == 0) 
	{
		try
		{
			if ($g_blnExecuteError == false) 
			{
				$blnResult = dbExecuteSQL($objConn_a, "commit", $strCaller_a);
				logDebug($strCaller_a . ': ' . getSessionDB(__FUNCTION__) . ':' . 'commit transaction', '');
				if ($blnResult) {
					$g_blnExecuteError = false;
					//$g_intErrorCode = 0;
					//$g_strErrorDescription = "";
					//dbClearCustomError();
					//dbClearSuccessMessage();
				}
			} 
			else 
			{
				dbExecuteSQL($objConn_a, "rollback", $strCaller_a);
				logDebug($strCaller_a . ': ' . getSessionDB(__FUNCTION__) . ':' . 'rollback transaction due to: ' . dbErrorDescription(false), '');
				$blnResult = false;
			}
		} 
		catch (PDOException $e) 
		{
			dbExecuteSQL($objConn_a, "rollback", $strCaller_a);
			logDebug($strCaller_a . ': ' . getSessionDB(__FUNCTION__) . ':' . 'rollback transaction (error) due to: ' . dbErrorDescription(false), '');
			$blnResult = false;
		}
	} 
	else 
	{
		$blnResult = !$g_blnExecuteError;
		if ($blnResult) 
		{
			//logDebug($strCaller_a . ': ' . getSessionDB(__FUNCTION__) . ':' . 'nested commit: ' . $g_intNestedTransactions, '');
			//logSQL(LOG_DEBUG_SQL_FILE, false, 'nested commit: ' . $g_intNestedTransactions, '', 0);
			logSQL($objConn_a->logFile, false, $strCaller_a . ': ' . getSessionDB(__FUNCTION__) . ':' . 'nested commit: ' . $g_intNestedTransactions, '', 0);
		} 
		else 
		{
			//logDebug($strCaller_a . ': ' . getSessionDB(__FUNCTION__) . ':' . 'nested rollback: ' . $g_intNestedTransactions . ' due to: ' . dbErrorDescription(false), '');
			//logSQL(LOG_DEBUG_SQL_FILE, false, 'nested rollback: ' . $g_intNestedTransactions, '', 0);
			logSQL($objConn_a->logFile, false, $strCaller_a . ': ' . getSessionDB(__FUNCTION__) . ':' . 'nested rollback: ' . $g_intNestedTransactions . ' due to: ' . dbErrorDescription(false), '', 0);
		}
	}

    return $blnResult;
}

function dbErrorCode()
{
    global $g_intErrorCode;

    return $g_intErrorCode;
}

function dbErrorDescription($blnCustomOnly_a)
{
	global $g_strCustomError;
	global $g_strErrorDescription;
	
	$strResult = "";
	
	if (strlen($g_strCustomError) > 0)
	{
		$strResult = $g_strCustomError;
	}
	else
	{
		if (!$blnCustomOnly_a)
		{
			$strResult = $g_strErrorDescription;
		}
	}
	
	return $strResult;
}

function dbSuccessMessage()
{
	global $g_strSuccessMessage;
	
	return $g_strSuccessMessage;
}

// source database is optional and substituted in the script where %SOURCEDB% is present
// returns true if successful, false if not
function dbExecuteScript($objConn_a, $strScriptFile_a, $strSourceDB_a)
{
    $blnResult = true;
	$blnInCommand = false;
    $blnInCommentML = false;
    $blnInCommentSL = false;
    $blnInQuot = false;
	$intBeginNestCount = 0;
    $strChar = '';
    $strCharPrev = '';
	$strCommand = '';
    $strQuotChar = '';

    $strScript = loadFile($strScriptFile_a);
    $strScript = str_replace("%SOURCEDB%", $strSourceDB_a, $strScript);

    if (strlen($strScript) > 0) 
	{
        $strSQL = '';
        $intEOF = strlen($strScript);

        $blnX = dbExecuteSQL($objConn_a, "set foreign_key_checks=0;");
        if ($blnX == false) {$blnResult = false;}

        //$blnX = dbExecuteSQL($objConn_a, $strScript);
        //if ($blnX == false) { $blnResult = false; }

        //$blnX = dbExecuteSQL($objConn_a, "set foreign_key_checks=1;");
        //if ($blnX == false) { $blnResult = false; }

        //return $blnResult;

        for ($intI = 0; $intI < $intEOF; $intI++) 
		{
            $strCharPrev = $strChar;
            $strChar = substr($strScript, $intI, 1);
			$strCommandChar = $strChar;
			
			//if ($strCommandChar == "\n")
			//{
				//$strCommand .= " ";
			//}
			//else if ($strCommandChar == "\t")
			//{
				//$strCommand .= " ";
			//}
			//else if ($strCommandChar == ";")
			//{
				//$strCommand .= "; ";
			//}
			//else
			//{
				$strCommand .= strtoupper($strCommandChar);
			//}
			
			if (strlen($strCommand) > 20)
			{
				$strCommand = substr($strCommand, -20);
			}
//logError(false,$strCommand);

            if ($blnInCommentML) 
			{
                // in multi-line comments
                if ($strCharPrev . $strChar == "*/") 
				{
                    $blnInCommentML = false;
                }
            } 
			else if ($blnInCommentSL) 
			{
                // in single-line comments
                if ($strChar == "\n") 
				{
                    $blnInCommentSL = false;
                }
            } 
			else 
			{
                if ($blnInQuot) 
				{
                    // in quotes
                    $strSQL .= $strChar;

                    if ($strChar == $strQuotChar) 
					{
                        $blnInQuot = false;
                    }
                } 
				else 
				{
                    if ($strChar == "'") 
					{
                        $strQuotChar = "'";
                        $blnInQuot = true;
                        $strSQL .= $strChar;
                    } 
					else if ($strChar == '"') 
					{
                        $strQuotChar = '"';
                        $blnInQuot = true;
                        $strSQL .= $strChar;
                    } 
					else if (($blnInCommand == false) && ($strChar == ";")) 
					{
                        // end of statement so we can execute it
                        //echo($strSQL . "<hr>");
                        $blnX = dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
                        if ($blnX == false) {$blnResult = false;}
                        $strSQL = "";
                    }
					else if ($strCharPrev . $strChar == "/*") 
					{
                        // starting a comment, so remove the last character from the statement so far
                        if (substr($strSQL, -1) == "/") 
						{
                            // remove last 1
                            $strSQL = substr($strSQL, 0, strlen($strSQL) - 1);
                        }
                        $blnInCommentML = true;
                    } 
					else if (($blnInCommand == false) && ($strCharPrev . $strChar == "--")) 
					{
                        // starting a comment, so remove the last character from the statement so far
                        if (substr($strSQL, -1) == "-") 
						{
                            // remove last 1
                            $strSQL = substr($strSQL, 0, strlen($strSQL) - 1);
                        }
                        $blnInCommentSL = true;
					//} else if (($blnInCommand == true) && (substr($strCommand, -6) == " BEGIN")) {
						//$intBeginNestCount++;
                        //$strSQL .= $strChar;
					//} else if (($blnInCommand == true) && (substr($strCommand, -5) == " CASE")) {
						//$intBeginNestCount--;	// rollback the begin as case followed
                        //$strSQL .= $strChar;
					//} else if (($blnInCommand == true) && (substr($strCommand, -5) == " END;")) {
					} 
					else if (($blnInCommand == true) && (substr($strCommand, -17) == "-- ENDOFSTATEMENT")) 
					{
						//$intBeginNestCount--;
                        $strSQL .= $strChar;
						$strSQL = str_replace("-- ENDOFSTATEMENT", "", $strSQL);
						
						//if ($intBeginNestCount == 0)
						//{
							$blnInCommand = false;
							// end of command so we can execute it
							//echo($strSQL . "<hr>");
							$blnX = dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
							if ($blnX == false) {$blnResult = false;}
							$strSQL = "";
						//}
					} 
					else if (($blnInCommand == false) && (substr($strCommand, -9) == " FUNCTION")) 
					{
						$blnInCommand = true;
						$intBeginNestCount = 0;
                        $strSQL .= $strChar;
					} 
					else if (($blnInCommand == false) && (substr($strCommand, -10) == " PROCEDURE")) 
					{
						$blnInCommand = true;
						$intBeginNestCount = 0;
                        $strSQL .= $strChar;
                    } 
					else 
					{
                        $strSQL .= $strChar;
                    }
                }
            }
        }

        $blnX = dbExecuteSQL($objConn_a, "set foreign_key_checks=1;");
        if ($blnX == false) {$blnResult = false;}
    }

    return $blnResult;
}

// execute an sql (insert, update, delete)
// returns true if successful, false if not
function dbExecuteSQL($objConn_a, $strSQL_a, $strCaller_a = "")
{
    global $g_blnExecuteError;
    global $g_intErrorCode;
	global $g_intLastAffected;
	global $g_strErrorDescription;

//logSQL($objConn_a->logFile, false, $strCaller_a . ': ' . getSessionDB(__FUNCTION__) . ':' . $strSQL_a . ' - ' . $intProcessTimeframe . ' seconds', $g_strErrorDescription, $intAffected);

    $blnResult = true;
    $intAffected = 0;
    $intStartProcess = time();
    $objStatement = null;

    dbInitialise($objConn_a);
    dbUTF8($objConn_a);
    try
    {
        $objStatement = $objConn_a->PDO->prepare($strSQL_a);
        $objStatement->execute();
        $intAffected = $objStatement->rowCount();
    } 
	catch (PDOException $e) 
	{
        $blnResult = false;
        $g_blnExecuteError = true;
        $g_intErrorCode = $e->getCode();
        $g_strErrorDescription = $e->getMessage();
    }

    $intStopProcess = time();
    $intProcessTimeframe = ($intStopProcess - $intStartProcess);

    //logSQL(LOG_DEBUG_SQL_FILE, false, $strSQL_a . ' - ' . $intProcessTimeframe . ' seconds', $g_strErrorDescription, $intAffected);
    logSQL($objConn_a->logFile, false, $strCaller_a . ': ' . getSessionDB(__FUNCTION__) . ':' . $strSQL_a . ' - ' . $intProcessTimeframe . ' seconds', $g_strErrorDescription, $intAffected);
	$g_intLastAffected = $intAffected;

    return $blnResult;
}

// execute an sql (insert, update, delete) but don't log any debug info unless there is an error
// returns true if successful, false if not
function dbExecuteSQLNoDebug($objConn_a, $strSQL_a, $strCaller_a = "")
{
    global $g_blnExecuteError;
    global $g_intErrorCode;
	global $g_intLastAffected;
	global $g_strErrorDescription;

    $blnResult = true;
    $intAffected = 0;
    $intStartProcess = time();
    $objStatement = null;

    dbInitialise($objConn_a);
    dbUTF8($objConn_a);
    try
    {
        $objStatement = $objConn_a->PDO->prepare($strSQL_a);
        $objStatement->execute();
        $intAffected = $objStatement->rowCount();
    } 
	catch (PDOException $e) 
	{
        $blnResult = false;
        $g_blnExecuteError = true;
        $g_intErrorCode = $e->getCode();
        $g_strErrorDescription = $e->getMessage();
    }

    if ($g_strErrorDescription != '') {
        $intStopProcess = time();
        $intProcessTimeframe = ($intStopProcess - $intStartProcess);

        //logSQL(LOG_DEBUG_SQL_FILE, false, $strSQL_a . ' - ' . $intProcessTimeframe . ' seconds', $g_strErrorDescription, $intAffected);
        logSQL($objConn_a->logFile, false, $strCaller_a . ': ' . getSessionDB(__FUNCTION__) . ':' . $strSQL_a . ' - ' . $intProcessTimeframe . ' seconds', $g_strErrorDescription, $intAffected);
    }
	$g_intLastAffected = $intAffected;

    return $blnResult;
}

// execute an sql (insert, update, delete)
// returns true if successful, false if not
function dbExecuteST($objConn_a, $objStatement_a, $strCaller_a = "")
{
    global $g_blnExecuteError;
    global $g_intErrorCode;
	global $g_intLastAffected;
	global $g_strErrorDescription;
	global $g_strLastStatement;

    $blnResult = true;
    $intAffected = 0;
    $intStartProcess = time();

    //dbInitialise($objConn_a);
    //dbUTF8($objConn_a);
    try
    {
        $objStatement_a->execute();
        $intAffected = $objStatement_a->rowCount();
    } 
	catch (PDOException $e) 
	{
        $blnResult = false;
        $g_blnExecuteError = true;
        $g_intErrorCode = $e->getCode();
        $g_strErrorDescription = $e->getMessage();
    }

    $intStopProcess = time();
    $intProcessTimeframe = ($intStopProcess - $intStartProcess);

    //logSQL(LOG_DEBUG_SQL_FILE, false, $g_strLastStatement . ' - ' . $intProcessTimeframe . ' seconds', $g_strErrorDescription, $intAffected);
    logSQL($objConn_a->logFile, false, $strCaller_a . ': ' . getSessionDB(__FUNCTION__) . ':' . $g_strLastStatement . ' - ' . $intProcessTimeframe . ' seconds', $g_strErrorDescription, $intAffected);
	$g_intLastAffected = $intAffected;

    return $blnResult;
}

// execute an sql (insert, update, delete) but don't log any debug info unless there is an error
// returns true if successful, false if not
function dbExecuteSTNoDebug($objConn_a, $objStatement_a, $strCaller_a = "")
{
    global $g_blnExecuteError;
    global $g_intErrorCode;
	global $g_intLastAffected;
	global $g_strErrorDescription;
	global $g_strLastStatement;

    $blnResult = true;
    $intAffected = 0;
    $intStartProcess = time();

    //dbInitialise($objConn_a);
    //dbUTF8($objConn_a);
    try
    {
        $objStatement_a->execute();
        $intAffected = $objStatement_a->rowCount();
    } 
	catch (PDOException $e) 
	{
        $blnResult = false;
        $g_blnExecuteError = true;
        $g_intErrorCode = $e->getCode();
        $g_strErrorDescription = $e->getMessage();
    }

    if ($g_strErrorDescription != '') 
	{
        $intStopProcess = time();
        $intProcessTimeframe = ($intStopProcess - $intStartProcess);

        //logSQL(LOG_DEBUG_SQL_FILE, false, $g_strLastStatement . ' - ' . $intProcessTimeframe . ' seconds', $g_strErrorDescription, $intAffected);
        logSQL($objConn_a->logFile, false, $strCaller_a . ': ' . getSessionDB(__FUNCTION__) . ':' . $g_strLastStatement . ' - ' . $intProcessTimeframe . ' seconds', $g_strErrorDescription, $intAffected);
    }
	$g_intLastAffected = $intAffected;

    return $blnResult;
}

// returns true if successful, false if not, 0 chunksize means don't chunk
function dbExport($strHostname_a, $strLogin_a, $strPassword_a, $strDatabase_a, $strTarget_a, $blnSchema_a, $blnData_a, $intChunk_a = 0, $intChunkOf_a = 0, $intChunkSize_a = 0)
{
    $blnResult = true;

    $objConn = dbOpen($strHostname_a, $strLogin_a, $strPassword_a, $strDatabase_a);

    $arrTables = dbGetTables($objConn);
    foreach ($arrTables as $arrTable) 
	{
        if ($blnSchema_a) 
		{
            appendFile($strTarget_a, $arrTable['create'] . ";\n\n-- ENDOFSTATEMENT\n\n"); //";\n\n");
        }

        if ($blnData_a) 
		{
            $strTable = $arrTable['name'];
            $strDatafile = $strTarget_a;
            $strDatafile = str_replace(".dat", "-" . $strTable . ".dat", $strDatafile);
            $strDatafile = str_replace(".sql", "-" . $strTable . ".dat", $strDatafile);

            $blnX = dbExportTableInternal($objConn, $strTable, $strDatafile, $intChunk_a, $intChunkOf_a, $intChunkSize_a);
            if ($blnX == false) {$blnResult = false;}

            //if (toBoolean(DBSYSTEM_FILESIZE)) {
                //if (filesize($strDatafile) == 0) {
                    //deleteFile($strDatafile);
                //}
            //}
        }
    }

    $arrFunctions = dbGetFunctions($objConn);
    foreach ($arrFunctions as $arrFunction) 
	{
        if ($blnSchema_a) 
		{
            appendFile($strTarget_a, $arrFunction['create'] . ";\n\n-- ENDOFSTATEMENT\n\n"); //";\n\n");
        }
    }

    $arrProcedures = dbGetProcedures($objConn);
    foreach ($arrProcedures as $arrProcedure) 
	{
        if ($blnSchema_a) 
		{
            appendFile($strTarget_a, $arrProcedure['create'] . ";\n\n-- ENDOFSTATEMENT\n\n"); //";\n\n");
        }
    }

    $arrViews = dbGetViews($objConn);
    foreach ($arrViews as $arrView) 
	{
        if ($blnSchema_a) 
		{
            appendFile($strTarget_a, $arrView['create'] . ";\n\n-- ENDOFSTATEMENT\n\n"); //";\n\n");
        }
    }

    dbClose($objConn);

    return $blnResult;
}

// returns true if successful, false if not, 0 chunksize means don't chunk
function dbExportTable($strHostname_a, $strLogin_a, $strPassword_a, $strDatabase_a, $strTablename_a, $strTarget_a, $blnSchema_a, $blnData_a, $intChunk_a = 0, $intChunkOf_a = 0, $intChunkSize_a = 0)
{
    $blnResult = true;

    $objConn = dbOpen($strHostname_a, $strLogin_a, $strPassword_a, $strDatabase_a);

    $arrTables = dbGetTable($objConn, $strTablename_a);
    foreach ($arrTables as $arrTable) 
	{
		if ($blnSchema_a) 
		{
			appendFile($strTarget_a, $arrTable['create'] . ";\n\n-- ENDOFSTATEMENT\n\n"); //";\n\n");
		}

		if ($blnData_a) 
		{
			$strTable = $arrTable['name'];
			$strDatafile = $strTarget_a;
			$strDatafile = str_replace(".dat", "-" . $strTable . ".dat", $strDatafile);
			$strDatafile = str_replace(".sql", "-" . $strTable . ".dat", $strDatafile);

			$blnX = dbExportTableInternal($objConn, $strTable, $strDatafile, $intChunk_a, $intChunkOf_a, $intChunkSize_a);
			if ($blnX == false) {$blnResult = false;}

			//if (toBoolean(DBSYSTEM_FILESIZE)) {
				//if (filesize($strDatafile) == 0) {
					//deleteFile($strDatafile);
				//}
			//}
		}
    }

    // $arrFunctions = dbGetFunctions($objConn);
    // foreach ($arrFunctions as $arrFunction) {
        // if ($blnSchema_a) {
            // appendFile($strTarget_a, $arrFunction['create'] . ";\n\n-- ENDOFSTATEMENT\n\n"); //";\n\n");
        // }
    // }

    // $arrProcedures = dbGetProcedures($objConn);
    // foreach ($arrProcedures as $arrProcedure) {
        // if ($blnSchema_a) {
            // appendFile($strTarget_a, $arrProcedure['create'] . ";\n\n-- ENDOFSTATEMENT\n\n"); //";\n\n");
        // }
    // }

    // $arrViews = dbGetViews($objConn);
    // foreach ($arrViews as $arrView) {
        // if ($blnSchema_a) {
            // appendFile($strTarget_a, $arrView['create'] . ";\n\n-- ENDOFSTATEMENT\n\n"); //";\n\n");
        // }
    // }

    dbClose($objConn);

    return $blnResult;
}

// chunkof 1 means don't chunk
function dbExportTableInternal($objConn_a, $strTable_a, $strDatafile_a, $intChunk_a = 0, $intChunkOf_a = 0, $intChunkSize_a = 0)
{
    $blnResult = true;

	$strDatafileBase = $strDatafile_a;

	$strSQL = "select count(*) returnvalue from ~TABLE~";
	$strSQL = str_replace("~TABLE~", $strTable_a, $strSQL);
	$intRecordCount = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
	if ($intRecordCount > 0)
	{
		$blnHasID = true;
		$intStart = 0;
		$strDatafile = $strDatafileBase;
		
		if ($intChunkOf_a > 1)
		{
			$intStart = ($intChunk_a - 1) * $intChunkSize_a;
			$strDatafile = str_replace(".dat", "-" . $intChunk_a . "-" . $intChunkOf_a . ".dat", $strDatafile);
		}

		if (toBoolean(DBSYSTEM_SIMULATEDLOCALOUTFILE)) 
		{
			$strSQL = "select * from ~TABLE~ limit 0, 1";
			$strSQL = str_replace("~TABLE~", $strTable_a, $strSQL);
			$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
			$intFieldCount = $objResult->columnCount();
			dbCloseRecordset($objResult);
			
			if ($intChunkOf_a > 1)
			{
				// NOTE: this one is ordered to force that there is no randomness between separate files based on purely limits... since no sort order is not guaranteed to be in any order
				$strSQL = "select * from ~TABLE~ order by id limit ~START~,~CHUNKSIZE~";
				$strSQL = str_replace("~TABLE~", $strTable_a, $strSQL);
				$strSQL = str_replace("~START~", $intStart, $strSQL);
				$strSQL = str_replace("~CHUNKSIZE~", $intChunkSize_a, $strSQL);
				$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__, false);

				if ($objResult == null)
				{
					$blnHasID = false;
					dbClearErrors();
				}
				
				if (!$blnHasID)
				{
					// NOTE: try again without sorting
					$strSQL = "select * from ~TABLE~ limit ~START~,~CHUNKSIZE~";
					$strSQL = str_replace("~TABLE~", $strTable_a, $strSQL);
					$strSQL = str_replace("~START~", $intStart, $strSQL);
					$strSQL = str_replace("~CHUNKSIZE~", $intChunkSize_a, $strSQL);
					$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
				}
			}
			else
			{
				$strSQL = "select * from ~TABLE~";
				$strSQL = str_replace("~TABLE~", $strTable_a, $strSQL);
				$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
			}
			
			try
			{
				$objFile = fopen($strDatafile, 'a');

				$strOutput = '';
				$intRow = 0;
				while ($arrRow = dbReadRecord($objResult)) 
				{
					for ($intField = 0; $intField < $intFieldCount; $intField++) 
					{
						if (is_null($arrRow[$intField])) 
						{
							$strOutput .= '\N';
						} 
						else 
						{
							$strValue = $arrRow[$intField];
							$strValue = str_replace("\\", "\\\\", $strValue);
							$strValue = str_replace(",", "\,", $strValue);
							$strValue = str_replace("\n", "\\\n", $strValue);
							if (isset($strValue)) 
							{
								$strOutput .= $strValue;
							}
						}

						if ($intField < ($intFieldCount - 1)) 
						{
							$strOutput .= ',';
						}
					}

					$strOutput .= "\n";
					// write every 200 records
					if (($intRow % 200) == 0) 
					{
						fwrite($objFile, $strOutput);
						$strOutput = '';
					}
					
					$intRow++;
				}

				if (strlen($strOutput) > 0) 
				{
					// write remaining records
					fwrite($objFile, $strOutput);
				}

				fclose($objFile);

				$blnResult = true;
			} 
			catch (Exception $e) 
			{
				if ($objFile != null) 
				{
					fclose($objFile);
				}
				$blnResult = false;
			}
			
			dbCloseRecordset($objResult);
			
		} 
		else 
		{
			if ($intChunkOf_a > 1)
			{
				// NOTE: this one is ordered to force that there is no randomness between separate files based on purely limits... since no sort order is not guaranteed to be in any order
				$strSQL = "select * from ~TABLE~ order by id limit ~START~,~CHUNKSIZE~ into outfile '~DATAFILE~' character set utf8 fields terminated by ',' escaped by '\\\\' lines terminated by '\\n'";
				$strSQL = str_replace("~TABLE~", $strTable_a, $strSQL);
				$strSQL = str_replace("~DATAFILE~", $strDatafile, $strSQL);
				$strSQL = str_replace("~START~", $intStart, $strSQL);
				$strSQL = str_replace("~CHUNKSIZE~", $intChunkSize_a, $strSQL);
				$blnResult = dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

				if (!$blnResult)
				{
					$blnHasID = false;
					dbClearErrors();
				}

				if (!$blnHasID)
				{
					// NOTE: try again without sorting
					$strSQL = "select * from ~TABLE~ limit ~START~,~CHUNKSIZE~ into outfile '~DATAFILE~' character set utf8 fields terminated by ',' escaped by '\\\\' lines terminated by '\\n'";
					$strSQL = str_replace("~TABLE~", $strTable_a, $strSQL);
					$strSQL = str_replace("~DATAFILE~", $strDatafile, $strSQL);
					$strSQL = str_replace("~START~", $intStart, $strSQL);
					$strSQL = str_replace("~CHUNKSIZE~", $intChunkSize_a, $strSQL);
					$blnResult = dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
				}
			}
			else
			{
				$strSQL = "select * from ~TABLE~ into outfile '~DATAFILE~' character set utf8 fields terminated by ',' escaped by '\\\\' lines terminated by '\\n'";
				$strSQL = str_replace("~TABLE~", $strTable_a, $strSQL);
				$strSQL = str_replace("~DATAFILE~", $strDatafile, $strSQL);
				$blnResult = dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
			}
		}
	}

    return $blnResult;
}

function dbExportToCSV($objConn_a, $strTable_a, $strFields_a, $strOrderBy_a, $strDatafile_a)
{
    $blnResult = true;

    if (toBoolean(DBSYSTEM_SIMULATEDLOCALOUTFILE)) 
	{
        $strSQL = "select ~FIELDS~ from ~TABLE~ ~ORDERBY~";
		$strSQL = str_replace("~FIELDS~", $strFields_a, $strSQL);
        $strSQL = str_replace("~TABLE~", $strTable_a, $strSQL);
		$strSQL = str_replace("~ORDERBY~", $strOrderBy_a, $strSQL);
        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
        $intFieldCount = $objResult->columnCount();

        try
        {
            $objFile = fopen($strDatafile_a, 'a');

            $strOutput = '';
            $intRow = 0;
            while ($arrRow = dbReadRecord($objResult)) 
			{
                for ($intField = 0; $intField < $intFieldCount; $intField++) 
				{
                    if (is_null($arrRow[$intField])) 
					{
                        $strOutput .= '\N';
                    } 
					else 
					{
                        $strValue = $arrRow[$intField];
                        $strValue = str_replace("\\", "\\\\", $strValue);
                        //$strValue = str_replace(",", "\,", $strValue);
						$strValue = str_replace("\"", "\\\"", $strValue);
                        $strValue = str_replace("\n", "\\\n", $strValue);
                        if (isset($strValue)) {
                            $strOutput .= "\"" . $strValue . "\"";
                        }
                    }

                    if ($intField < ($intFieldCount - 1)) 
					{
                        $strOutput .= ',';
                    }
                }

                $strOutput .= "\n";
                // write every 200 records
                if (($intRow % 200) == 0) 
				{
                    fwrite($objFile, $strOutput);
                    $strOutput = '';
                }
				
				$intRow++;
            }
            if (strlen($strOutput) > 0) 
			{
                // write remaining records
                fwrite($objFile, $strOutput);
            }

            fclose($objFile);

            $blnResult = true;
        } 
		catch (Exception $e) 
		{
            if ($objFile != null) 
			{
                fclose($objFile);
            }
            $blnResult = false;
        }

		dbCloseRecordset($objResult);

    } 
	else 
	{
        $strSQL = "select ~FIELDS~ from ~TABLE~ ~ORDERBY~ into outfile '~DATAFILE~' character set utf8 fields terminated by ',' optionally enclosed by '\"' lines terminated by '\\n'";
		$strSQL = str_replace("~FIELDS~", $strFields_a, $strSQL);
        $strSQL = str_replace("~TABLE~", $strTable_a, $strSQL);
		$strSQL = str_replace("~ORDERBY~", $strOrderBy_a, $strSQL);
        $strSQL = str_replace("~DATAFILE~", $strDatafile_a, $strSQL);
        $blnResult = dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
    }

    return $blnResult;
}

function dbExportToHTML($objConn_a, $strTable_a, $strFields_a, $strOrderBy_a, $strDatafile_a)
{
    $blnResult = true;

	$strSQL = "select ~FIELDS~ from ~TABLE~ ~ORDERBY~";
	$strSQL = str_replace("~FIELDS~", $strFields_a, $strSQL);
	$strSQL = str_replace("~TABLE~", $strTable_a, $strSQL);
	$strSQL = str_replace("~ORDERBY~", $strOrderBy_a, $strSQL);
	$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
	$intFieldCount = $objResult->columnCount();

	try
	{
		$objFile = fopen($strDatafile_a, 'a');
		
		$strOutput = "<html>";
		$strOutput .= "<head></head>";
		$strOutput .= "<body>";
		$strOutput .= "<table>";
		
		$intRow = 0;
		while ($arrRow = dbReadRecord($objResult)) 
		{
			$strOutput .= "<tr>";
			
			for ($intField = 0; $intField < $intFieldCount; $intField++) 
			{
				if (is_null($arrRow[$intField])) 
				{
					//$strOutput .= '\N';
					//$strOutput .= "<tr>";
					$strOutput .= "<td>&nbsp;</td>";
				} 
				else 
				{
					$strValue = $arrRow[$intField];
					$strValue = str_replace("\\", "\\\\", $strValue);
					//$strValue = str_replace(",", "\,", $strValue);
					$strValue = str_replace("\"", "\\\"", $strValue);
					$strValue = str_replace("\n", "\\\n", $strValue);
					if (isset($strValue)) 
					{
						//$strOutput .= "\"" . $strValue . "\"";
						$strOutput .= "<td>" . $strValue . "</td>";
					}
				}
				
				/*
				if ($intField < ($intFieldCount - 1)) {
					$strOutput .= ',';
				}
				 * 
				 */
			}

			$strOutput .= "</tr>";
			// write every 200 records
			if (($intRow % 200) == 0) 
			{
				fwrite($objFile, $strOutput);
				$strOutput = '';
			}
			
			$intRow++;
		}
		
		$strOutput .= "</table>";
		$strOutput .= "</body>";
		$strOutput .= "</html>";
		
		if (strlen($strOutput) > 0) 
		{
			// write remaining records
			fwrite($objFile, $strOutput);
		}

		fclose($objFile);

		$blnResult = true;
	} 
	catch (Exception $e) 
	{
		if ($objFile != null) 
		{
			fclose($objFile);
		}
		$blnResult = false;
	}

	dbCloseRecordset($objResult);
    
    return $blnResult;
}

function dbGetCodeFromID($objConn_a, $strTableName_a, $strID_a, $strCaller_a = "")
{
	$strResult = "";
	
	if (strlen($strID_a) > 0)
	{
		$strSQL = "select code returnvalue from ~TABLENAME~ where id = ~ID~";
		$strSQL = str_replace('~TABLENAME~', ff($strTableName_a), $strSQL);
		$strSQL = str_replace('~ID~', ff($strID_a), $strSQL);
		$strResult = dbReadValue($objConn_a, $strSQL, $strCaller_a);
	}
	
	return $strResult;
}

function dbGetDescriptionFromID($objConn_a, $strTableName_a, $strID_a, $strCaller_a = "")
{
	$strResult = "";
	
	if (strlen($strID_a) > 0)
	{
		$strSQL = "select description returnvalue from ~TABLENAME~ where id = ~ID~";
		$strSQL = str_replace('~TABLENAME~', ff($strTableName_a), $strSQL);
		$strSQL = str_replace('~ID~', ff($strID_a), $strSQL);
		$strResult = dbReadValue($objConn_a, $strSQL, $strCaller_a);
	}
	
	return $strResult;
}

function dbGetIDFromCode($objConn_a, $strTableName_a, $strCode_a, $strCaller_a = "")
{
	$strResult = "";
	
	if (strlen($strCode_a) > 0)
	{
		$strSQL = "select id returnvalue from ~TABLENAME~ where code = '~CODE~'";
		$strSQL = str_replace('~TABLENAME~', ff($strTableName_a), $strSQL);
		$strSQL = str_replace('~CODE~', ff($strCode_a), $strSQL);
		$strResult = dbReadValue($objConn_a, $strSQL, $strCaller_a);
	}
	
	return $strResult;
}

function dbGetExportedFiles($strSource_a, $strType_a)
{
	$arrResult = array();
	
	$strPath = $strSource_a;
	$strPath = strrev($strPath);
	$intPos = strpos($strPath, '/');
	$strPath = substr($strSource_a, 0, strlen($strSource_a) - $intPos);
	$arrFiles = getFiles($strPath, false);
    $intTypeLen = strlen($strType_a . '-');
    
	logDebug(print_r($arrFiles, true), '');
	
	foreach ($arrFiles as $strFile)
	{
		if ((strLeft($strFile, $intTypeLen, "") == $strType_a . '-') && (strRight($strFile, 4) == '.dat'))
		{
			$strFileName = $strPath . $strFile;
			$strTableName = $strFile;	// before processed
			$intChunk = 1;
			$intChunkOf = 1;
			
			$arrTemp = explode('.', $strTableName);
			$strTableName = $arrTemp[0];	// with prefix removed
			
			$arrTemp = explode('-', $strTableName);
			$strTableName = $arrTemp[1];
			if (count($arrTemp) > 2)
			{
				$intChunk = $arrTemp[2];
				$intChunkOf = $arrTemp[3];
			}
		
			$arrResult[] = array(
				"filename" => $strFileName,
				"tablename" => $strTableName,
				"chunk" => $intChunk,
				"chunkof" => $intChunkOf
			);
		}
	}
	
	return $arrResult;
}

// returns an array of functions
function dbGetFunctions($objConn_a)
{
    // read the functions
    $arrResult = array();

    $objResult = dbOpenRecordset($objConn_a, "show function status where db = '" . $objConn_a->getDatabaseName() . "'", __FUNCTION__);
    $arrRows = $objResult->fetchAll();
    dbCloseRecordset($objResult);

    $intI = 0;
    foreach ($arrRows as $arrRow) 
	{
        $strFunction = $arrRow['Name'];
        $strCreate = '';

        $objResult = dbOpenRecordset($objConn_a, 'show create function ' . $strFunction, __FUNCTION__);
        $arrColumns = $objResult->fetchAll();
        $strCreate = preg_replace("/AUTO_INCREMENT=[\w]*./", '', $arrColumns[0]['Create Function']);
        dbCloseRecordset($objResult);

        $arrResult[$intI]['name'] = $strFunction;
        $arrResult[$intI]['create'] = $strCreate;
        $intI++;
    }

    return $arrResult;
}

// returns an array of functions
function dbGetFunctionNames($objConn_a)
{
    // read the functions
    $arrResult = array();

    $objResult = dbOpenRecordset($objConn_a, "show function status where db = '" . $objConn_a->getDatabaseName() . "'", __FUNCTION__);
    $arrRows = $objResult->fetchAll();
    dbCloseRecordset($objResult);

    foreach ($arrRows as $arrRow) 
	{
        $strFunction = $arrRow[0];

        $arrResult[] = $strFunction;
    }

    return $arrResult;
}

// returns an array of procedures
function dbGetProcedures($objConn_a)
{
    // read the procedures
    $arrResult = array();

    $objResult = dbOpenRecordset($objConn_a, "show procedure status where db = '" . $objConn_a->getDatabaseName() . "'", __FUNCTION__);
    $arrRows = $objResult->fetchAll();
    dbCloseRecordset($objResult);

    $intI = 0;
    foreach ($arrRows as $arrRow) 
	{
        $strProcedure = $arrRow['Name'];
        $strCreate = '';

        $objResult = dbOpenRecordset($objConn_a, 'show create procedure ' . $strProcedure, __FUNCTION__);
        $arrColumns = $objResult->fetchAll();
        $strCreate = preg_replace("/AUTO_INCREMENT=[\w]*./", '', $arrColumns[0]['Create Procedure']);
        dbCloseRecordset($objResult);

        $arrResult[$intI]['name'] = $strProcedure;
        $arrResult[$intI]['create'] = $strCreate;
        $intI++;
    }

    return $arrResult;
}

// returns an array of procedures
function dbGetProcedureNames($objConn_a)
{
    // read the procedures
    $arrResult = array();

    $objResult = dbOpenRecordset($objConn_a, "show procedure status where db = '" . $objConn_a->getDatabaseName() . "'", __FUNCTION__);
    $arrRows = $objResult->fetchAll();
    dbCloseRecordset($objResult);

    foreach ($arrRows as $arrRow) 
	{
        $strProcedure = $arrRow[0];

        $arrResult[] = $strProcedure;
    }

    return $arrResult;
}

// returns the db schema elements as an array suitable to convert to JSON
function dbGetSchemaElements($strHostname_a, $strLogin_a, $strPassword_a, $strDatabase_a)
{
    $arrResult = array();

    $objConn = dbOpen($strHostname_a, $strLogin_a, $strPassword_a, $strDatabase_a);

    $arrTables = dbGetTableNames($objConn);
    $arrFunctions = dbGetFunctionNames($objConn);
    $arrProcedures = dbGetProcedureNames($objConn);
    $arrViews = dbGetViewNames($objConn);

	$arrResult = array(
		"tables" => $arrTables,
		"functions" => $arrFunctions,
		"procedures" => $arrProcedures,
		"views" => $arrViews
	);

    dbClose($objConn);

    return $arrResult;
}

function dbGetSchemaVersion($strHostname_a, $strLogin_a, $strPassword_a, $strDatabase_a)
{
    $strResult = "";

    $objConn = dbOpen($strHostname_a, $strLogin_a, $strPassword_a, $strDatabase_a);
    $strResult = systemSettingGet($objConn, "SCHEMA_VERSION");
    dbClose($objConn);

    return $strResult;
}

// returns an array with a single table
function dbGetTable($objConn_a, $strTablename_a)
{
    // read the tables
    $arrResult = array();

	$strSQL = "show full tables in " . $objConn_a->getDatabaseName() . " where table_type like '%TABLE%' and tables_in_" . $objConn_a->getDatabaseName() . " = '~TABLENAME~'";
	$strSQL = str_replace("~TABLENAME~", $strTablename_a, $strSQL);
    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
    $arrRows = $objResult->fetchAll();
    dbCloseRecordset($objResult);

    $intI = 0;
    foreach ($arrRows as $arrRow) 
	{
        $strTable = $arrRow[0];
        $strCreate = '';

        $objResult = dbOpenRecordset($objConn_a, 'show create table ' . $strTable, __FUNCTION__);
        $arrColumns = $objResult->fetchAll();
        $strCreate = preg_replace("/AUTO_INCREMENT=[\w]*./", '', $arrColumns[0][1]);
        dbCloseRecordset($objResult);

        $arrResult[$intI]['name'] = $strTable;
        $arrResult[$intI]['create'] = $strCreate;
        $intI++;
    }

    return $arrResult;
}

// returns an array of tables
function dbGetTables($objConn_a)
{
    // read the tables
    $arrResult = array();

    $objResult = dbOpenRecordset($objConn_a, "show full tables in " . $objConn_a->getDatabaseName() . " where table_type like '%TABLE%'", __FUNCTION__);
    $arrRows = $objResult->fetchAll();
    dbCloseRecordset($objResult);

    $intI = 0;
    foreach ($arrRows as $arrRow) 
	{
        $strTable = $arrRow[0];
        $strCreate = '';

        $objResult = dbOpenRecordset($objConn_a, 'show create table ' . $strTable, __FUNCTION__);
        $arrColumns = $objResult->fetchAll();
        $strCreate = preg_replace("/AUTO_INCREMENT=[\w]*./", '', $arrColumns[0][1]);
        dbCloseRecordset($objResult);

        $arrResult[$intI]['name'] = $strTable;
        $arrResult[$intI]['create'] = $strCreate;
        $intI++;
    }

    return $arrResult;
}

// returns an array of tables
function dbGetTableNames($objConn_a)
{
    // read the tables
    $arrResult = array();

    $objResult = dbOpenRecordset($objConn_a, "show full tables in " . $objConn_a->getDatabaseName() . " where table_type like '%TABLE%'", __FUNCTION__);
    $arrRows = $objResult->fetchAll();
    dbCloseRecordset($objResult);

    foreach ($arrRows as $arrRow) 
	{
        $strTable = $arrRow[0];
		
		$strSQL = "select count(*) returnvalue from ~TABLENAME~";
		$strSQL = str_replace("~TABLENAME~", ff($strTable), $strSQL);
		$intRecordCount = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

		$arrResult[] = array(
			"tablename" => $strTable,
			"recordcount" => $intRecordCount
		);
    }

    return $arrResult;
}

// returns an array of views
function dbGetViews($objConn_a)
{
    // read the views
    $arrResult = array();

    $objResult = dbOpenRecordset($objConn_a, "show full tables in " . $objConn_a->getDatabaseName() . " where table_type like '%VIEW%'", __FUNCTION__);
    $arrRows = $objResult->fetchAll();
    dbCloseRecordset($objResult);

    $intI = 0;
    foreach ($arrRows as $arrRow) 
	{
        $strView = $arrRow[0];
        $strCreate = '';

        $objResult = dbOpenRecordset($objConn_a, "show create view " . $objConn_a->getDatabaseName() . "." . $strView, __FUNCTION__);
        $arrColumns = $objResult->fetchAll();
        $strCreate = preg_replace("/AUTO_INCREMENT=[\w]*./", '', $arrColumns[0]['Create View']);
        dbCloseRecordset($objResult);

        $arrResult[$intI]['name'] = $strView;
        $arrResult[$intI]['create'] = $strCreate;
        $intI++;
    }

    return $arrResult;
}

// returns an array of views
function dbGetViewNames($objConn_a)
{
    // read the views
    $arrResult = array();

    $objResult = dbOpenRecordset($objConn_a, "show full tables in " . $objConn_a->getDatabaseName() . " where table_type like '%VIEW%'", __FUNCTION__);
    $arrRows = $objResult->fetchAll();
    dbCloseRecordset($objResult);

    foreach ($arrRows as $arrRow) 
	{
        $strView = $arrRow[0];

        $arrResult[] = $strView;
    }

    return $arrResult;
}

// returns true if successful, false if not
function dbImport($strHostname_a, $strLogin_a, $strPassword_a, $strDatabase_a, $strSource_a)
{
    $blnResult = true;

    $objConn = dbOpen($strHostname_a, $strLogin_a, $strPassword_a, $strDatabase_a);

    $blnX = dbExecuteSQL($objConn, "set foreign_key_checks=0;");
    if ($blnX == false) {$blnResult = false;}
    
	$arrTables = dbGetTables($objConn);
    foreach ($arrTables as $arrTable) 
	{
        $strTable = $arrTable['name'];
        $strDatafile = $strSource_a;
        $strDatafile = str_replace(".dat", "-" . $strTable . ".dat", $strDatafile);
        $strDatafile = str_replace(".sql", "-" . $strTable . ".dat", $strDatafile);

        if (file_exists($strDatafile)) 
		{
            if (toBoolean(DBSYSTEM_LOCALINFILE)) 
			{
                $strSQL = "load data LOCAL infile '~DATAFILE~' into table ~TABLE~ character set utf8 fields terminated by ',' escaped by '\\\\' lines terminated by '\\n'";
            } 
			else 
			{
                $strSQL = "load data infile '~DATAFILE~' into table ~TABLE~ character set utf8 fields terminated by ',' escaped by '\\\\' lines terminated by '\\n'";
            }
            $strSQL = str_replace("~TABLE~", $strTable, $strSQL);
            $strSQL = str_replace("~DATAFILE~", $strDatafile, $strSQL);
            $blnX = dbExecuteSQL($objConn, $strSQL, __FUNCTION__);
            if ($blnX == false) {$blnResult = false;}
        }
		else
		{
			logDebug("FILE NOT FOUND: " . $strDatafile . " in dbImport", "");
		}
    }
    $blnX = dbExecuteSQL($objConn, "set foreign_key_checks=1;");
    if ($blnX == false) {$blnResult = false;}

    dbClose($objConn);

    return $blnResult;
}

// returns true if successful, false if not
function dbImportFile($strHostname_a, $strLogin_a, $strPassword_a, $strDatabase_a, $strTablename_a, $strSource_a)
{
    $blnResult = true;

    $objConn = dbOpen($strHostname_a, $strLogin_a, $strPassword_a, $strDatabase_a);

    $blnX = dbExecuteSQL($objConn, "set foreign_key_checks=0;");
    if ($blnX == false) {$blnResult = false;}

	if (file_exists($strSource_a)) 
	{
		if (toBoolean(DBSYSTEM_LOCALINFILE)) 
		{
			$strSQL = "load data LOCAL infile '~DATAFILE~' into table ~TABLE~ character set utf8 fields terminated by ',' escaped by '\\\\' lines terminated by '\\n'";
		} 
		else 
		{
			$strSQL = "load data infile '~DATAFILE~' into table ~TABLE~ character set utf8 fields terminated by ',' escaped by '\\\\' lines terminated by '\\n'";
		}
		$strSQL = str_replace("~TABLE~", $strTablename_a, $strSQL);
		$strSQL = str_replace("~DATAFILE~", $strSource_a, $strSQL);
		$blnX = dbExecuteSQL($objConn, $strSQL, __FUNCTION__);
		if ($blnX == false) {$blnResult = false;}
	}

    $blnX = dbExecuteSQL($objConn, "set foreign_key_checks=1;");
    if ($blnX == false) {$blnResult = false;}

    dbClose($objConn);

    return $blnResult;
}

// returns true if successful, false if not
function dbImportTable($strHostname_a, $strLogin_a, $strPassword_a, $strDatabase_a, $strTablename_a, $strSource_a)
{
    $blnResult = true;

    $objConn = dbOpen($strHostname_a, $strLogin_a, $strPassword_a, $strDatabase_a);

    $blnX = dbExecuteSQL($objConn, "set foreign_key_checks=0;");
    if ($blnX == false) {$blnResult = false;}
    $arrTables = dbGetTable($objConn, $strTablename_a);
    foreach ($arrTables as $arrTable) 
	{
        $strTable = $arrTable['name'];
        $strDatafile = $strSource_a;
        $strDatafile = str_replace(".dat", "-" . $strTable . ".dat", $strDatafile);
        $strDatafile = str_replace(".sql", "-" . $strTable . ".dat", $strDatafile);

        if (file_exists($strDatafile)) 
		{
            if (toBoolean(DBSYSTEM_LOCALINFILE)) 
			{
                $strSQL = "load data LOCAL infile '~DATAFILE~' into table ~TABLE~ character set utf8 fields terminated by ',' escaped by '\\\\' lines terminated by '\\n'";
            } 
			else 
			{
                $strSQL = "load data infile '~DATAFILE~' into table ~TABLE~ character set utf8 fields terminated by ',' escaped by '\\\\' lines terminated by '\\n'";
            }
            $strSQL = str_replace("~TABLE~", $strTable, $strSQL);
            $strSQL = str_replace("~DATAFILE~", $strDatafile, $strSQL);
            $blnX = dbExecuteSQL($objConn, $strSQL, __FUNCTION__);
            if ($blnX == false) {$blnResult = false;}
        }
    }
    $blnX = dbExecuteSQL($objConn, "set foreign_key_checks=1;");
    if ($blnX == false) {$blnResult = false;}

    dbClose($objConn);

    return $blnResult;
}

// return the last inserted id
function dbLastInsertID($objConn_a)
{
    dbInitialise($objConn_a);
    return $objConn_a->PDO->lastInsertId();
}

// return the last inserted id
function dbLastAffected()
{
	global $g_intLastAffected;
    return $g_intLastAffected;
}

// open a database connection
// returns a database dbConnection object that contains both a readonly and readwrite connection to the same database
function dbOpen($strHostname_a, $strLogin_a, $strPassword_a, $strDatabase_a = '')
{
    $objResult = new dbConnection();
	$objResult->logFile = LOG_DEBUG_SQL_FILE;

    $strConnection = "mysql:host=" . $strHostname_a;
    if (strlen($strDatabase_a) > 0) 
	{
        $strConnection .= ";dbname=" . $strDatabase_a;
    }
    
	try 
	{
		if (toBoolean(DBSYSTEM_LOCALINFILE))
		{
			$objResult->PDO = new PDO($strConnection, $strLogin_a, $strPassword_a, array(PDO::MYSQL_ATTR_LOCAL_INFILE=>1));
		}
		else
		{
			$objResult->PDO = new PDO($strConnection, $strLogin_a, $strPassword_a);
		}
		
		if ($objResult->PDO)
		{
			$objResult->setDatabaseName($strDatabase_a);
			$objResult->PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
	}
	catch (PDOException $e) 
	{
		$objResult->PDO = null;
		$objResult->setDatabaseName("");
	}

    return $objResult;
}

// returns a recordset
function dbOpenRecordset($objConn_a, $strSQL_a, $strCaller_a = "", $blnFatal_a = true)
{
    $strError = '';
    $objResult = null;

logSQL($objConn_a->logFile, $blnFatal_a, $strCaller_a . ': ' . getSessionDB(__FUNCTION__) . ':' . $strSQL_a, $strError, 0);
    dbInitialise($objConn_a);
    dbUTF8($objConn_a);
    try
    {
        $objResult = $objConn_a->PDO->query($strSQL_a);
    } 
	catch (PDOException $e) 
	{
        $strError = $e->getMessage();
    }

    //logSQL(LOG_DEBUG_SQL_FILE, true, $strSQL_a, $strError, 0);
    logSQL($objConn_a->logFile, $blnFatal_a, $strCaller_a . ': ' . getSessionDB(__FUNCTION__) . ':' . $strSQL_a, $strError, 0);
    return $objResult;
}

// don't log anything unless there is an error
// returns a recordset
function dbOpenRecordsetNoDebug($objConn_a, $strSQL_a, $strCaller_a = "", $blnFatal_a = true)
{
    $strError = '';
    $objResult = null;

//logSQL($objConn_a->logFile, $blnFatal_a, $strCaller_a . ': ' . getSessionDB(__FUNCTION__) . ':' . $strSQL_a, $strError, 0);
    dbInitialise($objConn_a);
    dbUTF8($objConn_a);
    try
    {
        $objResult = $objConn_a->PDO->query($strSQL_a);
    } 
	catch (PDOException $e) 
	{
        $strError = $e->getMessage();
    }

    if ($strError != '') 
	{
        //logSQL(LOG_DEBUG_SQL_FILE, true, $strSQL_a, $strError, 0);
        logSQL($objConn_a->logFile, $blnFatal_a, $strCaller_a . ': ' . getSessionDB(__FUNCTION__) . ':' . $strSQL_a, $strError, 0);
    }

    return $objResult;
}

function dbPrepareSQL($objConn_a, $strSQL_a, $strCaller_a = "", $blnFatal_a = true)
{
    $strError = '';
    $objResult = null;

    dbInitialise($objConn_a);
    dbUTF8($objConn_a);
    try
    {
        $objResult = $objConn_a->PDO->prepare($strSQL_a);
    } 
	catch (PDOException $e) 
	{
        $strError = $e->getMessage();
        $g_blnExecuteError = true;
        $g_intErrorCode = $e->getCode();
        $g_strErrorDescription = $e->getMessage();
    }
	
    if ($strError != '') 
	{
        //logSQL(LOG_DEBUG_SQL_FILE, true, $strSQL_a, $strError, 0);
        logSQL($objConn_a->logFile, blnFatal_a, $strCaller_a . ': ' . getSessionDB(__FUNCTION__) . ':' . $strSQL_a, $strError, 0);
    }

	return $objResult;
}

	
// returns true if successful, false if not
function dbPurge($objConn_a)
{
    $blnResult = true;

    $blnX = dbExecuteSQL($objConn_a, "set foreign_key_checks=0;");
    if ($blnX == false) {$blnResult = false;}
	
    $arrTables = dbGetTables($objConn_a);
    foreach ($arrTables as $arrTable) 
	{
        $strTable = $arrTable['name'];

        $strSQL = "drop table ~TABLE~";
        $strSQL = str_replace("~TABLE~", $strTable, $strSQL);
        $blnX = dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
        if ($blnX == false) {$blnResult = false;}
    }
	
    $arrFunctions = dbGetFunctions($objConn_a);
    foreach ($arrFunctions as $arrFunction) 
	{
        $strFunction = $arrFunction['name'];

        $strSQL = "drop function ~FUNCTION~";
        $strSQL = str_replace("~FUNCTION~", $strFunction, $strSQL);
        $blnX = dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
        if ($blnX == false) {$blnResult = false;}
    }
	
    $arrProcedures = dbGetProcedures($objConn_a);
    foreach ($arrProcedures as $arrProcedure) 
	{
        $strProcedure = $arrProcedure['name'];

        $strSQL = "drop procedure ~PROCEDURE~";
        $strSQL = str_replace("~PROCEDURE~", $strProcedure, $strSQL);
        $blnX = dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
        if ($blnX == false) {$blnResult = false;}
    }
	
    $arrViews = dbGetViews($objConn_a);
    foreach ($arrViews as $arrView) 
	{
        $strView = $arrView['name'];

        $strSQL = "drop view ~VIEW~";
        $strSQL = str_replace("~VIEW~", $strView, $strSQL);
        $blnX = dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
        if ($blnX == false) {$blnResult = false;}
    }
	
    $blnX = dbExecuteSQL($objConn_a, "set foreign_key_checks=1;");
    if ($blnX == false) {$blnResult = false;}

    return $blnResult;
}

function dbClearErrors()
{
	global $g_blnExecuteError;
    global $g_intErrorCode;
    global $g_strErrorDescription;
	
	$g_blnExecuteError = false;
	$g_intErrorCode = 0;
	$g_strErrorDescription = "";
	dbClearCustomError();
	dbClearSuccessMessage();
}

function dbClearCustomError()
{
    global $g_strCustomError;
	
	$g_strCustomError = "";
}

function dbClearSuccessMessage()
{
    global $g_strSuccessMessage;
	
	$g_strSuccessMessage = "";
}

function dbRaiseCustomError($objConn_a, $strError_a)
{
	global $g_blnExecuteError;
    global $g_strCustomError;
	
	$g_blnExecuteError = true;
	$g_strCustomError = $strError_a;
}

function dbSetSuccessMessage($objConn_a, $strMessage_a)
{
    global $g_strSuccessMessage;
	
	$g_strSuccessMessage = $strMessage_a;
}

// reutrns a record
function dbReadRecord($objRecordset_a)
{
    //return $objRecordset_a->fetch(PDO::FETCH_ASSOC);
    return $objRecordset_a->fetch(PDO::FETCH_BOTH);
}

// returns the nominated field value retrieved by 'returnvalue' for a nominated table and ID value
function dbReadValueByID($objConn_a, $strTableName_a, $strField_a, $strID_a, $strCaller_a = "")
{
	$strResult = "";

	if (strlen($strID_a) > 0)
	{
		$strSQL = "select ~FIELD~ returnvalue from ~TABLENAME~ where id = ~ID~";
		$strSQL = str_replace('~TABLENAME~', ff($strTableName_a), $strSQL);
		$strSQL = str_replace('~FIELD~', ff($strField_a), $strSQL);
		$strSQL = str_replace('~ID~', ff($strID_a), $strSQL);
		$strResult = dbReadValue($objConn_a, $strSQL, $strCaller_a);
	}
	
	return $strResult;
}

// returns the value retrieved by 'returnvalue'
function dbReadValue($objConn_a, $strSQL_a, $strCaller_a = "")
{
    $strResult = "";

logDebug($strCaller_a . ': ' . getSessionDB(__FUNCTION__) . ':' . 'SQLHERE: ' . $strSQL_a, '');
	$objStatement = $objConn_a->PDO->prepare($strSQL_a);
	$objStatement->execute();
	$strResult = $objStatement->fetchColumn();
	$objStatement->closeCursor();
	
	$strResult .= "";

    return $strResult;
}

// returns the value retrieved by 'returnvalue' but without logging any debug info
function dbReadValueNoDebug($objConn_a, $strSQL_a, $strCaller_a = "")
{
    $strResult = "";

    //dbUTF8($objConn_a);
    $objResult = dbOpenRecordsetNoDebug($objConn_a, $strSQL_a, $strCaller_a);
    if ($arrRow = dbReadRecord($objResult)) 
	{
		if (array_key_exists('returnvalue', $arrRow))
		{
			$strResult = $arrRow['returnvalue'];
		} 
		else
		{
			logDebug("NO RETURNVALUE ERROR: " . $strSQL_a .  " IN: " . $strCaller_a, "");
		}
    }
    dbCloseRecordset($objResult);

    return $strResult;
}

// check if the db is used and if not set it up
function dbInitialise($objConn_a)
{
    if (!$objConn_a->isUsed()) 
	{
        $objConn_a->setUsed();
        $blnX = dbExecuteSQLNoDebug($objConn_a, "set sql_mode='NO_AUTO_VALUE_ON_ZERO';");
    }
}

// set db to utf8
function dbUTF8($objConn_a)
{
    $objConn_a->PDO->query("set names 'utf8'");
}

// format a field (escape it)
function ff($strField_a)
{
    $strResult = $strField_a;

    $strResult = trim(str_replace("\\", "\\\\", $strResult));
    $strResult = trim(str_replace("'", "''", $strResult));
	//$strResult = mysql_real_escape_string($strResult);

    return $strResult;
}

function ffstripchars($strField_a)
{
    $strResult = $strField_a;

    $strResult = trim(str_replace("\\", "", $strResult));
    $strResult = trim(str_replace("'", "", $strResult));
	$strResult = trim(str_replace(" ", "", $strResult));
	$strResult = trim(str_replace("(", "", $strResult));
	$strResult = trim(str_replace(")", "", $strResult));
	$strResult = trim(str_replace("-", "_", $strResult));

    return $strResult;
}

// format an entity (escape it)
function ffel($strField_a)
{
    $strResult = ffstripchars($strField_a);
	$strResult = strtolower($strResult);

    return $strResult;
}

// format an entity for permissions (escape it)
function ffeu($strField_a)
{
    $strResult = ffstripchars($strField_a);
	$strResult = strtoupper($strResult);

    return $strResult;
}

// format a field allowing nulls (escape it)
function ffn($strField_a)
{
    $strResult = 'null';

    if (isset($strField_a) && (strlen(trim($strField_a)) > 0)) 
	{
        $strResult = ff($strField_a);
    }

    return $strResult;
}

function num_replace($strFind_a, $strReplace_a, $str_a)
{
	$strReplace = $strReplace_a;

	if ($strReplace != "null")
	{
		$strReplace = preg_replace("/[^0-9,.]/", "", $strReplace);
	}

	return str_replace($strFind_a, $strReplace, $str_a);
}

// fetch a system setting
function systemSettingGet($objConn_a, $strKey_a)
{
    $strSQL = "select value returnvalue from ~TABLENAMESYSTEM~ where code = '~code~' limit 1";
	$strSQL = str_replace("~TABLENAMESYSTEM~", CORE_SYSTEM, $strSQL);
    $strSQL = str_replace('~code~', ff($strKey_a), $strSQL);
    return dbReadValue($objConn_a, $strSQL, __FUNCTION__);
}

// fetch a system setting with no debug info
function systemSettingGetNoDebug($objConn_a, $strKey_a)
{
    $strSQL = "select value returnvalue from ~TABLENAMESYSTEM~ where code = '~code~' limit 1";
	$strSQL = str_replace("~TABLENAMESYSTEM~", CORE_SYSTEM, $strSQL);
    $strSQL = str_replace('~code~', ff($strKey_a), $strSQL);
    return dbReadValueNoDebug($objConn_a, $strSQL, __FUNCTION__);
}

// update a system setting
// returns true if successful, false if not
function systemSettingPut($objConn_a, $strKey_a, $strValue_a)
{
    $strSQL = "update ~TABLENAMESYSTEM~ set value = '~value~' where code = '~code~'";
	$strSQL = str_replace("~TABLENAMESYSTEM~", CORE_SYSTEM, $strSQL);
    $strSQL = str_replace('~code~', ff($strKey_a), $strSQL);
    $strSQL = str_replace('~value~', ff($strValue_a), $strSQL);
    return dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
}
