<?php

// php version of inc-api.js

//function getGUID()
//{
    //return str_replace('.', '-', uniqid('', true));
//}

class osCall {

    protected $m_webServiceURL;
    protected $m_strDeviceIDCookie = '';
	protected $m_strSecurityToken = '';
    protected $m_intDataID = 1;

    protected $strLastResponse;

    function __construct() {
        $this->m_webServiceURL = APP_DOMAIN_PATH . URL_WEBSERVICE;
		$this->m_strDeviceIDCookie = $_SESSION['client_loggedin_token'];
        $this->m_strSecurityToken = $_SESSION['client_loggedin_token'];
		//file_put_contents("d:\dev\session.log", "inc-api.php osCall " . $this->m_strSecurityToken . ":" . $_SESSION['client_loggedin_token'] . "\n", FILE_APPEND);	// DEBUGSESSION
    }

    function ajaxCall($arrJSONRequest_a, $cbSuccess_a=null)
    {
        $arrResult = array();
        $blnHasError = false;
        
        try
        {
            $arrResponse = $this->getResponse($this->m_webServiceURL, $arrJSONRequest_a);

            if (isset($arrResponse['AWAFOS']))
            {
                $arrResult = $arrResponse['AWAFOS'];
                
                $intErrorCode = intval($arrResult['responsecode'], 10);
				$strMessage = $arrResult['message'];

                switch($intErrorCode)
                {
                    case 0:	// success with message (originally was without message, effectively this is the same as 1 now)                  
                        $blnHasError = false;
						if (strlen($strMessage) > 0)
						{
							echo("Attention: " . $strMessage . "<br>");
						}
                        break;        

					case 1:	// url (not implemented by this PHP handler, so always return error)
                        $blnHasError = true;
                        break;        
						
                    case 2:	// error with message
                        $blnHasError = true;
						if (strlen($strMessage) > 0)
						{
							echo("Attention: " . $strMessage . "<br>");
						}
                        break;
                    
                    case 3:	// forced logout
                        $blnHasError = true;
						if (strlen($strMessage) > 0)
						{
							echo("Attention: " . $strMessage . "<br>");
						}
                        $this->forceLogout();
                        break;
                    
                    case 4:	// document (not implemented by this PHP handler, so always return error)
                        $blnHasError = true;
                        break;
                    
                    case 5:	// url (not implemented by this PHP handler, so always return error)
                        $blnHasError = true;
                        break;
                    
                    case 6:	// url (not implemented by this PHP handler, so always return error)
                        $blnHasError = true;
                        break;
                    
                    case 7:	// url (not implemented by this PHP handler, so always return error)
                        $blnHasError = true;
                        break;
                    
                    default:	// undefined error
                        $blnHasError = true;
                        echo('Undefined or unknown error<br>');
                                                
                }
            }
            else
            {
                //echo('request: ' . print_r($arrJSONRequest_a, true) . '<br>'); // DEBUGSESSION
                //echo('response: ' . print_r($arrResponse, true) . '<br>');	// DEBUGSESSION
				//if (isset($_SERVER["HTTP_REFERER"])) // DEBUGSESSION
				//{ // DEBUGSESSION
					//echo('referer: ' . $_SERVER["HTTP_REFERER"] . '<br>'); // DEBUGSESSION
				//} // DEBUGSESSION
				//die(); // DEBUGSESSION
                //throw new Exception('Undefined or null response');
				$this->forceLogout();
            }
            
            
        }
        catch(Exception $objE)
        {
            print_r($objE->getMessage()); // it can be returned as error message?

        }

        return $arrResult;
    }


    function ajaxRequestCreate($strFunction_a, $arrParameters_a)
    {
        $strDataID = $this->m_intDataID;
        $this->m_intDataID++;

        if (strlen($strDataID) === 0)
        {
            $strDataID = 'sys';
        }

        $objJSON = new stdClass();
        $objRequest = new stdClass();

        $objRequest->securitytoken = $this->m_strSecurityToken;
        $objRequest->clientversion = CLIENT_VERSION;
        $objRequest->deviceidcookie = $this->m_strDeviceIDCookie; //''; //COOKIE_DEVICENAME;
        $objRequest->callerid = CALLERID_PUBLIC;
        $objRequest->dataid = $strDataID;
        $objRequest->function = $strFunction_a;
        $objRequest->parameters = $arrParameters_a;

        $objJSON->AWAFOS = $objRequest;
//echo(json_encode($objJSON) . "<br>"); 
        return json_encode($objJSON);

    }

