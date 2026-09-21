<?php

// function summary:

// CurlExecute($objCurl_a, $blnResultCode_a)
// CurlSetupAuthentication($objCurl_a, $strLogin_a, $strPassword_a)
// CurlSetupProgress($objCurl_a, $strProgressCallback_a)
// CurlSetupProxy($objCurl_a, $strProxyURL_a, $intProxyPort_a, $strProxyLogin_a, $strProxyPassword_a)
// getCurlError($intErrorCode_a)
// FTPGetFile($strLocal_a, $strRemote_a, $intPort_a, $strLogin_a, $strPassword_a, $strProgressCallback_a, $strProxyURL_a, $intProxyPort_a, $strProxyLogin_a, $strProxyPassword_a)
// FTPPutFile($strLocal_a, $strRemote_a, $intPort_a, $strLogin_a, $strPassword_a, $strProgressCallback_a, $strProxyURL_a, $intProxyPort_a, $strProxyLogin_a, $strProxyPassword_a)
// HTTPGet($strRemote_a, $intPort_a, $strLogin_a, $strPassword_a, $strProgressCallback_a, $strProxyURL_a, $intProxyPort_a, $strProxyLogin_a, $strProxyPassword_a)
// HTTPGetFile($strLocal_a, $strRemote_a, $intPort_a, $strLogin_a, $strPassword_a, $strProgressCallback_a, $strProxyURL_a, $intProxyPort_a, $strProxyLogin_a, $strProxyPassword_a)
// HTTPSendFile($strType_a, $strLocal_a, $strRemote_a, $strRemoteFilename_a, $intPort_a, $strLogin_a, $strPassword_a, $strProgressCallback_a, $strProxyURL_a, $intProxyPort_a, $strProxyLogin_a, $strProxyPassword_a)
// HTTPPutFile($strLocal_a, $strRemote_a, $intPort_a, $strLogin_a, $strPassword_a, $strProgressCallback_a, $strProxyURL_a, $intProxyPort_a, $strProxyLogin_a, $strProxyPassword_a)
// isCurlEnabled()
// transmitFile($strServerType_a, $strLocal_a, $strRemote_a, $strRemoteFilename_a, $intPort_a, $strLogin_a, $strPassword_a, $strProgressCallback_a, $strProxyURL_a, $intProxyPort_a, $strProxyLogin_a, $strProxyPassword_a)

function CurlExecute($objCurl_a, $blnResultCode_a)
{
    $strResponse = curl_exec($objCurl_a);
    $varResult = curl_errno($objCurl_a);

    if ($varResult == 0) 
	{
        if ($blnResultCode_a) 
		{
            $varResult = curl_getinfo($objCurl_a, CURLINFO_HTTP_CODE);
        } 
		else 
		{
            $varResult = $strResponse;
        }
    }

    $objInfo = curl_getinfo($objCurl_a);
    $strErrNo = curl_errno($objCurl_a);
    $strCurlErrMessage = curl_strerror($strErrNo);

    $arrResult = array();
    $arrResult["result"] = $varResult;
    $arrResult["curl_info"] = $objInfo;
    $arrResult["curl_response"] = $strResponse;
    $arrResult["curl_errno"] = $strErrNo;
    $arrResult["curl_errmessage"] = $strCurlErrMessage;

    return $arrResult;
}

function CurlSetupAuthentication($objCurl_a, $strLogin_a, $strPassword_a)
{
    $strUserPassword = $strLogin_a . ':' . $strPassword_a;
    if (strlen($strLogin_a) > 0) 
	{
        curl_setopt($objCurl_a, CURLOPT_USERPWD, $strUserPassword);
    }
}

function CurlSetupProgress($objCurl_a, $strProgressCallback_a)
{
    if (strlen($strProgressCallback_a) > 0) 
	{
        curl_setopt($objCurl_a, CURLOPT_BUFFERSIZE, 8192);
        curl_setopt($objCurl_a, CURLOPT_NOPROGRESS, false);
        curl_setopt($objCurl_a, CURLOPT_PROGRESSFUNCTION, $strProgressCallback_a);
    }
}

