<?php

function getHeader_fileformat($objConn_a)
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
        'id'    => 'fileformattemplate',
        'field' => 'g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_fileformattemplate',
        'name'  => 'File Type',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'formattype',
        'field' => 'g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_fileformattype',
        'name'  => 'Format Type',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'is_import',
        'field' => 'g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_is_import',
        'name'  => 'Is Import?',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'is_export',
        'field' => 'g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_is_export',
        'name'  => 'Is Export?',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'is_default',
        'field' => 'g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_is_default',
        'name'  => 'Is Default?',
        'sortable' => 'Y'
    );
                    
    return $arrResult;
}
