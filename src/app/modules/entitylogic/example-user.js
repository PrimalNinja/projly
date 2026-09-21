function entityform_user(objOS_a, strFormID_a, objParameters_a, objParentForm_a)
{
	var os = objOS_a;
	var m_objThis = this;
	var m_strFormID = strFormID_a;
	var m_objParameters = objParameters_a;
	var m_objParentForm = null;
	var m_objJFormRenderer = null;
	
	var FIELD_EMAILADDRESS = os.massageClassName('1d3fbeab-d440-4329-9b9c-aa93f6fe7c16', 'EMAILADDRESS');
	
	// utils
	
	// standard behaviour
	
	this.onRegister = function(objParentForm_a, objJFormRenderer_a)
	{
		m_objParentForm = objParentForm_a;
		m_objJFormRenderer = objJFormRenderer_a;

		os.bindEvent(m_objThis, m_strFormID, '.' + FIELD_EMAILADDRESS, 'EmailAddress', 'onClick');
	};
	
	// custom behaviour
	
	this.EmailAddress_onClick = function()
	{
		os.element(m_strFormID, '.' + FIELD_EMAILADDRESS).hide();
		alert('hello');
	};

	this.Renderer_onEvent = function(strType_a, strFormID_a, strLocator_a, strEventName_a)
	{
		var strName = htmlDecode(os.element(strFormID_a, strLocator_a).attr('name'));
//console.log('event: ' + strEventName_a + ' on ' + strName);
		
		if (strType_a === 'Entity')
		{
			if (strEventName_a === 'onChangeStart')
			{
				doNothing();
			}
			if (strEventName_a === 'onChangePending')
			{
				doNothing();
			}
			else if (strEventName_a === 'onChange')
			{
				doNothing();
			}
			else if (strEventName_a === 'onClear')
			{
				doNothing();
			}
		}
	};
}
