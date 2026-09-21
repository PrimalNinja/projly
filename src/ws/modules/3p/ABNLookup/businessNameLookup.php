<?php
include_once('ABNLookupService.php');

function businessNameLookup($strGUID, $strABN_a)
{
    $result = null;
    $arrResult = array();
    try
    {
        $abnlookup = new ABNLookupService($strGUID);
        try
        {
            $result = $abnlookup->searchByName($strABN_a);
        }
        catch (Exception $e)
        {
            throw $e;
        }
    } 
    catch(Exception $e)
    {
        $x = $e->getMessage();
        //debug($x);
    }

    if(isset($result->ABRPayloadSearchResults->response->searchResultsList)) {
            
        $objResult =  $result->ABRPayloadSearchResults->response->searchResultsList->searchResultsRecord;

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
    }
    
    return $arrResult;
}
