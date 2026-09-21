<?php

function calculateNegotiatedPrice($objConn_a, $strProductID_a, $strClientID_a, $intPriceIncGST_a)
{
    $strTableNameNegotiatedRate = getTableNameEntity('negotiatedrate', false);
    $strTableNameNegotiatedDiscount = getTableNameEntity('negotiateddiscount', false);

    $arrResult = array();
    $intPriceIncGST = $intPriceIncGST_a;

    // First find if there is a negotiated rate
    // Use rate with lowest number priority
    $strSQL = "
        select g56ec2ec9_a7c4_43cc_a6ac_9f6a710e3e94_percent percent, g56ec2ec9_a7c4_43cc_a6ac_9f6a710e3e94_dollar dollar
        from ~TABLENAMENEGOTIATEDRATE~
        where product_id = ~PRODUCTID~ and forclient_id = ~CLIENTID~
        order by g56ec2ec9_a7c4_43cc_a6ac_9f6a710e3e94_priority ASC
    ";
    $strSQL = str_replace('~TABLENAMENEGOTIATEDRATE~', ff($strTableNameNegotiatedRate), $strSQL);
    $strSQL = str_replace('~PRODUCTID~', ff($strProductID_a), $strSQL);
    $strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);

    // Use only first rate returned by query
    // Percentage overrides dollar amount if both exist
    // Update price accordingly
    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
    if ($arrRow = dbReadRecord($objResult))
    {
        $intPercent = $arrRow['percent'];
        $intDollar = $arrRow['dollar'];

        if (strlen($intPercent) > 0)
        {
            $intPriceIncGST = $intPriceIncGST_a * ($intPercent / 100);
        }
        else if (strlen($intDollar) > 0)
        {
            $intPriceIncGST = $intDollar;
        }
    }

    $intNegotiatedRateIncGST = $intPriceIncGST;

    // Now find if there is a negotiated discount
    // Apply all applicable discounts
    $strSQL = "
        select g5ca0bf62_1e0b_4d30_ba0b_c531b5e79e71_percent percent, g5ca0bf62_1e0b_4d30_ba0b_c531b5e79e71_dollar dollar
        from ~TABLENAMENEGOTIATEDDISCOUNT~
        where product_id = ~PRODUCTID~ and forclient_id = ~CLIENTID~
        order by g5ca0bf62_1e0b_4d30_ba0b_c531b5e79e71_priority ASC
    ";
    $strSQL = str_replace('~TABLENAMENEGOTIATEDDISCOUNT~', ff($strTableNameNegotiatedDiscount), $strSQL);
    $strSQL = str_replace('~PRODUCTID~', ff($strProductID_a), $strSQL);
    $strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);

    // Percentage overrides dollar amount if both exist
    // Update price accordingly
    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
    while ($arrRow = dbReadRecord($objResult))
    {
        $intPercent = $arrRow['percent'];
        $intDollar = $arrRow['dollar'];

        if (strlen($intPercent) > 0)
        {
            $intPriceIncGST -= $intNegotiatedRateIncGST * ($intPercent / 100);
        }
        else if (strlen($intDollar) > 0)
        {
            $intPriceIncGST -= $intDollar;
        }
    }

    if ($intPriceIncGST < 0)
    {
        $intPriceIncGST = 0;
    }

    $arrResult['priceincgst'] = round($intPriceIncGST, 2);
    $arrResult['priceexgst'] = round($intPriceIncGST - ($intPriceIncGST / 11), 2);

    return $arrResult;
}