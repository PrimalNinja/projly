<?php

function getHeader_integrationtask($objConn_a)
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
        'field' => 'descriptin',
        'name'  => 'Description',
        'sortable' => 'Y'
     );

     /*
     $arrResult[] = array(
        'id'    => 'is_enabled',
        'field' => 'is_enabled',
        'name'  => 'Is Enabled',
        'sortable' => 'Y'
     );
     */

	
    $arrResult[] = array(
        'id'    => 'outboundguid',
        'field' => 'gd554dd80_b3d7_4406_b011_168477f243d9_outboundguid',
        'name'  => 'Outbound GUID',
        'sortable' => 'Y'
     );

    $arrResult[] = array(
        'id'    => 'is_processed',
        'field' => 'gd554dd80_b3d7_4406_b011_168477f243d9_is_processed',
        'name'  => 'Is Processed',
        'sortable' => 'Y'
     );
     	
	$arrResult[] = array(
        'id'    => 'status',
        'field' => 'gd554dd80_b3d7_4406_b011_168477f243d9_status',
        'name'  => 'Status',
        'sortable' => 'N'
    );

    $arrResult[] = array(
        'id'    => 'server',
        'field' => 'gd554dd80_b3d7_4406_b011_168477f243d9_server',
        'name'  => 'Server',
        'sortable' => 'Y'
     );

    $arrResult[] = array(
        'id'    => 'parameters',
        'field' => 'gd554dd80_b3d7_4406_b011_168477f243d9_parameters',
        'name'  => 'Parameters',
        'sortable' => 'Y'
     );

     
                		               
    return $arrResult;
}
