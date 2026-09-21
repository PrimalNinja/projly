<?php

function getHeader_systemmodule_navitem($objConn_a)
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
        'id'    => 'navitem',
        'field' => 'gc209b671_55f5_4c07_bfb9_a9231f3c0c20_navitem',
        'name'  => 'Nav Item',
        'sortable' => 'N'
    );
	    
    // $arrResult[] = array(
        // 'id'    => 'modified',
        // 'field' => 'modified',
        // 'name'  => 'Modified',
        // 'sortable' => 'Y'
    // );        
    return $arrResult;
}
