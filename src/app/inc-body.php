
	<body>
		<!-- create required DOM containers. note: ge-temp-container must be after the formcontainer because inputlimiter causes a linefeed otherwise -->
		<div id="ge-busyindicator" class="gb-hidden" style="height: 160px; width: 100%; position: absolute; font-size: 3em; top: 50%; left: 0px; margin-top: -80px; text-align: center; color: red;"><img width="120px" height="160px" src="images/busy.gif"></div>
		<div id="ge-formerror-container" class="gb-hidden"></div>
		<div id="ge-form-container">
			<div id="ge-modal-layer" class="gb-hidden" style="position:absolute; width:100%; height:100%; background-color:#000000; filter:alpha(opacity=60); opacity:0.6; -moz-opacity:0.6; text-align:center; vertical-align:middle;"></div>
		</div>
		<div id="ge-landing-container"></div>
		<div id="ge-lazyload-container" class="gb-hidden"></div>
		<div id="ge-timer-container" left="-1000" class="gb-hidden"></div>
		<div id="ge-mapprovider-container" class="gb-hidden"></div>
		<div id="ge-temp-container" class="gb-hidden"></div>
		<div id="ge-widgettube-container" style="top:200px;"><a href="#" class="tubular-mute">Mute</a></div>

		<!-- zoom detection -->
		<div id="ge-zoom1-container" style="top:-2000px;left:10%;width:10px;height:10px;background:blue;position:absolute;"></div>
		<div id="ge-zoom2-container" style="top:-2000px;width:10px;height:10px;background:red;position:absolute;"></div>

		<div id="ge-progress" style="height: 6px; margin-top: -6px; position: absolute; top: 85%; -webkit-transition: all 500ms; -moz-transition: all 500ms; -o-transition: all 500ms; transition: all 500ms; width: 0%; background-color: white;"></div>
		<div id="ge-percentage" style="height: 40px; width: 100%; position: absolute; font-size: 3em; top: 75%; left: 0px; margin-top: -60px; text-align: center; color: white;">0%</div>

