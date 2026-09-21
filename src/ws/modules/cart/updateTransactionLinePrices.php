<?php

function updateTransactionLinePrices($objConn_a, $strClientID_a) 
{
	$strTableNamePaymentStatus = getTableNameEntity("paymentstatus", false);
    $strTableNameProduct = getTableNameEntity("product", false);
    $strTableNameTransaction = getTableNameEntity("transaction", false);
	$strTableNameTransactionHistory = getTableNameEntity("transactionhistory", false);
    $strTableNameTransactionLine = getTableNameEntity("transactionline", false);
	$strTableNameTransactionLineHistory = getTableNameEntity("transactionlinehistory", false);
	$strTableNameTransactionLinePending = getTableNameEntity("transactionlinepending", false);
	$strTableNameTransactionPending = getTableNameEntity("transactionpending", false);
    
    $strSQL = "
select tl.id transactionline_id, tl.jsondata, p.gffe35a8ad_d290_4ae3_8800_f2935dd30d07_graceperiod graceperiod, tl.product_id, tl.gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_applicanttype applicanttype, tl.applicant_id
from ~TABLENAMETRANSACTION~ t, ~TABLENAMETRANSACTIONLINE~ tl, ~TABLENAMEPRODUCT~ p
where t.client_id = ~CLIENTID~ and tl.transaction_id = t.id and tl.product_id = p.id
";
    $strSQL = str_replace('~TABLENAMETRANSACTION~', ff($strTableNameTransaction), $strSQL);
    $strSQL = str_replace('~TABLENAMETRANSACTIONLINE~', ff($strTableNameTransactionLine), $strSQL);
    $strSQL = str_replace('~TABLENAMEPRODUCT~', ff($strTableNameProduct), $strSQL);
    $strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
    
    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
    
    $arrTimePeriod = array('d' => 'day', 'm' => 'month', 'y' => 'year');
	$strSQL = "select id returnvalue from ~TABLENAMEPAYMENTSTATUS~ where code = 'PAID'";
	$strSQL = str_replace('~TABLENAMEPAYMENTSTATUS~', ff($strTableNamePaymentStatus), $strSQL);
	$strPaidID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
        
    while ($arrRow = dbReadRecord($objResult)) 
    {
        $strTransactionLineID = $arrRow['transactionline_id'];
		$strProductID = $arrRow['product_id'];
		$strApplicantID = $arrRow['applicant_id'];
        $strApplicantType = $arrRow['applicanttype'];
        $strGracePeriod = $arrRow['graceperiod'];
        $strJSONData = $arrRow['jsondata'];
		$strInGracePeriod = 'N';

        $arrJSONData = json_decode($strJSONData, true);

        if (strlen($strGracePeriod) > 0)
        {   
			$strSQL = "";
			if ($strApplicantType == 'CLIENT')
			{
				$strSQL = "
	select max(applicationdate) returnvalue from (
		select t.applicationdate from ~TABLENAMETRANSACTIONHISTORY~ t, ~TABLENAMETRANSACTIONLINEHISTORY~ tl 
		where t.client_id = ~CLIENTID~ and tl.transactionhistory_id = t.id and tl.gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_applicanttype = 'CLIENT' and tl.applicant_id = ~APPLICANTID~ and tl.product_id = ~PRODUCTID~
		union
		select t.applicationdate from ~TABLENAMETRANSACTIONPENDING~ t, ~TABLENAMETRANSACTIONLINEPENDING~ tl 
		where t.client_id = ~CLIENTID~ and tl.transactionpending_id = t.id and tl.gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_applicanttype = 'CLIENT' and tl.applicant_id = ~APPLICANTID~ and tl.product_id = ~PRODUCTID~ and t.paymentstatus_id = ~PAIDID~
	) temp
	";
			}
			else
			{
				// force no application date for non-client / non-person
				$strSQL = "select '' returnvalue from ~TABLENAMETRANSACTIONHISTORY~ where 1=0";
			}
			$strSQL = str_replace('~TABLENAMETRANSACTIONPENDING~', ff($strTableNameTransactionPending), $strSQL);
			$strSQL = str_replace('~TABLENAMETRANSACTIONHISTORY~', ff($strTableNameTransactionHistory), $strSQL);
			$strSQL = str_replace('~TABLENAMETRANSACTIONLINEHISTORY~', ff($strTableNameTransactionLineHistory), $strSQL);
			$strSQL = str_replace('~TABLENAMETRANSACTIONLINEPENDING~', ff($strTableNameTransactionLinePending), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
			$strSQL = str_replace('~PRODUCTID~', ff($strProductID), $strSQL);
			$strSQL = str_replace('~APPLICANTID~', ff($strApplicantID), $strSQL);
			$strSQL = str_replace('~PAIDID~', ff($strPaidID), $strSQL);
			$strApplicationDate = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		
			if (strlen($strApplicationDate) > 0)
			{ 
				$strNumber = $strGracePeriod;
				$strNumber = str_replace("d", "", $strNumber);
				$strNumber = str_replace("m", "", $strNumber);
				$strNumber = str_replace("y", "", $strNumber);
				$strDMY = str_replace($strNumber, "", $strGracePeriod);

				$strTimePeriod = $arrTimePeriod[$strDMY];

				$strGracePeriodTimeStamp = strtotime($strApplicationDate . " + $strNumber $strTimePeriod");

				if ($strGracePeriodTimeStamp > time())
				{                     
					$strInGracePeriod = 'Y';
				}
			}
        }
        
        if (toBoolean($strInGracePeriod))
        {
            $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "gd6f57b36-a2bb-47b9-a6ed-7df0335fc0c2", "PRICEEXGST", 0);
            $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "gd6f57b36-a2bb-47b9-a6ed-7df0335fc0c2", "PRICEINCGST", 0);
            
            $strJSONData = json_encode($arrJSONData);
            
            dbBeginTrans($objConn_a, __FUNCTION__);
            
            $strSQL = "update ~TABLENAMETRANSACTIONLINE~ set jsondata = '~JSONDATA~' where id = ~TRANSACTIONLINEID~";
            $strSQL = str_replace('~TABLENAMETRANSACTIONLINE~', ff($strTableNameTransactionLine), $strSQL);
            $strSQL = str_replace('~TRANSACTIONLINEID~', ff($strTransactionLineID), $strSQL);
            $strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
            dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
            
            exposeEntityData($objConn_a, 'SYSTEMFORM', 'TRANSACTIONLINE', $strTransactionLineID, $strJSONData);
            
            dbEndTrans($objConn_a, __FUNCTION__);
        }                   
    }
    
    dbCloseRecordset($objResult);
    
    return true;
}
