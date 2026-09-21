<?php

// copies an existing file from a specified location into the cluster
// cluster handling (the cluster key should be a base filename for which multiple files may share, eg: key: <filenamebase>, filename: <filenamebase>-barcode.jpg
function documentClusterCopy($strKey_a, $strFilename_a, $strSourcePath_a, $strDocumentRoot_a)
{	
    $strFilePath = getClusterPath($strDocumentRoot_a, $strKey_a, false, true) . $strFilename_a;

	logRepository('copy from: ' . $strSourcePath_a . " to: " .  $strFilePath);
    copy($strSourcePath_a, $strFilePath);
	return filesize($strFilePath);
}
