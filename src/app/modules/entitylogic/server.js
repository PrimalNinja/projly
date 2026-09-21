function entityform_server(objOS_a, strFormID_a, objParameters_a, objParentForm_a)
{
	var os = objOS_a;
	var m_objThis = this;
	var m_strFormID = strFormID_a;
	var m_objParameters = objParameters_a;
	
	var FIELD_FILESYSTEMPROTOCOL = os.massageClassName('gafb65c33-7df8-4b01-a919-4df125c08f0e', 'FILESYSTEMPROTOCOL');

	var FIELD_FILESYSTEM = os.massageClassName('gafb65c33-7df8-4b01-a919-4df125c08f0e', 'FILESYSTEM');
	var FIELD_SERVERPROTOCOL = os.massageClassName('gafb65c33-7df8-4b01-a919-4df125c08f0e', 'SERVERPROTOCOL');
	
	// utils
	
	// standard behaviour
	
	this.onRegister = function(objParentForm_a, objJFormRenderer_a)
	{
		os.bindEvent(m_objThis, m_strFormID, '.' + FIELD_FILESYSTEMPROTOCOL, 'FileSystemProtocol', 'onClick');
		
		showProtocol();
	};
	
	// custom behaviour
	
	this.onLoad = function()
	{
		showProtocol();
	};
	
	this.FileSystemProtocol_onClick = function(objThis_a)
	{
		showProtocol();
	};
	
	function showProtocol()
	{
		os.element(m_strFormID, '.' + FIELD_SERVERPROTOCOL + '_fg').hide();
		os.element(m_strFormID, '.' + FIELD_FILESYSTEM + '_fg').hide();

		var selectedRadio = os.element(m_strFormID, '.' + FIELD_FILESYSTEMPROTOCOL + ':checked').val();
		if(selectedRadio == 'FILESYSTEM')
		{
			os.element(m_strFormID, '.' + FIELD_FILESYSTEM + '_fg').show();
			os.element(m_strFormID, '.' + FIELD_SERVERPROTOCOL + '_fg').hide();
		}
		else
		{
			os.element(m_strFormID, '.' + FIELD_SERVERPROTOCOL + '_fg').show();
			os.element(m_strFormID, '.' + FIELD_FILESYSTEM + '_fg').hide();
		}
	}
}
