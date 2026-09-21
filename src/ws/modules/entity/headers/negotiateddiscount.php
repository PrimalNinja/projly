<?php

function getHeader_negotiateddiscount($objConn_a)
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

    $arrResult[] = array(
        'id'    => 'product',
        'field' => 'g5ca0bf62_1e0b_4d30_ba0b_c531b5e79e71_product',
        'name'  => 'Product',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'forclient',
        'field' => 'g5ca0bf62_1e0b_4d30_ba0b_c531b5e79e71_forclient',
        'name'  => 'Client',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'percent',
        'field' => 'g5ca0bf62_1e0b_4d30_ba0b_c531b5e79e71_percent',
        'name'  => 'Discount (%)',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'dollar',
        'field' => 'g5ca0bf62_1e0b_4d30_ba0b_c531b5e79e71_dollar',
        'name'  => 'Discount ($)',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'priority',
        'field' => 'g5ca0bf62_1e0b_4d30_ba0b_c531b5e79e71_priority',
        'name'  => 'Priority',
        'sortable' => 'Y'
    );
        
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
        
    // $arrResult[] = array(
        // 'id'    => 'modified',
        // 'field' => 'modified',
        // 'name'  => 'Modified',
        // 'sortable' => 'Y'
    // );
        
    return $arrResult;
}
