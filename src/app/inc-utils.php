<?php

	// end a block of javascript
	function endJavaScript()
	{
		echo('</script>');
	}

	// go to a URL
	function gotoURL($strURL_a)
	{
		//header('Location: ' . $strURL_a);
		startJavaScript();
		echo ("self.location='" . $strURL_a . "';");
		endJavaScript();
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

	// pass a value from PHP to JavaScript
    function passPHPValue($strIdentifier_a, $strValue_a)
    {
    	echo('var ' . $strIdentifier_a . ' = "' . $strValue_a . '";');
    }

	// upon receiving a JavaScript value back that was secured, unsecure it
    function revertSecuredValue($strSecuredValue_a)
    {
    	$strResult = "";
    	
    	$arrGUIDs = $_SESSION[SESSION_SECURITY];
    	$strKey = array_search($strSecuredValue_a, $arrGUIDs);
    	if (strlen($strKey) > 0)
    	{
    		$arrElements = explode(',', $strKey);
    		$strResult = $arrElements[1];
    	}
    	else
    	{
    		die();
    	}
    	
    	return $strResult;
    }

	// secure a PHP value and pass it to JavaScript
    function secureValue($strIdentifier_a, $strType_a, $strValue_a)
    {
    	$strKey = $strType_a . ',' . $strValue_a;

    	$arrGUIDs = $_SESSION[SESSION_SECURITY];
    	if (array_key_exists($strKey, $arrGUIDs))
    	{
    		// found key
    		$strResult = $arrGUIDs[$strKey];
    	}
    	else
    	{
    		// allocate key and add it
    		$strResult = getGUID();
    		$arrGUIDs[$strKey] = $strResult;
    		$_SESSION[SESSION_SECURITY] = $arrGUIDs;
    	}
    	//$_SESSION[SESSION_SECURITY] = array(); // clear array
    	
    	echo('var ' . $strIdentifier_a . ' = "' . $strResult . '";');
    }

	// start a block of javascript
	function startJavaScript()
	{
		echo('<script type="text/javascript">');
	}

	// create a guid
	function getGUID($strPrefix_a = '')
	{
		$strPrefix = $strPrefix_a;
		if ($strPrefix == '' || is_null($strPrefix)) 
		{ 
			$strPrefix = ''; 
		}

		$strTemplate = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx';
		$strGuid = '';
		
		for ($intI = 0; $intI < strlen($strTemplate); $intI++) 
		{
			$strChar = $strTemplate[$intI];
			if ($strChar == 'x' || $strChar == 'y') 
			{
				$intR = mt_rand(0, 15);
				$intV = ($strChar == 'x') ? $intR : (($intR & 0x3) | 0x8);
				$strGuid .= dechex($intV);
			} 
			else 
			{
				$strGuid .= $strChar;
			}
		}
		
		return $strPrefix . $strGuid;
	}

?>