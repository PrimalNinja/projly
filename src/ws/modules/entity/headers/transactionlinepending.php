<?php

function getHeader_transactionlinepending($objConn_a)
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
        'id'    => 'product',
        'field' => 'gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_product',
        'name'  => 'Product',
        'sortable' => 'Y'
    );
	
	// $arrResult[] = array(
        // 'id'    => 'description',
        // 'field' => 'gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_description',
        // 'name'  => 'Description',
        // 'sortable' => 'Y'
    // );
	
	$arrResult[] = array(
        'id'    => 'applicanttype',
        'field' => 'gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_applicanttype',
        'name'  => 'Applicant Type',
        'sortable' => 'Y'
    );
	
	$arrResult[] = array(
        'id'    => 'applicant',
        'field' => 'gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_applicant',
        'name'  => 'Applicant',
        'sortable' => 'Y'
    );
	
	$arrResult[] = array(
        'id'    => 'workqueuecode',
        'field' => 'gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_workqueuecode',
        'name'  => 'Work Queue Code',
        'sortable' => 'Y'
    );
	
	$arrResult[] = array(
        'id'    => 'priceexgst',
        'field' => 'gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_priceexgst',
        'name'  => 'Price $ (Ex GST)',
        'sortable' => 'Y'
    );
	
	$arrResult[] = array(
        'id'    => 'priceincgst',
        'field' => 'gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_priceincgst',
        'name'  => 'Price $ (Inc GST)',
        'sortable' => 'Y'
    );
        
	$arrResult[] = array(
        'id'    => 'status',
        'field' => 'status',
        'name'  => 'Status',
        'sortable' => 'N'
    );
        
    return $arrResult;
}
