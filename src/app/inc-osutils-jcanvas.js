/*jsl:option explicit*/
/*jsl:import inc-osutils.js*/

// ====================================================================================
// AWAFOS Utils v20241106 =============================================================
// copyright(C) 2012-2024 Mitsukibo, Julian Cassin & Francis Weston. All rights reserved.

function jCanvas(objOS_a, objOptions_a)
{
	var m_objThis = this;
	var os = objOS_a;

	var m_objCanvas;
	var m_objCanvas2d;
	var m_blnDrawingT = false;
	var m_blnDrawingM = false;
	var m_intScale = 1;

	// default options
	var m_objOptions =
	{
		cbOnDirty : '',
		backgroundColour : 'black',
		height : 0,
		penColour : 'white',
		touchoffsetX : 0,
		touchoffsetY : 0,
		width : 0
	};

	// allowable overridden options
	if (objOptions_a.cbOnDirty !== undefined)
	{
		m_objOptions.cbOnDirty = objOptions_a.cbOnDirty;
	}
	if (objOptions_a.penColour !== undefined)
	{
		m_objOptions.penColour = objOptions_a.penColour;
	}
	if (objOptions_a.backgroundColour !== undefined)
	{
		m_objOptions.backgroundColour = objOptions_a.backgroundColour;
	}
	if (objOptions_a.touchoffsetX !== undefined)
	{
		m_objOptions.touchoffsetX = objOptions_a.touchoffsetX;
	}
	if (objOptions_a.touchoffsetY !== undefined)
	{
		m_objOptions.touchoffsetY = objOptions_a.touchoffsetY;
	}
	if (objOptions_a.width !== undefined)
	{
		m_objOptions.width = objOptions_a.width;
	}
	if (objOptions_a.height !== undefined)
	{
		m_objOptions.height = objOptions_a.height;
	}

	var m_strElement = '';

	this.onDraw = function (objEvent_a)
	{
		if (m_blnDrawingM)
		{
			var objXY = getXY(objEvent_a, false);

			m_objCanvas2d.lineTo(objXY.x, objXY.y);
			m_objCanvas2d.strokeStyle = m_objOptions.penColour;
			m_objCanvas2d.stroke();
			objEvent_a.preventDefault();

			if ($.isFunction(m_objOptions.cbOnDirty))
			{
				m_objOptions.cbOnDirty();
			}
		}
	};

	this.onDrawEnd = function (objEvent_a)
	{
		if (m_blnDrawingM)
		{
			m_objThis.onDraw(objEvent_a);
			objEvent_a.preventDefault();
			m_blnDrawingM = false;
		}
	};

	this.onDrawStart = function (objEvent_a)
	{
		var objXY = getXY(objEvent_a, false);

		m_objCanvas2d.beginPath();
		m_objCanvas2d.moveTo(objXY.x, objXY.y);
		objEvent_a.preventDefault();
		m_blnDrawingM = true;
		m_blnDrawingT = false;
	};

	this.onTouchEnd = function (objEvent_a)
	{
		if (m_blnDrawingT)
		{
			m_objThis.onTouchMove(objEvent_a);
			objEvent_a.preventDefault();
			m_blnDrawingT = false;
		}
	};

	this.onTouchMove = function (objEvent_a)
	{
		if (m_blnDrawingT)
		{
			var objXY = getXY(objEvent_a, true);

			m_objCanvas2d.lineTo(objXY.x, objXY.y);
			m_objCanvas2d.strokeStyle = m_objOptions.penColour;
			m_objCanvas2d.stroke();
			objEvent_a.preventDefault();
		}
	};

	this.onTouchStart = function (objEvent_a)
	{
		var objXY = getXY(objEvent_a, true);

		m_objCanvas2d.beginPath();
		m_objCanvas2d.moveTo(objXY.x, objXY.y);
		objEvent_a.preventDefault();
		m_blnDrawingT = true;
		m_blnDrawingM = false;
	};

	function bindEvents()
	{
		if (os.hasCapability('touch'))
		{
			m_objCanvas.addEventListener('touchstart', m_objThis.onTouchStart);
			m_objCanvas.addEventListener('touchmove', m_objThis.onTouchMove);
			m_objCanvas.addEventListener('touchend', m_objThis.onTouchEnd);
		}

		m_objCanvas.addEventListener('mousedown', m_objThis.onDrawStart, false);
		m_objCanvas.addEventListener('mousemove', m_objThis.onDraw, false);
		m_objCanvas.addEventListener('mouseup', m_objThis.onDrawEnd, false);
	}

	function getXY(objEvent_a, blnTouch_a)
	{
		var objResult =
		{
			x : 0,
			y : 0
		};

		if (blnTouch_a)
		{
			objResult.x = objEvent_a.touches[0].pageX - m_objOptions.touchoffsetX;
			objResult.y = objEvent_a.touches[0].pageY - m_objOptions.touchoffsetY;
		}
		else
		{
			if (objEvent_a.layerX || objEvent_a.layerX === 0)
			{
				// firefox
				objResult.x = objEvent_a.layerX * m_intScale;
				objResult.y = objEvent_a.layerY * m_intScale;
			}
			else if (objEvent_a.offsetX || objEvent_a.offsetX === 0)
			{
				// opera
				objResult.x = objEvent_a.offsetX * m_intScale;
				objResult.y = objEvent_a.offsetY * m_intScale;
			}
		}

		return objResult;
	}

	this.clear = function ()
	{
		m_objCanvas2d.clearRect(0, 0, m_objOptions.width, m_objOptions.height);
		m_objCanvas2d.fillStyle = m_objOptions.backgroundColour;
		m_objCanvas2d.fillRect(0, 0, m_objOptions.width, m_objOptions.height);
	};

	this.getBlob = function ()
	{
		var objResult;

		m_objCanvas.toBlob(function (objBlob_a)
		{
			objResult = objBlob_a;
		}, 'image/jpeg');

		return objResult;
	};

	this.getJPEG = function ()
	{
		var objEncoder = new JPEGEncoder();
		var objResult = objEncoder.encode(m_objCanvas2d.getImageData(0, 0, m_objOptions.width, m_objOptions.height), 100);
		objResult = objResult.substr(objResult.indexOf(',') + 1).toString();

		return objResult;
	};

	this.getPNG = function ()
	{
		var objResult = m_objCanvas.toDataURL('image/png');
		objResult = objResult.substr(objResult.indexOf(',') + 1).toString();

		return objResult;
	};

	this.render = function (strElement_a)
	{
		m_strElement = strElement_a;

		m_objCanvas = $('#' + m_strElement)[0];
		m_objCanvas2d = m_objCanvas.getContext('2d');

		// setup colours
		m_objCanvas2d.fillStyle = m_objOptions.backgroundColour;
		m_objCanvas2d.fillRect(0, 0, m_objOptions.width, m_objOptions.height);

		m_blnDrawingM = false;
		m_blnDrawingT = false;

		bindEvents();
	};
}
