<?php

// fetch wiki pages
function fetchWiki($objConn_a, $strWikiPage_a)
{
	$strResult = '';
	$strWikiPage = filter_var($strWikiPage_a, FILTER_SANITIZE_STRING);

    // fetch
	if (file_exists(WIKI_PATH . 'pages/' . $strWikiPage . '.wiki'))
	{
		$strResult = file_get_contents(WIKI_PATH . 'pages/' . $strWikiPage . '.wiki');
		$strResult = str_replace("\r", "", $strResult);
	}
	
	if (strlen($strResult) == 0)
	{
		if (file_exists(WIKI_PATH . 'pages/index.wiki'))
		{
			$strResult = file_get_contents(WIKI_PATH . 'pages/index.wiki');
			$strResult = str_replace("\r", "", $strResult);
		}
	}
	
	return $strResult;
}
