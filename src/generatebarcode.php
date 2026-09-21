<?php

// for now the generatebarcode can work with action barcodes or content barcodes
// for content ones, an image is returned, for action ones it generates output
// that relates to the action*

// * this is for future actions, at this moment it only caters for one action which
//   is generate a welcome url

require_once('ws/inc-env.php');
require_once('ws/inc-constants.php');
require_once('ws/inc-settings.php');
require_once('ws/modules/utils/security.php'); 
require_once('ws/modules/3p/barcode/barcode.php');

// example links
// http://phpqrcode.sourceforge.net/examples/index.php?example=001

$strGUID = getGUID();	// the guid is used for the temporary barcode filename only
$strFilename = TEMP_GENERAL_PATH . $strGUID . ".png";
$strFilenameURL = URL_GENERAL_PATH . $strGUID . ".png";

// get action parameter
$strAction = "";
if (isset($_GET['action']))
{
	$strAction = $_GET['action'];	// this is the action GUID to identify content, not the actual barcode content
}

// get content parameter
$strContent = "";
if (isset($_GET['content']))
{
	$strContent = $_GET['content'];	// this is alternatively actual content
}

// get encoding parameter
$strEncoding = "QR"; //DEFAULT_ENCODING;
if (isset($_GET['encoding']))
{
	$strEncoding = $_GET['encoding'];	// this is alternatively actual encoding
}

// do we want to preserve any content URL escaping?
$strIsURL = "N";
if (isset($_GET['isurl']))
{
	$strIsURL = $_GET['isurl'];
}

if ($strIsURL == 'Y')
{
	$strContent = str_replace(" ", "%20", $strContent);
	$strContent = str_replace("&", "%26", $strContent);
}
else
{
	$strContent = str_replace("%20", " ", $strContent);
	$strContent = str_replace("%26", "&", $strContent);
}

if (strlen($strAction) > 0)
{
	$strBarcodeContent = URL_WELCOME_PAGE_BARCODE; // this is the barcode content
	$strBarcodeContent = str_replace("~TOKEN~", $strAction, $strBarcodeContent);

	// NOTE: ACTIONS CURRENTLY NON-FUNCTIONAL
	//QRcode::png($strBarcodeContent, $strFilename);	// create the barcode file so it can be served via html

	echo('<img src="' . $strFilenameURL . '" /><br><br>' . $strBarcodeContent);
}
else if (strlen($strContent) > 0)
{
	//QRcode::png($strContent);	// create the barcode image
	$strEncoding = strtolower($strEncoding);
	$strOptions = '';
	$objGenerator = new barcode_generator();
	$strImage = $objGenerator->render_image($strEncoding, $strContent, $strOptions);
	header('Content-Type: image/png');
	imagepng($strImage);
	imagedestroy($strImage);
}
