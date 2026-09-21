<?php

// check if a document from the cluster exists
function documentClusterExists($strKey_a, $strFilename_a, $strDocumentRoot_a)
{
    $strFilePath = getClusterPath($strDocumentRoot_a, $strKey_a, false, true) . $strFilename_a;
    return file_exists($strFilePath);
}
