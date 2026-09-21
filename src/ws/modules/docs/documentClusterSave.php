<?php

// saves the variable objData_a as a file into the cluster
// cluster handling (the cluster key should be a base filename for which multiple files may share, eg: key: <filenamebase>, filename: <filenamebase>-barcode.jpg
function documentClusterSave($strKey_a, $strFilename_a, $objData_a, $strDocumentRoot_a)
{
    $strFilePath = getClusterPath($strDocumentRoot_a, $strKey_a, false, true) . $strFilename_a;
    saveFile($strFilePath, $objData_a);
	return filesize($strFilePath);
}
