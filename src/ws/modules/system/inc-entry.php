<?php

$arrFunctions = array(
    "system_processcommand" => array("dependencies" => 'system/actionProcessCommand', "function" => 'actionProcessCommand')
);

$arrFunction = validateFunction($arrFunctions, $strFunction);
if ($arrFunction !== null) {
    $strResult = dispatchFunction($this->m_objConn, $strSecurityToken, $strDataID, $arrFunction, $arrParameters);
    $blnDispatched = true;
}
