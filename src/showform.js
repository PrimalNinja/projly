$(document).ready(function()
{
	os({ "container": "ge-form-container", "orientation": Window_onOrientationChange, "dependencies": "osfull", "progress": "ge-progress", "percentage": "ge-percentage" }, function()
	{
		$(window).hashchange(function(objEvent_a) { Window_onHashChange(objEvent_a); } );
		Window_onHashChange();
	});

	function Window_onHashChange(objEvent_a)
	{
		os().hashChange(location.hash);
	}

	function Window_onOrientationChange(intOrientation_a)
	{
		os().initialiseViewPort();
	}
});
