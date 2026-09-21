<?php

$arrFunctions = array(
    "report_generatereporttopdf" => array("dependencies" => 'report/actionGenerateReportToPDF', "function" => 'actionGenerateReportToPDF'),
);

$arrFunction = validateFunction($arrFunctions, $strFunction);
if ($arrFunction !== null) {
    $strResult = dispatchFunction($this->m_objConn, $strSecurityToken, $strDataID, $arrFunction, $arrParameters);
    $blnDispatched = true;
}
