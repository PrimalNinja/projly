<?php

function getHeader_product($objConn_a)
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
        
	$arrResult[] = array(
        'id'    => 'applications',
        'field' => 'gffe35a8ad_d290_4ae3_8800_f2935dd30d07_applications',
        'name'  => 'Applications',
        'sortable' => 'Y'
    );
		
	$arrResult[] = array(
        'id'    => 'producttype',
        'field' => 'gffe35a8ad_d290_4ae3_8800_f2935dd30d07_producttype',
        'name'  => 'Product Type',
        'sortable' => 'Y'
    );
		
    $arrResult[] = array(
        'id'    => 'displayorder',
        'field' => 'gffe35a8ad_d290_4ae3_8800_f2935dd30d07_displayorder',
        'name'  => 'Display Order',
        'sortable' => 'Y'
    ); 
		       
    $arrResult[] = array(
        'id'    => 'code',
        'field' => 'code',
        'name'  => 'Code',
        'sortable' => 'Y'
    ); 
		       
	$arrResult[] = array(
        'id'    => 'description',
        'field' => 'description',
        'name'  => 'Description',
        'sortable' => 'Y'
    );
		
	$arrResult[] = array(
        'id'    => 'priceincgst',
        'field' => 'gffe35a8ad_d290_4ae3_8800_f2935dd30d07_priceincgst',
        'name'  => 'Price inc. GST',
        'sortable' => 'Y'
    );
		
	$arrResult[] = array(
        'id'    => 'profilelist',
        'field' => 'gffe35a8ad_d290_4ae3_8800_f2935dd30d07_profilelist',
        'name'  => 'Profiles',
        'sortable' => 'Y'
    );
		
	$arrResult[] = array(
        'id'    => 'requirements',
        'field' => 'gffe35a8ad_d290_4ae3_8800_f2935dd30d07_requirements',
        'name'  => 'Requirements',
        'sortable' => 'Y'
    );
		
	$arrResult[] = array(
        'id'    => 'behaviourcategory',
        'field' => 'gffe35a8ad_d290_4ae3_8800_f2935dd30d07_behaviourcategory',
        'name'  => 'Behaviour',
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
