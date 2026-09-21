/*jsl:option explicit*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-os.js*/
function core_frmRegister(objOS_a, strFormID_a, objParameters_a)
{
	var os = objOS_a;
	var m_objThis = this;
	var m_strFormID = strFormID_a;
	var m_objParameters = objParameters_a;
	var m_strCurrentTab = 'RegistrationType';
	var m_blnIsEmployer = true;

	var m_blnAccountCheckerRan = false;
	var m_blnClientCodeExists = false;

	var m_objRegistrationTypes = null;
    var m_objRegistrationType = null;

	var m_intToFetch = 0;
	var m_intFetched = 0;
	var m_intErrors = 0;

	var m_strFormFields = 'ge-registrationtype-field,ge-registrationtype-option1,ge-registrationtype-option,ge-registrationtypenext-button';
		m_strFormFields += 'ge-accountname-field,ge-fullname-field,ge-clientcode-field,ge-login-field,ge-password-field,ge-confirmpassword-field,ge-salespersoncode-field,ge-accountnext-button,ge-accountprev-button';
		m_strFormFields += 'ge-businessname-field,ge-abn-field,ge-about-field,ge-termsconditions-field,ge-privacy-field,ge-aboutnext-button,ge-aboutprev-button';
		m_strFormFields += 'ge-venuename-field,ge-venueaddressline1-field,ge-venueaddressline2-field,ge-venuesuburb-field,ge-venuestate-field,ge-venuepostcode-field,ge-venuecountry-field,ge-venuephonenumber-field,ge-venueemailaddress-field,ge-venuenext-button,ge-venueprev-button';
		m_strFormFields += 'ge-contactfullname-field,ge-contactaddressline1-field,ge-contactaddressline2-field,ge-contactsuburb-field,ge-contactstate-field,ge-contactpostcode-field,ge-contactcountry-field,ge-contactphonenumber-field,ge-contactemailaddress-field,ge-contactnext-button,ge-contactprev-button';
		m_strFormFields += 'ge-contactfullname-2-field,ge-contactaddressline1-2-field,ge-contactaddressline2-2-field,ge-contactsuburb-2-field,ge-contactstate-2-field,ge-contactpostcode-2-field,ge-contactcountry-2-field,ge-contactphonenumber-2-field,ge-contactemailaddress-2-field,ge-register-button,ge-contactprev-2-button';
		m_strFormFields += 'ge-terms-link,ge-privacy-link';

	// ------------------------------------------------------------------------------------

	var m_objSuburbFinder0 = new suburbFinder(os, m_strFormID, 'ge-venuesuburb-field', 'ge-venuestate-field', 'ge-venuepostcode-field', 'ge-venuecountry-field', os.getProperty('defaultcountry'));
	var m_objSuburbFinder1 = new suburbFinder(os, m_strFormID, 'ge-contactsuburb-field', 'ge-contactstate-field', 'ge-contactpostcode-field', 'ge-contactcountry-field', os.getProperty('defaultcountry'));
	var m_objSuburbFinder2 = new suburbFinder(os, m_strFormID, 'ge-contactsuburb-2-field', 'ge-contactstate-2-field', 'ge-contactpostcode-2-field', 'ge-contactcountry-2-field', os.getProperty('defaultcountry'));
    var m_objBusinessNameFinder = new abnFinder(os, m_strFormID, 'ge-abn-field', 'ge-businessname-field', 'BusinessName', true, 'ge-abnsearch-button');

	// ------------------------------------------------------------------------------------

	// ====================================================================================
	// HELPERS ============================================================================

	function clientCodeCreate()
	{
		var strAccountName = stripChars(os.element(m_strFormID, '.ge-accountname-field').val());
		var strFullName = stripChars(os.element(m_strFormID, '.ge-fullname-field').val());

		var strClientCode = os.str_left(strAccountName, 6);
		strClientCode += os.str_left(strFullName, 3);

		strClientCode = strClientCode.toUpperCase();

		os.element(m_strFormID, '.ge-clientcode-field').val(strClientCode);
	}

	function resizeForm()
	{
		if (os.getViewPort().width < 768)
		{
			os.element(m_strFormID, '.ge-form').css("width", "315px");
			//os.element(m_strFormID, '.ge-form').css("left", (os.getViewPort().width - 316) / 2); //center form
		}
		else
		{
			os.element(m_strFormID, '.ge-form').css("width", "420px");
			//os.element(m_strFormID, '.ge-form').css("left", (os.getViewPort().width - 421) / 2); //center form
		}
	}
    
    function isValidDate(strDate_a)
    {
        var blnResult = false;
        
        // return format is yyyy-mm-dd
        var strDate = os.dateToISO(DATE_OUTPUTFORMAT, strDate_a);
        
        var arrDateParts = strDate.split('-');        
        var blnValidDD = false;
        var blnValidMM = false;
        var blnValidYYYY = false;
                
        if (arrDateParts[0] !== undefined && parseInt(arrDateParts[0], 10) > 999 && arrDateParts[0].toString().length === 4 )
        {
            blnValidYYYY = true;
        }

        if (arrDateParts[1] !== undefined && ( parseInt(arrDateParts[1], 10) > 0  && parseInt(arrDateParts[1], 10) <= 12 ) )
        {
            blnValidMM = true;
        }

        if (arrDateParts[2] !== undefined && ( parseInt(arrDateParts[2], 10) > 0  && parseInt(arrDateParts[2], 10) <= 31 ) )
        {
            blnValidDD = true;
        }  
                                    
        blnResult = blnValidDD && blnValidMM && blnValidYYYY;
        
        return blnResult;
    }
    
	function validateAboutPanel()
	{
		var strError = '';

		if(m_blnIsEmployer)
		{
			if ($.trim(os.element(m_strFormID, '.ge-businessname-field').val()).length === 0)
			{
				strError += '<li>Business Name is required.</li>';
			}
			if ($.trim(os.element(m_strFormID, '.ge-abn-field').val()).length === 0)
			{
				strError += '<li>An ABN is required.</li>';
			}
		}

		if (!os.element(m_strFormID, '.ge-termsconditions-field').is(':checked'))
		{
			strError += '<li>The terms and conditions has not been agreed to yet.</li>';
		}
		if (!os.element(m_strFormID, '.ge-privacy-field').is(':checked'))
		{
			strError += '<li>The privacy policy has not been agreed to yet.</li>';
		}

		return strError;
	}

	function validateAccountPanel()
	{
		var strError = '';

		if(m_blnIsEmployer)
		{
			if ($.trim(os.element(m_strFormID, '.ge-accountname-field').val()).length === 0)
			{
				strError += '<li>Trading Name is required.</li>';
			}
		}
		else
		{
			if ($.trim(os.element(m_strFormID, '.ge-fullname-field').val()).length === 0)
			{
				strError += '<li>First Name is required.</li>';
			}
		}
		if ($.trim(os.element(m_strFormID, '.ge-login-field').val()).length === 0)
		{
			strError += '<li>Email Address is required.</li>';
		}
		else
		{
			var email = os.element(m_strFormID, '.ge-login-field').val();
			if (!os.isEmailAddress(email))
			{
				strError += '<li>Email Address must be valid.</li>';
			}
		}

		if ($.trim(os.element(m_strFormID, '.ge-password-field').val()).length === 0)
		{
			strError += '<li>Password is required.</li>';
		}

		if ($.trim(os.element(m_strFormID, '.ge-confirmpassword-field').val()).length === 0)
		{
			strError += '<li>Confirm Password is required.</li>';
		}
		else
		{
			if (os.element(m_strFormID, '.ge-password-field').val() != os.element(m_strFormID, '.ge-confirmpassword-field').val())
			{
				strError += '<li>The passwords do not match.</li>';
			}
		}

		return strError;
	}

	function validateContactPanel()
	{
		var strError = '';

		if ($.trim(os.element(m_strFormID, '.ge-contactfullname-field').val()).length === 0)
		{
			strError += '<li>First Name is required.</li>';
        }
        
        if(os.toBoolean(m_objRegistrationType.is_addressline1mandatory))
        {
            if ($.trim(os.element(m_strFormID, '.ge-contactaddressline1-field').val()).length === 0)
            {
                strError += '<li>Address Line 1 is required.</li>';
            }
        }
        
		if ($.trim(os.element(m_strFormID, '.ge-contactsuburb-field').val()).length === 0)
		{
			strError += '<li>Suburb is required.</li>';
		}
		if ($.trim(os.element(m_strFormID, '.ge-contactstate-field').val()).length === 0)
		{
			strError += '<li>State is required.</li>';
		}
		if ($.trim(os.element(m_strFormID, '.ge-contactpostcode-field').val()).length === 0)
		{
			strError += '<li>Postcode is required.</li>';
		}
		if ($.trim(os.element(m_strFormID, '.ge-contactcountry-field').val()).length === 0)
		{
			strError += '<li>Country is required.</li>';
		}
		if ($.trim(os.element(m_strFormID, '.ge-contactphonenumber-field').val()).length === 0)
		{
			strError += '<li>Phone Number is required.</li>';
		}
		if ($.trim(os.element(m_strFormID, '.ge-contactemailaddress-field').val()).length === 0)
		{
			strError += '<li>Email Address is required.</li>';
		}

		return strError;
	}

	function validateContact2Panel()
	{
		var strError = '';

		// if ($.trim(os.element(m_strFormID, '.ge-contactfullname2-field').val()).length === 0)
		// {
			// strError += '<li>First Name is required.</li>';
        // }
        
        // if(os.toBoolean(m_objRegistrationType.is_addressline1mandatory))
        // {
            // if ($.trim(os.element(m_strFormID, '.ge-contactaddressline12-field').val()).length === 0)
            // {
                // strError += '<li>Address Line 1 is required.</li>';
            // }
        // }
        
		// if ($.trim(os.element(m_strFormID, '.ge-contactsuburb2-field').val()).length === 0)
		// {
			// strError += '<li>Suburb is required.</li>';
		// }
		// if ($.trim(os.element(m_strFormID, '.ge-contactpostcode2-field').val()).length === 0)
		// {
			// strError += '<li>Postcode is required.</li>';
		// }
		// if ($.trim(os.element(m_strFormID, '.ge-contactcountry2-field').val()).length === 0)
		// {
			// strError += '<li>Country is required.</li>';
		// }
		// if ($.trim(os.element(m_strFormID, '.ge-contactphonenumber2-field').val()).length === 0)
		// {
			// strError += '<li>Phone Number is required.</li>';
		// }
		// if ($.trim(os.element(m_strFormID, '.ge-contactemailaddress2-field').val()).length === 0)
		// {
			// strError += '<li>Email Address is required.</li>';
		// }

		return strError;
	}

	function validateRegistrationTypePanel()
	{
		var strError = '';

		if (!os.element(m_strFormID, '.ge-registrationtype-option').is(':checked'))
		{
			strError += '<li>You must choose a profile to proceed.</li>';
		}

		return strError;
	}

	// function verifyAccount(cbContinue_a)
	// {
		// var strErrors = validateAccountPanel();

		// if (strErrors.length > 0)
		// {
			// strErrors = '<ul>' + strErrors + '</ul>';
			
			//have an error, so display it
			// os.dialogAlertScroll(strErrors, doNothing);
		// }
		// else
		// {
			// verifyAccount2(cbContinue_a);
		// }
	// }

	// function verifyAccount2(cbContinue_a)
	// {
		// var strClientCode = '';
		// var strLogin = '';

		// strClientCode = os.element(m_strFormID, '.ge-clientcode-field').val();
		// strLogin = os.element(m_strFormID, '.ge-login-field').val();

		// var objJSON = os.ajaxRequestCreate('core_clientverify', [
					// {
						// "name" : "clientcode",
						// "value" : strClientCode
					// },
					// {
						// "name" : "login",
						// "value" : strLogin
					// }
				// ]);
		// os.ajaxCall(URL_WEBSERVICE, objJSON, cbContinue_a, os.ajaxError, doNothing, true);
	// }

	function validateVenuePanel()
	{
		var strError = '';

		if ($.trim(os.element(m_strFormID, '.ge-venuename-field').val()).length === 0)
		{
			strError += '<li>Venue Name is required.</li>';
        }
        
        if(os.toBoolean(m_objRegistrationType.is_addressline1mandatory))
        {
            if ($.trim(os.element(m_strFormID, '.ge-venueaddressline1-field').val()).length === 0)
            {
                strError += '<li>Address Line 1 is required.</li>';
            }
        }
        
		if ($.trim(os.element(m_strFormID, '.ge-venuesuburb-field').val()).length === 0)
		{
			strError += '<li>Suburb is required.</li>';
		}
		if ($.trim(os.element(m_strFormID, '.ge-venuestate-field').val()).length === 0)
		{
			strError += '<li>State is required.</li>';
		}
		if ($.trim(os.element(m_strFormID, '.ge-venuepostcode-field').val()).length === 0)
		{
			strError += '<li>Postcode is required.</li>';
		}
		if ($.trim(os.element(m_strFormID, '.ge-venuecountry-field').val()).length === 0)
		{
			strError += '<li>Country is required.</li>';
		}
		if ($.trim(os.element(m_strFormID, '.ge-venuephonenumber-field').val()).length === 0)
		{
			strError += '<li>Phone Number is required.</li>';
		}
		if ($.trim(os.element(m_strFormID, '.ge-venueemailaddress-field').val()).length === 0)
		{
			strError += '<li>Email Address is required.</li>';
		}

		return strError;
	}

	// ====================================================================================
	// POPULATING =========================================================================

	function populateForm()
	{
		m_objSuburbFinder0.bind();
		m_objSuburbFinder1.bind();
		m_objSuburbFinder2.bind();
		//m_objABNFinder.bind();
		m_objBusinessNameFinder.bind();

		os.element(m_strFormID, '.ge-venuecountry-field').val(os.getProperty('defaultcountry'));
		os.element(m_strFormID, '.ge-contactcountry-field').val(os.getProperty('defaultcountry'));
		os.element(m_strFormID, '.ge-contactcountry-2-field').val(os.getProperty('defaultcountry'));
		os.element(m_strFormID, '.ge-appname-field').html(APP_NAME);

		showRegistrationTypePanel();

		if (ENABLE_REGISTER === 'TRUE')
		{
			os.element(m_strFormID, '.rowAllowRegister').show();
			//os.element(m_strFormID, '.lnkForgot').show();
		}
		else
		{
			os.element(m_strFormID, '.rowAllowRegister').hide();
			//os.element(m_strFormID, '.lnkForgot').hide();
		}

		// defaulting
		os.element(m_strFormID, '.ge-english-name').text(DATABASENAME);
		os.element(m_strFormID, '.ge-app-name').text(APP_NAME);
		os.element(m_strFormID, '.ge-app-copyright').text(APP_COPYRIGHT);
		os.element(m_strFormID, '.ge-client-version').text('version ' + APP_VERSION);

		os.limitInput(m_strFormID, '.ge-clientcode-field', 100, true);
		os.limitInput(m_strFormID, '.ge-login-field', 40, true);
		os.limitInput(m_strFormID, '.ge-password-field', 20, true);

		var intRegistrationTypes = 0;
		var strDefault = "";
		var strHTMLRegistrationTypes = '';
		var strHTMLRegistrationTypeEmployer = '';
		var strHTMLRegistrationTypeIndividual = '';
		var strFirstClass = ' ge-registrationtype-option1';
		var intRegistrationTypeEmployer = 0;
		var intRegistrationTypeIndividual = 0;

		intRegistrationTypes = m_objRegistrationTypes.length;
		if (intRegistrationTypes === 1)
		{
			strDefault = ' checked="checked"';
		}
		

		processArray(m_objRegistrationTypes, function (objRegistrationType_a)
		{
			if(objRegistrationType_a.is_employer == 'Y')
			{
				strHTMLRegistrationTypeEmployer += '<tr>';
				strHTMLRegistrationTypeEmployer += '    <td width="80%"><b>' + htmlEncode(objRegistrationType_a.description) + '</b></td>';
				strHTMLRegistrationTypeEmployer += '    <td><input type="radio" name="registrationtype" class="ge-registrationtype-option' + strFirstClass + '" value="' + htmlEncode(objRegistrationType_a.id) + '" is_employer="Y"' + strDefault + '></td>';
				strHTMLRegistrationTypeEmployer += '</tr>';
				strHTMLRegistrationTypeEmployer += '<tr>';
				strHTMLRegistrationTypeEmployer += '    <td><br>' + htmlEncode(objRegistrationType_a.longdescription) + '<br><br></td>';
				strHTMLRegistrationTypeEmployer += '    <td>&nbsp;</td>';
				strHTMLRegistrationTypeEmployer += '</tr>';
				intRegistrationTypeEmployer += 1;
			}
			else
			{
				strHTMLRegistrationTypeIndividual += '<tr>';
				strHTMLRegistrationTypeIndividual += '    <td width="80%"><b>' + htmlEncode(objRegistrationType_a.description) + '</b></td>';
 				strHTMLRegistrationTypeIndividual += '    <td><input type="radio" name="registrationtype" class="ge-registrationtype-option' + strFirstClass + '" value="' + htmlEncode(objRegistrationType_a.id) + '" is_employer="N"' + strDefault + '></td>';
				strHTMLRegistrationTypeIndividual += '</tr>';
				strHTMLRegistrationTypeIndividual += '<tr>';
				strHTMLRegistrationTypeIndividual += '    <td><br>' + htmlEncode(objRegistrationType_a.longdescription) + '<br><br></td>';
				strHTMLRegistrationTypeIndividual += '    <td>&nbsp;</td>';
				strHTMLRegistrationTypeIndividual += '</tr>';
				intRegistrationTypeIndividual += 1;
			}
			strFirstClass = '';
		}
		);

		if(intRegistrationTypeIndividual > 0)
		{
			strHTMLRegistrationTypes += '<tr>';
			strHTMLRegistrationTypes += '    <td colspan="2" width="100%"><center><u><b>I am registering as an individual</b></u></center></td>';
			strHTMLRegistrationTypes += '</tr>';
			strHTMLRegistrationTypes += '<tr>';
			strHTMLRegistrationTypes += '    <td colspan="2" width="100%">&nbsp;</td>';
			strHTMLRegistrationTypes += '</tr>';
			strHTMLRegistrationTypes += strHTMLRegistrationTypeIndividual;
		}

		strHTMLRegistrationTypes += '<tr>';
		strHTMLRegistrationTypes += '    <td colspan="2" width="100%"><hr></td>';
		strHTMLRegistrationTypes += '</tr>';


		if(intRegistrationTypeEmployer > 0)
		{
			strHTMLRegistrationTypes += '<tr>';
			strHTMLRegistrationTypes += '    <td colspan="2" width="100%"><center><u><b>I am registering as a business</b></u></center></td>';
			strHTMLRegistrationTypes += '</tr>';
			strHTMLRegistrationTypes += '<tr>';
			strHTMLRegistrationTypes += '    <td colspan="2" width="100%">&nbsp;</td>';
			strHTMLRegistrationTypes += '</tr>';
			strHTMLRegistrationTypes += strHTMLRegistrationTypeEmployer;
		}






		os.element(m_strFormID, '.gb-registrationtype-field').html(strHTMLRegistrationTypes);
		os.bindEvent(m_objThis, m_strFormID, '.ge-registrationtype-option', 'RegistrationTypeButton', 'onClick');

		setTabOrder();
		
		if (intRegistrationTypes === 1)
		{
			os.element(m_strFormID, '.ge-accountprev-button').hide();
			m_objThis.RegistrationTypeButton_onClick();
			m_objThis.RegistrationTypeNextButton_onClick();
			setTabOrderPanel('AccountPanel');
		}
		//os.element(m_strFormID, '.ge-businessname-field').focus();
	}

	function showAboutPanel()
	{
		m_strCurrentTab = 'AboutPanel';
		os.element(m_strFormID, '.gb-panel-registrationtype').hide();
		os.element(m_strFormID, '.gb-panel-account').hide();
		os.element(m_strFormID, '.gb-panel-venue').hide();
		os.element(m_strFormID, '.gb-panel-contact').hide();
		os.element(m_strFormID, '.gb-panel-contact2').hide();
		os.element(m_strFormID, '.gb-panel-about').show();


		if (m_blnIsEmployer)
		{
			os.element(m_strFormID, '.ge-businessname-field').focus();
		}
		setTabOrderPanel('AboutPanel');
	}

	function showAccountPanel()
	{
		m_strCurrentTab = 'Account';
		os.element(m_strFormID, '.gb-panel-registrationtype').hide();
		os.element(m_strFormID, '.gb-panel-account').show();
		os.element(m_strFormID, '.gb-panel-venue').hide();
		os.element(m_strFormID, '.gb-panel-contact').hide();
		os.element(m_strFormID, '.gb-panel-contact2').hide();
		os.element(m_strFormID, '.gb-panel-about').hide();

		if (m_blnIsEmployer)
		{
			os.element(m_strFormID, '.ge-accountname-field').focus();
		}
		else
		{
			os.element(m_strFormID, '.ge-fullname-field').focus();
		}
		setTabOrderPanel('AccountPanel');
	}

	function showContactPanel(objResponse_a)
	{
		m_strCurrentTab = 'Contact';
		os.element(m_strFormID, '.gb-panel-registrationtype').hide();
		os.element(m_strFormID, '.gb-panel-account').hide();
		os.element(m_strFormID, '.gb-panel-venue').hide();
		os.element(m_strFormID, '.gb-panel-contact').show();
		os.element(m_strFormID, '.gb-panel-contact2').hide();
		os.element(m_strFormID, '.gb-panel-about').hide();
		os.element(m_strFormID, '.ge-contactfullname-field').focus();
		setTabOrderPanel('ContactPanel');
	}

	function showContact2Panel(objResponse_a)
	{
		m_strCurrentTab = 'Contact 2';
		os.element(m_strFormID, '.gb-panel-registrationtype').hide();
		os.element(m_strFormID, '.gb-panel-account').hide();
		os.element(m_strFormID, '.gb-panel-venue').hide();
		os.element(m_strFormID, '.gb-panel-contact').hide();
		os.element(m_strFormID, '.gb-panel-contact2').show();
		os.element(m_strFormID, '.gb-panel-about').hide();
		os.element(m_strFormID, '.ge-contactfullname-2-field').focus();
		setTabOrderPanel('ContactPanel2');
	}

	function showRegistrationTypePanel()
	{
		m_strCurrentTab = 'RegistrationType';
		os.element(m_strFormID, '.gb-panel-registrationtype').show();
		os.element(m_strFormID, '.gb-panel-account').hide();
		os.element(m_strFormID, '.gb-panel-venue').hide();
		os.element(m_strFormID, '.gb-panel-contact').hide();
		os.element(m_strFormID, '.gb-panel-contact2').hide();
		os.element(m_strFormID, '.gb-panel-about').hide();
		setTabOrderPanel('RegistrationTypePanel');
	}

	function showVenuePanel(objResponse_a)
	{
		m_strCurrentTab = 'Venue';
		os.element(m_strFormID, '.gb-panel-registrationtype').hide();
		os.element(m_strFormID, '.gb-panel-account').hide();
		os.element(m_strFormID, '.gb-panel-venue').show();
		os.element(m_strFormID, '.gb-panel-contact').hide();
		os.element(m_strFormID, '.gb-panel-contact2').hide();
		os.element(m_strFormID, '.gb-panel-about').hide();
		os.element(m_strFormID, '.ge-contactfullname-field').focus();
		setTabOrderPanel('ContactPanel');
	}

	// ====================================================================================
	// WEBSERVICE RETURNS =================================================================

	function registerSuccess(objResponse_a)
	{
		if (IMMEDIATE_REGISTRATION === 'TRUE')
		{
			os.gotoURL('#!core.frmRegisterConfirmation', "");
		}
		else
		{
			os.gotoURL('#!core.frmRegisterVerification', "");
		}
	}

	function registrationTypesFetched(objResponse_a)
	{
		m_objRegistrationTypes = objResponse_a;
		asyncDataIsFetched();
	}

	// ====================================================================================
	// WEBSERVICE CALLS ===================================================================

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

	function fetchData(blnFetchAccount_a)
	{
		if (blnFetchAccount_a)
		{
			m_intToFetch = 1;
			m_intFetched = 0;
			m_intErrors = 0;

			fetchRegistrationTypes();
		}
		else
		{
			m_intToFetch = 1;
			m_intFetched = 0;
			m_intErrors = 0;

			populateForm();
		}
	}

	function fetchRegistrationTypes()
	{
		var objJSON = os.ajaxRequestCreate('core_registrationtypesfetchactive', []);
		os.ajaxCall(URL_WEBSERVICE, objJSON, registrationTypesFetched, os.ajaxError, doNothing, true);
	}

	function register()
	{
		// system fields
		var strUserAgent = os.getAgent();
		var strBrowserCapabilities = os.getBrowserCapabilities();
		
		// account type fields
		var strRegistrationTypeID = '';
		var arrRegistrationTypes = os.element(m_strFormID, '.ge-registrationtype-option');

		processArray(arrRegistrationTypes, function (objRegistrationType_a)
		{
			if( os.element(m_strFormID, objRegistrationType_a).is(':checked') )
			{
				strRegistrationTypeID = os.element(m_strFormID, objRegistrationType_a).val();
			}
		});

		// account fields
		var strAccountName = os.element(m_strFormID, '.ge-accountname-field').val();
		var strFullName = os.element(m_strFormID, '.ge-fullname-field').val();
		var strClientCode = os.element(m_strFormID, '.ge-clientcode-field').val();
		var strLogin = os.element(m_strFormID, '.ge-login-field').val();
		var strPassword = os.element(m_strFormID, '.ge-password-field').val();
		var strSalesPersonCode = os.element(m_strFormID, '.ge-salespersoncode-field').val();

		// venue fields
		var strVenueName = os.element(m_strFormID, '.ge-venuename-field').val();
		var strVenueAddressLine1 = os.element(m_strFormID, '.ge-venueaddressline1-field').val();
		var strVenueAddressLine2 = os.element(m_strFormID, '.ge-venueaddressline2-field').val();
		var strVenueSuburb = os.element(m_strFormID, '.ge-venuesuburb-field').val();
		var strVenueState = os.element(m_strFormID, '.ge-venuestate-field').val();
		var strVenuePostcode = os.element(m_strFormID, '.ge-venuepostcode-field').val();
		var strVenueCountry = os.element(m_strFormID, '.ge-venuecountry-field').val();
		var strVenuePhoneNumber = os.element(m_strFormID, '.ge-venuephonenumber-field').val();
		var strVenueEmailAddress = os.element(m_strFormID, '.ge-venueemailaddress-field').val();

		// contact fields
		var strContactFullName = os.element(m_strFormID, '.ge-contactfullname-field').val();
		var strContactAddressLine1 = os.element(m_strFormID, '.ge-contactaddressline1-field').val();
		var strContactAddressLine2 = os.element(m_strFormID, '.ge-contactaddressline2-field').val();
		var strContactSuburb = os.element(m_strFormID, '.ge-contactsuburb-field').val();
		var strContactState = os.element(m_strFormID, '.ge-contactstate-field').val();
		var strContactPostcode = os.element(m_strFormID, '.ge-contactpostcode-field').val();
		var strContactCountry = os.element(m_strFormID, '.ge-contactcountry-field').val();
		var strContactPhoneNumber = os.element(m_strFormID, '.ge-contactphonenumber-field').val();
		var strContactEmailAddress = os.element(m_strFormID, '.ge-contactemailaddress-field').val();

		// contact 2 fields
		var strContactFullName_2 = os.element(m_strFormID, '.ge-contactfullname-2-field').val();
		var strContactAddressLine1_2 = os.element(m_strFormID, '.ge-contactaddressline1-2-field').val();
		var strContactAddressLine2_2 = os.element(m_strFormID, '.ge-contactaddressline2-2-field').val();
		var strContactSuburb_2 = os.element(m_strFormID, '.ge-contactsuburb-2-field').val();
		var strContactState_2 = os.element(m_strFormID, '.ge-contactstate-2-field').val();
		var strContactPostcode_2 = os.element(m_strFormID, '.ge-contactpostcode-2-field').val();
		var strContactCountry_2 = os.element(m_strFormID, '.ge-contactcountry-2-field').val();
		var strContactPhoneNumber_2 = os.element(m_strFormID, '.ge-contactphonenumber-2-field').val();
		var strContactEmailAddress_2 = os.element(m_strFormID, '.ge-contactemailaddress-2-field').val();

		// about fields
		var strBusinessName = os.element(m_strFormID, '.ge-businessname-field').val();
		var strABN = os.element(m_strFormID, '.ge-abn-field').val();
		var strAbout = os.element(m_strFormID, '.ge-about-field').val();

		// fine print
		var strTermsConditions = 'N';
		if (os.element(m_strFormID, '.ge-termsconditions-field').is(':checked'))
		{
			strTermsConditions = 'Y';
		}


		var strPrivacy = 'N';

		if (os.element(m_strFormID, '.ge-privacy-field').is(':checked'))
		{
			strPrivacy = 'Y';
		}

		var objJSON = os.ajaxRequestCreate('public_register',
				[
					{
						"name" : "useragent",
						"value" : strUserAgent
					},

					{
						"name" : "registrationtype_id",
						"value" : strRegistrationTypeID
					},

					{
						"name" : "accountname",
						"value" : strAccountName
					},
					{
						"name" : "fullname",
						"value" : strFullName
					},
					{
						"name" : "clientcode",
						"value" : strClientCode
					},
					{
						"name" : "login",
						"value" : strLogin
					},
					{
						"name" : "password",
						"value" : strPassword
					},
					{
						"name" : "salespersoncode",
						"value" : strSalesPersonCode
					},

					{
						"name" : "venuename",
						"value" : strVenueName
					},
					{
						"name" : "venueaddressline1",
						"value" : strVenueAddressLine1
					},
					{
						"name" : "venueaddressline2",
						"value" : strVenueAddressLine2
					},
					{
						"name" : "venuesuburb",
						"value" : strVenueSuburb
					},
					{
						"name" : "venuestate",
						"value" : strVenueState
					},
					{
						"name" : "venuepostcode",
						"value" : strVenuePostcode
					},
					{
						"name" : "venuecountry",
						"value" : strVenueCountry
					},
					{
						"name" : "venuephonenumber",
						"value" : strVenuePhoneNumber
					},
					{
						"name" : "venueemailaddress",
						"value" : strVenueEmailAddress
					},
					{
						"name" : "contactfullname",
						"value" : strContactFullName
					},
					{
						"name" : "contactaddressline1",
						"value" : strContactAddressLine1
					},
					{
						"name" : "contactaddressline2",
						"value" : strContactAddressLine2
					},
					{
						"name" : "contactsuburb",
						"value" : strContactSuburb
					},
					{
						"name" : "contactstate",
						"value" : strContactState
					},
					{
						"name" : "contactpostcode",
						"value" : strContactPostcode
					},
					{
						"name" : "contactcountry",
						"value" : strContactCountry
					},
					{
						"name" : "contactphonenumber",
						"value" : strContactPhoneNumber
					},
					{
						"name" : "contactemailaddress",
						"value" : strContactEmailAddress
					},
					{
						"name" : "contactfullname_2",
						"value" : strContactFullName_2
					},
					{
						"name" : "contactaddressline1_2",
						"value" : strContactAddressLine1_2
					},
					{
						"name" : "contactaddressline2_2",
						"value" : strContactAddressLine2_2
					},
					{
						"name" : "contactsuburb_2",
						"value" : strContactSuburb_2
					},
					{
						"name" : "contactstate_2",
						"value" : strContactState_2
					},
					{
						"name" : "contactpostcode_2",
						"value" : strContactPostcode_2
					},
					{
						"name" : "contactcountry_2",
						"value" : strContactCountry_2
					},
					{
						"name" : "contactphonenumber_2",
						"value" : strContactPhoneNumber_2
					},
					{
						"name" : "contactemailaddress_2",
						"value" : strContactEmailAddress_2
					},

					{
						"name" : "businessname",
						"value" : strBusinessName
					},
					{
						"name" : "abn",
						"value" : strABN
					},
					{
						"name" : "about",
						"value" : strAbout
					},
					{
						"name" : "termsconditions",
						"value" : strTermsConditions
					},
					{
						"name" : "privacy",
						"value" : strPrivacy
					},
					{
						"name" : "capabilities",
						"value" : strBrowserCapabilities
					}
				]);
		os.ajaxCall(URL_WEBSERVICE, objJSON, registerSuccess, os.ajaxError, doNothing, true);
	}

	// ====================================================================================
	// BINDINGS ===========================================================================

	function bindGlobals()
	{
		// unbindings
		os.unbindEvents(m_strFormID, m_strFormFields);

		// bindings
		os.bindEvent(m_objThis, m_strFormID, '.ge-form', 'Form', 'onClick');

		os.bindEvent(m_objThis, m_strFormID, '.ge-registrationtypenext-button', 'RegistrationTypeNextButton', 'onClick');

		os.bindEvent(m_objThis, m_strFormID, '.ge-accountprev-button', 'AccountPrevButton', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-accountnext-button', 'AccountNextButton', 'onClick');

		os.bindEvent(m_objThis, m_strFormID, '.ge-aboutnext-button', 'AboutNextButton', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-aboutprev-button', 'AboutPrevButton', 'onClick');

		os.bindEvent(m_objThis, m_strFormID, '.ge-accountname-field', 'CodeCriteria', 'onKeyUp');
		os.bindEvent(m_objThis, m_strFormID, '.ge-fullname-field', 'CodeCriteria', 'onKeyUp');

		os.bindEvent(m_objThis, m_strFormID, '.ge-venuenext-button', 'VenueNextButton', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-venueprev-button', 'VenuePrevButton', 'onClick');

		os.bindEvent(m_objThis, m_strFormID, '.ge-contactnext-button', 'ContactNextButton', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-contactprev-button', 'ContactPrevButton', 'onClick');

		//os.bindEvent(m_objThis, m_strFormID, '.ge-contactnext-2-button', 'Contact2NextButton', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-contactprev-2-button', 'Contact2PrevButton', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-register-button', 'RegisterButton', 'onClick');

		os.bindEvent(m_objThis, m_strFormID, '.ge-terms-link', 'TermsConditionLink', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-privacy-link', 'PrivacyPolicyLink', 'onClick');
	}

	// ====================================================================================
	// FORM EVENTS ========================================================================

	this.PrivacyPolicyLink_onClick = function()
	{
		os.showFormLocal('entity.frmHTMLForm', { entity:'systemform', formentity:'CMS', formcode:'CMS', mode:'view', title:APP_SHORT_NAME + ' Privacy Policy', code:'PRIVACY_POLICY' }, null, true);
	};

	this.TermsConditionLink_onClick = function()
	{
		os.showFormLocal('entity.frmHTMLForm', { entity:'systemform', formentity:'CMS', formcode:'CMS', mode:'view', title:APP_SHORT_NAME + ' Terms and Conditions', code:'TERMS_CONDITIONS' }, null, true);
	};

	this.Form_allowMultipleInstances = function ()
	{
		return false;
	};

	//this.Form_onBroadcast = function (strQueue_a, strMessage_a)
	//{
		//if (((strQueue_a === 'orientation') || (strQueue_a === 'viewport')) && (strMessage_a === 'change'))
		//{
			//resizeForm();
			//m_objThis.Form_onResize();
		//}
	//};

	this.Form_canClose = function ()
	{
		return false;
	};

	// on_click gives form the focus, setup all the tabs
	this.Form_onClick = function ()
	{
		os.setFormFocus(m_objThis, m_strFormID);
	};

	this.Form_onFocus = function ()
	{
		setTabOrder();
		resizeForm();
		os.closeExclusive(m_strFormID);
	};

	this.Form_onLoad = function ()
	{
		bindGlobals();
		fetchData(true);
	};

	this.Form_onPermissionCheck = function ()
	{
		return true;
	};

	//this.Form_onResize = function (intWidth_a, intHeight_a)
	//{
		//var intHeight = os.getCanvasHeight();
		//intHeight = intHeight - 40; // that is reserved in the htm to push the form down
		//if (intHeight >= 700)
		//{
			//intHeight = 700;
		//}

		//os.element(m_strFormID, '.ge-panel-content').height((intHeight) + 'px');
		//os.element(m_strFormID, '.ge-panel-overflow').css('overflow-y','auto');
	//};

	this.Form_onResize = function (intWidth_a, intHeight_a)
	{
		var intHeight = intHeight_a;
		os.element(m_strFormID, '.ge-panel-content').height((intHeight) + 'px');
		os.element(m_strFormID, '.ge-panel-overflow').height('100%');
		os.element(m_strFormID, '.ge-panel-overflow').css('overflow-x','hidden');
		os.element(m_strFormID, '.ge-panel-overflow').css('overflow-y','auto');
	};

	// ====================================================================================
	// OTHER EVENTS =======================================================================

	this.AboutNextButton_onClick = function ()
	{
		var strErrors = validateAboutPanel();

		if (strErrors.length > 0)
		{
			strErrors = '<ul>' + strErrors + '</ul>';
			
			// have an error, so display it
			os.dialogAlertScroll(strErrors, doNothing);
		}
		else
		{
			//var strAccountName = os.element(m_strFormID, '.ge-accountname-field').val();
			//var strBusinessName = os.element(m_strFormID, '.ge-businessname-field').val();
			//if (strBusinessName.length === 0)
			//{
				//os.element(m_strFormID, '.ge-businessname-field').val(strAccountName);
			//}

            //showVenuePanel();
            showContactPanel();
		}
	};

	this.AboutPrevButton_onClick = function ()
	{
		showAccountPanel();
	};

	this.AccountNextButton_onClick = function ()
	{
		var strErrors = validateAccountPanel();

		if (strErrors.length > 0)
		{
			strErrors = '<ul>' + strErrors + '</ul>';

			// have an error, so display it
			os.dialogAlertScroll(strErrors, doNothing);
		}
		else
		{
			var strFullName = os.element(m_strFormID, '.ge-fullname-field').val();
			var strContactFullName = os.element(m_strFormID, '.ge-contactfullname-field').val();
			if (strContactFullName.length === 0)
			{
				os.element(m_strFormID, '.ge-contactfullname-field').val(strFullName);
			}

			var strEmailAddress = os.element(m_strFormID, '.ge-login-field').val();
			var strContactEmailAddress = os.element(m_strFormID, '.ge-contactemailaddress-field').val();
			if (strContactEmailAddress.length === 0)
			{
				os.element(m_strFormID, '.ge-contactemailaddress-field').val(strEmailAddress);
			}

			//showContactPanel();
			showAboutPanel();
		}
	};

	this.AccountPrevButton_onClick = function ()
	{
		showRegistrationTypePanel();
	};

	this.RegistrationTypeButton_onClick = function ()
	{
		var arrRegistrationTypes = os.element(m_strFormID, '.ge-registrationtype-option');
        var strRegistrationTypeID;

		processArray(arrRegistrationTypes, function (rdoRegistrationType_a)
		{
			if( os.element(m_strFormID, rdoRegistrationType_a).is(':checked') )
			{
                m_blnIsEmployer = os.toBoolean(os.element(m_strFormID, rdoRegistrationType_a).attr('is_employer'));
                strRegistrationTypeID = os.element(m_strFormID, rdoRegistrationType_a).val();
			}
		}
		);

		if(m_blnIsEmployer)
		{
			os.element(m_strFormID, '.gb-isindividual').hide();
			os.element(m_strFormID, '.gb-isemployer').show();
		}
		else
		{
			os.element(m_strFormID, '.gb-isemployer').hide();
			os.element(m_strFormID, '.gb-isindividual').show();
        }
        
        processArray(m_objRegistrationTypes, function (objRegistrationType_a)
		{
            if(objRegistrationType_a.id == strRegistrationTypeID)
            {
                m_objRegistrationType = objRegistrationType_a;
            }
		}
        );        
	};

	this.CodeCriteria_onChange = function()
	{
		clientCodeCreate();
	};

	this.CodeCriteria_onKeyUp = function()
	{
		clientCodeCreate();
	};

	this.ContactNextButton_onClick = function ()
	{
		var strErrors = validateContactPanel();

		if (strErrors.length > 0)
		{
			strErrors = '<ul>' + strErrors + '</ul>';
			
			// have an error, so display it
			os.dialogAlertScroll(strErrors, doNothing);
		}
		else
		{
			//var strAccountName = os.element(m_strFormID, '.ge-accountname-field').val();
			//var strBusinessName = os.element(m_strFormID, '.ge-businessname-field').val();
			//if (strBusinessName.length === 0)
			//{
				//os.element(m_strFormID, '.ge-businessname-field').val(strAccountName);
			//}

			showContact2Panel();
		}
	};

	this.ContactPrevButton_onClick = function ()
	{
		//os.element(m_strFormID, '.ge-businessname-field').val(os.element(m_strFormID, '.ge-businessname-field').val());
		//showAccountPanel();
        //showVenuePanel();
        showAboutPanel();
	};

	//this.Contact2NextButton_onClick = function ()
	//{
		//var strErrors = validateContact2Panel();

		//if (strErrors.length > 0)
		//{
			// strErrors = '<ul>' + strErrors + '</ul>';
			
			// have an error, so display it
			//os.dialogAlertScroll(strErrors, doNothing);
		//}
		//else
		//{
			//var strAccountName = os.element(m_strFormID, '.ge-accountname-field').val();
			//var strBusinessName = os.element(m_strFormID, '.ge-businessname-field').val();
			//if (strBusinessName.length === 0)
			//{
				//os.element(m_strFormID, '.ge-businessname-field').val(strAccountName);
			//}

			//showAboutPanel();
		//}
	//};

	this.Contact2PrevButton_onClick = function ()
	{
		//os.element(m_strFormID, '.ge-businessname-field').val(os.element(m_strFormID, '.ge-businessname-field').val());
		showContactPanel();
	};

	this.RegisterButton_onClick = function ()
	{
		var strErrors = '';
		strErrors = validateContact2Panel();

		if (strErrors.length > 0)
		{
			strErrors = '<ul>' + strErrors + '</ul>';
			
			// have an error, so display it
			os.dialogAlertScroll(strErrors, doNothing);
		}
		else
		{
			// let's just verify the account a second time anyway
			//verifyAccount(register);
			register();
		}
	};

	this.RegistrationTypeNextButton_onClick = function ()
	{
		var strErrors = validateRegistrationTypePanel();

		if (strErrors.length > 0)
		{
			strErrors = '<ul>' + strErrors + '</ul>';
			
			// have an error, so display it
			os.dialogAlertScroll(strErrors, doNothing);
		}
		else
		{
			if (m_blnIsEmployer)
			{
				// clear these fields because we don't want employer account codes to be made of individual names
				os.element(m_strFormID, '.ge-fullname-field').val('');
				clientCodeCreate();
			}
			else
			{
				// clear this field because we don't want individual account codes to be made of employername
				os.element(m_strFormID, '.ge-accountname-field').val('');
				clientCodeCreate();
			}
			
			showAccountPanel();
		}
	};

	this.VenueNextButton_onClick = function ()
	{
		var strErrors = validateVenuePanel();

		if (strErrors.length > 0)
		{
			strErrors = '<ul>' + strErrors + '</ul>';
			
			// have an error, so display it
			os.dialogAlertScroll(strErrors, doNothing);
		}
		else
		{
			//var strAccountName = os.element(m_strFormID, '.ge-accountname-field').val();
			//var strBusinessName = os.element(m_strFormID, '.ge-businessname-field').val();
			//if (strBusinessName.length === 0)
			//{
				//os.element(m_strFormID, '.ge-businessname-field').val(strAccountName);
			//}

			showContactPanel();
		}
	};

	this.VenuePrevButton_onClick = function ()
	{
		//os.element(m_strFormID, '.ge-businessname-field').val(os.element(m_strFormID, '.ge-businessname-field').val());
		showAccountPanel();
	};

	// ====================================================================================
	// TABBING ============================================================================
	//

	function setTabOrder()
	{
		os.unbindEvents(m_strFormID, 'ge-tab-start,ge-tab-end');
		os.bindEvent(m_objThis, m_strFormID, '.ge-tab-start', 'TabStart', 'onFocus');
		os.bindEvent(m_objThis, m_strFormID, '.ge-tab-end', 'TabEnd', 'onFocus');

		os.setTabOrder(m_strFormID, 'ge-tab-start,' + m_strFormFields + ',ge-tab-end');
	}

	this.TabEnd_onFocus = function ()
	{
		os.element(m_strFormID, '.ge-registrationtype-option1').focus();
	};

	this.TabStart_onFocus = function ()
	{
		os.element(m_strFormID, '.ge-register-button').focus();
	};

	function setTabOrderPanel(strPanel_a)
	{
		var m_strPanelFormFields = '';

		os.unbindEvents(m_strFormID, 'ge-tab-start,ge-tab-end');
		os.bindEvent(m_objThis, m_strFormID, '.ge-tab-start', 'TabStart', 'onFocus');
		os.bindEvent(m_objThis, m_strFormID, '.ge-tab-end', 'TabEnd', 'onFocus');

		switch(strPanel_a)
		{
			case 'AccountPanel':
				//m_strPanelFormFields = 'ge-accountname-field,ge-fullname-field,ge-accountcode-field,ge-login-field,ge-password-field,ge-confirmpassword-field,ge-salespersoncode-field,ge-accountnext-button,ge-accountprev-button';
				m_strPanelFormFields = 'ge-accountname-field,ge-fullname-field,ge-clientcode-field,ge-login-field,ge-password-field,ge-confirmpassword-field,ge-salespersoncode-field,ge-accountnext-button,ge-accountprev-button';
				break;
			case 'AboutPanel':
				//m_strPanelFormFields = 'ge-businessname-field,ge-abn-field,ge-about-field,ge-agreement-field,ge-privacy-field,ge-aboutprev-button,ge-register-button';
				m_strPanelFormFields = 'ge-businessname-field,ge-abn-field,ge-about-field,ge-termsconditions-field,ge-privacy-field,ge-aboutnext-button,ge-aboutprev-button';
				break;
			case 'VenuePanel':
				m_strPanelFormFields = 'ge-venuename-field,ge-venueaddressline1-field,ge-venueaddressline2-field,ge-venuesuburb-field,ge-venuestate-field,ge-venuepostcode-field,ge-venuecountry-field,ge-venuephonenumber-field,ge-venueemailaddress-field,ge-venuenext-button,ge-venueprev-button';
				break;
			case 'ContactPanel':
				//m_strPanelFormFields = 'ge-contactfullname-field,ge-contactaddress1-field,ge-contactaddress2-field,ge-contactsuburb-field,ge-contactstate-field,ge-contactpostcode-field,ge-contactcountry-field,ge-contactphonenumber-field,ge-contactemailaddress-field,ge-contactnext-button,ge-contactprev-button';
				m_strPanelFormFields = 'ge-contactfullname-field,ge-contactaddressline1-field,ge-contactaddressline2-field,ge-contactsuburb-field,ge-contactstate-field,ge-contactpostcode-field,ge-contactcountry-field,ge-contactphonenumber-field,ge-contactemailaddress-field,ge-contactnext-button,ge-contactprev-button';
				break;
			case 'ContactPanel2':
				m_strPanelFormFields = 'ge-contactfullname-2-field,ge-contactaddressline1-2-field,ge-contactaddressline2-2-field,ge-contactsuburb-2-field,ge-contactstate-2-field,ge-contactpostcode-2-field,ge-contactcountry-2-field,ge-contactphonenumber-2-field,ge-contactemailaddress-2-field,ge-register-button,ge-contactprev-2-button';
				break;
			case 'RegistrationTypePanel':
				m_strPanelFormFields = 'ge-accounttype-field,ge-accounttype-option1,ge-accounttype-option,ge-accounttypenext-button';
				break;

			default:
				doNothing();
				break;

		}
		os.setTabOrder(m_strFormID, 'ge-tab-start,' + m_strPanelFormFields + ',ge-tab-end');
	}

}
