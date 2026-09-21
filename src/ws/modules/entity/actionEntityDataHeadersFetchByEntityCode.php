<?php

function actionEntityDataHeadersFetchByEntityCode($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a) { 
    
    $arrResult = array();

    $strEntityCode = getJSONParameter($arrParameters_a, 'entitycode');
    $strFormEntityCode = getJSONParameter($arrParameters_a, 'formentitycode');
	
	// permission check
	if (!hasPermission($objConn_a, 'VW_' . ffeu($strEntityCode), __FUNCTION__, true)) {return false;}
 
	$strHeadersCallFunc = "";
    if (dependencies('entity/headers/' . ffel($strEntityCode), true)) 
	{ 
        $strHeadersCallFunc = 'getHeader_' . ffel($strEntityCode);
    }
    else if (dependencies('entity/headers/default')) 
	{ 
        $strHeadersCallFunc = 'getHeader_default';
    }
    
	$strFlagsCallFunc = "";
    if (dependencies('entity/flags/' . ffel($strEntityCode), true)) 
	{ 
        $strFlagsCallFunc = 'getFlags_' . ffel($strEntityCode);
    }
    else if (dependencies('entity/flags/default')) 
	{ 
        $strFlagsCallFunc = 'getFlags_default';
    }

    $arrFields = $strHeadersCallFunc($objConn_a);
	$strFlags = $strFlagsCallFunc($objConn_a, $strEntityCode, $strFormEntityCode);
        
    $arrResult = array(
        'fields' => $arrFields,
        'flags'  => $strFlags
    );
    
    return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
}
