<?php

function getHeader_reminder($objConn_a)
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
        'id'    => 'description',
        'field' => 'description',
        'name'  => 'Description',
        'sortable' => 'Y'
    );
               
	$arrResult[] = array(
        'id'    => 'message',
        'field' => 'adee66ca_8443_4e3e_8ddf_b5a480bf2b49_message',
        'name'  => 'Message',
        'sortable' => 'Y'
    );
	
	$arrResult[] = array(
        'id'    => 'date',
        'field' => 'adee66ca_8443_4e3e_8ddf_b5a480bf2b49_date',
        'name'  => 'Date To Remind',
        'sortable' => 'Y'
    );
	
	$arrResult[] = array(
        'id'    => 'type',
        'field' => 'type',
        'name'  => 'Type',
        'sortable' => 'N'
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
