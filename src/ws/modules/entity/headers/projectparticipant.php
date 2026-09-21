<?php

function getHeader_projectparticipant($objConn_a)
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
        'id'    => 'projectstakeholder',
        'field' => 'fff8db5be0_1045_441f_b7a2_769fd8de46f0_projectstakeholder',
        'name'  => 'Stakeholder',
        'sortable' => 'Y'
    );
    
    $arrResult[] = array(
        'id'    => 'description',
        'field' => 'description',
        'name'  => 'Description',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'participanttype',
        'field' => 'fff8db5be0_1045_441f_b7a2_769fd8de46f0_projectparticipanttype',
        'name'  => 'Participant Type',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'costcategory',
        'field' => 'fff8db5be0_1045_441f_b7a2_769fd8de46f0_projectcostcategory',
        'name'  => 'Cost Category',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'billingcategory',
        'field' => 'fff8db5be0_1045_441f_b7a2_769fd8de46f0_projectbillingcategory',
        'name'  => 'Billing Category',
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
        
    $arrResult[] = array(
        'id'    => 'code',
        'field' => 'code',
        'name'  => 'Participant ID',
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
