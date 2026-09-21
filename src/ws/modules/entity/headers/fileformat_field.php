<?php

function getHeader_fileformat_field($objConn_a)
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
        'id'    => 'filedatatype',
        'field' => 'gdca0c616_9e33_4f80_adb1_c964e9f44713_filedatatype',
        'name'  => 'Data Type',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'position',
        'field' => 'gdca0c616_9e33_4f80_adb1_c964e9f44713_position',
        'name'  => 'Position',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'length',
        'field' => 'gdca0c616_9e33_4f80_adb1_c964e9f44713_length',
        'name'  => 'Length',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'multiplier',
        'field' => 'gdca0c616_9e33_4f80_adb1_c964e9f44713_multiplier',
        'name'  => 'Multiplier',
        'sortable' => 'Y'
    );


    $arrResult[] = array(
        'id'    => 'is_mandatory',
        'field' => 'gdca0c616_9e33_4f80_adb1_c964e9f44713_is_mandatory',
        'name'  => 'Mandatory',
        'sortable' => 'Y'
    );
                    
    return $arrResult;
}
