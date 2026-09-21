<?php

function getHeader_profile($objConn_a)
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
        'id'    => 'code',
        'field' => 'f9a0f134f_54b8_4e11_81b3_02d4f51dadd0_code',
        'name'  => 'Code',
        'sortable' => 'Y'
    );
	
	$arrResult[] = array(
        'id'    => 'description',
        'field' => 'description',
        'name'  => 'Description',
        'sortable' => 'Y'
    );
    // $arrResult[] = array(
        // 'id'    => 'version',
        // 'field' => 'version',
        // 'name'  => 'Version',
        // 'sortable' => 'N'
    // );
        
    $arrResult[] = array(
        'id'    => 'isadmin',
        'field' => 'f9a0f134f_54b8_4e11_81b3_02d4f51dadd0_isadmin',
        'name'  => 'Admin',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'is_default',
        'field' => 'f9a0f134f_54b8_4e11_81b3_02d4f51dadd0_isdefault',
        'name'  => 'Default',
        'sortable' => 'Y'
    );
        
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
