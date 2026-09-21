<?php

// $strFormDataID_a = ENTITY_RELATIONSIHP
// $strRelativeID_a = ENTITY

// code in before display add events:
//		default fields within the JSON for display purposes before a user starts filling in a form
//
// code in after events: 
//		exposing fields
//		populating manually created fields
//
// code in before events: 
// 		modifying the json that is to be stored (it is stored automatically)
//		validation such as uniqueness (don't forget to put unique indexes on field combinations you need to be unique)
//
// event order:  
//		before events, saving of json and common fields, after events, transfer of exposed field's values
//
function beforeDisplayAddUpdate_entity_relationship($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $blnUpdate_a, $strMode_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function beforeAddUpdate_entity_relationship($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
		
    if ($blnUpdate_a == false)
    {
        $strFK = createFKName();

        $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "gdc4a5462-7953-4be9-8fc6-38d46bcef202", "FKNAME", $strFK);
	}
	
	return $arrJSONData;
}

function afterAddUpdate_entity_relationship($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
		
    $strTableNameEntity = getTableNameEntity("entity", false);
    $strTableNameEntityRelationship = getTableNameEntity("entity_relationship", false);

    if ($blnUpdate_a == false)
    {
        $strSQL = "update ~TABLENAMEENTITYRELATIONSHIP~ set relatedentity_id = ~RELATEDENTITYID~ where id = ~ID~";
        $strSQL = str_replace('~TABLENAMEENTITYRELATIONSHIP~', ff($strTableNameEntityRelationship), $strSQL);
        $strSQL = str_replace('~ID~', ff($strFormDataID_a), $strSQL);
		$strSQL = str_replace('~RELATEDENTITYID~', ff($strRelativeID_a), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

        // adding foreign key field to relationship table
        
        $arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData, "gdc4a5462-7953-4be9-8fc6-38d46bcef202", "RELATIONSHIPENTITY");
        $strRelationshipEntityID = $arrJSONField['p_value'];

		$strFieldName = formValueGetBySectionCodeFieldCode($arrJSONData, "gdc4a5462-7953-4be9-8fc6-38d46bcef202", "FIELDNAME");
		$strFK = formValueGetBySectionCodeFieldCode($arrJSONData, "gdc4a5462-7953-4be9-8fc6-38d46bcef202", "FKNAME");

        $strSQL = "select code returnvalue from ~TABLENAMEENTITY~ where id = ~RELATIONSIHPENTITYID~";
        $strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
        $strSQL = str_replace('~RELATIONSIHPENTITYID~', ff($strRelationshipEntityID), $strSQL);
        $strRelationshipEntityCode = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
        
        $strSQL = "select code returnvalue from ~TABLENAMEENTITY~ where id = ~ENTITYID~";
        $strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
        $strSQL = str_replace('~ENTITYID~', ff($strRelativeID_a), $strSQL);
        $strRelatedEntityCode = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

        $strTableNameRelationshipEntity = getTableNameEntity(strtolower($strRelationshipEntityCode), false);
        $strTableNameRelatedEntity = getTableNameEntity(strtolower($strRelatedEntityCode), false);

		$strRelationshipEntityField = strtolower($strRelationshipEntityCode) . '_id';
		if (strlen($strFieldName) > 0)
		{
			$strRelationshipEntityField = strtolower($strFieldName);
		}
        
        // add field and foreign key
        
        $strSQL = "alter table ~TABLENAMERELATEDENTITY~ 
                    add column `~RELATIONSHIPENTITYFIELD~` bigint(20) null";
        $strSQL = str_replace('~TABLENAMERELATIONSHIPENTITY~', ff($strTableNameRelationshipEntity), $strSQL);
        $strSQL = str_replace('~TABLENAMERELATEDENTITY~', ff($strTableNameRelatedEntity), $strSQL);
        $strSQL = str_replace('~RELATIONSHIPENTITYFIELD~', ff($strRelationshipEntityField), $strSQL);
        dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

        $strSQL = "alter table ~TABLENAMERELATEDENTITY~ 
                    add constraint ~FK~ foreign key (`~RELATIONSHIPENTITYFIELD~`) references `~TABLENAMERELATIONSHIPENTITY~` (`id`)";
        $strSQL = str_replace('~TABLENAMERELATIONSHIPENTITY~', ff($strTableNameRelationshipEntity), $strSQL);
        $strSQL = str_replace('~TABLENAMERELATEDENTITY~', ff($strTableNameRelatedEntity), $strSQL);
        $strSQL = str_replace('~RELATIONSHIPENTITYFIELD~', ff($strRelationshipEntityField), $strSQL);
        $strSQL = str_replace('~FK~', ff($strFK), $strSQL);
        dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);        
    }

	return $arrJSONData;
}

