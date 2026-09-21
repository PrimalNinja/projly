<?php

function getHeader_entity_relationship($objConn_a)
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
        'id'    => 'relationshipentity',
        'field' => 'gdc4a5462_7953_4be9_8fc6_38d46bcef202_relationshipentity',
        'name'  => 'Related Entity',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'fieldname',
        'field' => 'gdc4a5462_7953_4be9_8fc6_38d46bcef202_fieldname',
        'name'  => 'Field Name',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'fkname',
        'field' => 'gdc4a5462_7953_4be9_8fc6_38d46bcef202_fkname',
        'name'  => 'Foreign Key Name',
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
