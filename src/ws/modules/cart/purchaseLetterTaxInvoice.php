<?php

function purchaseLetterTaxInvoice($objConn_a, $strClientID_a, $strTransactionID_a)
{
	$strResult = "";
    
    if (dependencies('setting/settingGet'))
    {
        $strTableNamePaymentStatus = getTableNameEntity("paymentstatus", false);
        $strTableNameProduct = getTableNameEntity("product", false);
        $strTableNameSKUDiscountRecipient = getTableNameEntity("skudiscount_recipient", false);
        $strTableNameSKUDiscount = getTableNameEntity("skudiscount", false);
        $strTableNameTransaction = getTableNameEntity('transaction', false);
        $strTableNameTransactionHistory = getTableNameEntity("transactionhistory", false);
        $strTableNameTransactionLine = getTableNameEntity('transactionline', false);
        $strTableNameTransactionLineHistory = getTableNameEntity("transactionlinehistory", false);
        $strTableNameTransactionLinePending = getTableNameEntity("transactionlinepending", false);
        $strTableNameTransactionPending = getTableNameEntity("transactionpending", false);
        $strTableNameAccount = getTableNameEntity('account', false);
        $strTableNamePaymentmethod = getTableNameEntity('paymentmethod', false);

        $strTransactionID = $strTransactionID_a;
        $strClientID = $strClientID_a;
        $strSystemOwnerClientID = getSystemOwnerClientID($objConn_a);

        $blnInCart = false; //toBoolean($strInCart);

        $arrTransaction = array();

		$strBusinessName = settingGet($objConn_a, 'CORE', 'BNAME', $strSystemOwnerClientID, '', '', '', __FUNCTION__);
		$strBusinessNumber = settingGet($objConn_a, 'CORE', 'BNUM', $strSystemOwnerClientID, '', '', '', __FUNCTION__);
		$strBusinessPhone = settingGet($objConn_a, 'CORE', 'BPHONE', $strSystemOwnerClientID, '', '', '', __FUNCTION__);
		$strBusinessAddress = settingGet($objConn_a, 'CORE', 'BADDR', $strSystemOwnerClientID, '', '', '', __FUNCTION__);

        $strSuburb = '';
        $strState = '';
        $strPostcode = '';

        $arrBusinessData = array(
            'name' => $strBusinessName,
            'abn' => $strBusinessNumber,
            'address' => $strBusinessAddress,
            'suburb' => $strSuburb,
            'state' => $strState,
            'postcode' => $strPostcode
        );

        $arrTransaction['businessdata'] = $arrBusinessData;
        $arrTransaction['invoicedate'] = getISODate();

        // fetch transaction (cart)

        $strSQL = "select id returnvalue from ~TABLENAMEPRODUCT~ where code = 'SKUDELIVERY'";
        $strSQL = str_replace('~TABLENAMEPRODUCT~', ff($strTableNameProduct), $strSQL);        
        $strProductSKUDeliveryID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

        $strSQL = "select id returnvalue from ~TABLENAMEPAYMENTSTATUS~ where code = 'PAID'";
        $strSQL = str_replace('~TABLENAMEPAYMENTSTATUS~', ff($strTableNamePaymentStatus), $strSQL);
        $strPaidID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
        $strPayerName = '';
        $strPayerAddressLine1 = '';
        $strPayerAddressLine2 = '';
        $strPayerSuburb = '';
        $strPayerState = '';
        $strPayerPostcode = '';
        $strPayerNotes = '';

        $strSQL = "select 
            paymentstatus_id, 
            paymentmethod_id, 
            g31f68ccc_e168_40b8_a7db_693353aa7aea_payername payername,
            g31f68ccc_e168_40b8_a7db_693353aa7aea_payeraddressline1 payeraddressline1,
            g31f68ccc_e168_40b8_a7db_693353aa7aea_payeraddressline2 payeraddressline2,
            g31f68ccc_e168_40b8_a7db_693353aa7aea_payersuburb payersuburb,
            g31f68ccc_e168_40b8_a7db_693353aa7aea_payerstate payerstate,
            g31f68ccc_e168_40b8_a7db_693353aa7aea_payerpostcode payerpostcode,
            g31f68ccc_e168_40b8_a7db_693353aa7aea_payernotes payernotes,
            g31f68ccc_e168_40b8_a7db_693353aa7aea_paymentdate paymentdate, 
            g31f68ccc_e168_40b8_a7db_693353aa7aea_receiptnumber receiptnumber, 
            g31f68ccc_e168_40b8_a7db_693353aa7aea_is_paid is_paid, 
            document_id, 
            progress 
            from ~TABLETRANSACTION~ 
            where id = ~TRANSACTIONID~";

        if ($blnInCart)
        {
            $strSQL = str_replace('~TABLETRANSACTION~', ff($strTableNameTransaction), $strSQL);
        }
        else
        {
            $strSQL = str_replace('~TABLETRANSACTION~', ff($strTableNameTransactionPending), $strSQL);
        }

        $strSQL = str_replace('~TRANSACTIONID~', ff($strTransactionID), $strSQL);

        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

        if ($arrRow = dbReadRecord($objResult)) 
        {
            $arrTransaction['receiptnumber'] = $arrRow['receiptnumber'];
            $arrTransaction['is_paid'] = $arrRow['paymentstatus_id'] == $strPaidID;

            $strPayerName = $arrRow['payername'];
            $strPayerAddressLine1 = $arrRow['payeraddressline1'];
            $strPayerAddressLine2 = $arrRow['payeraddressline2'];
            $strPayerSuburb = $arrRow['payersuburb'];
            $strPayerState = $arrRow['payerstate'];
            $strPayerPostcode = $arrRow['payerpostcode'];
            $strPayerNotes = $arrRow['payernotes'];
            //$arrTransaction['invoicedate'] = $arrRow['paymentdate'];
        }

        dbCloseRecordset($objResult);

        $arrPayerData = array(
            'payername' => $strPayerName,
            'addressline1' => $strPayerAddressLine1,
            'addressline2' => $strPayerAddressLine2,
            'suburb' => $strPayerSuburb,
            'state' => $strPayerState,
            'postcode' => $strPayerPostcode,
            'notes' => $strPayerNotes
        );

        $arrTransaction['payerdata'] = $arrPayerData;

        // fetch transactionline (cartline)
        $strSQL = "select tl.product_id, tl.gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_applicanttype applicanttype, tl.applicant_id, tl.gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_description description, tl.gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_product product, 
                    tl.gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_priceincgst price_incgst, tl.gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_priceexgst price_exgst, tl.gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_transactionstatus transactionstatus,
                    p.gffe35a8ad_d290_4ae3_8800_f2935dd30d07_graceperiod graceperiod 
                    from ~TABLENAMETRANSACTIONLINE~ tl, ~TABLENAMEPRODUCT~ p 
                    where p.id = tl.product_id and tl.transaction_id = ~TRANSACTIONID~";
        $strSQL = str_replace('~TABLENAMEPRODUCT~', ff($strTableNameProduct), $strSQL);
        if ($blnInCart)
        {
            $strSQL = str_replace('~TABLENAMETRANSACTIONLINE~', ff($strTableNameTransactionLine), $strSQL);        
        }
        else
        {                    
            $strSQL = str_replace('~TABLENAMETRANSACTIONLINE~', ff($strTableNameTransactionLinePending), $strSQL);        
        }
        $strSQL = str_replace('~TRANSACTIONID~', ff($strTransactionID), $strSQL);

        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

        $arrTimePeriod = array('d' => 'day', 'm' => 'month', 'y' => 'year');

        $arrTransactionLines = array();

        while ($arrRow = dbReadRecord($objResult)) { 

            $strGracePeriod = $arrRow['graceperiod'];
            $strProductID = $arrRow['product_id'];
            $strApplicantID = $arrRow['applicant_id'];
            $strApplicantType = $arrRow['applicanttype'];
            $strInGracePeriod = 'N';
            $strIsPostage = 'N';

            if ($strProductID === $strProductSKUDeliveryID)
            {
                $strIsPostage = 'Y';
            }

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
                    // force no application date for non-client
                    $strSQL = "select '' returnvalue from ~TABLENAMETRANSACTIONHISTORY~ where 1=0";
                }
                $strSQL = str_replace('~TABLENAMETRANSACTIONPENDING~', ff($strTableNameTransactionPending), $strSQL);
                $strSQL = str_replace('~TABLENAMETRANSACTIONHISTORY~', ff($strTableNameTransactionHistory), $strSQL);
                $strSQL = str_replace('~TABLENAMETRANSACTIONLINEHISTORY~', ff($strTableNameTransactionLineHistory), $strSQL);
                $strSQL = str_replace('~TABLENAMETRANSACTIONLINEPENDING~', ff($strTableNameTransactionLinePending), $strSQL);
                $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
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

            $floatPriceIncGST = $arrRow['price_incgst'];
            $floatPriceExGST = $arrRow['price_exgst'];

            $arrTransactionLines[] = array(
                'description'  => $arrRow['description'],
                'transactionstatus' => $arrRow['transactionstatus'],
                'ispostage' => $strIsPostage,
                'price_incgst' => $floatPriceIncGST,
                'price_exgst'  => $floatPriceExGST,
                'ingraceperiod' => $strInGracePeriod
            );
        }

        dbCloseRecordset($objResult);

        // for discounts
        $arrDiscountLines = array();

        // fetch discount ONLY if it's in transactionpending (cart already commited)
        if (!$blnInCart)
        {
            $strSQL  = "select sum(dr.ge19434a0_aca0_4d40_8b55_afb39c9d3116_prediscountpriceincgst) prediscountpriceincgst, sum(ge19434a0_aca0_4d40_8b55_afb39c9d3116_discountpriceincgst) discountpriceincgst, d.gff781b96_52b1_4e6d_92c1_00a6c1d6ffb4_discountcode discountcode ";
            $strSQL .= "from ~TABLENAMESKUDISCOUNTRECIPIENT~ dr, ~TABLENAMESKUDISCOUNT~ d, ~TABLENAMETRANSACTIONLINEPENDING~ tl ";
            $strSQL .= "where tl.id=dr.transactionline_id and d.id=dr.skudiscount_id and tl.transaction_id= ~TRANSACTIONID~ ";
            $strSQL .= "group by d.gff781b96_52b1_4e6d_92c1_00a6c1d6ffb4_discountcode order by d.gff781b96_52b1_4e6d_92c1_00a6c1d6ffb4_discountcode";
            $strSQL = str_replace('~TABLENAMESKUDISCOUNTRECIPIENT~', ff($strTableNameSKUDiscountRecipient), $strSQL);
            $strSQL = str_replace('~TABLENAMESKUDISCOUNT~', ff($strTableNameSKUDiscount), $strSQL);
            $strSQL = str_replace('~TABLENAMETRANSACTIONLINEPENDING~', ff($strTableNameTransactionLinePending), $strSQL);
            $strSQL = str_replace('~TRANSACTIONID~', ff($strTransactionID), $strSQL);

            $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

            while ($arrRow = dbReadRecord($objResult))
            {
                $strDiscountCode = $arrRow['discountcode'];
                $strDiscountIncGST = floatval($arrRow['prediscountpriceincgst']) - floatval($arrRow['discountpriceincgst']);

                if ($strDiscountIncGST > 0)
                {
                    $arrDiscountLines[] = array(
                        'discountcode' => $strDiscountCode,
                        'discountincgst' => $strDiscountIncGST
                    );
                }
            }

            dbCloseRecordset($objResult);
        }

        $arrTransaction['transactionlines'] = $arrTransactionLines;
        $arrTransaction['discountlines'] = $arrDiscountLines;

        $strSQL = "select * from ~TABLEENTITYACCOUNT~ where client_id = ~CLIENTID~";
        $strSQL = str_replace('~TABLEENTITYACCOUNT~', ff($strTableNameAccount), $strSQL);
        $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);

        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

        if ($arrRow = dbReadRecord($objResult)) 
        {
            $arrClientData = array(
                'ownerclient'   => ($strSystemOwnerClientID == $strClientID),
                'clientname'   => $arrRow['ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_accountname'],
                'businessname' => $arrRow['ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_businessname'],
                'abn'          => $arrRow['ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_abn'],
                'address1'     => $arrRow['ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_billingaddressline1'],
                'address2'     => $arrRow['ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_billingaddressline2'],
                'suburb'       => $arrRow['ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_billingsuburb'],
                'state'        => $arrRow['ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_billingstate'],
                'postcode'     => $arrRow['ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_billingpostcode']
            );
        }

        dbCloseRecordset($objResult);

        $arrTransaction['clientdata'] = $arrClientData;

        $arrBusinessData = $arrTransaction['businessdata'];
        $arrClientData = $arrTransaction['clientdata'];
        $arrPayerData = $arrTransaction['payerdata'];
        $arrTransactionLines = $arrTransaction['transactionlines'];
        $arrDiscountLines = $arrTransaction['discountlines'];

        $fltPriceIncGST = 0;
        $fltPriceExGST = 0;
        $fltPriceShipping = 0;

        $strInvoiceDate = getDateReportOut($arrTransaction['invoicedate']);
        $strReceiptNumber = $arrTransaction['receiptnumber'];

        $blnIsPaid = toBoolean($arrTransaction['is_paid']);

        $strPurchaItemsContent = "";

        foreach ($arrTransactionLines as $arrItemLine) {

            $fltShippingGST = 0;
            $fltShippingIncGST = 0;
            $fltShippingExGST = 0;

            if (!toBoolean($arrItemLine['ispostage']))
            {
                $strPurchaItemsContent .= "<tr>";
                $strPurchaItemsContent .= "<td class=\"col1\">1</td>";
                $strPurchaItemsContent .= "<td class=\"col2\">". htmlspecialchars($arrItemLine['description']) . "</td>";
                $strPurchaItemsContent .= "<td class=\"colstatus\">". htmlspecialchars($arrItemLine['transactionstatus']) . "</td>";

                if ((toBoolean($arrItemLine['ingraceperiod'])) && ($arrItemLine['price_incgst'] == 0))
                { 
                    $strPurchaItemsContent .= "<td class=\"col3\">". formatMoney($arrItemLine['price_incgst']) . " (Grace)</td>";                
                }   
                else
                { 
                    $strPurchaItemsContent .= "<td class=\"col3\">". formatMoney($arrItemLine['price_incgst']) . "</td>";
                }            
                $strPurchaItemsContent .= "</tr>";

                $fltPriceIncGST += floatval($arrItemLine['price_incgst']);
                $fltPriceExGST  += floatval($arrItemLine['price_exgst']);
            }
            else
            {                
                $fltShippingIncGST = floatval($arrItemLine['price_incgst']);                
                $fltShippingExGST  = floatval($arrItemLine['price_exgst']);
                $fltShippingGST   += $fltShippingIncGST - $fltShippingExGST;

                $fltPriceShipping += $fltShippingIncGST;
            }
        }

        $strDiscountItemsContent = "";

        if (count($arrDiscountLines) > 0)
        {
            foreach ($arrDiscountLines as $arrDiscountItemLine)
            {
                $strDiscountItemsContent .= "<tr>";
                $strDiscountItemsContent .= "<td class=\"col1\">".$arrDiscountItemLine['discountcode']."</td>";
                $strDiscountItemsContent .= "<td class=\"col2\">".formatMoney($arrDiscountItemLine['discountincgst'])."</td>";
                $strDiscountItemsContent .= "</tr>";
            }
        }


        $fltGSTTotal = $fltPriceIncGST - $fltPriceExGST;
        $fltSubTotal = $fltPriceIncGST; //$fltPriceExGST;

        $fltGSTTotal += $fltShippingGST;

        $fltTotal = $fltSubTotal + $fltPriceShipping; //$fltGSTTotal + $fltSubTotal + $fltPriceShipping;

        $strGSTTotal = formatMoney($fltGSTTotal);
        $strSubTotal = formatMoney($fltSubTotal);
        $strTotalShipping = formatMoney($fltPriceShipping);
        $strTotal = formatMoney($fltTotal);

        if ($fltPriceShipping > 0)
        {
            $strTotalShipping = formatMoney($fltPriceShipping);
        }
        else
        {
            $strTotalShipping = 'FREE';
        }
        
        $strReceiptLabel = "RECEIPT";

        if (strlen($arrBusinessData['abn']) > 0 ) {
            $strReceiptLabel = "TAX INVOICE";
        }

        if ($blnIsPaid)
        {
            $strReceiptLabel .= ' Paid In Full';
        }

        $strClientABN = "";

        if (strlen($arrClientData['abn']) > 0 ) 
        {
            $strClientABN = 'ABN ' . $arrClientData['abn'];
        }

     $strResult =
        '<style>'
        .   'table { border:0 none; width:100%; border-collapse: collapse; }'
        .   'table tr th, table tr td { text-align: left; vertical-align: top; }'
        .   '.box-border {'
        .   '     border:1px solid #dbdbdb;'
        .   '}'
        .   '.box-pad { padding:10px; }'
        .   'table.table-bordered tr th, table.table-bordered tr td { width:100%; padding:10px;  border:1px solid #dbdbdb; }'
        .   '.purchase-details .col1 { width:100px; }'
        .   '.purchase-details .col2 { width:50%; }'
        .   '.purchase-details .colstatus { width:100px; }'
        .   '.purchase-details .col3 { width:100px; }'
        .   '.purchase-totals { margin-top:10px; }'
        .   '.purchase-totals .col1 { width:66.7%; border:0px none; border-right:1px solid #dbdbdb; }'
        .   '.purchase-totals .col2 { width:100px; }'
        .   '.purchase-totals .col3 { width:100px; }'
        .   '.discount-details .col1 { width:79.8%; }'     
        .   '.discount-details .col2 { width:100px; }'
        . '</style>';

     /*
        $strResult .=

         '<div style="padding:10px 0">'
        .    '<strong>' . $strReceiptLabel . '</strong>'
        . '</div>'
        . '<table cellspacing="0" cellpadding="0" style="width:100%;border-collapse: collapse">'
        .    '<tr>';

        if ($arrClientData['ownerclient'] == false)
        {
            $strResult .= '<td class="box-border box-pad" style="width:49%">'
            .            htmlspecialchars($arrClientData['clientname'])
            .            '<br />'
            .            htmlspecialchars($strClientABN)
            .            '<br />'
            .            htmlspecialchars($arrClientData['address1']) . ' ' . htmlspecialchars($arrClientData['address2'])
            .            '<br />'
            .            htmlspecialchars($arrClientData['suburb']) . ' ' . htmlspecialchars($arrClientData['state']) . ' ' . htmlspecialchars($arrClientData['postcode'])
            .       '</td>';
        }

        $strResult .= '<td style="width:40px" style="border-right:1px solid #dbdbdb;">&nbsp;</td>'
        .       '<td class="box-border box-pad" style="width:49%">'
        .       '<table>'
        .           '<tr>'
        .               '<td>Invoice:</td>'
        .               '<td>' . $strReceiptNumber . '</td>'
        .           '</tr>'
        .           '<tr>'
        .               '<td>Date:</td>'
        .                '<td>' . $strInvoiceDate . '</td>'
        .            '</tr>'
        .         '</table>'
        .        '</td>'
        .    '</tr>'
        . '</table>';

        if (strlen($arrPayerData['payername']) > 0)
        {
            $strResult .= '<br><strong>TO:</strong><br><br>' . htmlspecialchars($arrPayerData['payername']) . '<br />';
            if (strlen($arrPayerData['addressline1']) > 0)
            {
                $strResult .= htmlspecialchars($arrPayerData['addressline1'])	. '<br />';
            }
            if (strlen($arrPayerData['addressline2']) > 0)
            {
                $strResult .= htmlspecialchars($arrPayerData['addressline2'])	. '<br />';
            }
            $strResult .= htmlspecialchars($arrPayerData['suburb']) . ' ' . htmlspecialchars($arrPayerData['state'])	. ' ' . htmlspecialchars($arrPayerData['postcode']) . '<br />';
        }
        */


        $strResult .= '<table class="table-bordered purchase-details" style="margin-top:20px">'
        .   '<tr>'
        .       '<th class="col1" style="100px" width="100px">Qty</th>'
        .       '<th class="col2" style="50%" width="50%">Details</th>'
        .       '<th class="colstatus" style="100px" width="100px">Status</th>'
        .       '<th class="col3" style="100px" width="100px">Price<br />Inc. GST</th>'
        .    '</tr>'
        .    $strPurchaItemsContent
        . '</table>';
        
        $strResult .= '<table class="table-bordered purchase-totals">';
        
        $strResult .= '<tr>'
                    .       '<td class="col1" style="width:66.7%" width="66.7%">&nbsp;</td>'
                    .       '<td class="col2" style="100px" width="100px">Delivery:</td>'
                    .       '<td class="col3" style="100px" width="100px">' . $strTotalShipping . '</td>'
                    .   '</tr>';        

        $strResult .=  '<tr>'
                    .       '<td class="col1" style="width:66.7%" width="66.7%">&nbsp;</td>'
                    .       '<td class="col2" style="100px" width="100px">Total Inc. GST*:</td>'
                    .       '<td class="col3" style="100px" width="100px">' . $strTotal . '</td>'
                    .   '</tr>';

        $strResult .= '</table>';


        $strResult .= '<div style="padding:10px 0">'
                    .        '* includes GST of ' . $strGSTTotal
                    .   '</div>';



        if (strlen($strDiscountItemsContent) > 0)
        {
            $strResult .= '<table class="table-bordered discount-details" style="margin-top:20px">';
            $strResult .= '<tr>';
            $strResult .=      '<th class="col1" style="width:79.8%" width="79.8%">Discounts Given</th>';
            $strResult .=      '<th class="col2" style="100px" width="100px">Discount Inc. GST</th>';
            $strResult .= '</tr>';
            $strResult .= $strDiscountItemsContent;
            $strResult .= '</table>';
        }
    }

    return $strResult;
}
