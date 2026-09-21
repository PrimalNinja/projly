<?php

// function summary:

// createJSONResponse($strDataID_a, $strResponseCode_a, $strMessage_a, $arrResponse_a)
// encodeTagIDs($arr_a)
// getJSONFilterParameter(&$arrFilter_a, $strName_a, $blnRemoveFilter_a)
// getJSONParameter($arrParameters_a, $strName_a)
// sendJSONResponse($strJSON_a)

// format a json response with required OS header info
function createJSONResponse($strDataID_a, $strResponseCode_a, $strMessage_a, $arrResponse_a)
{
    $arrResult = array(
        "AWAFOS" => array(
            "dataid" => $strDataID_a,
            "responsecode" => $strResponseCode_a,
            "message" => $strMessage_a,
            "response" => $arrResponse_a,
        ),
    );

    $strResult = json_encode($arrResult);
    //logDebug($strResult, '');

    return $strResult;
}

function encodeTagIDs($arr_a)
{
	$arrResult = $arr_a;

	if ($arrResult["tagtype"] == "batchjobid")
	{
		$arrResult["tag"] = secureEntityValue('BATCHJOB', $arrResult["tag"]);
	}
	else if ($arrResult["tagtype"] == "documentid")
	{
		$arrResult["tag"] = secureEntityValue('DOCUMENT', $arrResult["tag"]);
	}
	
	return $arrResult;
}

// read a json filter paramater and return the value
// $arrFilter_a is passed by reference, if blnRemoveFilter is true. the field will be remove from $arrFilter_a
// return value - mixed. either string or array
function getJSONFilterParameter(&$arrFilter_a, $strName_a, $blnRemoveFilter_a)
{
    $strResult = '';
    
	foreach ($arrFilter_a as $intI => $arrFilter)
	{
		if ($arrFilter_a[$intI]['field'] == $strName_a)
		{
		   $strResult =  $arrFilter_a[$intI]['value'];
		   
		   if ($blnRemoveFilter_a)
		   {
				unset($arrFilter_a[$intI]);
		   }
		   
		   break;
		}                       
	}
    
    return $strResult;
}

// read a json parameter
function getJSONParameter($arrParameters_a, $strName_a)
{
    $strResult = '';

    foreach ($arrParameters_a as $arrElement) 
	{
        $strParameterName = $arrElement['name'];
		$strParameterValue = '';
		if (array_key_exists('value', $arrElement))
		{
			$strParameterValue = $arrElement['value'];
		}

        switch ($strParameterName) 
		{
            case $strName_a:
				$strResult = $strParameterValue;
                break;
        }
    }

    return $strResult;
}

// format a json response with required OS header info
function sendJSONResponse($strJSON_a)
{
    sendResponse(200, $strJSON_a, 'application/json');
}

