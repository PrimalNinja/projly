<?php

function elementArray($arr_a, $str_a, $varDefault_a)
{
	$varResult = $varDefault_a;
	
	if (array_key_exists($str_a, $arr_a))
	{
		if (count($arr_a[$str_a]) > 0)
		{
			$varResult = $arr_a[$str_a];
		}
	}

	return $varResult;
}

function elementString($arr_a, $str_a, $varDefault_a)
{
	$varResult = $varDefault_a;
	
	if (array_key_exists($str_a, $arr_a))
	{
		if (strlen($arr_a[$str_a]) > 0)
		{
			$varResult = $arr_a[$str_a];
		}
	}

	return $varResult;
}

function getSessionDB()
{
	$strResult = "(unspecified)";

	return $strResult;
}

function getSystemClientID($objConn_a)
{
	$strTableNameClient = getTableNameEntity("client", false);
	//$strTableNameClient = "d_client";

	$strSQL = "select id returnvalue from ~TABLENAMECLIENT~ where code = '~CLIENTCODE~'";
	$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
	$strSQL = str_replace('~CLIENTCODE~', SYSTEM_CLIENT, $strSQL);
	return dbReadValue($objConn_a, $strSQL, __FUNCTION__);
}

// Helper method to get a string description for an HTTP status code
// From http://www.gen-x-design.com/archives/create-a-rest-api-with-php/ 
function getStatusCodeMessage($intStatus_a)
{
    // these could be stored in a .ini file and loaded
    // via parse_ini_file()... however, this will suffice
    // for an example
    $arrCodes = Array(
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
        505 => 'HTTP Version Not Supported'
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

function safetyDie($strReason_a)
{
    logDebug($strReason_a, '');
    die();
}

function sendJSONResponse($strJSON_a)
{
	sendResponse(200, $strJSON_a, 'application/json');
}

function sendResponse($intStatus_a = 200, $strBody_a = '', $strContentType_a = 'text/html')
{
    $strHeader = 'HTTP/1.1 ' . $intStatus_a . ' ' . getStatusCodeMessage($intStatus_a);
    header($strHeader);
    header('Content-type: ' . $strContentType_a);
    echo $strBody_a;
}

function strLeft($str_a, $intLength_a, $strAppend_a)
{
    $strResult = '';

    if ($intLength_a >= strlen($str_a)) {
        $strResult = $str_a;
    } else {
        $strResult = substr($str_a, 0, $intLength_a) . $strAppend_a;
    }

    return $strResult;
}

function strRight($str_a, $intLength_a)
{
    $strResult = '';

    if ($intLength_a >= strlen($str_a)) {
        $strResult = $str_a;
    } else {
        $strResult = substr($str_a, strlen($str_a) - $intLength_a, $intLength_a);
    }

    return $strResult;
}

// convert a string to a boolean value
function toBoolean($str_a)
{
    $blnResult = false;

    if (($str_a == '1') || ($str_a == 'Y') || ($str_a == 'YES') || ($str_a == 'T') || ($str_a == 'TRUE') || ($str_a == 'y') || ($str_a == 'yes') || ($str_a == 't') || ($str_a == 'true')) {
        $blnResult = true;
    }

    return $blnResult;
}

function updateStatus($strStatus_a, $strOptionCode_a, $strTaskSet_a, $strTaskSetDescription_a, $intTaskSet_a, $intTaskSets_a, $strTaskDescription_a, $intTask_a, $intTasks_a, $intStartProcess_a, $strAlert_a, $arrResult_a)
{
	$intCurrentTime = time();
	$intProcessTimeframe = ($intCurrentTime - $intStartProcess_a);
	
	$arrStatus[] = array(
		"statusmsg" => $strStatus_a,
		"optioncode" => $strOptionCode_a,
		"tasksetname" => $strTaskSet_a,
		"tasksetdescription" => $strTaskSetDescription_a,
		"taskset" => $intTaskSet_a,
		"tasksets" => $intTaskSets_a,
		"taskdescription" => $strTaskDescription_a,
		"task" => $intTask_a,
		"tasks" => $intTasks_a,
		"processtime" => $intProcessTimeframe,
		"alertmsg" => $strAlert_a,
		"result" => $arrResult_a
	);
	$strStatus = json_encode($arrStatus);
	saveFile('status.json', $strStatus);
	return $strStatus;
}

?>
