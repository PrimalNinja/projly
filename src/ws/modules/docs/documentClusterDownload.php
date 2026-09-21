<?php

// copies an existing file from a specified location into the cluster
// cluster handling (the cluster key should be a base filename for which multiple files may share, eg: key: <filenamebase>, filename: <filenamebase>-barcode.jpg
function documentClusterDownload($strKey_a, $strFilename_a, $strSaveAsName_a, $strContentType_a, $blnZip_a, $blnForceDownload_a, $strDocumentRoot_a)
{		
    $strFilePath = getClusterPath($strDocumentRoot_a, $strKey_a, false, true) . $strFilename_a;
    sendResponseFile($strSaveAsName_a, $strFilePath, $strContentType_a, $blnZip_a, $blnForceDownload_a);
}
