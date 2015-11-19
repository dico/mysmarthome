$(document).ready(function() {
	//jQuery("abbr.timeago").timeago();
	fetchTemperature();

	/*
	$(document).on("click", "a.close, #fade", function() {
		loader('end');
	});
	*/
});


function fetchTemperature() {
	loader('start'); // start loader

	// Request data with ajax
	$.ajax({
		type: "POST",
		url: "modules/dashboard/touchHandler.php?action=fetchClimaValues",
        datatype: 'json',
		cache: false,
		success: function(data){

			//console.log(data);

			// Parse to JSON
			var response = JSON.parse(data);

			// Loop JSON and update values
			$.each(response, function() {
				$('#device-' + this.deviceID + ' .device-box-title').html(this.name);
				$('#device-' + this.deviceID + ' .device-box-temp').html(this.temp + '&deg;');
				$('#device-' + this.deviceID + ' .device-box-humidity').html(this.humidity + '%');
				$('#device-' + this.deviceID + ' .device-box-lastupdate .timeago').html("<abbr class='timeago' title='"+this.last_update_iso+"'>"+this.last_update_human+"</abbr>");
				//$('#device-' + this.deviceID + ' .device-box-lastupdate .timeago').html(this.last_update_human);
				//$('#device-' + this.deviceID + ' .device-box-lastupdate2').html(this.last_update_human);
				//$('#device-' + this.deviceID + ' .device-box-lastupdate .timeago').attr('title', this.last_update_iso);
			});

			loader('end'); // stop loader
			
			console.log("Updated....");

			jQuery("abbr.timeago").timeago(); // update timeago

			// Start function again in x sec
			setTimeout(function(){
				fetchTemperature();
			}, 10000);
		},
		error: function() {
			alert("En feil har oppstått. Vennligst prøv på nytt eller kontakt webansvarlig om feilen vedvarer... (AJAX ERROR)");
		},
		dataType: "html"
	});
}


function loader(action) {

	if (action == 'start') {
		//Fade in Background
		//$('body').append('<div id="fade"></div>'); //Add the fade layer to bottom of the body tag.
		//$('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn(); //Fade in the fade layer - .css({'filter' : 'alpha(opacity=80)'}) is used to fix the IE Bug on fading transparencies
		$('#ajax-loader').html("<img style='display:block; margin:0 auto;' src='core/images/ajax-loader.gif' alt='ajax-loader' />");
	}

	else if (action == 'end') {
		//$('#fade').fadeOut(function() {$('#fade').remove();});
		$('#ajax-loader').html("");
	}
}