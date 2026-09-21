<?php

// function summary:

// html2PdfBrowser($strHTML_a, $strName_a, $strPageLayout_a)
// html2PdfBrowserBasic($strHTML_a)
// html2PdfDownload($strHTML_a, $strName_a)
// html2PdfSaveFile($strHTML_a, $strPathDestination_a)
// html2PdfSaveFileLandscape($strHTML_a, $strPathDestination_a)
// html2PdfStream($strHTML_a)

//HTML to PDF and Open in Web Browser
function html2PdfBrowser($strHTML_a, $strName_a, $strPageLayout_a)
{
    if (dependencies('3p/Html2Pdf/html2pdf.class'))
    {
        $strPageLayout = 'P';
       if ( $strPageLayout_a == 'LANDSCAPE')
       {
           $strPageLayout = 'L';
       }

        $html2pdf = new HTML2PDF($strPageLayout,'A4','en');
        //$html2pdf->pdf->SetDisplayMode('fullpage');
        @$html2pdf->writeHTML($strHTML_a);
        @$html2pdf->Output($strName_a, 'I');
        exit();
    }
}

//HTML to PDF and Open in Web Browser without a Name
function html2PdfBrowserBasic($strHTML_a)
{
    if (dependencies('3p/Html2Pdf/html2pdf.class'))
    {
        $html2pdf = new HTML2PDF('P','A4','en');
        @$html2pdf->writeHTML($strHTML_a);
        @$html2pdf->Output();
        exit();
    }
}

//HTML to PDF and Force Download in Web Browser
function html2PdfDownload($strHTML_a, $strName_a)
{
    if (dependencies('3p/Html2Pdf/html2pdf.class'))
    {
        $html2pdf = new HTML2PDF('P','A4','en');
        @$html2pdf->writeHTML($strHTML_a);
        @$html2pdf->Output($strName_a, 'D');
        exit();
    }
}

//HTML to PDF and Save it to a destination path
//NOTE: $strPathDestination must posses complete path with filename and also overwrites existing filename it already exists
function html2PdfSaveFile($strHTML_a, $strPathDestination_a)
{
    $blnResult = false;
    if (dependencies('3p/Html2Pdf/html2pdf.class'))
    {
        $html2pdf = new HTML2PDF('P','A4','en');
        @$html2pdf->writeHTML($strHTML_a);
        @$html2pdf->Output($strPathDestination_a, 'F');
        $blnResult = true;
    }
    return $blnResult;
}

function html2PdfSaveFileLandscape($strHTML_a, $strPathDestination_a)
{
    $blnResult = false;
    if (dependencies('3p/Html2Pdf/html2pdf.class'))
    {
        $html2pdf = new HTML2PDF('L','A4','en');
        @$html2pdf->writeHTML($strHTML_a);
        @$html2pdf->Output($strPathDestination_a, 'F');
        $blnResult = true;
    }
    return $blnResult;
}

//HTML to PDF and Return PDF Contents or Stream
//This can be used on using / attaching to email without creating a physical file
function html2PdfStream($strHTML_a)
{
    if (dependencies('3p/Html2Pdf/html2pdf.class'))
    {
        $html2pdf = new HTML2PDF('P','A4','en');
        @$html2pdf->writeHTML($strHTML_a);
        $objPDFStream = @$html2pdf->Output('', 'S');
        return $objPDFStream;
    }
}
