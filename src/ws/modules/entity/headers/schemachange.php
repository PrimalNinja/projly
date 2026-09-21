<?php

function getHeader_schemachange($objConn_a)
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
        'id'    => 'priority',
        'field' => 'id',
        'name'  => 'Priority',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'entitycode',
        'field' => 'g523b5f4a_fc16_4f51_ad0c_c1cee86223bd_entitycode',
        'name'  => 'Entity Code',
        'sortable' => 'Y'
    );
	
    $arrResult[] = array(
        'id'    => 'code',
        'field' => 'code',
        'name'  => 'Batch',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'description',
        'field' => 'description',
        'name'  => 'Description',
        'sortable' => 'Y'
    );
	
    //$arrResult[] = array(
        //'id'    => 'field',
        //'field' => 'g523b5f4a_fc16_4f51_ad0c_c1cee86223bd_field',
        //'name'  => 'Field',
        //'sortable' => 'Y'
    //);
	
    //$arrResult[] = array(
        //'id'    => 'function',
        //'field' => 'g523b5f4a_fc16_4f51_ad0c_c1cee86223bd_function',
        //'name'  => 'Function',
        //'sortable' => 'Y'
    //);
	
    //$arrResult[] = array(
        //'id'    => 'isdone',
        //'field' => 'g523b5f4a_fc16_4f51_ad0c_c1cee86223bd_isdone',
        //'name'  => 'Is Done',
        //'sortable' => 'Y'
    //);

    $arrResult[] = array(
        'id'    => 'error',
        'field' => 'g523b5f4a_fc16_4f51_ad0c_c1cee86223bd_error',
        'name'  => 'Error',
        'sortable' => 'Y'
    );
	
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
