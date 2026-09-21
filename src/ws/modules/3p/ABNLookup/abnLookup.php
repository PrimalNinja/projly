<?php
include_once('ABNLookupService.php');

function abnLookup( $strABN_a)
{
    $result = null;
    $arrResult = array();
    try
    {
        $abnlookup = new ABNLookupService(ABNLOOKUPGUID);
        try
        {
            $result = $abnlookup->searchByAbn($strABN_a);
        }
        catch (Exception $e)
        {
            throw $e;
        }
    } 
    catch(Exception $e)
    {
        echo $e->getMessage();
        print_r($e);
    }

    $objResult =  $result->ABRPayloadSearchResults->response; //->searchResultsList->searchResultsRecord;

    foreach($objResult as $row)
    {

        $businessname = "";
        $id = "";
        $status = "";
        $state = "";
        $postcode = "";

        if(isset($row->ABN->identifierValue))
        {
            $id = $row->ABN->identifierValue;
        }

        if(isset($row->businessName->organisationName))
        {
            $businessname = $row->businessName->organisationName;
        }

        if(isset($row->mainTradingName->organisationName))
        {
            $businessname = $row->mainTradingName->organisationName;
        }

        if(isset($row->legalName->fullName))
        {
            $businessname = $row->legalName->fullName;
        }

        if(isset($row->otherTradingName->organisationName))
        {
            $businessname = $row->otherTradingName->organisationName;
        }


        if(isset($row->mainName->organisationName))
        {
            $businessname = $row->mainName->organisationName;
        }


        if(isset($row->ABN->identifierStatus))
        {
            $status = $row->ABN->identifierStatus;
        }
        if(isset($row->mainBusinessPhysicalAddress->stateCode))
        {
            $state = $row->mainBusinessPhysicalAddress->stateCode;
        }
        if(isset($row->mainBusinessPhysicalAddress->postcode))
        {
            $postcode = $row->mainBusinessPhysicalAddress->postcode;
        }



        $arrResult[] = array(
            "id" => $id,
            "businessname" => $businessname,
            "status" => $status,
            "state" => $state,
            "postcode" => $postcode,
        );
    }
    $arrResult = $objResult;
    return $arrResult;
}


//define('ABNLOOKUPGUID', '1dac19fb-5087-4056-bce4-6eff3059658b');
//$x = abnLookup('7101');
//print_r($x);




//Example Response NOT SAME with Business Name 
//which is capable of multi result non exact word.
//-----------------------------------------------------------------


/*
stdClass Object
(
    [usageStatement] => The Registrar of the ABR monitors the quality of the information available on this website and updates the information regularly. However, neither the Registrar of the ABR nor the Commonwealth guarantee that the information available through this service (including search results) is accurate, up to date, complete or accept any liability arising from the use of or reliance upon this site.
    [dateRegisterLastUpdated] => 2016-08-31
    [dateTimeRetrieved] => 2016-08-31T21:08:56.6204616+10:00
    [businessEntity] => stdClass Object
        (
            [recordLastUpdatedDate] => 2016-06-15
            [ABN] => stdClass Object
                (
                    [identifierValue] => 71010661428
                    [isCurrentIndicator] => Y
                    [replacedFrom] => 0001-01-01
                )

            [entityStatus] => stdClass Object
                (
                    [entityStatusCode] => Cancelled
                    [effectiveFrom] => 2016-06-11
                    [effectiveTo] => 0001-01-01
                )

            [ASICNumber] => 
            [entityType] => stdClass Object
                (
                    [entityTypeCode] => PTR
                    [entityDescription] => Other Partnership
                )

            [mainName] => stdClass Object
                (
                    [organisationName] => J.P CASSIN & F.E WESTON
                    [effectiveFrom] => 2010-11-22
                )

            [mainBusinessPhysicalAddress] => stdClass Object
                (
                    [stateCode] => VIC
                    [postcode] => 3150
                    [effectiveFrom] => 2014-09-16
                    [effectiveTo] => 0001-01-01
                )

        )

)
*/

/*
in the event that the user will not enter a correct / valid ABN value it will respond with
-----------------------------------------------------------------------------------------------

stdClass Object
(
    [dateRegisterLastUpdated] => 0001-01-01
    [dateTimeRetrieved] => 2016-08-31T21:14:25.3301687+10:00
    [exception] => stdClass Object
        (
            [exceptionDescription] => Search text is not a valid ABN or ACN
            [exceptionCode] => WEBSERVICES
        )

)
*/
