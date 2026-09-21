<?php

// function summary:

// addToArrayIfPresent(&$arr_a, $strKey_a, $var_a, $blnIsOptional_a = false) 
// elementArray($arr_a, $str_a, $varDefault_a)
// elementString($arr_a, $str_a, $varDefault_a)
// findPostcode($objConn_a, $strSuburb_a, $strState_a, $strCountry_a)
// fixBoolean($str_a)
// getFirstNonEmptyValue($arr_a) 
// getParameterIDList($arrParameters_a, $blnForceList_a)
// getStatusCodeMessage($intStatus_a)
// InStr($strHaystack_a, $strNeedle_a) 
// InStrNext($strHaystack_a, $strNeedle_a, $intStart_a) 
// strLeft($str_a, $intLength_a, $strAppend_a)
// strNormalize($str_a)
// strPad($str_a, $intLength_a, $strChar_a, $intDirection_a = STR_PAD_RIGHT) 
// strRight($str_a, $intLength_a)
// toBoolean($str_a)
// validateSuburbStatePostcodeCombination($objConn_a, $strSuburb_a, $strState_a, $strPostcode_a)

function addToArrayIfPresent(&$arr_a, $strKey_a, $var_a, $blnIsOptional_a = false) 
{
    $blnIsEmpty = (is_array($var_a) && empty($var_a)) || strlen($var_a) === 0 || $var_a === null;

    if (!$blnIsOptional_a || !$blnIsEmpty) 
	{
		if (strlen($strKey_a) === 0) 
		{
            $arr_a[] = $var_a;
        } 
		else 
		{
            $arr_a[$strKey_a] = $var_a;
        }
    }
}

function elementArray($arr_a, $str_a, $varDefault_a)
{
    $varResult = $varDefault_a;

	if ($arr_a != null)
	{
		if (array_key_exists($str_a, $arr_a)) 
		{
			if (count($arr_a[$str_a]) > 0) 
			{
				$varResult = $arr_a[$str_a];
			}
		}
	}

    return $varResult;
}

function elementString($arr_a, $str_a, $varDefault_a)
{
    $varResult = $varDefault_a;

	if ($arr_a != null)
	{
		if (array_key_exists($str_a, $arr_a)) 
		{
			if (strlen($arr_a[$str_a]) > 0) 
			{
				$varResult = $arr_a[$str_a];
			}
		}
	}

    return $varResult;
}

function findPostcode($objConn_a, $strSuburb_a, $strState_a, $strCountry_a)
{
	$strResult = "";
	$strTableNameSuburb = getTableNameEntity("suburb", false);
		
	// note: suburbs we don't care about clientid as it always comes from the system area
	$strSQL = "select count(*) returnvalue from ~TABLENAMESUBURB~ where suburb = '~SUBURB~' and state = '~STATE~' and country = '~COUNTRY~'";
	$strSQL = str_replace("~TABLENAMESUBURB~", ff($strTableNameSuburb), $strSQL);
	$strSQL = str_replace("~SUBURB~", ff($strSuburb_a), $strSQL);
	$strSQL = str_replace("~STATE~", ff($strState_a), $strSQL);
	$strSQL = str_replace("~COUNTRY~", ff($strCountry_a), $strSQL);
	$intCount = intval(dbReadValue($objConn_a, $strSQL, __FUNCTION__), 10);
	
	if ($intCount == 1)
	{
		$strSQL = "select postcode returnvalue from ~TABLENAMESUBURB~ where suburb = '~SUBURB~' and state = '~STATE~' and country = '~COUNTRY~'";
		$strSQL = str_replace("~TABLENAMESUBURB~", ff($strTableNameSuburb), $strSQL);
		$strSQL = str_replace("~SUBURB~", ff($strSuburb_a), $strSQL);
		$strSQL = str_replace("~STATE~", ff($strState_a), $strSQL);
		$strSQL = str_replace("~COUNTRY~", ff($strCountry_a), $strSQL);
		$strResult = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
	}
	
	return $strResult;
}

// fix a boolean value
function fixBoolean($str_a)
{
    $strResult = 'N';

    if (toBoolean($str_a)) 
	{
        $strResult = 'Y';
    }

    return $strResult;
}

function getFirstNonEmptyValue($arr_a) 
{
	$strResult = "";
	
    foreach ($arr_a as $str) 
	{
        if (!empty($str) && (strlen($strResult) == 0))
		{
            $strResult = $str;
        }
    }
	
    return $strResult;
}

