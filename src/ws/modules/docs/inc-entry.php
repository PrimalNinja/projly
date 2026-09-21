<?php

$arrFunctions = array(
    "docs_documentdownload" => array("dependencies" => 'docs/actionDocsDocumentDownload', "function" => 'actionDocsDocumentDownload'),
    "docs_fetchimage" => array("dependencies" => 'docs/actionDocsFetchImage', "function" => 'actionDocsFetchImage'),
    "docs_imagethumbsfetchnext" => array("dependencies" => 'docs/actionDocsImageThumbsFetchNext', "function" => 'actionDocsImageThumbsFetchNext')
);

$arrFunction = validateFunction($arrFunctions, $strFunction);
if ($arrFunction !== null) {
    $strResult = dispatchFunction($this->m_objConn, $strSecurityToken, $strDataID, $arrFunction, $arrParameters);
    $blnDispatched = true;
}
