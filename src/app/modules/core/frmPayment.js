/*jsl:option explicit*/
/*jsl:import ..\..\inc-os.js*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-osutils-jdock.js*/

function core_frmPayment(objOS_a, strFormID_a, objParameters_a)
{
	var os = objOS_a;
	var m_objThis = this;
	var m_strFormID = strFormID_a;
	var m_objParameters = objParameters_a;

	// ------------------------------------------------------------------------------------

	var m_blnReadonly = false;
	var m_blnFormDirty = false;

	var m_intToFetch = 0;
	var m_intFetched = 0;
	var m_intErrors = 0;

	var m_strUnbindFormFields = 'ge-ccaccountname-field,ge-ccnumber-field,ge-ccmonth-field,ge-ccyear-field,ge-cctype-option,ge-ccsecuritycode-field'; // excludes suburbfinder fields

	var m_strFormMode = '';
	var m_blnIsCreditCard = 'Y';

	// ------------------------------------------------------------------------------------
    var m_strProductFilter = '';
    var m_strProductID = ''; //required parameter if the mode is addtocart
    var m_blnPaymentRequired = false;

	var m_arrPaymentMethods = null;
	var m_arrProducts = null;
	var m_objAccountPaymentStatus = null;
    var m_objTransaction = null;
	var m_strProgress = '';

    var m_fltTotalIncGST = 0;
	var m_blnPaymentInProcess = false;

    var m_objProduct;

	var m_strFormTitle = m_objParameters.title; // optional form title
	if (m_strFormTitle === undefined)
	{
		m_strFormTitle = ""; // + ' ' + m_strFormEntityCode;
	}

	var m_strApplicantName = m_objParameters.applicantname;
	if (m_strApplicantName === undefined)
	{
		m_strApplicantName = ""; // will assume yourself
	}
	
    var m_blnMultiselection = os.toBoolean(m_objParameters.multi); // if true, allow multi-selection in products

	var m_strProductTypeHeading = m_objParameters.producttypeheading; // optional form producttypeheading
	if (m_strProductTypeHeading === undefined)
	{
		m_strProductTypeHeading = "";
	}

	var m_strProductType = m_objParameters.producttype; // optional form producttype
	if (m_strProductType === undefined)
	{
		m_strProductType = "";
	}

    // var m_blnModeCheckout = m_objParameters.checkout;

    // if(m_blnModeCheckout === undefined)
	// {
       // m_blnModeCheckout = false; // default to false
    // }
    // else
	// {
       // m_blnModeCheckout = os.toBoolean(m_blnModeCheckout);
    // }

    m_strProductID = m_objParameters.productid;
    if(m_strProductID === undefined)
	{
       m_strProductID = '';
    }

    //mode values
    var MODE_ADDTOCART = 'addtocart'; // will not show frmPayment form, but product will automatically added to cart. required: product id to be pass.
    var MODE_CHECKOUT = 'checkout'; // will not show product selection will go directy the checkout section
	var MODE_CONTINUE = 'continue'; // continue with current progress
    var MODE_SELECTPRODUCT = 'selectproduct'; // will show product selection



    var m_strMode = m_objParameters.mode; // value: addtocart, checkout, continue, selectproduct

    if(m_strMode === undefined)
	{
       m_strMode = MODE_CONTINUE;
    }

	var m_strOriginalMode = m_strMode; // the mode the form is opened in


    // ====================================================================================

	var m_objDock;
	var m_arrMap = [
		{
			location : 'root',
			title : 'Home',
			layout : [
				['CloseButton']
			]
		}
	];

	var m_arrTiles = [
		// miscellaneous tiles
		{
			id : '-',
			caption : '',
			classes : '',
			permissions : [],
			action : '',
			tip : '',
			type : 'blank'
		},
		{
			id : 'CloseButton',
			caption : 'Close',
			classes : 'btn-success',
			permissions : [],
			action : function ()
			{
				m_objThis.FormClose_onClick();
			},
			tip : 'Click here to close the form',
			type : 'toolbarbutton'
		}
	];

	// ====================================================================================
	// HELPERS ============================================================================

	function afterAjaxError()
	{
		setDirty(true);
	}

	function cancelTransaction()
	{
        var strPrompt = "Are you sure you wish to clear your cart?";

        os.dialogConfirm(strPrompt, function ()
        {
            clearCart();
			os.closeForm(m_strFormID);
        });
	}

	function getProduct(strProductID_a)
	{
		var objResult; // = null;

        processArray(m_arrProducts, function (objProduct_a)
        {
            if (objProduct_a.id === strProductID_a)
			{
				objResult = objProduct_a;
                return true;
            }
        });

		return objResult;
	}

    function processCreditCard()
	{
        var strErrors = '';
        var osObj = os.element(m_strFormID, '.ge-paymentmethod-field:checked');
        var strPaymentCode = osObj.data('code');
        var strClassPaymentMethod = '.ge-paymentmethod-' + strPaymentCode;
        var objPaymentMethod = os.element(m_strFormID, strClassPaymentMethod);
        var objCard = {};
        var strPaymentMethodID = osObj.val();

        objCard.ccaccountname = os.element(objPaymentMethod, '.ge-ccaccountname-field').val();
        objCard.ccnumber = os.element(objPaymentMethod, '.ge-ccnumber-field').val();
        objCard.ccmonth = os.element(objPaymentMethod, '.ge-ccmonth-field').val();
        objCard.ccyear = os.element(objPaymentMethod, '.ge-ccyear-field').val();
        objCard.ccsecuritycode = ""; //os.element(objPaymentMethod, '.ge-ccsecuritycode-field').val();

        strErrors = validateCard(objCard);

        if (strErrors.length > 0)
		{
			// have an error, so display it
			os.dialogAlertScroll(strErrors, function () {});
		}
		else
		{
			cartCommit(strPaymentMethodID, objCard);
		}
    }

	function setDirty(blnDirty_a)
	{
		m_blnFormDirty = blnDirty_a;

		if (m_blnReadonly)
		{
			//m_objDock.disableButtons('SaveButton', 'gs-darkblue-background-colour gs-glow-focusborder gs-red-background-colour gs-glow-dirty');
			doNothing();
		}
		else
		{
			if (m_blnFormDirty)
			{
				//m_objDock.disableButtons('SaveButton', 'gs-darkblue-background-colour');
				//m_objDock.enableButtons('SaveButton', 'gs-red-background-colour gs-glow-focusborder gs-glow-dirty');
				doNothing();
			}
			else
			{
				//m_objDock.disableButtons('SaveButton', 'gs-red-background-colour gs-glow-dirty');
				//m_objDock.enableButtons('SaveButton', 'gs-darkblue-background-colour gs-glow-focusborder');
				doNothing();
			}
		}
	}

	function showCheckoutError()
	{
		showNoSteps();
		os.element(m_strFormID, '.ge-step-checkouterror').show();
	}

	function showNoSteps()
	{
		os.element(m_strFormID, '.ge-step-checkouterror').hide();
		os.element(m_strFormID, '.ge-step-cart').hide();
		os.element(m_strFormID, '.ge-step-payer').hide();
		os.element(m_strFormID, '.ge-step-paymentmethods').hide();
		os.element(m_strFormID, '.ge-step-products').hide();
		os.element(m_strFormID, '.ge-step-addproducts').hide();
		os.element(m_strFormID, '.ge-step-thankyou').hide();
	}

	function showProducts()
	{
		m_strProgress = 'products';
		showNoSteps();
		populateProducts();
		populateCart('.ge-cartcontent1');
		bindProducts();
		bindCart1();
		os.element(m_strFormID, '.ge-step-products').show();
	}
	
	function showPayer()
	{
		m_strProgress = 'payer';
		showNoSteps();
		populatePayer();
		bindPayer();
		os.element(m_strFormID, '.ge-step-payer').show();
	}
	
	function showCart()
	{
        // only process if there is a transactionid returned. 
        // otherwise, it means transaction already does not exists
        if (m_objTransaction.transactionlines.length > 0)
        {
            m_strProgress = 'cart';
            showNoSteps();
            var intItemCount = populateCart('.ge-cartcontent2');
            bindCart2(intItemCount);
            os.element(m_strFormID, '.ge-step-cart').show();
        }
        else
        {
            m_strProgress = '';
            showProducts();
        }
	}
	
    function showPaymentMethods()
	{
		showNoSteps();
		populatePaymentMethods();
		populatePaymentMethodFields();
		bindPaymentMethods();
		os.element(m_strFormID, '.ge-step-paymentmethods').show();
    }
	
    function showThankyou()
	{
		showNoSteps();
		os.element(m_strFormID, '.ge-step-thankyou').show();
    }
	
	function validate()
	{
		var strError = '';

        if (m_strProgress === 'products')
		{
			if (m_objTransaction.transactionlines.length === 0)
			{
				if (!os.element(m_strFormID, '.ge-products-option').is(':checked'))
				{
					strError += '<li>Product / Subscription is required.</li>';
				}
			}
        }
		else if (m_strProgress === 'paymentmethods')
		{
			if (m_blnPaymentRequired)
			{
				if (!os.element(m_strFormID, '.ge-paymentmethod-field').is(':checked'))
				{
					strError += '<li>Payment Method is required.</li>';
				}
			}
        }

		if (strError.length > 0)
		{
			strError = '<ul>' + strError + '</ul>';
		}

		return strError;
	}

    function validateCard(objCard_a)
	{
        var strError = '';

        if ($.trim(objCard_a.ccaccountname).length === 0)
        {
            strError += '<li>Account Name is required.</li>';
        }

        if ($.trim(objCard_a.ccnumber).length === 0)
        {
            strError += '<li>Credit Card Number is required.</li>';
        }

        if ($.trim(objCard_a.ccmonth).length === 0)
        {
            strError += '<li>Credit Card Expiry Month is required.</li>';
        }

        if ($.trim(objCard_a.ccyear).length === 0)
        {
            strError += '<li>Credit Card Expiry Year is required</li>';
        }

        //if ($.trim(objCard_a.ccsecuritycode).length === 0)
        //{
            //strError += '<li>Credit Card Security Code is required.</li>';
        //}

		if (strError.length > 0)
		{
			strError = '<ul>' + strError + '</ul>';
		}

		return strError;

    }

	// ====================================================================================
	// POPULATING =========================================================================

	function initialiseForm()
	{
		// resize the form
		var intViewPortWidth = os.getViewPort().width;
		var intViewPortHeight = os.getViewPort().height;
		os.resizeAndRepositionForm(m_strFormID, intViewPortHeight*0.1, intViewPortWidth*0.1, intViewPortHeight*0.8, intViewPortWidth*0.8);
        os.element(m_strFormID, '.ge-form-title').text(m_strFormTitle);
		os.element(m_strFormID, '.ge-popupblockerurl').attr("href", POPUPBLOCKER + os.getBrowser().name);
		
		os.element(m_strFormID, '.ge-producttypeheading').text(m_strProductTypeHeading);
		os.element(m_strFormID, '.ge-producttype').text(m_strProductType);
	}

    function populateDock()
	{
		m_objDock = new jDock(os,
		{
			"alwaysvisiblebuttoncount": 2,
			"map" : m_arrMap,
			"tiles" : m_arrTiles
		});

		m_objDock.render(m_strFormID, '.ge-button-panel');
	}

    function prePopulateValidate()
	{
        var strErrors = '';

        if (m_strMode === MODE_ADDTOCART)
		{
           if(m_strProductID.length === 0)
		   {
              strErrors = "<li>Product is required.</li>";
            }
            else
			{
                processArray(m_arrProducts, function (objProduct_a)
                {
                    if(objProduct_a.id === m_strProductID)
                    {
                        m_objProduct = objProduct_a;
                    }

                });

                // if product does not exists on the product list. do not proceed
                if(m_objProduct === undefined)
				{
                    strErrors = "<li>The product is not available</li>";
                }
            }
        }

        if(strErrors.length > 0)
        {
           strErrors = '<ul>' + strErrors + '</ul>';
        }

        return strErrors;
    }

    function addToCartModeAfterProcess()
	{
        var strMsg = "Product added to cart";

        os.closeForm(m_strFormID);
        os.dialogAlertScroll(strMsg, doNothing);
    }

	function populateForm()
	{
        var strErrors  = '';
        
        strErrors = prePopulateValidate();

        if(strErrors.length > 0)
        {
            os.closeForm(m_strFormID);
            os.dialogAlertScroll(strErrors, doNothing);
        }
        else
        {
            if (m_strMode === MODE_ADDTOCART)
			{
                addToCart([m_objProduct.id], 'cart', addToCartModeAfterProcess);
            }
            else 
            {
				if (m_strMode === MODE_CONTINUE)
				{
					if (m_objTransaction.progress.length > 0)
					{
					   m_strProgress = m_objTransaction.progress;
					}
				}
				else if (m_strMode === MODE_CHECKOUT)
				{
					//showCart();
					showPayer();	// it might have no content
				}
				else	// MODE_CONTINUE or MODE_SELECTPRODUCT
				{
					// work out which page to show
					if (m_strProgress === 'payer')
					{
						showPayer();
					}
					else if (m_strProgress === 'cart')
					{
						showCart();
					}
					else
					{
						os.element(m_strFormID, '.ge-cartmessage-wrapper').show();
						// var intLines = m_objTransaction.transactionlines.length;
						// if (intLines === 0)
						// {
							// os.element(m_strFormID, '.ge-cartmessage').html('<h4><font color="blue">You currently have no items in your cart.</font></h4><br>');
						// }
						// else if (intLines === 1)
						// {
							// os.element(m_strFormID, '.ge-cartmessage').html('<h4><font color="blue">You currently have ' + htmlEncode(intLines) + ' item in your cart.</font></h4><br>');
						// }
						// else
						// {
							// os.element(m_strFormID, '.ge-cartmessage').html('<h4><font color="blue">You currently have ' + htmlEncode(intLines) + ' items in your cart.</font></h4><br>');
						// }
						showProducts();
					}
				}
            }
        }
	}

    function populateCart(strCartContentTag_a)
	{
        var fltIncGST = 0;
        var fltExGST = 0;
		var fltTotalExGST = 0;
		var fltTotalIncGST = 0;
		var fltTotalGST = 0;
        var intItemCount = 0;
		var strContent = '';
        var strGracePeriodLabel = '';
		var strIncGST = '';

		strContent += '<table class="table orderreview table-bordered">';
		strContent +=   '<thead>';
		strContent +=       '<tr><th>Details</th><th style="text-align:center">Remove</th><th>Total (Inc-GST)</th></tr>';
		strContent +=   '</thead>';
		strContent +=   '<tbody>';

        processArray(m_objTransaction.transactionlines, function (objTransactionLine_a)
		{
            if(os.toBoolean(objTransactionLine_a.ingraceperiod))
            {
                fltExGST = 0;
                fltIncGST = 0;
				strIncGST = '$' + os.formatMoney(fltIncGST);
                strGracePeriodLabel = '&nbsp;(grace)';
            }
            else
            {
                fltExGST = objTransactionLine_a.priceexgst;
                fltIncGST = objTransactionLine_a.priceincgst;
				strIncGST = '$' + os.formatMoney(fltIncGST);
                strGracePeriodLabel = '';
            }

            fltTotalExGST += parseInt(fltExGST * 100, 10) * 0.01;
            fltTotalIncGST += parseInt(fltIncGST * 100, 10) * 0.01;

            strContent += '<tr>';
            strContent += '<td>' + htmlEncode(objTransactionLine_a.description) + '</td>';
            strContent += '<td style="text-align:center"><span class="gb-button ge-removeproduct-button" data-id="'+htmlEncode(objTransactionLine_a.id)+'"><i class="ge-trash' + intItemCount + ' glyphicon glyphicon-trash"></i></span></td>';
            strContent += '<td>' + strIncGST + strGracePeriodLabel + '</td>';
            strContent += '</tr>';

            intItemCount++;
		});

		strContent +=   '</tbody>';
		strContent +=   '<tfoot>';
		
		fltTotalGST = parseInt((fltTotalIncGST * 100) - (fltTotalExGST * 100), 10) * 0.01;

		strContent +=   '<tr><td colspan="2">GST:</td><td>$' + os.formatMoney(fltTotalGST) + '</td></tr>';
		strContent +=   '<tr><td colspan="2">Total (Inc-GST):</td><td>$' + os.formatMoney(fltTotalIncGST) + '</td></tr>';
		strContent +=   '</tfoot>';
		strContent +=   '</table>';

		m_blnPaymentRequired = (fltTotalIncGST > 0);
        m_fltTotalIncGST = fltTotalIncGST;

		if (intItemCount > 0)
		{
			os.element(m_strFormID, strCartContentTag_a).html(strContent);
			
			intItemCount = 0;
			processArray(m_objTransaction.transactionlines, function (objTransactionLine_a)
			{
				os.showTip(m_strFormID, '.ge-trash' + intItemCount, 'bottom center', 'top center', 2, 'Remove this item from the cart');
				intItemCount++;
			});
		}
		else
		{
			strContent = 'Your cart is empty.';
			os.element(m_strFormID, strCartContentTag_a).html(strContent);
		}

		return intItemCount;
    }

	function populatePayer()
	{
		os.element(m_strFormID, '.ge-payername-field').val(m_objTransaction.payername);
		os.element(m_strFormID, '.ge-payeraddressline1-field').val(m_objTransaction.payeraddressline1);
		os.element(m_strFormID, '.ge-payeraddressline2-field').val(m_objTransaction.payeraddressline2);
		os.element(m_strFormID, '.ge-payersuburb-field').val(m_objTransaction.payersuburb);
		os.element(m_strFormID, '.ge-payerstate-field').val(m_objTransaction.payerstate);
		os.element(m_strFormID, '.ge-payerpostcode-field').val(m_objTransaction.payerpostcode);
		os.element(m_strFormID, '.ge-payernotes-field').val(m_objTransaction.payernotes);
	}
	
    function populatePaymentMethods()
	{
        var strContent = '';
        var strSelected = '';

        if(m_fltTotalIncGST > 0)
        {
            strContent += '<div style="margin:10px 0;"><b>Total (Inc-GST):&nbsp;&nbsp;$' + os.formatMoney(m_fltTotalIncGST) + '</b></div><br>';

        }

        strContent += '<ul class="list-items">';

		if (m_blnPaymentRequired)
		{
			processArray(m_arrPaymentMethods, function (objPaymentMethod_a)
			{
				if (os.toBoolean(objPaymentMethod_a.enabled))
				{
					strSelected = '';

					if (m_objTransaction.paymentmethodid !== null && m_objTransaction.paymentmethodid.length > 0)
					{
						if (m_objTransaction.paymentmethodid === objPaymentMethod_a.id)
						{
							strSelected = 'checked';
						}
					}
					
					var strDescription = objPaymentMethod_a.description;
					if (os.toBoolean(objPaymentMethod_a.is_cc) && (objPaymentMethod_a.paymentproviderdescription.length > 0))
					{
						strDescription += ' (' + objPaymentMethod_a.paymentproviderdescription + ')';
					}

					strContent +=    '<li class="radio">';
					strContent +=       '<label>';
					strContent +=           '<input type="radio" ' + strSelected + ' class="ge-paymentmethod-option ge-paymentmethod-field" name="ge-paymentmethod" data-code="' + htmlEncode(objPaymentMethod_a.code) + '" data-iscreditcard="' + htmlEncode(objPaymentMethod_a.is_cc) + '" value="' + htmlEncode(objPaymentMethod_a.id) + '"  />&nbsp;' + htmlEncode(strDescription);
					strContent +=       '</label>';
					strContent +=    '</li>';
				}
			});
		}
		else
		{
			strContent += '<b>No payment is required.</b><br/><br/>To proceed please press the <b>Confirm Purchase</b> button below.';
		}

        strContent += '</ul><br>';
        os.element(m_strFormID, '.ge-paymentmethodscontent').html(strContent);
    }

    function populatePaymentMethodFields()
	{
        var strContent = '';
        var strHidden = '';

        strContent += '<ul class="ge-paymentmethodfields list-items">';

        processArray(m_arrPaymentMethods, function (objPaymentMethod_a)
		{
            strHidden = ' gb-hidden';
            if (m_objTransaction.paymentmethodid !== null && m_objTransaction.paymentmethodid.length > 0)
			{
                if (m_objTransaction.paymentmethodid === objPaymentMethod_a.id)
				{
                    strHidden = '';
                }
            }

            strContent +=    '<li class="ge-paymentmethod-' + htmlEncode(objPaymentMethod_a.code) + strHidden + '">';

            if (os.toBoolean(objPaymentMethod_a.enabled))
			{
                if (os.toBoolean(objPaymentMethod_a.is_cc))
				{
					if (objPaymentMethod_a.notes.length > 0)
					{
						strContent += '<div>' + htmlEncode(objPaymentMethod_a.notes) + '</div><br><br>';
					}
					
                    strContent += '<h4>Credit Card Details</h4>';
                    strContent += '<div class="form-group">';
                    strContent +=   '<label>Account Name:</label>';
                    strContent +=    '<input type="text" class="form-control ge-ccaccountname-field" placeholder="Account Name">';
                    strContent += '</div>';
                    strContent += '<div class="form-group">';
                    strContent +=   '<label>Credit Card Number:</label>';
                    strContent +=    '<input type="text" class="form-control ge-ccnumber-field" placeholder="Credit Card Number">';
                    strContent += '</div>';
                    strContent += '<div class="form-group row">';
                    strContent +=     '<div class="col-md-6">';
                    strContent +=         '<label>Expiry Date MM:</label>';
                    strContent +=       '<input type="text" class="form-control ge-ccmonth-field" placeholder="MM">';
                    strContent +=    '</div>';
                    strContent +=    '<div class="col-md-6">';
                    strContent +=        '<label>Expiry Date YYYY:</label>';
                    strContent +=        '<input type="text" class="form-control ge-ccyear-field" placeholder="YYYY">';
                    strContent +=    '</div>';
                    strContent += '</div>';
                    //strContent += '<div class="form-group">';
                    //strContent +=   '<label>Security Code:</label>';
                    //strContent +=    '<input type="text" class="form-control ge-ccsecuritycode-field" placeholder="Security Code">';
                    //strContent += '</div>';

                }
                else
				{
                    strContent += '<div>' + htmlEncode(objPaymentMethod_a.notes) + '</div>';
                }

                strContent +=    '</li>';
            }
		});

        strContent += '</ul>';
        os.element(m_strFormID, '.ge-paymentmethodfields').html(strContent);
        //os.element(m_strFormID, '.ge-continue-button').removeAttr('disabled');
    }

    function populateProducts()
	{
		var intProducts = 0;
        var strContent = '';
        var strPriceIncGST = '0.00';
        var strPriceExGST = '0.00';
        var strGracePeriodLabel = '';
        //m_strApplicantName
		
        strContent += '<ul class="list-items">';

        processArray(m_arrProducts, function (objProduct_a)
        {
            var blnVisible = true;
            /*
            if (m_objTransaction.transactionlines.length > 0)
			{
                processArray(m_objTransaction.transactionlines, function(objTransactionLine_a)
                {
                    if (objProduct_a.id === objTransactionLine_a.productid)
                    {
                        blnVisible = false;
                    }
                 });
            }
             */
			if (blnVisible)
			{
                if(os.toBoolean(objProduct_a.ingraceperiod))
                {
                    strPriceIncGST = "$0.00";
                    strGracePeriodLabel = "&nbsp;(grace)";
                }
                else
                {
                    strPriceIncGST = '$' + os.formatMoney(objProduct_a.price_incgst);
                    strGracePeriodLabel = "";
                }

				strContent += '<li class="radio">';

				if(m_blnMultiselection)
				{
					strContent += '<label><input type="checkbox" class="ge-products-option" name="ge-product[]" value="' + htmlEncode(objProduct_a.id) + '"  />&nbsp;' + htmlEncode(objProduct_a.description) + '&nbsp;&nbsp;' + strPriceIncGST + strGracePeriodLabel + '</label>';
					intProducts++;
				}
				else
				{
					strContent += '<label><input type="radio" class="ge-products-option" name="ge-product" value="' + htmlEncode(objProduct_a.id) + '"  />&nbsp;' + htmlEncode(objProduct_a.description) + '&nbsp;&nbsp;' + strPriceIncGST + strGracePeriodLabel + '</label>';
					intProducts++;
				}

				strContent += '</li>';
			}
        });

        strContent += '</ul>';

        if(m_strApplicantName.length > 0)
		{
            os.element(m_strFormID, '.ge-applicantname-header').html(htmlEncode(m_strApplicantName));
        }
		
        os.element(m_strFormID, '.ge-productscontent').html(strContent);

		if (intProducts > 0)
		{
			os.element(m_strFormID, '.ge-step-addproducts').show();
		}
		else
		{
			os.element(m_strFormID, '.ge-step-addproducts').hide();
		}
		os.element(m_strFormID, '.ge-step-products').show();
    }

	// ====================================================================================
	// WEBSERVICE RETURNS =================================================================

	// after all asynchronous fetching we go to here
	function asyncDataIsFetched()
	{
		m_intFetched++;
		if (m_intToFetch == m_intFetched)
		{
			populateForm();
		}
	}

	function asyncError()
	{
		if (m_intErrors === 0)
		{
			os.ajaxError();
		}
		m_intErrors++;
	}

    function cartFetched(objResponse_a)
	{
		m_objTransaction = objResponse_a[0];
		asyncDataIsFetched();
    }

	function paymentMethodsFetched(objResponse_a)
	{
		m_arrPaymentMethods = objResponse_a;
		asyncDataIsFetched();
	}

    function productsFetched(objResponse_a)
	{
		m_arrProducts = objResponse_a;
		asyncDataIsFetched();
	}

    function paymentProcessed(objResponse_a)
	{
        m_blnPaymentInProcess = false;

        showThankyou();
    }

    function cartRefetched(objResponse_a)
	{
		m_objTransaction = objResponse_a[0];
		refetchProducts();
		// if (m_objTransaction.transactionlines.length === 0)
		// {
			// m_strMode = m_strOriginalMode;
			// populateForm();
		// }
		// else
		// {
			// m_strMode = MODE_CHECKOUT;
			// populateForm();
		// }
    }

    function productsRefetched(objResponse_a)
	{
		m_arrProducts = objResponse_a;

		if (m_objTransaction.transactionlines.length === 0)
		{
			m_strMode = m_strOriginalMode;
			populateForm();
		}
		else
		{
			//m_strMode = MODE_CHECKOUT;
			m_strMode = m_strOriginalMode;
			populateForm();
		}
    }

    function cmsThankyouFetched(objResponse_a)
	{
        os.element(m_strFormID, '.ge-thankyoucontent').html(objResponse_a[0].content);
        asyncDataIsFetched();
    }

	// ====================================================================================
	// WEBSERVICE CALLS ===================================================================

	// cancel the cart but not the pending client product security
    function clearCart()
	{
        var objJSON = os.ajaxRequestCreate('cart_clear', []);
		os.ajaxCall(URL_WEBSERVICE, objJSON, doNothing, os.ajaxError, doNothing, true);
    }

    function addToCart(arrProductIDs_a, strProgress_a, cbNextStep_a)
	{
        //alert(cbNextStep_a);

		function updateProgressWrapper(strID_a)
		{
            var strProductDescription = '';
            var objProduct;

            processArray(arrProductIDs_a, function(strProductID_a)
			{
                objProduct = getProduct(strProductID_a);

				if (m_strApplicantName.length > 0)
				{
					strProductDescription = objProduct.description + " for " + m_strApplicantName;
				}
				else
				{
					strProductDescription = objProduct.description;
				}
                m_objTransaction.transactionlines.push({"id":strID_a, "productid":strProductID_a, "description":strProductDescription, "priceincgst" : objProduct.price_incgst, "priceexgst" : objProduct.price_exgst, "ingraceperiod" : objProduct.ingraceperiod  });
			});

            updateProgress(strProgress_a, cbNextStep_a, false);
		}

        var objJSON = os.ajaxRequestCreate('cart_add',
		[
			{
				'name'  : 'id',
				'value' : arrProductIDs_a[0]
			},
			{
				'name'  : 'idlist',
				'value' : arrProductIDs_a
			},
			{
				'name'  : 'applicantname',
				'value' : m_strApplicantName
			}
		]);

        os.ajaxCall(URL_WEBSERVICE, objJSON, updateProgressWrapper, os.ajaxError, doNothing, true);
    }

    function getCartReceipt()
	{
        var objJSON = os.ajaxRequestCreate('cart_getreceipt',[]);

        os.ajaxCall(URL_WEBSERVICE, objJSON, doNothing, os.ajaxError, doNothing, true);
    }

    // JHUN this should update the pending cart only, not the underlying account
    function fetchProducts()
	{
        var objJSON = os.ajaxRequestCreate('cart_fetchproducts', [
            {
                'name' : 'filter',
                'value' : m_strProductFilter
            }
        ]);
		os.ajaxCall(URL_WEBSERVICE, objJSON, productsFetched, os.ajaxError, doNothing, true);
    }

    function fetchCart()
	{
        var objJSON = os.ajaxRequestCreate('cart_fetch', []);
        os.ajaxCall(URL_WEBSERVICE, objJSON, cartFetched, os.ajaxError, doNothing, true);
    }

	function fetchData()
	{
		m_intToFetch = 4;
		m_intFetched = 0;
		m_intErrors = 0;

		fetchCart();
		fetchPaymentMethods();
		fetchProducts();
		fetchCMSThankyou();
	}

	function fetchPaymentMethods()
	{
        var objJSON = os.ajaxRequestCreate('cart_fetchpaymentmethods', []);
		os.ajaxCall(URL_WEBSERVICE, objJSON, paymentMethodsFetched, os.ajaxError, doNothing, true);
	}

    function cartCommit(strPaymentMethodID_a, objCard_a)
	{
		var objJSON = os.ajaxRequestCreate("cart_commit",
                [
                    {
                        'name'  : 'paymentmethodid',
                        'value' : strPaymentMethodID_a
                    },
                    {
                        'name'  : 'card',
                        'value' : objCard_a
                    }
                ]);

         os.ajaxCall(URL_WEBSERVICE, objJSON, paymentProcessed, os.ajaxError, doNothing, true);
    }

    function fetchCMSThankyou()
	{
        var objJSON = os.ajaxRequestCreate('cart_fetchcmsthankyou', []);
        os.ajaxCall(URL_WEBSERVICE, objJSON, cmsThankyouFetched, os.ajaxError, doNothing, true);
    }

    function refetchCart()
	{
        var objJSON = os.ajaxRequestCreate('cart_fetch', []);
        os.ajaxCall(URL_WEBSERVICE, objJSON, cartRefetched, os.ajaxError, doNothing, true);
    }

    function refetchProducts()
	{
        var objJSON = os.ajaxRequestCreate('cart_fetchproducts', [
            {
                'name' : 'filter',
                'value' : m_strProductFilter
            }
        ]);
        os.ajaxCall(URL_WEBSERVICE, objJSON, productsRefetched, os.ajaxError, doNothing, true);
    }

    function removeProductsFromCart(arrProductIDs_a)
    {
        var objJSON = os.ajaxRequestCreate('cart_productsremove',[
		{
			'name'  : 'id',
			'value' : arrProductIDs_a[0]
		},
        {
			'name'  : 'idlist',
			'value' : arrProductIDs_a
		}]);

        os.ajaxCall(URL_WEBSERVICE, objJSON, refetchCart, os.ajaxError, doNothing, true);
    }

    function updateProgress(strProgress_a, cbNextStep_a, blnUpdatePayer_a)
	{

        var objJSON = os.ajaxRequestCreate('cart_updateprogress',
                            [
                                {   'name'  : 'progress',
                                    'value' : strProgress_a
                                },
                                {   'name'  : 'updatepayer',
                                    'value' : blnUpdatePayer_a
                                },
                                {   'name'  : 'payername',
                                    'value' : m_objTransaction.payername
                                },
                                {   'name'  : 'payeraddressline1',
                                    'value' : m_objTransaction.payeraddressline1
                                },
                                {   'name'  : 'payeraddressline2',
                                    'value' : m_objTransaction.payeraddressline2
                                },
                                {   'name'  : 'payersuburb',
                                    'value' : m_objTransaction.payersuburb
                                },
                                {   'name'  : 'payerstate',
                                    'value' : m_objTransaction.payerstate
                                },
                                {   'name'  : 'payerpostcode',
                                    'value' : m_objTransaction.payerpostcode
                                },
                                {   'name'  : 'payernotes',
                                    'value' : m_objTransaction.payernotes
                                }
                            ]);

        os.ajaxCall(URL_WEBSERVICE, objJSON, cbNextStep_a, os.ajaxError, doNothing, true);
    }

	// ====================================================================================
	// BINDINGS ===========================================================================

	function bindCart1()
	{
		// unbindings
		os.unbindEvents(m_strFormID, 'ge-removeproduct-button,ge-cartcancel-button');

		// bindings
        os.bindEvent(m_objThis, m_strFormID, '.ge-removeproduct-button', 'RemoveProductButton', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-cartcancel-button', 'CartCancelButton', 'onClick');
	}
	
	function bindCart2(intItemCount_a)
	{
		// unbindings
		os.unbindEvents(m_strFormID, 'ge-cartnext-button,ge-cartpayer-button,ge-cartviewreceipt-button,ge-removeproduct-button,ge-cartcancel-button');

		// bindings
		os.bindEvent(m_objThis, m_strFormID, '.ge-cartnext-button', 'CartNextButton', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-cartpayer-button', 'CartPayerButton', 'onClick');
        os.bindEvent(m_objThis, m_strFormID, '.ge-cartviewreceipt-button', 'ViewReceiptButton', 'onClick');
        os.bindEvent(m_objThis, m_strFormID, '.ge-removeproduct-button', 'RemoveProductButton', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-cartcancel-button', 'CartCancelButton', 'onClick');

        if(intItemCount_a > 0)
        {
            os.element(m_strFormID, '.ge-step-cart .ge-cartnext-button').removeAttr('disabled');
            os.element(m_strFormID, '.ge-step-cart .ge-cartviewreceipt-button').removeAttr('disabled');
        }
        else
        {
            os.element(m_strFormID, '.ge-step-cart .ge-cartnext-button').attr('disabled', 'disabled');
            os.element(m_strFormID, '.ge-step-cart .ge-cartviewreceipt-button').attr('disabled', 'disabled');
        }
	}
	
	function bindPayer()
	{
		// unbindings
		os.unbindEvents(m_strFormID, 'ge-payerproceed-button,ge-cartcancel-button');

		// bindings
		os.bindEvent(m_objThis, m_strFormID, '.ge-payerproceed-button', 'PayerProceedButton', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-cartcancel-button', 'CartCancelButton', 'onClick');
	}

	function bindPaymentMethods(lngTotalPrice_a)
	{
		// unbindings
		os.unbindEvents(m_strFormID, m_strUnbindFormFields);

		// unbindings
		os.unbindEvents(m_strFormID, 'ge-paymentmethodsconfirm-button,ge-paymentmethodscart-button,ge-paymentmethodsviewreceipt-button,ge-paymentmethod-option,ge-cartcancel-button');

		// bindings
		os.bindEvent(m_objThis, m_strFormID, '.ge-paymentmethodsconfirm-button', 'PaymentMethodsConfirmButton', 'onClick');
        os.bindEvent(m_objThis, m_strFormID, '.ge-paymentmethodscart-button', 'PaymentMethodsCartButton', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-paymentmethodsviewreceipt-button', 'ViewReceiptButton', 'onClick');
        os.bindEvent(m_objThis, m_strFormID, '.ge-paymentmethod-option', 'PaymentMethodSelect', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-cartcancel-button', 'CartCancelButton', 'onClick');

        if(m_fltTotalIncGST > 0)
        {
			os.element(m_strFormID, '.ge-paymentmethodsconfirm-button').attr('disabled', 'disabled');
		}
	}
	
	function bindProducts()
	{
		// unbindings
		os.unbindEvents(m_strFormID, 'ge-productaddtocart-button,ge-productnext-button,ge-cartcancel-button');

		// bindings
        os.bindEvent(m_objThis, m_strFormID, '.ge-productaddtocart-button', 'ProductAddToCartButton', 'onClick');
        os.bindEvent(m_objThis, m_strFormID, '.ge-productnext-button', 'ProductNextButton', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-cartcancel-button', 'CartCancelButton', 'onClick');
		
		if (m_blnMultiselection)
		{
			os.element(m_strFormID, '.ge-step-addproducts').show();
			os.element(m_strFormID, '.ge-cartmessage-wrapper').show();
		}
		else
		{
			if (m_objTransaction.transactionlines.length === 0)
			{
				os.element(m_strFormID, '.ge-cartmessage-wrapper').hide();
				os.element(m_strFormID, '.ge-step-addproducts').show();
			}
			else
			{
				os.element(m_strFormID, '.ge-step-addproducts').hide();
				os.element(m_strFormID, '.ge-cartmessage-wrapper').show();
			}
		}
	}

	function bindGlobals()
	{
		// unbindings
		os.unbindEvents(m_strFormID, 'gb-form');

		// bindings
		os.bindEvent(m_objThis, m_strFormID, '.gb-form-close', 'FormClose', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onClick');
		//os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onDblClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-formtitle-inner-panel', 'FormTitle', 'onClick');
	}

	// ====================================================================================
	// FORM EVENTS ========================================================================

	this.Form_allowMultipleInstances = function ()
	{
		return false;
	};

	this.Form_canClose = function ()
	{
		return true;
	};

	this.Form_isDirty = function ()
	{
		return m_blnFormDirty;
	};

	// on_click gives form the focus, setup all the tabs
	this.Form_onClick = function ()
	{
		os.setFormFocus(m_objThis, m_strFormID);
	};

	this.Form_onFocus = function (objParameters_a)
	{
		setTabOrder();
	};

	this.Form_onLoad = function ()
	{
        if (m_objParameters.filter !== undefined && m_objParameters.filter.length > 0) 
		{
           m_strProductFilter = m_objParameters.filter;
        }

		m_blnReadonly = false; // note: this should be based on whether we are looking at our own profile or another

        initialiseForm();
        populateDock();
        bindGlobals();
        fetchData();
	};

	this.Form_onPermissionCheck = function ()
	{
		return true;
	};

	this.Form_onResize = function(intWidth_a, intHeight_a)
	{
        os.element(m_strFormID, '.gb-overflow').height((intHeight_a - 127) + 'px');
		os.element(m_strFormID, '.gb-overflow').css('overflow-y', 'scroll');

	};

    this.FormClose_onClick = function ()
	{
		os.closeForm(m_strFormID);
	};


	// ====================================================================================
	// OTHER EVENTS =======================================================================

	this.CartCancelButton_onClick = function(objThis_a)
	{
		cancelTransaction();
	};

	this.CartNextButton_onClick = function (objThis_a)
	{	
		updateProgress("cartpage", showPaymentMethods, false);
	};
	
	this.CartPayerButton_onClick = function (objThis_a)
	{
		updateProgress("payerpage", showPayer, false);
	};

	this.PaymentMethodsCartButton_onClick = function (objThis_a)
	{	
		updateProgress("cartpage", showCart, false);
	};
	
	this.ProductAddToCartButton_onClick = function (objThis_a)
	{
		var arrObjProducts = os.element(m_strFormID, '.ge-products-option:checked'); //.val();
		var arrProductIDs = [];

		processArray(arrObjProducts, function(objProduct_a)
		{
			arrProductIDs.push(objProduct_a.value);
		});

		if (arrProductIDs.length > 0)
		{
			addToCart(arrProductIDs, "cartpage", refetchCart);
		}
		else
		{
			var strErrors = "<ul><li>Product or subscription are required.</li></ul>";
			os.dialogAlertScroll(strErrors, function () {} );
		}
	};
	
	this.ProductNextButton_onClick = function (objThis_a)
	{
		if (m_objTransaction.transactionlines.length > 0)
		{
			updateProgress("payerpage", showPayer, false);
		}
		else
		{
			var strErrors = "<ul><li>You have no products or subscriptions in your cart.</li></ul>";
			os.dialogAlertScroll(strErrors, function () {} );
		}
	};
	
	this.PayerProceedButton_onClick = function(objThis_a)
	{
		m_objTransaction.payername = os.element(m_strFormID, '.ge-payername-field').val();
		m_objTransaction.payeraddressline1 = os.element(m_strFormID, '.ge-payeraddressline1-field').val();
		m_objTransaction.payeraddressline2 = os.element(m_strFormID, '.ge-payeraddressline2-field').val();
		m_objTransaction.payersuburb = os.element(m_strFormID, '.ge-payersuburb-field').val();
		m_objTransaction.payerstate = os.element(m_strFormID, '.ge-payerstate-field').val();
		m_objTransaction.payerpostcode = os.element(m_strFormID, '.ge-payerpostcode-field').val();
		m_objTransaction.payernotes = os.element(m_strFormID, '.ge-payernotes-field').val();
		
		updateProgress("cartpage", showCart, true);
	};

	this.PaymentMethodsConfirmButton_onClick = function ()
	{
		if (!m_blnPaymentInProcess)
		{
			m_blnPaymentInProcess = true;
			
			var strErrors = '';

			strErrors = validate();

			if (strErrors.length > 0)
			{
				// have an error, so display it
				os.dialogAlertScroll(strErrors, function () {});
			}
			else
			{
				var osObj = os.element(m_strFormID, '.ge-paymentmethod-field:checked');
				var blnIsCC = os.toBoolean( osObj.data('iscreditcard') );

				if (blnIsCC)
				{
					processCreditCard();
				}
				else
				{
					var strPaymentMethodID = '';
					var arrPaymentCheckbox = os.element(m_strFormID, '.ge-paymentmethod-field');

					processArray(arrPaymentCheckbox, function (chkBox)
					{
						if (os.element(m_strFormID, chkBox).is(':checked'))
						{
							strPaymentMethodID = htmlDecode(os.element(m_strFormID, chkBox).val());
						}
					});

					cartCommit(strPaymentMethodID, null);
				}
			}

			//m_blnPaymentInProcess = false;
		}
	};

	this.PaymentMethodSelect_onClick = function (objThis_a)
	{
		var osObj = os.element(m_strFormID ,'.ge-paymentmethod-option:checked');
		var strCode = osObj.data('code');

		var osObjPaymentDetails = os.element(m_strFormID, '.ge-paymentmethodfields');

		os.element(osObjPaymentDetails, 'ul li').addClass('gb-hidden');
		os.element(osObjPaymentDetails, 'ul li.ge-paymentmethod-' + htmlEncode(strCode)).removeClass('gb-hidden');
		
		os.element(m_strFormID, '.ge-paymentmethodsconfirm-button').removeAttr('disabled');
	};

	this.ViewReceiptButton_onClick = function(objThis_a)
	{
        getCartReceipt();
    };

    this.RemoveProductButton_onClick = function(objThis_a) 
	{
        var objThis = os.element(objThis_a); //.val();
        var arrIDs = [];

        arrIDs.push(objThis.data('id'));

        if(arrIDs.length > 0)
		{
            removeProductsFromCart(arrIDs);
        }
    };

	// ====================================================================================
	// TABBING ============================================================================

	function setTabOrder()
	{
		os.unbindEvents(m_strFormID, 'ge-tab-start,ge-tab-end');
		os.bindEvent(m_objThis, m_strFormID, '.ge-tab-start', 'TabStart', 'onFocus');
		os.bindEvent(m_objThis, m_strFormID, '.ge-tab-end', 'TabEnd', 'onFocus');

		os.setTabOrder(m_strFormID, 'ge-tab-start,ge-save-button,ge-tab-end');
	}

	this.TabEnd_onFocus = function ()
	{
		//os.element(m_strFormID, '.ge-products-option1').focus();
	};

	this.TabStart_onFocus = function ()
	{
		//os.element(m_strFormID, '.ge-cancel-button').focus();
	};
}