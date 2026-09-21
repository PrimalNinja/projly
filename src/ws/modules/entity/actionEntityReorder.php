<?php

function actionEntityReorder($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a) 
{ 
	$strTableNameEntity = getTableNameEntity('entity', false);
	
    $strResult = "";
    $strDescription = ''; 
	
	// permission check
    if (!hasPermission($objConn_a, 'NOCHECK', __FUNCTION__, true)) {return false;}
	
	$strEntityCode = strtoupper(getJSONParameter($arrParameters_a, 'entitycode'));
	$strSourceID = getJSONParameter($arrParameters_a, 'sourceid');
	$strDestinationID = getJSONParameter($arrParameters_a, 'destinationid');
			
	$strDraggedID = revertSecuredValue($strSourceID, 'id', true);
	$strDraggedOnToID = revertSecuredValue($strDestinationID, 'id', true);
						
	$strSQL = "select ffe65a2521_c3d0_42fc_8d25_dedd43876fff_dragorderfield returnvalue from ~TABLENAMEENTITY~ where ffe65a2521_c3d0_42fc_8d25_dedd43876fff_code = '~ENTITYCODE~'";
	$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
	$strSQL = str_replace('~ENTITYCODE~', ffeu($strEntityCode), $strSQL);
    $strDragorderfield = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
    
    $strSQL = "select ffe65a2521_c3d0_42fc_8d25_dedd43876fff_dragordergroupfield returnvalue from ~TABLENAMEENTITY~ where ffe65a2521_c3d0_42fc_8d25_dedd43876fff_code = '~ENTITYCODE~'";
	$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
	$strSQL = str_replace('~ENTITYCODE~', ffeu($strEntityCode), $strSQL);
	$strDragorderGroupfield = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
	
	if (strlen($strDragorderfield) > 0)
	{
		$strTableNameEntityReorder = getTableNameEntity(strtolower($strEntityCode), false);
		
		
		$strSQL = "select ~DRAGORDERFIELD~ returnvalue from ~TABLENAMEENTITYREORDER~ where id = ~ID~";
		$strSQL = str_replace('~TABLENAMEENTITYREORDER~', ff($strTableNameEntityReorder), $strSQL);
		$strSQL = str_replace('~DRAGORDERFIELD~', ff($strDragorderfield), $strSQL);
		$strSQL = str_replace('~ID~', ff($strDraggedID), $strSQL);
		$strDraggedSortorder = dbReadValue($objConn_a, $strSQL, __FUNCTION__);		
		
		$strSQL = "select ~DRAGORDERFIELD~ returnvalue from ~TABLENAMEENTITYREORDER~ where id = ~ID~";
		$strSQL = str_replace('~TABLENAMEENTITYREORDER~', ff($strTableNameEntityReorder), $strSQL);
		$strSQL = str_replace('~DRAGORDERFIELD~', ff($strDragorderfield), $strSQL);
		$strSQL = str_replace('~ID~', ff($strDraggedOnToID), $strSQL);
        $strDraggedOnToSortorder = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
        
        $strDragorderGroupfieldID = "";
        
        if (strlen($strDragorderGroupfield) > 0)
        {
            $strSQL = "select ~DRAGORDERGROUPFIELD~ returnvalue from ~TABLENAMEENTITYREORDER~ where id = ~ID~";
            $strSQL = str_replace('~TABLENAMEENTITYREORDER~', ff($strTableNameEntityReorder), $strSQL);
            $strSQL = str_replace('~DRAGORDERGROUPFIELD~', ff($strDragorderGroupfield), $strSQL);
            $strSQL = str_replace('~ID~', ff($strDraggedOnToID), $strSQL);
            $strDragorderGroupfieldID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
        }

		dbBeginTrans($objConn_a, __FUNCTION__);
				
        // update sortorder field that has value of null or empty
        if (strlen($strDragorderGroupfield) > 0)
        {
            $strSQL = "update ~TABLENAMEENTITYREORDER~ set ~DRAGORDERFIELD~ = id where ~DRAGORDERGROUPFIELD~ = ~DRAGORDERGROUPFIELDID~ and (~DRAGORDERFIELD~ is null or ~DRAGORDERFIELD~ = '' or ~DRAGORDERFIELD~ = 0)";
            $strSQL = str_replace('~TABLENAMEENTITYREORDER~', ff($strTableNameEntityReorder), $strSQL);
            $strSQL = str_replace('~DRAGORDERFIELD~', ff($strDragorderfield), $strSQL);
            $strSQL = str_replace('~DRAGORDERGROUPFIELD~', ff($strDragorderGroupfield), $strSQL);
            $strSQL = str_replace('~DRAGORDERGROUPFIELDID~', ff($strDragorderGroupfieldID), $strSQL);
        }
        else
        {
            $strSQL = "update ~TABLENAMEENTITYREORDER~ set ~DRAGORDERFIELD~ = id where ~DRAGORDERFIELD~ is null or ~DRAGORDERFIELD~ = '' or ~DRAGORDERFIELD~ = 0";
            $strSQL = str_replace('~TABLENAMEENTITYREORDER~', ff($strTableNameEntityReorder), $strSQL);
            $strSQL = str_replace('~DRAGORDERFIELD~', ff($strDragorderfield), $strSQL);
        }

		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		
		if (intval($strDraggedSortorder, 10) > intval($strDraggedOnToSortorder, 10))
		{
            // add 1 to all sort orders as we are moving an item upward, but negative to ensure uniqueness while bulkupdating
            if (strlen($strDragorderGroupfield) > 0)
            {
                $strSQL = "update ~TABLENAMEENTITYREORDER~ set ~DRAGORDERFIELD~ = -(~DRAGORDERFIELD~ + 1) where ~DRAGORDERGROUPFIELD~ = ~DRAGORDERGROUPFIELDID~ and ~DRAGORDERFIELD~ >= ~DRAGGEDONTOSORTORDER~ and ~DRAGORDERFIELD~ < ~DRAGGEDSORTORDER~";
                $strSQL = str_replace('~TABLENAMEENTITYREORDER~', ff($strTableNameEntityReorder), $strSQL);
                $strSQL = str_replace('~DRAGORDERFIELD~', ff($strDragorderfield), $strSQL);
                $strSQL = str_replace('~DRAGGEDSORTORDER~', ff($strDraggedSortorder), $strSQL);
                $strSQL = str_replace('~DRAGGEDONTOSORTORDER~', ff($strDraggedOnToSortorder), $strSQL);
                $strSQL = str_replace('~DRAGORDERGROUPFIELD~', ff($strDragorderGroupfield), $strSQL);
                $strSQL = str_replace('~DRAGORDERGROUPFIELDID~', ff($strDragorderGroupfieldID), $strSQL);
            }
            else
            {
                $strSQL = "update ~TABLENAMEENTITYREORDER~ set ~DRAGORDERFIELD~ = -(~DRAGORDERFIELD~ + 1) where ~DRAGORDERFIELD~ >= ~DRAGGEDONTOSORTORDER~ and ~DRAGORDERFIELD~ < ~DRAGGEDSORTORDER~";
                $strSQL = str_replace('~TABLENAMEENTITYREORDER~', ff($strTableNameEntityReorder), $strSQL);
                $strSQL = str_replace('~DRAGORDERFIELD~', ff($strDragorderfield), $strSQL);
                $strSQL = str_replace('~DRAGGEDSORTORDER~', ff($strDraggedSortorder), $strSQL);
                $strSQL = str_replace('~DRAGGEDONTOSORTORDER~', ff($strDraggedOnToSortorder), $strSQL);
            }

			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		}
		else
		{
            // subtract 1 to all sort orders as we are moving an item downward, but negative to ensure uniqueness while bulkupdating
            if (strlen($strDragorderGroupfield) > 0)
            {
                $strSQL = "update ~TABLENAMEENTITYREORDER~ set ~DRAGORDERFIELD~ = -(~DRAGORDERFIELD~ - 1) where ~DRAGORDERGROUPFIELD~ = ~DRAGORDERGROUPFIELDID~ and ~DRAGORDERFIELD~ > ~DRAGGEDSORTORDER~ and ~DRAGORDERFIELD~ <= ~DRAGGEDONTOSORTORDER~";
                $strSQL = str_replace('~TABLENAMEENTITYREORDER~', ff($strTableNameEntityReorder), $strSQL);
                $strSQL = str_replace('~DRAGORDERFIELD~', ff($strDragorderfield), $strSQL);
                $strSQL = str_replace('~DRAGGEDSORTORDER~', ff($strDraggedSortorder), $strSQL);
                $strSQL = str_replace('~DRAGGEDONTOSORTORDER~', ff($strDraggedOnToSortorder), $strSQL);
                $strSQL = str_replace('~DRAGORDERGROUPFIELD~', ff($strDragorderGroupfield), $strSQL);
                $strSQL = str_replace('~DRAGORDERGROUPFIELDID~', ff($strDragorderGroupfieldID), $strSQL);
            }
            else
            {
                $strSQL = "update ~TABLENAMEENTITYREORDER~ set ~DRAGORDERFIELD~ = -(~DRAGORDERFIELD~ - 1) where ~DRAGORDERFIELD~ > ~DRAGGEDSORTORDER~ and ~DRAGORDERFIELD~ <= ~DRAGGEDONTOSORTORDER~";
                $strSQL = str_replace('~TABLENAMEENTITYREORDER~', ff($strTableNameEntityReorder), $strSQL);
                $strSQL = str_replace('~DRAGORDERFIELD~', ff($strDragorderfield), $strSQL);
                $strSQL = str_replace('~DRAGGEDSORTORDER~', ff($strDraggedSortorder), $strSQL);
                $strSQL = str_replace('~DRAGGEDONTOSORTORDER~', ff($strDraggedOnToSortorder), $strSQL);
            }

			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		}
		
		// update our temporary unique sortorder to the real value we want
		$strSQL = "update ~TABLENAMEENTITYREORDER~ set ~DRAGORDERFIELD~ = ~DRAGGEDONTOSORTORDER~ where id = ~ID~";
		$strSQL = str_replace('~TABLENAMEENTITYREORDER~', ff($strTableNameEntityReorder), $strSQL);
		$strSQL = str_replace('~DRAGORDERFIELD~', ff($strDragorderfield), $strSQL);
		$strSQL = str_replace('~DRAGGEDONTOSORTORDER~', ff($strDraggedOnToSortorder), $strSQL);
		$strSQL = str_replace('~ID~', ff($strDraggedID), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		
        // make any negative sortorders positive again
        if (strlen($strDragorderGroupfield) > 0)
        {
            $strSQL = "update ~TABLENAMEENTITYREORDER~ set ~DRAGORDERFIELD~ = -~DRAGORDERFIELD~ where ~DRAGORDERGROUPFIELD~ = ~DRAGORDERGROUPFIELDID~ and ~DRAGORDERFIELD~ < 0";
            $strSQL = str_replace('~TABLENAMEENTITYREORDER~', ff($strTableNameEntityReorder), $strSQL);
            $strSQL = str_replace('~DRAGORDERFIELD~', ff($strDragorderfield), $strSQL);
            $strSQL = str_replace('~ID~', ff($strDraggedID), $strSQL);
            $strSQL = str_replace('~DRAGORDERGROUPFIELD~', ff($strDragorderGroupfield), $strSQL);
            $strSQL = str_replace('~DRAGORDERGROUPFIELDID~', ff($strDragorderGroupfieldID), $strSQL);
        }
        else
        {
            $strSQL = "update ~TABLENAMEENTITYREORDER~ set ~DRAGORDERFIELD~ = -~DRAGORDERFIELD~ where ~DRAGORDERFIELD~ < 0";
            $strSQL = str_replace('~TABLENAMEENTITYREORDER~', ff($strTableNameEntityReorder), $strSQL);
            $strSQL = str_replace('~DRAGORDERFIELD~', ff($strDragorderfield), $strSQL);
            $strSQL = str_replace('~ID~', ff($strDraggedID), $strSQL);
        }

		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		
		dbEndTrans($objConn_a, __FUNCTION__);				
	}
	
	$strResult = createJSONResponse($strDataID_a, RESPONSE_OK, $strDescription, []);
          
    return $strResult;
}
