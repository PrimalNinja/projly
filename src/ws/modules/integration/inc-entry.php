<?php

$arrFunctions = array(
	"integration_inbound" => array("dependencies" => 'integration/actionIntegrationInbound', "function" => 'actionIntegrationInbound'),
    "integration_outbound" => array("dependencies" => 'integration/actionIntegrationOutbound', "function" => 'actionIntegrationOutbound'),    
    "integration_taskcreate" => array("dependencies" => 'integration/actionIntegrationTaskCreate', "function" => 'actionIntegrationTaskCreate')    
);

$arrFunction = validateFunction($arrFunctions, $strFunction);

if ($arrFunction !== null) {
    $strResult = dispatchFunction($this->m_objConn, $strSecurityToken, $strDataID, $arrFunction, $arrParameters);
    $blnDispatched = true;
}