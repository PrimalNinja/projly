<?php

// returns an error if there is one, or an empty string if no error
function processGenerateFormReport($objConn_a, $strDocumentID_a, $strDescription_a, $strPrinterType_a, $strFilenameBase_a, $arrJSONData_a)
{
    $strError = "";
	$strPath = "";
    $strResult = STAT_READY;

    if (dependencies('docs/documentCommit,docs/documentComponentAdd,docs/documentOpen,print/loadTemplate')) {

        $strHTML = $arrJSONData_a["data"];
		$strHTML = str_replace('class="form-control fb-map-btn"', 'style="display: none;" class="form-control fb-map-btn"', $strHTML);
		$strHTML = str_replace('panel-collapse collapse in', 'panel ', $strHTML);
		$strHTML = str_replace('panel-collapse collapse', 'panel ', $strHTML);
		$strHTML = str_replace('src="', 'src="' . URL_APP_PATH, $strHTML);
		$strHTML = str_replace('class="form-control fb-imagepicker-btn"', 'style="display: none;" class="form-control fb-imagepicker-btn"', $strHTML);
		$strHTML = str_replace('class="form-control fb-imagegallery-btn"', 'style="display: none;" class="form-control fb-imagegallery-btn"', $strHTML);
		$strGPS = str_replace('~GPS~', '', MAPSURL);
		$strHTML = str_replace('fb-map-link" style="display: none;"  href="', 'fb-map-link" style="display: block;"  href="' . $strGPS, $strHTML);

        $strTemplateWithReplacedValues  = '        <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';
        $strTemplateWithReplacedValues .= '        <html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US"> ';
        $strTemplateWithReplacedValues .= '            <head>';
        $strTemplateWithReplacedValues .= '                <meta http-equiv="X-UA-Compatible" content="IE=8, IE=9, IE=10" >';
        $strTemplateWithReplacedValues .= '                <meta charset="UTF-8" />';
        $strTemplateWithReplacedValues .= '                <meta name="viewport" content="width=device-width, initial-scale=1.0, xmaximum-scale=3.0, xminimum-scale=0.25" />';
        $strTemplateWithReplacedValues .= '                <!--========= BUNDLE CSS =========-->';
        $strTemplateWithReplacedValues .= '                <link rel="stylesheet" type="text/css" href="' .URL_APP_PATH . DYNAMIC_APP_DIR . 'bundle/bootstrap.css">';
        $strTemplateWithReplacedValues .= '                <link rel="stylesheet" type="text/css" href="' .URL_APP_PATH . DYNAMIC_APP_DIR . 'bundle/bootstrap-theme.min.css">';
        $strTemplateWithReplacedValues .= '                <link rel="stylesheet" type="text/css" href="' .URL_APP_PATH . DYNAMIC_APP_DIR . 'css/themes/default/theme.css">';
		$strTemplateWithReplacedValues .= '                <link rel="stylesheet" type="text/css" href="' .URL_APP_PATH . DYNAMIC_APP_DIR . 'modules/widgetformbuilder/assets/css/form-builder.css">';
        $strTemplateWithReplacedValues .= '                <title></title>';
        $strTemplateWithReplacedValues .= '            </head>';
        $strTemplateWithReplacedValues .= '            <body>' . $strHTML . '</body>';
        $strTemplateWithReplacedValues .= '            </html>';

		$strFilename = 'form-report.html';	// if 1 component it can be hardcoded
        documentOpen($objConn_a, $strDocumentID_a);
        documentComponentAdd($objConn_a, $strDocumentID_a, $strFilename, $strTemplateWithReplacedValues);
        documentCommit($objConn_a, '', $strDocumentID_a);

		// full path for returning
		$strREL_CURRENTREPOSITORY_PRINT_DOCUMENT = '';
		if (ENABLE_CLIENTDATABASES == 'TRUE')
		{
			if (getSessionDB(__FUNCTION__) == "client")
			{
				$strREL_CURRENTREPOSITORY_PRINT_DOCUMENT = REL_CLIENTREPOSITORY_PRINT_DOCUMENT;
			}
			else if (getSessionDB(__FUNCTION__) == "system")
			{
				$strREL_CURRENTREPOSITORY_PRINT_DOCUMENT = REL_SYSTEMREPOSITORY_PRINT_DOCUMENT;
			}
		}
		else
		{
			$strREL_CURRENTREPOSITORY_PRINT_DOCUMENT = REL_SYSTEMREPOSITORY_PRINT_DOCUMENT;
		}
		$strPath = getClusterPath($strREL_CURRENTREPOSITORY_PRINT_DOCUMENT, $strFilenameBase_a, false, false);
		$strResult = STAT_COMPLETED;
    }

    $arrResult = array("result"=> $strResult, "error"=>$strError, "tagtype"=>'filenamebase', "tag"=> $strFilenameBase_a . "-form-report", "path"=> $strPath );
    return $arrResult;
}

