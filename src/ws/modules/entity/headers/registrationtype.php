<?php

function getHeader_registrationtype($objConn_a)
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
        'field' => 'ff4909db1e_8bee_4c2b_be10_6d0853789635_applications',
        'name'  => 'Applications',
        'sortable' => 'Y'
    );
        
 	$arrResult[] = array(
        'id'    => 'displayorder',
        'field' => 'ff4909db1e_8bee_4c2b_be10_6d0853789635_displayorder',
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
        'id'    => 'isindividual',
        'field' => 'ff4909db1e_8bee_4c2b_be10_6d0853789635_isindividual',
        'name'  => 'Individual',
        'sortable' => 'Y'
    );
                    
    $arrResult[] = array(
        'id'    => 'isindividual',
        'field' => 'ff4909db1e_8bee_4c2b_be10_6d0853789635_isindividual',
        'name'  => 'Individual',
        'sortable' => 'Y'
    );
                    
    $arrResult[] = array(
        'id'    => 'isenabled',
        'field' => 'isenabled',
        'name'  => 'Enabled',
        'sortable' => 'Y'
    );
        
    return $arrResult;
}
