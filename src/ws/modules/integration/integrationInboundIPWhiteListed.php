<?php
function integrationInboundIPWhiteListed($objConn_a, $strIntegrationInboundID_a, $strIPAddress_a)
{
    $strTableNameIntegrationInbound = getTableNameEntity("integrationinbound", false);
    $strTableNameInboundWhiteList = getTableNameEntity("inbound_whitelist", false);

    $blnResult = false;

    $strSQL = "select g8fbd6661_d8e6_4279_b9a5_5e1042970937_use_whitelist returnvalue from ~TABLENAMEINTEGRATIONINBOUND~ where id = ~INTEGRATIONINBOUNDID~";
    $strSQL = str_replace('~TABLENAMEINTEGRATIONINBOUND~', ff($strTableNameIntegrationInbound), $strSQL);
    $strSQL = str_replace('~INTEGRATIONINBOUNDID~', ff($strIntegrationInboundID_a), $strSQL);
    $strUseWhitelist = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

    if ($strUseWhitelist === 'Y')
    {
        $strSQL = "select count(*) returnvalue from ~TABLENAMEINBOUNDWHITELIST~ where integrationinbound_id = ~INTEGRATIONINBOUNDID~ and gff06200b_9c01_4408_a038_d07c85e5132e_ipaddress = '~IPADDRESS~'";
        $strSQL = str_replace('~TABLENAMEINBOUNDWHITELIST~', ff($strTableNameInboundWhiteList), $strSQL);
        $strSQL = str_replace('~INTEGRATIONINBOUNDID~', ff($strIntegrationInboundID_a), $strSQL);
        $strSQL = str_replace('~IPADDRESS~', ff($strIPAddress_a), $strSQL);
        $strResultCount = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

        if (intval($strResultCount) > 0)
        {
            $blnResult = true;
        }
    }
    else
    {
        $blnResult = true;
    }

    return $blnResult;
}