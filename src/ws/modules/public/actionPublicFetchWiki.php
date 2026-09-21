<?php

// fetch wiki pages
function actionPublicFetchWiki($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    $arrResult = array();
	$strWikiContent = '';

    // permission check
	if (!hasPermission($objConn_a, 'TODO', __FUNCTION__, true)) {return false;}

    // parameters
    $strWikiPage = getJSONParameter($arrParameters_a, 'wiki');
	
	if (dependencies('public/fetchWiki'))
	{
		$strWikiContent = fetchWiki($objConn_a, $strWikiPage);
	}
	
	$arrResult[] = array(
		"wiki" => $strWikiContent
	);

    return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
}
