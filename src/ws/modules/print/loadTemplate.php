<?php

// load a template, the filename is the exact filename of the template we wish to load eg: <filenamebase>.htm
function loadTemplate($strDocumentTypeCode_a, $strPrinterTypeCode_a, $strFilename_a)
{
    $strDocumentTypeDir = '';
    $strPrinterTypeDir = $strPrinterTypeCode_a;

    // work out the document type subdirectory
	if ($strDocumentTypeCode_a == 'R') 
	{
        $strDocumentTypeDir = 'receipts';
    }

	$strPATH_CURRENTREPOSITORY_TEMPLATES = '';
	
	if (ENABLE_CLIENTDATABASES == 'TRUE')
	{
		if (getSessionDB(__FUNCTION__) == "client")
		{
			$strPATH_CURRENTREPOSITORY_TEMPLATES = PATH_CLIENTREPOSITORY_TEMPLATES;
		}
		else if (getSessionDB(__FUNCTION__) == "system")
		{
			$strPATH_CURRENTREPOSITORY_TEMPLATES = PATH_SYSTEMREPOSITORY_TEMPLATES;
		}
	}
	else
	{
		$strPATH_CURRENTREPOSITORY_TEMPLATES = PATH_SYSTEMREPOSITORY_TEMPLATES;
	}
	
    $strFilePath = $strPATH_CURRENTREPOSITORY_TEMPLATES . strtolower($strDocumentTypeDir) . '/' . strtolower($strPrinterTypeDir) . '/' . $strFilename_a;
    return loadFile($strFilePath);
}