// takes in a set of parameters and tries to work out an ID List from either 'idlist' (an array of IDs) or 'id' (a singular ID), 
// but entitydataid and formentitydataid are also supported for singular
function getParameterIDList($arrParameters_a, $blnForceList_a)
{
    $strResult = "";

    $strID = getJSONParameter($arrParameters_a, 'id');
	$strEntityDataID = getJSONParameter($arrParameters_a, 'entitydataid');
	$strFormEntityDataID = getJSONParameter($arrParameters_a, 'formentitydataid');
	$arrIDList = getJSONParameter($arrParameters_a, 'idlist');
	
	// take the ID values in priority order
	if (strlen($strID) == 0)
	{
		$strID = $strEntityDataID;
		if (strlen($strID) == 0)
		{
			$strID = $strFormEntityDataID;
		}
	}
	
	// take the ID in preference to the IDList, unless we want to force the list
    if ((strlen($strID) > 0) && ($blnForceList_a == false)) 
	{
        // create a list of one
        $strResult = revertSecuredValue($strID, 'id', true);
    } 
	else 
	{
        // if singluar not found, try multiple
		if ((count($arrIDList) == 0) && (strlen($strID) > 0))
		{
			// create a list of one
			$strResult = revertSecuredValue($strID, 'id', true);
		}
		else
		{
			foreach ($arrIDList as $varID) 
			{
				$strID = "";

				if (gettype($varID) == "array") 
				{
					$strID = $varID['id'];
				} 
				else 
				{
					$strID = $varID;
				}

				if (strlen($strResult) > 0) 
				{
					$strResult .= ",";
				}

				$strResult .= revertSecuredValue($strID, 'id', true);
			}
		}
    }

    return $strResult;
}

// Helper method to get a string description for an HTTP status code
// From http://www.gen-x-design.com/archives/create-a-rest-api-with-php/
function getStatusCodeMessage($intStatus_a)
{
    // these could be stored in a .ini file and loaded
    // via parse_ini_file()... however, this will suffice
    // for an example
    $arrCodes = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => '(Unused)',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
    );

    return (isset($arrCodes[$intStatus_a])) ? $arrCodes[$intStatus_a] : '';
}

// return the index of a string within another, deals with the stupid strpos behaviour of PHP where it has a return value of 0 which means both found and not found at the same time
function InStr($strHaystack_a, $strNeedle_a) 
{ 
	$strResult = strpos($strHaystack_a, $strNeedle_a); 
	if ($strResult !== false) 
	{ 
		return $strResult; 
	} 
	else 
	{ 
		return -1; 
	} 
} 

function InStrNext($strHaystack_a, $strNeedle_a, $intStart_a) 
{ 
	$strResult = strpos($strHaystack_a, $strNeedle_a, $intStart_a); 
	if ($strResult !== false) 
	{ 
		return $strResult; 
	} 
	else 
	{ 
		return -1; 
	} 
} 

function strLeft($str_a, $intLength_a, $strAppend_a)
{
    $strResult = '';

    if ($intLength_a >= strlen($str_a)) 
	{
        $strResult = $str_a;
    } 
	else 
	{
        $strResult = substr($str_a, 0, $intLength_a) . $strAppend_a;
    }

    return $strResult;
}

function strNormalize($str_a)
{
    // Normalize line endings using Global
    // Convert all line-endings to UNIX format
    $str_a = str_replace(CRLF, LF, $str_a);
    $str_a = str_replace(TAB, ' ', $str_a);
    $str_a = str_replace(CR, LF, $str_a);
    // Don't allow out-of-control blank lines
    $str_a = preg_replace("/\n{2,}/", LF . LF, $str_a);
    return $str_a;
}

// Add space padding
function strPad($str_a, $intLength_a, $strChar_a, $intDirection_a = STR_PAD_RIGHT) 
{
    $intCurrentLength = strlen($str_a);
	$strResult = $str_a;
    
    if ($intCurrentLength > $intLength_a) 
	{
        $strResult = substr($strResult, 0, $intLength_a); // Truncate string
    } 
	else if ($intCurrentLength < $intLength_a) 
	{
        $strResult = str_pad($strResult, $intLength_a, $strChar_a, $intDirection_a); // Pad with spaces
    }
    
    return $strResult; // Return as is if length matches
}

function strRight($str_a, $intLength_a)
{
    $strResult = '';

    if ($intLength_a >= strlen($str_a)) 
	{
        $strResult = $str_a;
    } 
	else 
	{
        $strResult = substr($str_a, strlen($str_a) - $intLength_a, $intLength_a);
    }

    return $strResult;
}

// convert a string to a boolean value
function toBoolean($str_a)
{
    $blnResult = false;

    if (($str_a == '1') || ($str_a == 'Y') || ($str_a == 'YES') || ($str_a == 'T') || ($str_a == 'TRUE') || ($str_a == 'y') || ($str_a == 'yes') || ($str_a == 't') || ($str_a == 'true') || $str_a === true)
	{
        $blnResult = true;
    }

    return $blnResult;
}

// validate suburb, postcode combination
function validateSuburbStatePostcodeCombination($objConn_a, $strSuburb_a, $strState_a, $strPostcode_a)
{
	$blnResult = true;
	$strTableNameSuburb = getTableNameEntity("suburb", false);
		
	// note: suburbs we don't care about clientid as it always comes from the system area
	$strSQL = "select count(*) returnvalue from ~TABLENAMESUBURB~ where suburb = '~SUBURB~' and state = '~STATE~' and postcode = '~POSTCODE~'";
	$strSQL = str_replace("~TABLENAMESUBURB~", ff($strTableNameSuburb), $strSQL);
	$strSQL = str_replace("~SUBURB~", ff($strSuburb_a), $strSQL);
	$strSQL = str_replace("~STATE~", ff($strState_a), $strSQL);
	$strSQL = str_replace("~POSTCODE~", ff($strPostcode_a), $strSQL);
	$intCount = intval(dbReadValue($objConn_a, $strSQL, __FUNCTION__), 10);
	
	if ($intCount == 0)
	{
		$blnResult = false;
	}
	
	return $blnResult;
}

