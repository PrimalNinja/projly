<?php

$arrFunctions = array(
	"projly_fetchcharts" => array("dependencies" => 'projly/actionProjlyFetchCharts', "function" => 'actionProjlyFetchCharts')
);

$arrFunction = validateFunction($arrFunctions, $strFunction);
if ($arrFunction !== null) {
    $strResult = dispatchFunction($this->m_objConn, $strSecurityToken, $strDataID, $arrFunction, $arrParameters);
    $blnDispatched = true;
}