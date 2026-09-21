<?php

function getHeader_messagetemplate($objConn_a)
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
        
     $arrResult[] = array(
        'id'    => 'code',
        'field' => 'code',
        'name'  => 'Code',
        'sortable' => 'Y'
     );
        
	$arrResult[] = array(
        'id'    => 'description',
        'field' => 'ff3cf93a11_6df2_4982_a62a_45dd3b699608_longdescription',
        'name'  => 'Description',
        'sortable' => 'Y'
    );
	
    $arrResult[] = array(
        'id'    => 'subject',
        'field' => 'ff3cf93a11_6df2_4982_a62a_45dd3b699608_subject',
        'name'  => 'Subject',
        'sortable' => 'Y'
    );
	
	$arrResult[] = array(
        'id'    => 'messagetemplatetype',
        'field' => 'ff3cf93a11_6df2_4982_a62a_45dd3b699608_messagetemplatetype',
        'name'  => 'Message Template Type',
        'sortable' => 'Y'
    );
        	
    // $arrResult[] = array(
        // 'id'    => 'version',
        // 'field' => 'version',
        // 'name'  => 'Version',
        // 'sortable' => 'N'
    // );
        
    $arrResult[] = array(
        'id'    => 'isenabled',
        'field' => 'isenabled',
        'name'  => 'Enabled',
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
