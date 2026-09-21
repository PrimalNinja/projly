<?php

$arrFunctions = array(
	"cart_add" => array("dependencies" => 'cart/actionCartAdd',    "function" => 'actionCartAdd'),    
	"cart_clear" => array("dependencies" => 'cart/actionCartClear',    "function" => 'actionCartClear'),
    "cart_commit" => array("dependencies" => 'cart/actionCartCommit', "function" => 'actionCartCommit'),
	"cart_fetch" => array("dependencies" => 'cart/actionCartFetch',    "function" => 'actionCartFetch'),
    "cart_fetchcmsthankyou" => array("dependencies" => 'cart/actionCartFetchCMSThankyou', "function" => 'actionCartFetchCMSThankyou'),    
	"cart_fetchpaymentmethods" => array("dependencies" => 'cart/actionCartFetchPaymentMethods', "function" => 'actionCartFetchPaymentMethods'),
	"cart_fetchproducts" => array("dependencies" => 'cart/actionCartFetchProducts', "function" => 'actionCartFetchProducts'),
    "cart_getreceipt" => array("dependencies" => 'cart/actionCartGetReceipt',    "function" => 'actionCartGetReceipt'),
	"cart_updateprogress" => array("dependencies" => 'cart/actionCartUpdateProgress',    "function" => 'actionCartUpdateProgress'),
    "cart_productsremove" => array("dependencies" => 'cart/actionCartProductsRemove', "function" => 'actionCartProductsRemove')
);

$arrFunction = validateFunction($arrFunctions, $strFunction);
if ($arrFunction !== null) {
    $strResult = dispatchFunction($this->m_objConn, $strSecurityToken, $strDataID, $arrFunction, $arrParameters);
    $blnDispatched = true;
}
