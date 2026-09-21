<?php

function getHeader_clientproduct($objConn_a)
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
        'id'    => 'applicantname',
        'field' => 'ff252aa5f0_e8df_4c52_a5c4_de677e1edf85_applicantname',
        'name'  => 'Applicant',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'product',
        'field' => 'ff252aa5f0_e8df_4c52_a5c4_de677e1edf85_product',
        'name'  => 'Product',
        'sortable' => 'Y'
    );
        
	$arrResult[] = array(
        'id'    => 'purchasedate',
        'field' => 'ff252aa5f0_e8df_4c52_a5c4_de677e1edf85_purchasedate',
        'name'  => 'Purchase Date',
        'sortable' => 'Y'
    );
	
	$arrResult[] = array(
        'id'    => 'expirydate',
        'field' => 'ff252aa5f0_e8df_4c52_a5c4_de677e1edf85_expirydate',
        'name'  => 'Expiry Date',
        'sortable' => 'Y'
    );
	
	$arrResult[] = array(
        'id'    => 'paymentstatus',
        'field' => 'ff252aa5f0_e8df_4c52_a5c4_de677e1edf85_paymentstatus',
        'name'  => 'Payment Status',
        'sortable' => 'Y'
    );
	
	$arrResult[] = array(
        'id'    => 'paymentdate',
        'field' => 'ff252aa5f0_e8df_4c52_a5c4_de677e1edf85_paymentdate',
        'name'  => 'Payment Date',
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
        
    $arrResult[] = array(
        'id'    => 'isenabled',
        'field' => 'isenabled',
        'name'  => 'Enabled',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'modified',
        'field' => 'modified',
        'name'  => 'Modified',
        'sortable' => 'Y'
    );
	
    return $arrResult;
}
