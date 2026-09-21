<?php

function getHeader_transactionpending($objConn_a)
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

    // $arrResult[] = array(
        // 'id'    => 'created',
        // 'field' => 'createdatetime',
        // 'name'  => 'Created',
        // 'sortable' => 'Y'
    // );
	
	$arrResult[] = array(
        'id'    => 'modified',
        'field' => 'modified',
        'name'  => 'Modified',
        'sortable' => 'Y'
    );

        
    // $arrResult[] = array(
        // 'id'    => 'version',
        // 'field' => 'version',
        // 'name'  => 'Version',
        // 'sortable' => 'N'
    // );

	$arrResult[] = array(
        'id'    => 'payername',
        'field' => 'g31f68ccc_e168_40b8_a7db_693353aa7aea_payername',
        'name'  => 'Payer',
        'sortable' => 'Y'
    );
	
	$arrResult[] = array(
        'id'    => 'receiptnumber',
        'field' => 'g31f68ccc_e168_40b8_a7db_693353aa7aea_receiptnumber',
        'name'  => 'Receipt Number',
        'sortable' => 'Y'
    );
	
	$arrResult[] = array(
        'id'    => 'paymentmethod',
        'field' => 'g31f68ccc_e168_40b8_a7db_693353aa7aea_paymentmethod',
        'name'  => 'Payment Method',
        'sortable' => 'Y'
    );
	
	$arrResult[] = array(
        'id'    => 'priceexgst',
        'field' => 'g31f68ccc_e168_40b8_a7db_693353aa7aea_priceexgst',
        'name'  => 'Price $ (Ex GST)',
        'sortable' => 'Y'
    );
	
	$arrResult[] = array(
        'id'    => 'priceincgst',
        'field' => 'g31f68ccc_e168_40b8_a7db_693353aa7aea_priceincgst',
        'name'  => 'Price $ (Inc GST)',
        'sortable' => 'Y'
    );
        
	$arrResult[] = array(
        'id'    => 'paymentstatus',
        'field' => 'g31f68ccc_e168_40b8_a7db_693353aa7aea_paymentstatus',
        'name'  => 'Payment Status',
        'sortable' => 'Y'
    );
	
	$arrResult[] = array(
        'id'    => 'statusdescription',
        'field' => 'g31f68ccc_e168_40b8_a7db_693353aa7aea_statusdescription',
        'name'  => 'Transaction Status',
        'sortable' => 'Y'
    );
        
    return $arrResult;
}
