<?php

function getHeader_messageprepared($objConn_a)
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
        'id'    => 'recipients',
        'field' => 'recipients',
        'name'  => 'Recipients',
        'sortable' => 'N'
    );
        
    $arrResult[] = array(
        'id'    => 'subject',
        'field' => 'subject',
        'name'  => 'Subject',
        'sortable' => 'N'
    );
        
    $arrResult[] = array(
        'id'    => 'priority',
        'field' => 'priority',
        'name'  => 'Priority',
        'sortable' => 'N'
    );
        
    $arrResult[] = array(
        'id'    => 'retries',
        'field' => 'retries',
        'name'  => 'Retries',
        'sortable' => 'N'
    );
        
    $arrResult[] = array(
        'id'    => 'sent',
        'field' => 'sent',
        'name'  => 'Is Sent?',
        'sortable' => 'N'
    );
        
    $arrResult[] = array(
        'id'    => 'modified',
        'field' => 'modifydatetime',
        'name'  => 'Modified',
        'sortable' => 'N'
    );
        
    return $arrResult;
}
