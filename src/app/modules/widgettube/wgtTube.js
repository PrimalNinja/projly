/*jsl:option explicit*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-os.js*/
function widgettube_wgtTube(objOS_a, strFormID_a, objParameters_a)
{
	var os = objOS_a;
	var m_objThis = this;
	var m_strFormID = strFormID_a;
	var m_objParameters = objParameters_a;
	
	var m_blnMute = true;

	// ====================================================================================
	// POPULATING =========================================================================

	function showTube()
	{
		os.element(m_objParameters.target).tubular(
		{
			videoId : LANDINGPAGE_VIDEOID,
			wrapperZIndex: 3,
			mute : m_blnMute
		}
		); //default: e4Is32W-ppk, t-ara: 9dz72M74dtY, dgBXkkWHwmI, daftpunk: 3CgkWmKJLuE, f(x): iv-8-EgPEY0
		
		updateMute();
		if (LANDINGPAGE_AUDIO === 'TRUE')
		{
			$('#ge-widgettube-container').show();
			$('.tubular-mute').bind('click', Play_onClick);
		}
	}
	
	function Play_onClick()
	{
		m_blnMute = !m_blnMute;
		updateMute();
	}
	
	function updateMute()
	{
		var str = '';
		
		// glyphicons don't work for some reason
		if (m_blnMute)
		{
			str = 'Play';	// <i class="glyphicon glyphicon-volume-off"></i>
		}
		else
		{
			str = 'Mute';	// <i class="glyphicon glyphicon-volume-up"></i>
		}
		
		$('#ge-widgettube-mute').html(str);
	}

	// ====================================================================================
	// FORM EVENTS ========================================================================

	this.Form_allowMultipleInstances = function ()
	{
		return false;
	};

	this.Form_onPermissionCheck = function ()
	{
		return true;
	};

	this.Form_onLoad = function ()
	{
		showTube();
	};
}