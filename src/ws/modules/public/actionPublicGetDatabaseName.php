<?php

// get the database name
function actionPublicGetDatabaseName($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a, $strDeviceIDCookie_a)
{
    $strResult = "";

    // permission check
    if (!hasPermission($objConn_a, 'NOCHECK', __FUNCTION__, true)) { return false; }

    $strResult = createJSONResponse($strDataID_a, RESPONSE_OK, '', array("databasename" => DBSYSTEM_ENGLISHNAME));

    return $strResult;
}