// ***IMPORTANT*** PUT ALL FUNCTIONALITY THAT RELIES ON THE EXPOSED FIELDS IN HERE!!!
function afterAddUpdateExpose_entity_relationship($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function beforeDelete_entity_relationship($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
    $strTableNameEntity = getTableNameEntity("entity", false);
    $strTableNameEntityRelationship = getTableNameEntity("entity_relationship", false);

    $strSQL = "select relationshipentity_id, relatedentity_id, gdc4a5462_7953_4be9_8fc6_38d46bcef202_fkname fkname, gdc4a5462_7953_4be9_8fc6_38d46bcef202_fieldname fieldname from ~TABLENAMERELATIONSHIPENTITY~ where id = ~ID~";
    $strSQL = str_replace('~TABLENAMERELATIONSHIPENTITY~', ff($strTableNameEntityRelationship), $strSQL);
    $strSQL = str_replace('~ID~', ff($strFormDataID_a), $strSQL);
    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

    if ($arrRow = dbReadRecord($objResult))
    { 
        $strRelationshipEntityID = $arrRow['relationshipentity_id'];
        $strRelatedEntityID = $arrRow['relatedentity_id'];
		$strFieldName = $arrRow['fieldname'];
        $strFK = $arrRow['fkname'];
        
        $strSQL = "select code returnvalue from ~TABLENAMEENTITY~ where id = ~RELATIONSHIPENTITYID~";
        $strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
        $strSQL = str_replace('~RELATIONSHIPENTITYID~', ff($strRelationshipEntityID), $strSQL);
        $strRelationshipEntityCode = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

        $strSQL = "select code returnvalue from ~TABLENAMEENTITY~ where id = ~RELATEDENTITYID~";
        $strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
        $strSQL = str_replace('~RELATEDENTITYID~', ff($strRelatedEntityID), $strSQL);
        $strRelatedEntityCode = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

        $strTableNameRelatedEntity = getTableNameEntity(strtolower($strRelatedEntityCode), false);

        $strRelationshipEntityField = strtolower($strRelationshipEntityCode) . '_id';
		if (strlen($strFieldName) > 0)
		{
			$strRelationshipEntityField = strtolower($strFieldName);
		}
        
        $strSQL = "alter table `~TABLENAMERELATEDENTITY~` 
                    drop foreign KEY `~FK~`,
                    drop column `~FIELD~`,
                    drop index `~FK~`
                    ";
                    
        $strSQL = str_replace('~TABLENAMERELATEDENTITY~', ff($strTableNameRelatedEntity), $strSQL);
        $strSQL = str_replace('~FIELD~', ff($strRelationshipEntityField), $strSQL);        
        $strSQL = str_replace('~FK~', ff($strFK), $strSQL);        
        dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);                
    }

    dbCloseRecordset($objResult);
}

function afterDelete_entity_relationship($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{       
}

// called before fetching, useful if jsondata is null to dynamically create it from the fields
function beforeSelect_entity_relationship($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a)
{
}
