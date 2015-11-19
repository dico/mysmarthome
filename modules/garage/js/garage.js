$(document).ready(function() {
	setInterval(function(){
		//$(".garage-webcam-refresh").attr("src", "/myimg.jpg?"+new Date().getTime());

		$('img.garage-webcam-refresh').each(function() {
			console.log('Refreshing image');
			var url = $(this).attr('data-url');
			$(".garage-webcam-refresh").attr("src", url+"?"+new Date().getTime());
			console.log('URL: ' + url);
		});

	},10000);
});

/*function garage_webcam_update() {
	//$('#garage-webcam').html('Updating image...');

	$.ajax({
		type: "GET",
		url: "modules/garage/webcam.php",
		cache: false,
		success: function(data) {
			
			$('#garage-webcam').html(data);
		},
		error: function() {
			console.log("AJAX Error: Could not fetch webcam image");
		},
		dataType: "html"
	});
}*/


// Use these when sensors are up and running
/*function garageOpen(id)
{
	console.log('Opening garage door ' + id);
	toggleGarageDoors(id);
	$("#garage-door-close-" + id).slideUp(2000);
}


function garageClose(id)
{
	console.log('Closing garage door ' + id);
	toggleGarageDoors(id);
	$("#garage-door-close-" + id).slideDown(2000);
}*/


function toggleGarageDoors(id, extID)
{
	$( "#garage-door-close-" + id ).slideToggle(2000);


	$.ajax({
		type: "GET",
		url: "bindings/telldus.live/execute.php?ajax=true&action=bell&deviceID=" + extID,
		data: {},
		cache: false,
		success: function(data){
			console.log("AJAX success");
			console.log(data);
		},
		error: function() {
			console.log("AJAX Error");
		},
		dataType: "html"
	});
}