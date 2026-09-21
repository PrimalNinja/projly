<?php

function processClientProductExpired($objConn_a) 
{
    if (dependencies('batch/processes/personGroupAddPersonAndEMail,batch/processes/personGroupCreate') &&
		dependencies('security/clientProductDeactivate,security/profilesInitialise')) 
	{
        $strTableNameClient = getTableNameEntity("client", false);
        $strTableNameClientProduct = getTableNameEntity("clientproduct", false);
		$strTableNamePerson = getTableNameEntity("person", false);
		$strTableNamePersonExtension = getTableNameEntityExtension("person");
        $strTableNameProduct = getTableNameEntity("product", false);

        // initialisations
        $strClientID = getSystemOwnerClientID($objConn_a); 
        
        $strSQL = "select id, client_id, product_id, ff252aa5f0_e8df_4c52_a5c4_de677e1edf85_expirydate expirydate, emailrenewaldate, emailexpireddate
					from ~TABLENAMECLIENTPRODUCT~ where 
					ff252aa5f0_e8df_4c52_a5c4_de677e1edf85_expirydate is not null and 
					ff252aa5f0_e8df_4c52_a5c4_de677e1edf85_expirydate <> '' and 
					is_enabled = 'Y' and
					curdate() >= ff252aa5f0_e8df_4c52_a5c4_de677e1edf85_expirydate
					order by id";
        $strSQL = str_replace('~TABLENAMECLIENTPRODUCT~', ff($strTableNameClientProduct), $strSQL);
		$objResultExpiredProducts = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
        
		while ($arrRowExpiredProduct = dbReadRecord($objResultExpiredProducts)) 
		{
			$strClientProductID = $arrRowExpiredProduct['id'];
			$strToClientID = $arrRowExpiredProduct['client_id'];
			$strPersonID = getPersonIDByClientIDViaAccount($objConn_a, $strToClientID);
			$strProductID = $arrRowExpiredProduct['product_id'];
			$strExpiryDate = $arrRowExpiredProduct['expirydate'];

			$strSQL = "select pe.befb634c_50f2_405a_b0a4_eb7461ec4780_donotcontact returnvalue 
						from ~TABLENAMEPERSON~ p, ~TABLENAMEPERSONEXTENSION~ pe
						where pe.id = p.id and p.is_archived = 'N' and p.id = ~PERSONID~";
			$strSQL = str_replace('~TABLENAMEPERSON~', ff($strTableNamePerson), $strSQL);
			$strSQL = str_replace('~TABLENAMEPERSONEXTENSION~', ff($strTableNamePersonExtension), $strSQL);
			$strSQL = str_replace('~PERSONID~', ff($strPersonID), $strSQL);
			$strDoNotContact = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
			
			if (toBoolean($strDoNotContact))
			{
				// deactivate the product
				clientProductDeactivate($objConn_a, $strClientProductID);
			}
			else
			{
				$strSQL = "select pe.befb634c_50f2_405a_b0a4_eb7461ec4780_emailaddress returnvalue 
							from ~TABLENAMEPERSON~ p, ~TABLENAMEPERSONEXTENSION~ pe
							where pe.id = p.id and p.id = ~PERSONID~";
				$strSQL = str_replace('~TABLENAMEPERSON~', ff($strTableNamePerson), $strSQL);
				$strSQL = str_replace('~TABLENAMEPERSONEXTENSION~', ff($strTableNamePersonExtension), $strSQL);
				$strSQL = str_replace('~PERSONID~', ff($strPersonID), $strSQL);
				$strEmailAddress = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
				
				$strSQL = "select code, description, gffe35a8ad_d290_4ae3_8800_f2935dd30d07_renewalgroup renewalgroup, gffe35a8ad_d290_4ae3_8800_f2935dd30d07_expirygroup expirygroup from ~TABLENAMEPRODUCT~ where id = ~PRODUCTID~";
				$strSQL = str_replace('~TABLENAMEPRODUCT~', ff($strTableNameProduct), $strSQL);
				$strSQL = str_replace('~PRODUCTID~', ff($strProductID), $strSQL);
				$objResultProduct = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

				if ($arrRowProduct = dbReadRecord($objResultProduct)) 
				{
					$strProductCode = $arrRowProduct['code'];
					$strProductDescription = $arrRowProduct['description'];
					$strProductRenewalGroup = $arrRowProduct['renewalgroup'];
					$strProductExpiryGroup = $arrRowProduct['expirygroup'];

					$strGroupCode = dateTimeToYM(getISODate()) . '_' . $strProductExpiryGroup;
					$strGroupName = str_replace('_', ' ', $strGroupCode);

					$strPersonGroupID = personGroupCreate($objConn_a, $strClientID, $strEmailAddress, $strGroupCode, $strGroupName);
					if (strlen($strPersonGroupID) > 0)
					{
						personGroupAddPersonAndEMail($objConn_a, $strClientID, $strPersonGroupID, $strToClientID, $strPersonID, $strEmailAddress, false, true, $strProductExpiryGroup, 'clientproduct', $strClientProductID, $strProductCode, $strProductDescription, $strExpiryDate);
					}

					// deactivate the product
					clientProductDeactivate($objConn_a, $strClientProductID);
					
					// uninstall product's profile
					profilesInitialise($objConn_a, $strToClientID);
				}
				dbCloseRecordset($objResultProduct);
			}
		}
		dbCloseRecordset($objResultExpiredProducts);
    }
}
