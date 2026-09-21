<?php

function getHeader_import($objConn_a)
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
       
    // $arrResult[] = array(
        // 'id' => 'branchname',
        // 'field' => 'gdc026800_9533_4d67_a347_7946a5401f72_branch',
        // 'name' => 'Branch',
        // 'sortable' => 'Y'
    // );
    
	$arrResult[] = array(
        'id' => 'status',
        'field' => 'gdc026800_9533_4d67_a347_7946a5401f72_status',
        'name' => 'Status',
        'sortable' => 'Y'
    );
    
	$arrResult[] = array(
        'id'    => 'importdate',
        'field' => 'gdc026800_9533_4d67_a347_7946a5401f72_importdate',
        'name'  => 'Import Date',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'filetype',
        'field' => 'gdc026800_9533_4d67_a347_7946a5401f72_filetype',
        'name'  => 'File Type',
        'sortable' => 'Y'
    );   
        
    $arrResult[] = array(
        'id'    => 'notes',
        'field' => 'gdc026800_9533_4d67_a347_7946a5401f72_notes',
        'name'  => 'Notes',
        'sortable' => 'Y'
    );   
        
    return $arrResult;
}
