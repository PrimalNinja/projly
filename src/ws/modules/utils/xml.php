<?php

// function summary:

// xmlAddChild($objXMLElement_a, $strChildName_a)
// xmlAddChildTag($objXMLElement_a, $strChildName_a, $strValue_a)
// xmlBooleanTTL($strValue_a)
// xmlDate($strValue_a, $strFormat_a)
// xmlEnc($strXML_a)
// xmlFixCRLFTTL($strValue_a)
// xmlFixHeaderTTL($strValue_a)

function xmlAddChild($objXMLElement_a, $strChildName_a)
{
    return $objXMLElement_a->addChild($strChildName_a);
}

function xmlAddChildTag($objXMLElement_a, $strChildName_a, $strValue_a)
{
    $strValue = str_replace('&', '&amp;', $strValue_a);
    return $objXMLElement_a->addChild($strChildName_a, $strValue);
}

function xmlBooleanTTL($strValue_a)
{
    $strResult = "NO";

    if (toBoolean($strValue_a)) {
        $strResult = "Yes";
    }

    return $strResult;
}

function xmlDate($strValue_a, $strFormat_a)
{
    $strResult = date_create($strValue_a);
    $strResult = date_format($strResult, $strFormat_a);

    return $strResult;
}

function xmlEnc($strXML_a)
{
	$strXML = $strXML_a;
	$strXML = str_replace('&', '&amp;', $strXML);
	$strXML = str_replace('<', '&lt;', $strXML);
	$strXML = str_replace('>', '&gt;', $strXML);
	$strXML = str_replace("'", '&apos;', $strXML);
	$strXML = str_replace(chr(34), '&quot;', $strXML);

	return $strXML;
}

function xmlFixCRLFTTL($strValue_a)
{
    $strResult = $strValue_a;

    $strResult = str_replace('><', '>' . CRLF . '<', $strResult) . CRLF;

    return $strResult;
}

function xmlFixHeaderTTL($strValue_a)
{
    $strResult = $strValue_a;

    $strResult = str_replace('<?xml version="1.0"?>', '', $strResult);
    $strResult = str_replace("\n", '', $strResult);
    $strResult = str_replace("\r", '', $strResult);
    $strResult = str_replace("\t", '', $strResult);

    return $strResult;
}
