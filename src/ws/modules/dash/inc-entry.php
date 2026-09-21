<?php

$arrFunctions = array(
	"dash_fetchcharts" => array("dependencies" => 'dash/actionDashFetchCharts', "function" => 'actionDashFetchCharts')
);

$arrFunction = validateFunction($arrFunctions, $strFunction);
if ($arrFunction !== null) {
    $strResult = dispatchFunction($this->m_objConn, $strSecurityToken, $strDataID, $arrFunction, $arrParameters);
    $blnDispatched = true;
}