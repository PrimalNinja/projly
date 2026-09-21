<?php

function getHeader_systemmodule($objConn_a)
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
        'id'    => 'sortorder',
        'field' => 'sortorder',
        'name'  => 'Sort Order',
        'sortable' => 'N'
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
        'id'    => 'dependencies',
        'field' => 'gdb7a73ca_38b3_43bf_9d17_a599f23507e5_dependencies',
        'name'  => 'Dependencies',
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
