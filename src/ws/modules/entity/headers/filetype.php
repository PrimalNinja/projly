<?php

function getHeader_filetype($objConn_a)
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
        'id'    => 'is_definable',
        'field' => 'gc5913c98_7aff_4753_9b43_6f388e7b707f_is_definable',
        'name'  => 'Is Definable?',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'is_import',
        'field' => 'gc5913c98_7aff_4753_9b43_6f388e7b707f_is_import',
        'name'  => 'Importable',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'is_simpleimport',
        'field' => 'gc5913c98_7aff_4753_9b43_6f388e7b707f_is_simpleimport',
        'name'  => 'Simple Import',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'is_export',
        'field' => 'gc5913c98_7aff_4753_9b43_6f388e7b707f_is_export',
        'name'  => 'Exportable',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'applications',
        'field' => 'gc5913c98_7aff_4753_9b43_6f388e7b707f_applications',
        'name'  => 'Applications',
        'sortable' => 'Y'
    );
                    
    return $arrResult;
}
