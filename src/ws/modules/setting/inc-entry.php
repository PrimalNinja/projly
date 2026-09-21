<?php

$arrFunctions = array(
);

$arrFunction = validateFunction($arrFunctions, $strFunction);
if ($arrFunction !== null) 
{
    $strResult = dispatchFunction($this->m_objConn, $strSecurityToken, $strDataID, $arrFunction, $arrParameters);
    $blnDispatched = true;
}
