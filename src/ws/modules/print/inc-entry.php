<?php

$arrFunctions = array(
	"print_fetchpage" => array("dependencies" => 'print/actionFetchPage', "function" => 'actionFetchPage'),
	"print_printjobcompleted" => array("dependencies" => 'print/actionPrintPrintJobCompleted', "function" => 'actionPrintPrintJobCompleted'),
	"print_printjobfetchnext" => array("dependencies" => 'print/actionPrintJobFetchNext', "function" => 'actionPrintJobFetchNext'),
	"print_printstuff" => array("dependencies" => 'print/actionPrintPrintStuff', "function" => 'actionPrintPrintStuff')
);

$arrFunction = validateFunction($arrFunctions, $strFunction);
if ($arrFunction !== null) {
    $strResult = dispatchFunction($this->m_objConn, $strSecurityToken, $strDataID, $arrFunction, $arrParameters);
    $blnDispatched = true;
}
