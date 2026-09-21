<?php

// returns an error if there is one, or an empty string if no error
function processGenerateReceiptTypeA($objConn_a, $strDocumentID_a, $strDescription_a, $strPrinterType_a, $strFilenameBase_a, $arrJSONData_a, $arrDocumentMetaData_a, $arrPrintingMeta_a)
{
    $arrResult = array(
		"result" => STAT_ERROR, 
		"error" => "Process Generate Receipt Type A Error", 
		"tagtype" => "", 
		"tag" => "", 
		"path" => "", 
		"metadata" => "");

	$strTableNameDocument = getTableNameEntity("document", false);

    $strError = "";
	$strPath = "";

	logPrinting("start at location: processGenerateReceiptTypeA");	
	
    if (dependencies('utils/html2Pdf') &&
        dependencies('print/loadTemplate') &&
        dependencies('docs/documentComponentAdd,docs/documentComponentCopy,docs/documentSimpleCopy,docs/documentCommit,docs/documentOpen'))
	{
        $intReceiptPrintNoCopies = $arrJSONData_a['numberofcopies'];
        $intReceiptMarginLeft = '0';
        $intReceiptMarginTop = '0';

        // Set margins
        $strMargins = $arrJSONData_a['margins'];
        $arrMargins = json_decode($strMargins, true);
        $intReceiptMarginLeft = $arrMargins["left"];
        $intReceiptMarginTop = $arrMargins["top"];

        $arrTransaction = $arrJSONData_a['transaction'];
        $arrPaymentmethods = $arrJSONData_a['paymentmethods'];

        $arrBusinessData = $arrTransaction['businessdata'];
        $arrClientData = $arrTransaction['clientdata'];
		$arrPayerData = $arrTransaction['payerdata'];
        $arrTransactionLines = $arrTransaction['transactionlines'];

        $fltPriceIncGST = 0;
        $fltPriceExGST = 0;

        $strInvoiceDate = getDateReportOut($arrTransaction['invoicedate']);
        $strReceiptNumber = $arrTransaction['receiptnumber'];
        
        $blnIsPaid = toBoolean($arrTransaction['is_paid']);
        
        $strPurchaItemsContent = "";

        foreach ($arrTransactionLines as $arrItemLine) 
		{
            $strPurchaItemsContent .= "<tr>";
            $strPurchaItemsContent .= "<td class=\"col1\">1</td>";
            $strPurchaItemsContent .= "<td class=\"col2\">". htmlspecialchars($arrItemLine['description']) . "</td>";
            
            if ((toBoolean($arrItemLine['ingraceperiod'])) && ($arrItemLine['price_incgst'] == 0))
            { 
                $strPurchaItemsContent .= "<td class=\"col3\">". number_format($arrItemLine['price_incgst'],2) . " (Grace)</td>";                
            }   
            else
            { 
                $strPurchaItemsContent .= "<td class=\"col3\">". number_format($arrItemLine['price_incgst'],2) . "</td>";
            }            
            $strPurchaItemsContent .= "</tr>";

            $fltPriceIncGST += floatval($arrItemLine['price_incgst']);
            $fltPriceExGST  += floatval($arrItemLine['price_exgst']);
        }


        $fltGSTTotal = $fltPriceIncGST - $fltPriceExGST;
        $fltSubTotal = $fltPriceExGST;
        $fltTotal = $fltGSTTotal + $fltSubTotal;

        $strGSTTotal = number_format($fltGSTTotal,2);
        $strSubTotal = number_format($fltSubTotal,2);
        $strTotal = number_format($fltTotal,2);
                
        $strReceiptLabel = "RECEIPT";

        if (strlen($arrBusinessData['abn']) > 0) 
		{
            $strReceiptLabel = "TAX INVOICE";
        }
        
        if ($blnIsPaid)
        {
            $strReceiptLabel .= ' Paid In Full';
        }

        $strRowPaymentmethods = '';
        $intColumn = 2;
        $intRows = ceil(count($arrPaymentmethods) / $intColumn);

        $intIndex = 0;

        for ($intRow=0; $intRow < $intRows; $intRow++) 
		{
            $strColumnContent = '';

            for ($intJ=0; $intJ < $intColumn; $intJ++ ) 
			{
                if ($intIndex < count($arrPaymentmethods)) 
				{
                    $arrRowPaymentmethod = $arrPaymentmethods[$intIndex];

                    $strColumnContent .= '<td style="width:50%;padding-bottom:20px">';
                    $strColumnContent .= '<div style="font-weight:bold;">By&nbsp;' . htmlspecialchars($arrRowPaymentmethod['description']) . '</div>';
                    $strColumnContent .= '<div style="margin-top:10px">'. nl2br(htmlspecialchars($arrRowPaymentmethod['notes'])) . '</div>';
                    $strColumnContent .= '</td>';

                }
                else 
				{
                    $strColumnContent .= '<td style="width:50%;padding-bottom:20px">&nbsp;</td>';
                }

                $intIndex++;
            }

            $strRowPaymentmethods .= '<tr>' . $strColumnContent . '</tr>';
        }

        $strInvoiceLogo = 'invoice_logo_' . APP_CODE . '.png';
        
		// save the logo
        documentOpen($objConn_a, $strDocumentID_a);
        documentComponentCopy($objConn_a, $strDocumentID_a, 'logo.png', APP_PATH . "images/" . $strInvoiceLogo);
        documentCommit($objConn_a, '', $strDocumentID_a);

		$strREL_CURRENTREPOSITORY_PRINT_DOCUMENT = '';
		if (ENABLE_CLIENTDATABASES == 'TRUE')
		{
			if (getSessionDB(__FUNCTION__) == "client")
			{
				$strREL_CURRENTREPOSITORY_PRINT_DOCUMENT = REL_CLIENTREPOSITORY_PRINT_DOCUMENT;
			}
			else if (getSessionDB(__FUNCTION__) == "system")
			{
				$strREL_CURRENTREPOSITORY_PRINT_DOCUMENT = REL_SYSTEMREPOSITORY_PRINT_DOCUMENT;
			}
		}
		else
		{
			$strREL_CURRENTREPOSITORY_PRINT_DOCUMENT = REL_SYSTEMREPOSITORY_PRINT_DOCUMENT;
		}
        $strPath = getClusterPath($strREL_CURRENTREPOSITORY_PRINT_DOCUMENT, $strFilenameBase_a, false, true);

        $strLogoImage =  URL_APP_PATH  . $strPath . $strFilenameBase_a . '-logo.png';

		$strContent ='<style>'
			.   'table { border:0 none; width:100%; border-collapse: collapse; }'
			.   'table tr th, table tr td { text-align: left; vertical-align: top; }'
			.   '.box-border {'
			.   '     border:1px solid #dbdbdb;'
			.   '}'
			.   '.box-pad { padding:10px; }'
			.   'table.table-bordered tr th, table.table-bordered tr td { width:100%; padding:10px;  border:1px solid #dbdbdb; }'
			.   '.purchase-details .col1 { width:150px; }'
			.   '.purchase-details .col2 { width:54.1%; }'
			.   '.purchase-details .col3 { width:100px; }'
			.   '.purchase-totals { margin-top:10px; }'
			.   '.purchase-totals .col1 { width:60.8%; border:0px none; border-right:1px solid #dbdbdb; }'
			.   '.purchase-totals .col2 { width:100px; }'
			.   '.purchase-totals .col3 { width:100px; }'
			. '</style>'
			. '<table cellspacing="0" cellpadding="0">'
			.   '<tr>'
			.       '<td style="width:50%;">'
			.            htmlspecialchars($arrBusinessData['name'])
			.            '<br />';

		if ( strlen($arrBusinessData['abn']) > 0) 
		{
			$strContent .= 'ABN ' . htmlspecialchars($arrBusinessData['abn']) . '<br />';
		}

		$strClientABN = "";
		if (strlen($arrClientData['abn']) > 0) 
		{
			$strClientABN = 'ABN ' . $arrClientData['abn'];
		}

		$strContent .= htmlspecialchars($arrBusinessData['address'])
			.        '</td>'
			.        '<td style="width:50%;text-align: right">'
			// .            '<img src="' . $strLogoImage .'" />'
			.        '</td>'
			.    '</tr>'
			. '</table>'
			. '<div style="padding:10px 0">'
			.    '<strong>' . $strReceiptLabel . '</strong>'
			. '</div>'
			. '<table cellspacing="0" cellpadding="0" style="width:100%;border-collapse: collapse">'
			.    '<tr>';
			
		if ($arrClientData['ownerclient'] == false)
		{
			$strContent .= '<td class="box-border box-pad" style="width:49%">'
				.            htmlspecialchars($arrClientData['clientname'])
				.            '<br />'
				.            htmlspecialchars($strClientABN)
				.            '<br />'
				.            htmlspecialchars($arrClientData['address1']) . ' ' . htmlspecialchars($arrClientData['address2'])
				.            '<br />'
				.            htmlspecialchars($arrClientData['suburb']) . ' ' . htmlspecialchars($arrClientData['state']) . ' ' . htmlspecialchars($arrClientData['postcode'])
				.       '</td>';
		}
		
		$strContent .= '<td style="width:40px" style="border-right:1px solid #dbdbdb;">&nbsp;</td>'
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
			$strContent .= '<br><strong>TO:</strong><br><br>' . htmlspecialchars($arrPayerData['payername']) . '<br />';
			if (strlen($arrPayerData['addressline1']) > 0)
			{
				$strContent .= htmlspecialchars($arrPayerData['addressline1'])	. '<br />';
			}
			if (strlen($arrPayerData['addressline2']) > 0)
			{
				$strContent .= htmlspecialchars($arrPayerData['addressline2'])	. '<br />';
			}
			$strContent .= htmlspecialchars($arrPayerData['suburb']) . ' ' . htmlspecialchars($arrPayerData['state'])	. ' ' . htmlspecialchars($arrPayerData['postcode']) . '<br />';
		}
			
		$strContent .= '<table class="table-bordered purchase-details" style="margin-top:20px">'
			.   '<tr>'
			.       '<th class="col1">Qty</th>'
			.       '<th class="col2">Details</th>'
			.       '<th class="col3">Total<br />Inc-GST</th>'
			.    '</tr>'
			.    $strPurchaItemsContent
			. '</table>'
			. '<table class="table-bordered purchase-totals">'
			.   '<tr>'
			.       '<td class="col1">&nbsp;</td>'
			.       '<td class="col2">Subtotal:</td>'
			.       '<td class="col3">' . $strSubTotal . '</td>'
			.   '</tr>'
			.   '<tr>'
			.       '<td class="col1">&nbsp;</td>'
			.        '<td class="col2">GST:</td>'
			.        '<td class="col3">'. $strGSTTotal . '</td>'
			.   '</tr>'
			.    '<tr>'
			.       '<td class="col1">&nbsp;</td>'
			.       '<td class="col2">Total (Inc GST):</td>'
			.       '<td class="col3">' . $strTotal . '</td>'
			.   '</tr>'
			. '</table>'
			. '<div style="margin-top:20px;padding-top:20px;border-top:2px dashed #000;">'
			.   '<table cellspacing="0" cellpadding="0" style="width:100%;border-collapse: collapse">'
			.       '<tr>'
			.           '<td style="width:50%">'
			.               '<span style="font-weight:bold;font-size:14px;">INVOICE #' . $strReceiptNumber . '</span>'
			.           '</td>'
			.           '<td style="width:50%">'
			.               '<span style="font-weight:bold;font-size:14px;">Amount Due:&nbsp;&nbsp;&nbsp;' . $strTotal . '</span>'
			.          '</td>'
			.       '</tr>'
			.       '<tr>'
			.           '<td style="width:50%">'
			.               '<h3>How To Pay</h3>'
			.           '</td>'
			.           '<td style="width:50%">'
			.               '&nbsp;'
			.          '</td>'
			.       '</tr>'
			.       $strRowPaymentmethods
			.   '</table>'
			. '</div>';

        // START print job updates
		$strREL_CURRENTREPOSITORY_PRINT_DOCUMENT = '';
		if (ENABLE_CLIENTDATABASES == 'TRUE')
		{
			if (getSessionDB(__FUNCTION__) == "client")
			{
				$strREL_CURRENTREPOSITORY_PRINT_DOCUMENT = REL_CLIENTREPOSITORY_PRINT_DOCUMENT;
			}
			else if (getSessionDB(__FUNCTION__) == "system")
			{
				$strREL_CURRENTREPOSITORY_PRINT_DOCUMENT = REL_SYSTEMREPOSITORY_PRINT_DOCUMENT;
			}
		}
		else
		{
			$strREL_CURRENTREPOSITORY_PRINT_DOCUMENT = REL_SYSTEMREPOSITORY_PRINT_DOCUMENT;
		}
        $documentpath = getClusterPath($strREL_CURRENTREPOSITORY_PRINT_DOCUMENT, $strFilenameBase_a, false, true);

        //$strDescription = 'receipt for purchase # ' .$strReceiptNumber;
        $strMetaData = '{ "legacy":true, "invoicenumber":"' . $strReceiptNumber . '" }';
        $strFilename = 'invoice.pdf';

        $objPdfDocument = json_encode($arrJSONData_a);

        $strSQL = "select client_id, user_id, documenttype_id from ~TABLENAMEDOCUMENT~ where id = ~DOCUMENTID~";
		$strSQL = str_replace('~TABLENAMEDOCUMENT~', ff($strTableNameDocument), $strSQL);
        $strSQL = str_replace('~DOCUMENTID~', ff($strDocumentID_a), $strSQL);

        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
        $arrRow = dbReadRecord($objResult);

		$strTempPDFFile = TEMP_GENERAL_PATH . getFilenameGUID() . '.pdf';
        $blnResult = html2PdfSaveFile($strContent, $strTempPDFFile);

        documentOpen($objConn_a, $strDocumentID_a);
        documentComponentCopy($objConn_a, $strDocumentID_a, $strFilename, $strTempPDFFile);
        documentCommit($objConn_a, '', $strDocumentID_a);

		deleteFile($strTempPDFFile);

        $arrResult = array(
			"result" =>  STAT_COMPLETED, 
			"error" => "", 
			"tagtype" => "documentid", 
			"tag" => $strDocumentID_a, 
			"path" => "", 
			"metadata" => $strMetaData);
	}

	logPrinting("end at location: processGenerateReceiptTypeA");	

    return $arrResult;
}