    function getResponse($strWebServiceURL_a, $arrJSONRequest_a)
    {
        $arrResponse = array();

        $strResult =  $this->cURLRequest($strWebServiceURL_a, $arrJSONRequest_a);

        //$strResult =  $this->fileGetContentsRequest($strWebServiceURL_a, $arrJSONRequest_a);

        $arrResponse = json_decode($strResult, true);

        return $arrResponse;
    }

    function fileGetContentsRequest($strWebServiceURL_a, $strJSONRequest_a) {

        $strPHPSessID = '_PHPSESSID_=' . session_id();

        $strWebServiceURL = $strWebServiceURL_a;

        $strWebServiceURL  .= '?' . $strPHPSessID;

        $arrContextOptions = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/json; charset=UTF-8',
                'content' => $strJSONRequest_a
            )
        );

        $objContext  = stream_context_create($arrContextOptions);

        $strResponse = file_get_contents($strWebServiceURL, false, $objContext);

        return $strResponse;
    }

    function cURLRequest($strWebServiceURL_a, $strPostFields_a)
    {
        $intTimeout = 20;
        $strUserAgent = $_SERVER['HTTP_USER_AGENT'];
        $strWebServiceURL = $strWebServiceURL_a;
        $strCookieFile = SERVER_TEMP_DIR . 'cookies/' . $this->m_strSecurityToken . '-mitsukibo-cookie';

        $objCurl = curl_init();
        curl_setopt($objCurl, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=UTF-8'));
        curl_setopt($objCurl, CURLOPT_URL, $strWebServiceURL);
        curl_setopt($objCurl, CURLOPT_REFERER, $strWebServiceURL);
        curl_setopt($objCurl, CURLOPT_HEADER, 0);
        curl_setopt($objCurl, CURLOPT_POST, true);
        curl_setopt($objCurl, CURLOPT_POSTFIELDS, $strPostFields_a);
        curl_setopt($objCurl, CURLOPT_USERAGENT, $strUserAgent);

        curl_setopt($objCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($objCurl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($objCurl, CURLOPT_CONNECTTIMEOUT, $intTimeout);
        curl_setopt($objCurl, CURLOPT_SSL_VERIFYPEER, false);

        if (ENABLE_SESSION_COOKIES == 'TRUE')
        {
            curl_setopt($objCurl, CURLOPT_COOKIEFILE, $strCookieFile);
            curl_setopt($objCurl, CURLOPT_COOKIEJAR, $strCookieFile);
            curl_setopt ($objCurl, CURLOPT_COOKIE, $this->m_strSecurityToken);
        }

        $strResponse = curl_exec($objCurl);
        //$strCurlInfo = curl_getinfo($objCurl);
        //print_r($strResponse);

        $this->strLastResponse = $strResponse;

        curl_close($objCurl);

        return $strResponse;
    }

    // for debugging
    function getLastResponse()
    {
        return $this->strLastResponse;
    }
    
    function forceLogout()
    {
        // need to call logout        

		//file_put_contents("d:\dev\session.log", "inc-api.php forceLogout " . ":" . $_SESSION['client_loggedin_token'] . "\n", FILE_APPEND);	// DEBUGSESSION

		// note: cannot modify header here, so must use JS
		userLogout($this);	// first make sure the server is logged out, then using JS log the client out
		
		?>
			<script type="text/javascript">
				window.location.href = "<?php echo(APP_LOGOUT); ?>";
			</script>
		<?php
        die;
    }
}

function userLogout($objOSCall_a)
{
    $osCall = $objOSCall_a;

    $strJSON = $osCall->ajaxRequestCreate('public_logout', []);

    $arrResult = $osCall->ajaxCall($strJSON);

    return $arrResult;
}

function getDatabaseName($objOSCall_a)
{
    $osCall = $objOSCall_a;

    $strJSON = $osCall->ajaxRequestCreate('public_getdatabasename', []);

    $arrResult = $osCall->ajaxCall($strJSON);

    return $arrResult;
}

$osCall = new osCall();
