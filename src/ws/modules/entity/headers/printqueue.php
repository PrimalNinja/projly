<?php

function getHeader_printqueue($objConn_a)
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
        'id'    => 'code',
        'field' => 'gff9b32c5_96c5_4850_8834_3ed24e70d727_code',
        'name'  => 'Code',
        'sortable' => 'Y'
    );

	$arrResult[] = array(
        'id'    => 'description',
        'field' => 'gff9b32c5_96c5_4850_8834_3ed24e70d727_description',
        'name'  => 'Description',
        'sortable' => 'Y'
    );
   
	$arrResult[] = array(
        'id'    => 'is_public',
        'field' => 'is_public',
        'name'  => 'Is Public',
        'sortable' => 'Y'
    );    
    
	$arrResult[] = array(
        'id'    => 'is_enabled',
        'field' => 'is_enabled',
        'name'  => 'Enabled',
        'sortable' => 'Y'
    );    
                		               
    return $arrResult;
}
