<?php

$arrFunctions = array(
    "esb_broadcast" => array("dependencies" => 'esb/actionESBBroadcast', "function" => 'actionESBBroadcast'),
    "esb_check" => array("dependencies" => 'esb/actionESBCheck', "function" => 'actionESBCheck'),
    "esb_listen" => array("dependencies" => 'esb/actionESBListen', "function" => 'actionESBListen'),
    "esb_registerbroadcaster" => array("dependencies" => 'esb/actionESBRegisterBroadcaster', "function" => 'actionESBRegisterBroadcaster'),
    "esb_registerlistener" => array("dependencies" => 'esb/actionESBRegisterListener', "function" => 'actionESBRegisterListener')
);

$arrFunction = validateFunction($arrFunctions, $strFunction);
if ($arrFunction !== null) {
    $strResult = dispatchFunction($this->m_objConn, $strSecurityToken, $strDataID, $arrFunction, $arrParameters);
    $blnDispatched = true;
}
