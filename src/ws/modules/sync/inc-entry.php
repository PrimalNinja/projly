<?php

$arrFunctions = array(
	"Xsync_downloadformmessages" => array("dependencies" => 'sync/actionDownloadFormMessages', "function" => 'actionDownloadFormMessages'),
	"Xsync_downloadpublishedforms" => array("dependencies" => 'sync/actionDownloadPublishedForms', "function" => 'actionDownloadPublishedForms'),
	"Xsync_uploadtimesheet" => array("dependencies" => 'sync/actionUploadTimesheet', "function" => 'actionUploadTimesheet'),
	"Xsync_uploadworkingform" => array("dependencies" => 'sync/actionUploadWorkingForm', "function" => 'actionUploadWorkingForm')
);

$arrFunction = validateFunction($arrFunctions, $strFunction);
if ($arrFunction !== null) {
    $strResult = dispatchFunction($this->m_objConn, $strSecurityToken, $strDataID, $arrFunction, $arrParameters);
    $blnDispatched = true;
}