function CurlSetupProxy($objCurl_a, $strProxyURL_a, $intProxyPort_a, $strProxyLogin_a, $strProxyPassword_a)
{
    $strProxyUserPassword = $strProxyLogin_a . ':' . $strProxyPassword_a;

    if (strlen($strProxyURL_a) > 0) 
	{
        curl_setopt($objCurl_a, CURLOPT_PROXY, $strProxyURL_a);
        curl_setopt($objCurl_a, CURLOPT_PROXYPORT, $intProxyPort_a);
        if (strlen($strProxyLogin_a) > 0) 
		{
            curl_setopt($objCurl_a, CURLOPT_PROXYUSERPWD, $strProxyUserPassword);
        }
    }
}

function getCurlError($intErrorCode_a)
{
    $strResult = '';

    $arrCurlErrors = array(
		'',
        'CURLE_UNSUPPORTED_PROTOCOL',
        'CURLE_FAILED_INIT',
        'CURLE_URL_MALFORMAT',
        'CURLE_URL_MALFORMAT_USER',
        'CURLE_COULDNT_RESOLVE_PROXY',
        'CURLE_COULDNT_RESOLVE_HOST',
        'CURLE_COULDNT_CONNECT',
        'CURLE_FTP_WEIRD_SERVER_REPLY',
        'CURLE_REMOTE_ACCESS_DENIED',
		'',
        'CURLE_FTP_WEIRD_PASS_REPLY',
		'',
        'CURLE_FTP_WEIRD_PASV_REPLY',
        'CURLE_FTP_WEIRD_227_FORMAT',
        'CURLE_FTP_CANT_GET_HOST',
		'',
        'CURLE_FTP_COULDNT_SET_TYPE',
        'CURLE_PARTIAL_FILE',
        'CURLE_FTP_COULDNT_RETR_FILE',
		'',
        'CURLE_QUOTE_ERROR',
        'CURLE_HTTP_RETURNED_ERROR',
        'CURLE_WRITE_ERROR',
		'',
        'CURLE_UPLOAD_FAILED',
        'CURLE_READ_ERROR',
        'CURLE_OUT_OF_MEMORY',
        'CURLE_OPERATION_TIMEDOUT',
		'',
        'CURLE_FTP_PORT_FAILED',
        'CURLE_FTP_COULDNT_USE_REST',
		'',
        'CURLE_RANGE_ERROR',
        'CURLE_HTTP_POST_ERROR',
        'CURLE_SSL_CONNECT_ERROR',
        'CURLE_BAD_DOWNLOAD_RESUME',
        'CURLE_FILE_COULDNT_READ_FILE',
        'CURLE_LDAP_CANNOT_BIND',
        'CURLE_LDAP_SEARCH_FAILED',
		'',
        'CURLE_FUNCTION_NOT_FOUND',
        'CURLE_ABORTED_BY_CALLBACK',
        'CURLE_BAD_FUNCTION_ARGUMENT',
		'',
        'CURLE_INTERFACE_FAILED',
		'',
        'CURLE_TOO_MANY_REDIRECTS',
        'CURLE_UNKNOWN_TELNET_OPTION',
        'CURLE_TELNET_OPTION_SYNTAX',
		'',
        'CURLE_PEER_FAILED_VERIFICATION',
        'CURLE_GOT_NOTHING',
        'CURLE_SSL_ENGINE_NOTFOUND',
        'CURLE_SSL_ENGINE_SETFAILED',
        'CURLE_SEND_ERROR',
        'CURLE_RECV_ERROR',
		'',
        'CURLE_SSL_CERTPROBLEM',
        'CURLE_SSL_CIPHER',
        'CURLE_SSL_CACERT',
        'CURLE_BAD_CONTENT_ENCODING',
        'CURLE_LDAP_INVALID_URL',
        'CURLE_FILESIZE_EXCEEDED',
        'CURLE_USE_SSL_FAILED',
        'CURLE_SEND_FAIL_REWIND',
        'CURLE_SSL_ENGINE_INITFAILED',
        'CURLE_LOGIN_DENIED',
        'CURLE_TFTP_NOTFOUND',
        'CURLE_TFTP_PERM',
        'CURLE_REMOTE_DISK_FULL',
        'CURLE_TFTP_ILLEGAL',
        'CURLE_TFTP_UNKNOWNID',
        'CURLE_REMOTE_FILE_EXISTS',
        'CURLE_TFTP_NOSUCHUSER',
        'CURLE_CONV_FAILED',
        'CURLE_CONV_REQD',
        'CURLE_SSL_CACERT_BADFILE',
        'CURLE_REMOTE_FILE_NOT_FOUND',
        'CURLE_SSH',
        'CURLE_SSL_SHUTDOWN_FAILED',
        'CURLE_AGAIN',
        'CURLE_SSL_CRL_BADFILE',
        'CURLE_SSL_ISSUER_ERROR',
        'CURLE_FTP_PRET_FAILED',
        'CURLE_RTSP_CSEQ_ERROR',
        'CURLE_RTSP_SESSION_ERROR',
        'CURLE_FTP_BAD_FILE_LIST',
        'CURLE_CHUNK_FAILED'
    );

    if (($intErrorCode_a >= 1) && ($intErrorCode_a <= 88)) 
	{
        $strResult = $arrCurlErrors[$intErrorCode_a];
    }

    return $strResult;
}

