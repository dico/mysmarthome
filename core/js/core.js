$(document).ready(function() {
	//updateLoginSession();



	// Datepicker
	$('.datepicker').datepicker({
		format: 'yyyy-mm-dd',
		todayBtn: true,
		todayHighlight: true,
		autoclose: true
	});



	$('.toolTip').tooltip({
		'placement': 'top',
	});



	/*
		Toogle button
		Lights, etc
	*/	
	$(".toggleLightSwitch").bootstrapSwitch();
	console.log('Devices loaded...');

	//$(document).on("change", '.toggleSwitch', function() {
	$('.toggleLightSwitch').change(function() {
		console.log('Btn clicked');
		var intID = $(this).attr('data-int-id');
		console.log('intID: ' + intID);
	});

	$('.toggleLightSwitch').on('switchChange.bootstrapSwitch', function(event, state) {
		//console.log(this); // DOM element
		//console.log(event); // jQuery event
		var intID = $(this).attr('data-int-id');
		var extID = $(this).attr('data-ext-id');
		console.log('intID: ' + intID);
		console.log(state); // true | false

		if (state) action = 'turnOn';
		else action = 'turnOff';

		lights('telldus.live', intID, extID, action, '');
	});



	/*
		Bootstap tweak
		Close modal for fresh popup on ajax
	*/	
	$('body').on('hidden.bs.modal', '.modal', function () {
		$(this).removeData('bs.modal');
	});
});




function ajaxLoader(type) {
	if (type == 'start') {
		$('#ajax-loader').html("<img style='display:block; margin:0 auto; height:10px;' src='core/images/ajax-loader.gif' alt='ajax-loader' />");
	}

	else {
		$('#ajax-loader').html("");
	}
}


/**
* Load when MSH starts
*
*/

function mshLoad() {
	//setInterval(function() { updateLoginSession(); }, 200);
}



/**
* Keep login session alive
*
*/

function updateLoginSession() {
	$.ajax({
		type: "GET",
		url: "core/updateLogin.php",
		cache: false,
		success: function(data) {

			/*
			setInterval(function(){
				updateLoginSession();
			}, 3000);
			*/
			console.log('Session updated');
			var timerId = setTimeout(function() { updateLoginSession(); }, 30000)


		},
		error: function() {
			console.log("ERROR updateing login session");
		},
		dataType: "html"
	});
}