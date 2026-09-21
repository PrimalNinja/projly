<?php
class ABNLookupService extends SoapClient{

    private $guid = "";

    public function __construct($guid)
    {
        $this->guid = $guid;
        $params = array(
            'soap_version' => SOAP_1_1,
            'exceptions' => true,
            'trace' => 1,
            'cache_wsdl' => WSDL_CACHE_NONE
        );

        parent::__construct('http://abr.business.gov.au/abrxmlsearch/ABRXMLSearch.asmx?WSDL', $params);
    }

    public function searchByAbn($abn, $historical = 'N'){
        $params = new stdClass();
        $params->searchString               = $abn;
        $params->includeHistoricalDetails   = $historical;
        $params->authenticationGuid         = $this->guid;
        return $this->ABRSearchByABN($params);
    }

    public function searchByName($company_name){
        $params = new stdClass();
        $params->externalNameSearch = new stdClass();
        $params->externalNameSearch->name = $company_name;
        $params->externalNameSearch->postcode = "";
        $params->externalNameSearch->legalName = "";
        $params->externalNameSearch->tradingName = "";
        $params->externalNameSearch->NSW = "";
        $params->externalNameSearch->SA = "";
        $params->externalNameSearch->ACT = "";
        $params->externalNameSearch->VIC = "";
        $params->externalNameSearch->WA = "";
        $params->externalNameSearch->NT = "";
        $params->externalNameSearch->QLD = "";

        $params->authenticationGuid = $this->guid;
        return $this->ABRSearchByName($params);
    }


}
?>