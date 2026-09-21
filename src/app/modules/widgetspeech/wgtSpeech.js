/*jsl:option explicit*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-os.js*/
function widgetspeech_wgtSpeech(objOS_a, strFormID_a, objParameters_a)
{
	var os = objOS_a;
	var m_objThis = this;
	var m_strFormID = strFormID_a;
	var m_objParameters = objParameters_a;

	var m_objAccents = [
		{
			"id" : 'en-AU',
			"description" : 'Australia'
		},
		{
			"id" : 'en-CA',
			"description" : 'Canada'
		},
		{
			"id" : 'en-IN',
			"description" : 'India'
		},
		{
			"id" : 'en-NZ',
			"description" : 'New Zealand'
		},
		{
			"id" : 'en-ZA',
			"description" : 'South Africa'
		},
		{
			"id" : 'en-GB',
			"description" : 'United Kingdom'
		},
		{
			"id" : 'en-US',
			"description" : 'United States'
		}
	];

	// can be setting driven
	var m_strAccent = 'en-AU';

	var m_strFinalTranscript = '';

	var m_blnRecognizing = false;
	var m_blnIgnoreOnEnd = false;

	var m_blnDictating = false;
	var m_objRecognition = null;
	var m_objSynth = null;
	var m_arrSynthVoices = [];
	var m_objVoice = '';
	
	var m_fltConfidenceRate = 0.7;

	// ====================================================================================
	// SPEECH =============================================================================

	function initialiseSpeechRecognition()
	{
		m_objRecognition = new webkitSpeechRecognition();

		m_objRecognition.continuous = true;
		m_objRecognition.interimResults = false;

		m_objRecognition.onstart = function ()
		{
			m_blnRecognizing = true;
			//os.broadcast(m_strFormID, 'speech', 'speech is on');
		};

		m_objRecognition.onerror = function (event)
		{
			speak("Speech System Error");
			if (event.error == 'no-speech')
			{
				m_blnIgnoreOnEnd = true;
			}

			if (event.error == 'audio-capture')
			{
				m_blnIgnoreOnEnd = true;
			}

			if (event.error == 'not-allowed')
			{
				m_blnIgnoreOnEnd = true;
			}

			var strError = event.error;
			os.processSpeech(strError);
		};
		
		m_objRecognition.onend = function()
		{
			m_blnRecognizing = false;

			if (m_blnIgnoreOnEnd)
			{
				return;
			}

			if (!m_strFinalTranscript)
			{
				return;
			}

			os.broadcast(m_strFormID, 'speech', 'speech dictate off');
		};

		m_objRecognition.onresult = function (event)
		{
			var strLastWords = '';
			
			if (typeof(event.results) == 'undefined')
			{
				m_objRecognition.onend = null;
				m_objRecognition.stop();
				os.broadcast(m_strFormID, 'speech', 'speech system off');
				return;
			}

			for (var intI = event.resultIndex; intI < event.results.length; ++intI)
			{
				if (event.results[intI][0].confidence >= m_fltConfidenceRate)
				{
					strLastWords = event.results[intI][0].transcript;
				}
			}

			if (os.processSpeech(strLastWords, m_blnDictating))
			{
				doNothing();
			}
			else
			{
				if (m_blnDictating)
				{
//console.log('DICTATING: ' + strLastWords);
					m_strFinalTranscript += strLastWords;
				}
			}
		};

		os.registerSpeech(m_objThis, 'Google');
	}

	function initialiseSpeechSynthesis()
	{
		m_objSynth = window.speechSynthesis;
		
		if (m_objSynth.getVoices().length === 0) 
		{
			m_objSynth.addEventListener('voiceschanged', function() 
			{
				setVoice();
			});
		}
		else 
		{
			setVoice();
		}
    }
    
    //function initialiseTellTime()
    //{
        //setInterval(tellTheTime, 60 * 60 * 1000);
    //}
	
	function setVoice()
	{
		var blnFound = false;
		m_arrSynthVoices = m_objSynth.getVoices();
		//alert(strStatus_a + ":" + JSON.stringify(m_arrSynthVoices));

		for (var intI = 0; intI < m_arrSynthVoices.length; intI++) 
		{
			if(m_arrSynthVoices[intI].lang === 'en-AU') 
			{
				m_objVoice = m_arrSynthVoices[intI];
				blnFound = true;
				break;
			}
		}

		if (!blnFound)
		{
			m_objVoice = m_arrSynthVoices[0];
		}
	}
	
	function speak(str_a)
	{
		var objUtter = new SpeechSynthesisUtterance();
		objUtter.rate = 1;
		objUtter.pitch = 0.5;
		objUtter.text = str_a;
		objUtter.voice = m_objVoice;

		// event after text has been spoken
		objUtter.onend = function() 
		{
			doNothing();
		};

		m_objSynth.speak(objUtter);
    }
    
    function tellTheTime()
    {
        var objNow = new Date();
        var intHour = objNow.getHours();
		var intMinutes = objNow.getMinutes();
        var strClockTime;
        var strTime = '';
		var strMinutes = '';

        if(intHour >= 12)
        {
            intHour = intHour - 12;
            strClockTime = 'pm';
        }
        else
        {
            strClockTime = 'am';
        }
		
		if (intMinutes < 10)
		{
			strMinutes = 'oh ' + intMinutes;
		}
		else
		{
			strMinutes = intMinutes;
		}

        strTime = 'The time is ' + intHour + ' ' + strMinutes + ' ' + strClockTime + '.';

        if(strTime.length > 0)
        {
            speak(strTime);
        }
    }

	// ====================================================================================
	// BINDINGS ===========================================================================

	function bindGlobals()
	{
		// unbindings
		os.unbindEvents(m_strFormID, 'gb-form');

		// bindings
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onDblClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onMouseEnter');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onMouseLeave');
	}

	// ====================================================================================
	// FORM EVENTS ========================================================================

	this.Form_allowMultipleInstances = function ()
	{
		return false;
	};

	this.Form_onBroadcast = function (strQueue_a, strMessage_a)
	{
		if (strQueue_a === 'speech')
		{
			if (strMessage_a === 'speech system on')
			{
				m_objRecognition.start();
				m_blnDictating = false;
				m_strFinalTranscript = '';
			}
			else if (strMessage_a === 'speech system off')
			{
				m_objRecognition.stop();
			}
			else if (strMessage_a === 'speech dictate on')
			{
				speak("Speech Dictate On");
				m_strFinalTranscript = '';
				m_blnDictating = true;
			}
			else if (strMessage_a === 'speech dictate off')
			{
				m_blnDictating = false;
				speak("You said quote");
				speak(m_strFinalTranscript);
				speak("end quote.");
				os.copyToClipboard(m_strFinalTranscript);
				m_strFinalTranscript = '';
			}
			else if (strMessage_a === 'what is the time now')
			{
				tellTheTime();
			}
        }
        
        if(strQueue_a === 'speak')
        {
            speak(strMessage_a);
        }
	};

	this.Form_onPermissionCheck = function ()
	{
		//		var blnResult = (!('webkitSpeechRecognition' in window));
		return true;
	};

	this.Form_onLoad = function ()
	{
		bindGlobals();

		// initialise speech synthesis
		initialiseSpeechSynthesis();

		// initialise speech recognition
		initialiseSpeechRecognition();

        //initialiseTellTime();

		// start speech recognition
		m_objRecognition.lang = m_strAccent;
	};
}