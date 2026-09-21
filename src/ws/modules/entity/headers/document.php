<?php

function getHeader_document($objConn_a)
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
        
    //$arrResult[] = array(
        //'id'    => 'code',
        //'field' => 'code',
        //'name'  => 'Code',
        //'sortable' => 'Y'
    //);
		        
 	$arrResult[] = array(
        'id'    => 'description',
        'field' => 'description',
        'name'  => 'Description',
        'sortable' => 'Y'
    );

 	$arrResult[] = array(
        'id'    => 'tags',
        'field' => 'ffe70fd7f1_401b_4c5c_b096_76f67d466982_tags',
        'name'  => 'Tags',
        'sortable' => 'Y'
    );
	
    $arrResult[] = array(
        'id'    => 'isenabled',
        'field' => 'isenabled',
        'name'  => 'Enabled',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'documentdate',
        'field' => 'ffe70fd7f1_401b_4c5c_b096_76f67d466982_documentdate',
        'name'  => 'Document Date',
        'sortable' => 'Y'
    );
	
	$arrResult[] = array(
        'id'    => 'documentrepository',
        'field' => 'ffe70fd7f1_401b_4c5c_b096_76f67d466982_documentrepository',
        'name'  => 'Repository',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'modified',
        'field' => 'modified',
        'name'  => 'Modified',
        'sortable' => 'Y'
    );
        
    return $arrResult;
}
