<?php

function getHeader_negotiatedrate($objConn_a)
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
        'field' => 'g56ec2ec9_a7c4_43cc_a6ac_9f6a710e3e94_product',
        'name'  => 'Product',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'forclient',
        'field' => 'g56ec2ec9_a7c4_43cc_a6ac_9f6a710e3e94_forclient',
        'name'  => 'Client',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'percent',
        'field' => 'g56ec2ec9_a7c4_43cc_a6ac_9f6a710e3e94_percent',
        'name'  => 'Rate (%)',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'dollar',
        'field' => 'g56ec2ec9_a7c4_43cc_a6ac_9f6a710e3e94_dollar',
        'name'  => 'Rate ($)',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'priority',
        'field' => 'g56ec2ec9_a7c4_43cc_a6ac_9f6a710e3e94_priority',
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
