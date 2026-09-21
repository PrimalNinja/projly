<?php

// load a document from the cluster returning the actual file content
// cluster handling (the cluster key should be a base filename for which multiple files may share, eg: key: <filenamebase>, filename: <filenamebase>-barcode.jpg
function documentClusterLoad($strKey_a, $strFilename_a, $strDocumentRoot_a)
{
    $strFilePath = getClusterPath($strDocumentRoot_a, $strKey_a, false, true) . $strFilename_a;
    return loadFile($strFilePath);
}
