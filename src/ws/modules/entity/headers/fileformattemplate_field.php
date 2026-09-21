<?php

function getHeader_fileformattemplate_field($objConn_a)
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
        'id'    => 'datatype',
        'field' => 'g17b76dbc_4845_4184_9757_5b7c5d52818b_filedatatype',
        'name'  => 'Data Type',
        'sortable' => 'Y'
    );
             
    $arrResult[] = array(
        'id'    => 'is_mandatory',
        'field' => 'g17b76dbc_4845_4184_9757_5b7c5d52818b_is_mandatory',
        'name'  => 'Mandatory',
        'sortable' => 'Y'
    );
             
    return $arrResult;
}
