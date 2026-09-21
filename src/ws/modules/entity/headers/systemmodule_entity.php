<?php

function getHeader_systemmodule_entity($objConn_a)
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
        'id'    => 'category',
        'field' => 'category',
        'name'  => 'Category',
        'sortable' => 'N'
    );
	
    $arrResult[] = array(
        'id'    => 'entityentity',
        'field' => 'g4f6f774a_fe22_46c1_819b_ad161593721b_entityentity',
        'name'  => 'Entity',
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