function FTPGetFile($strLocal_a, $strRemote_a, $intPort_a, $strLogin_a, $strPassword_a, $strProgressCallback_a, $strProxyURL_a, $intProxyPort_a, $strProxyLogin_a, $strProxyPassword_a)
{
    $objFile = fopen($strLocal_a, 'wb');
    $objCurl = curl_init();

    curl_setopt($objCurl, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($objCurl, CURLOPT_FILE, $objFile);
    curl_setopt($objCurl, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($objCurl, CURLOPT_FTP_USE_EPSV, false);
    curl_setopt($objCurl, CURLOPT_PORT, $intPort_a);
    curl_setopt($objCurl, CURLOPT_URL, $strRemote_a);

    CurlSetupAuthentication($objCurl, $strLogin_a, $strPassword_a);
    CurlSetupProgress($objCurl, $strProgressCallback_a);
    CurlSetupProxy($objCurl, $strProxyURL_a, $intProxyPort_a, $strProxyLogin_a, $strProxyPassword_a);
    $arrResult = CurlExecute($objCurl, true);

    curl_close($objCurl);
    fclose($objFile);

    return $arrResult;
}

function FTPPutFile($strLocal_a, $strRemote_a, $intPort_a, $strLogin_a, $strPassword_a, $strProgressCallback_a, $strProxyURL_a, $intProxyPort_a, $strProxyLogin_a, $strProxyPassword_a)
{
    $objFile = fopen($strLocal_a, 'r');
    $objCurl = curl_init();

    curl_setopt($objCurl, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($objCurl, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($objCurl, CURLOPT_INFILE, $objFile);
    curl_setopt($objCurl, CURLOPT_INFILESIZE, filesize($strLocal_a));
    curl_setopt($objCurl, CURLOPT_PORT, $intPort_a);
    curl_setopt($objCurl, CURLOPT_UPLOAD, true);
    curl_setopt($objCurl, CURLOPT_URL, $strRemote_a);

    CurlSetupAuthentication($objCurl, $strLogin_a, $strPassword_a);
    CurlSetupProgress($objCurl, $strProgressCallback_a);
    CurlSetupProxy($objCurl, $strProxyURL_a, $intProxyPort_a, $strProxyLogin_a, $strProxyPassword_a);
    $arrResult = CurlExecute($objCurl, true);

    curl_close($objCurl);
    fclose($objFile);

    return $arrResult;
}

function HTTPGet($strRemote_a, $intPort_a, $strLogin_a, $strPassword_a, $strProgressCallback_a, $strProxyURL_a, $intProxyPort_a, $strProxyLogin_a, $strProxyPassword_a)
{
    $objCurl = curl_init();

    curl_setopt($objCurl, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($objCurl, CURLOPT_HEADER, false);
    curl_setopt($objCurl, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($objCurl, CURLOPT_PORT, $intPort_a);
    curl_setopt($objCurl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($objCurl, CURLOPT_URL, $strRemote_a);

    CurlSetupAuthentication($objCurl, $strLogin_a, $strPassword_a);
    CurlSetupProgress($objCurl, $strProgressCallback_a);
    CurlSetupProxy($objCurl, $strProxyURL_a, $intProxyPort_a, $strProxyLogin_a, $strProxyPassword_a);
    $arrResult = CurlExecute($objCurl, false);

    curl_close($objCurl);

    return $arrResult;
}

function HTTPGetFile($strLocal_a, $strRemote_a, $intPort_a, $strLogin_a, $strPassword_a, $strProgressCallback_a, $strProxyURL_a, $intProxyPort_a, $strProxyLogin_a, $strProxyPassword_a)
{
    $objFile = fopen($strLocal_a, 'wb');
    $objCurl = curl_init();

    curl_setopt($objCurl, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($objCurl, CURLOPT_FILE, $objFile);
    curl_setopt($objCurl, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($objCurl, CURLOPT_FTP_USE_EPSV, false);
    curl_setopt($objCurl, CURLOPT_PORT, $intPort_a);
    curl_setopt($objCurl, CURLOPT_URL, $strRemote_a);

    CurlSetupAuthentication($objCurl, $strLogin_a, $strPassword_a);
    CurlSetupProgress($objCurl, $strProgressCallback_a);
    CurlSetupProxy($objCurl, $strProxyURL_a, $intProxyPort_a, $strProxyLogin_a, $strProxyPassword_a);
    $arrResult = CurlExecute($objCurl, true);

    curl_close($objCurl);
    fclose($objFile);

    return $arrResult;
}

// type = PUT or POST
function HTTPSendFile($strType_a, $strLocal_a, $strRemote_a, $strRemoteFilename_a, $intPort_a, $strLogin_a, $strPassword_a, $strProgressCallback_a, $strProxyURL_a, $intProxyPort_a, $strProxyLogin_a, $strProxyPassword_a)
{
    $objCurl = curl_init();
    $strFilename = basename($strRemoteFilename_a);
    $strExtension = pathinfo($strRemoteFilename_a, PATHINFO_EXTENSION);

    $strGUID = uniqid();
    $strPostFields = "--" . $strGUID . "\r\n" .
    "Content-Disposition: form-data; name=\"Resource\"; filename=\"" . $strFilename . "\"\r\n" .
    "Content-Type: application/" . $strExtension . "\r\n" .
    "\r\n" .
    file_get_contents($strLocal_a) . "\r\n" .
        "--" . $strGUID . "--";

    $arrHeader = array("Content-Type: multipart/form-data; boundary=" . $strGUID, "Content-Length: " . strlen($strPostFields));

    curl_setopt($objCurl, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($objCurl, CURLOPT_CUSTOMREQUEST, $strType_a);
    curl_setopt($objCurl, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($objCurl, CURLOPT_HEADER, true);
    curl_setopt($objCurl, CURLOPT_HTTPHEADER, $arrHeader);
    curl_setopt($objCurl, CURLOPT_PORT, $intPort_a);
    curl_setopt($objCurl, CURLOPT_POSTFIELDS, $strPostFields);
    curl_setopt($objCurl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($objCurl, CURLOPT_URL, $strRemote_a);

    CurlSetupAuthentication($objCurl, $strLogin_a, $strPassword_a);
    CurlSetupProgress($objCurl, $strProgressCallback_a);
    CurlSetupProxy($objCurl, $strProxyURL_a, $intProxyPort_a, $strProxyLogin_a, $strProxyPassword_a);
    $arrResult = CurlExecute($objCurl, true);

    curl_close($objCurl);

    return $arrResult;
}

// alternate method
function HTTPPutFile($strLocal_a, $strRemote_a, $intPort_a, $strLogin_a, $strPassword_a, $strProgressCallback_a, $strProxyURL_a, $intProxyPort_a, $strProxyLogin_a, $strProxyPassword_a)
{
    $objFile = fopen($strLocal_a, 'r');
    $objCurl = curl_init();

    curl_setopt($objCurl, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($objCurl, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($objCurl, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($objCurl, CURLOPT_INFILE, $objFile);
    curl_setopt($objCurl, CURLOPT_INFILESIZE, filesize($strLocal_a));
    curl_setopt($objCurl, CURLOPT_PORT, $intPort_a);
    curl_setopt($objCurl, CURLOPT_PUT, true);
    curl_setopt($objCurl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($objCurl, CURLOPT_UPLOAD, true);
    curl_setopt($objCurl, CURLOPT_URL, $strRemote_a);

    CurlSetupAuthentication($objCurl, $strLogin_a, $strPassword_a);
    CurlSetupProgress($objCurl, $strProgressCallback_a);
    CurlSetupProxy($objCurl, $strProxyURL_a, $intProxyPort_a, $strProxyLogin_a, $strProxyPassword_a);
    $arrResult = CurlExecute($objCurl, true);

    curl_close($objCurl);
    fclose($objFile);

    return $arrResult;
}

function isCurlEnabled()
{
    $blnResult = true;

    if (!is_callable('curl_init')) 
	{
        $blnResult = false;
    }

    return $blnResult;
}

function transmitFile($strServerType_a, $strLocal_a, $strRemote_a, $strRemoteFilename_a, $intPort_a, $strLogin_a, $strPassword_a, $strProgressCallback_a, $strProxyURL_a, $intProxyPort_a, $strProxyLogin_a, $strProxyPassword_a)
{
    $arrResult = array();
    $blnResult = true;
    $strProxyURL = $strProxyURL_a;

    if (strlen($strProxyURL) > 0) 
	{
        $strProxyURL = 'http://' . $strProxyURL;
    }

    switch ($strServerType_a) 
	{
        case SERVERTYPE_FTPGET:
            $strRemote = 'ftp://' . $strRemote_a . $strRemoteFilename_a;
            $arrResult = FTPGetFile($strLocal_a, $strRemote, $intPort_a, $strLogin_a, $strPassword_a, $strProgressCallback_a, $strProxyURL, $intProxyPort_a, $strProxyLogin_a, $strProxyPassword_a);
            break;

        case SERVERTYPE_FTPPUT:
            $strRemote = 'ftp://' . $strRemote_a . $strRemoteFilename_a;
            $arrResult = FTPPutFile($strLocal_a, $strRemote, $intPort_a, $strLogin_a, $strPassword_a, $strProgressCallback_a, $strProxyURL, $intProxyPort_a, $strProxyLogin_a, $strProxyPassword_a);
            break;

        case SERVERTYPE_HTTPGET:
            $strRemote = 'http://' . $strRemote_a . $strRemoteFilename_a;
            $arrResult = HTTPGetFile($strLocal_a, $strRemote, $intPort_a, $strLogin_a, $strPassword_a, $strProgressCallback_a, $strProxyURL, $intProxyPort_a, $strProxyLogin_a, $strProxyPassword_a);
            break;

        case SERVERTYPE_HTTPPOST:
            $strRemote = 'http://' . $strRemote_a;
            $arrResult = HTTPSendFile('POST', $strLocal_a, $strRemote, $strRemoteFilename_a, $intPort_a, $strLogin_a, $strPassword_a, $strProgressCallback_a, $strProxyURL, $intProxyPort_a, $strProxyLogin_a, $strProxyPassword_a);
            break;

        case SERVERTYPE_HTTPPUT:
            $strRemote = 'http://' . $strRemote_a;
            $arrResult = HTTPSendFile('PUT', $strLocal_a, $strRemote, $strRemoteFilename_a, $intPort_a, $strLogin_a, $strPassword_a, $strProgressCallback_a, $strProxyURL, $intProxyPort_a, $strProxyLogin_a, $strProxyPassword_a);
            break;

        case SERVERTYPE_HTTPSGET:
            $strRemote = 'https://' . $strRemote_a . $strRemoteFilename_a;
            $arrResult = HTTPGetFile($strLocal_a, $strRemote, $intPort_a, $strLogin_a, $strPassword_a, $strProgressCallback_a, $strProxyURL, $intProxyPort_a, $strProxyLogin_a, $strProxyPassword_a);
            break;

        case SERVERTYPE_HTTPSPOST:
            $strRemote = 'https://' . $strRemote_a;
            $arrResult = HTTPSendFile('POST', $strLocal_a, $strRemote, $strRemoteFilename_a, $intPort_a, $strLogin_a, $strPassword_a, $strProgressCallback_a, $strProxyURL, $intProxyPort_a, $strProxyLogin_a, $strProxyPassword_a);
            break;

        case SERVERTYPE_HTTPSPUT:
            $strRemote = 'https://' . $strRemote_a;
            $arrResult = HTTPSendFile('PUT', $strLocal_a, $strRemote, $strRemoteFilename_a, $intPort_a, $strLogin_a, $strPassword_a, $strProgressCallback_a, $strProxyURL, $intProxyPort_a, $strProxyLogin_a, $strProxyPassword_a);
            break;

        default:
            $blnResult = false;
    }

    $intResult = $arrResult["result"];
    if (($intResult != 200) && ($intResult != 226)) 
	{
        $blnResult = false;
    }

    $arrResult["result"] = $blnResult;
    return $arrResult;
}
