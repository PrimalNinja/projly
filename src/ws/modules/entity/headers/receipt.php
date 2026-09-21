<?php

function getHeader_receipt($objConn_a)
{
    $arrResult = array();

	if (toBoolean(ENABLE_ROWNUM))
	{
		$arrResult[] = array(
			'id'    => 'rownum',
			'field' => 'rownum',
			'name'  => '#',
			'sortable' => 'N'
		);
	}

    //$arrResult[] = array(
        //'id'    => 'formentitycode',
        //'field' => 'formentitycode',
        //'name'  => 'Entity',
        //'sortable' => 'N'
    //);

    // $arrResult[] = array(
        // 'id'    => 'code',
        // 'field' => 'code',
        // 'name'  => 'Code',
        // 'sortable' => 'Y'
    // );

    $arrResult[] = array(
        'id'    => 'invoicenumber',
        'field' => 'ff6b4c87cd_9b74_40de_9375_83f280731846_invoicenumber',
        'name'  => 'Invoice #',
        'sortable' => 'Y'
    );

	$arrResult[] = array(
        'id'    => 'invoicedate',
        'field' => 'ff6b4c87cd_9b74_40de_9375_83f280731846_invoicedate',
        'name'  => 'Invoice Date',
        'sortable' => 'Y'
    );

	$arrResult[] = array(
        'id'    => 'billingbillto',
        'field' => 'ff6b4c87cd_9b74_40de_9375_83f280731846_billingbillto',
        'name'  => 'Billing Name',
        'sortable' => 'Y'
    );

	$arrResult[] = array(
        'id'    => 'billingsuburb',
        'field' => 'ff6b4c87cd_9b74_40de_9375_83f280731846_billingsuburb',
        'name'  => 'Suburb',
        'sortable' => 'Y'
    );

	$arrResult[] = array(
        'id'    => 'billingstate',
        'field' => 'ff6b4c87cd_9b74_40de_9375_83f280731846_billingstate',
        'name'  => 'State',
        'sortable' => 'Y'
    );

	$arrResult[] = array(
        'id'    => 'billingpostcode',
        'field' => 'ff6b4c87cd_9b74_40de_9375_83f280731846_billingpostcode',
        'name'  => 'Postcode',
        'sortable' => 'Y'
    );

	$arrResult[] = array(
        'id'    => 'abn',
        'field' => 'ff6b4c87cd_9b74_40de_9375_83f280731846_abn',
        'name'  => 'ABN',
        'sortable' => 'Y'
    );

	$arrResult[] = array(
        'id'    => 'subtotal',
        'field' => 'ff6b4c87cd_9b74_40de_9375_83f280731846_subtotal',
        'name'  => 'Sub Total',
        'sortable' => 'Y'
    );

	$arrResult[] = array(
        'id'    => 'gst',
        'field' => 'ff6b4c87cd_9b74_40de_9375_83f280731846_gst',
        'name'  => 'GST',
        'sortable' => 'Y'
    );

	$arrResult[] = array(
        'id'    => 'total',
        'field' => 'ff6b4c87cd_9b74_40de_9375_83f280731846_total',
        'name'  => 'Total',
        'sortable' => 'Y'
    );

	// $arrResult[] = array(
        // 'id'    => 'description',
        // 'field' => 'description',
        // 'name'  => 'Description',
        // 'sortable' => 'Y'
    // );
    // $arrResult[] = array(
        // 'id'    => 'version',
        // 'field' => 'version',
        // 'name'  => 'Version',
        // 'sortable' => 'N'
    // );

    // $arrResult[] = array(
        // 'id'    => 'isenabled',
        // 'field' => 'isenabled',
        // 'name'  => 'Enabled',
        // 'sortable' => 'Y'
    // );

    // $arrResult[] = array(
        // 'id'    => 'modified',
        // 'field' => 'modified',
        // 'name'  => 'Modified',
        // 'sortable' => 'Y'
    // );

    return $arrResult;
}
