<?php

function getHeader_projectresource($objConn_a)
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
        // 'id'    => 'code',
        // 'field' => 'code',
        // 'name'  => 'Code',
        // 'sortable' => 'Y'
    // );
        
    $arrResult[] = array(
        'id'    => 'description',
        'field' => 'description',
        'name'  => 'Description',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'resourcetype',
        'field' => 'ff31dde036_5ea2_4d57_9b43_704671d5ae78_projectresourcetype',
        'name'  => 'Resource Type',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'costcategory',
        'field' => 'ff31dde036_5ea2_4d57_9b43_704671d5ae78_projectcostcategory',
        'name'  => 'Cost Category',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'billingcategory',
        'field' => 'ff31dde036_5ea2_4d57_9b43_704671d5ae78_projectbillingcategory',
        'name'  => 'Billing Category',
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
