$(document).ready(function() {
	$(document).on("click", ".more", function(event){
		console.log('More clicked');
		event.preventDefault();
		//$(this).parent().next('.show-more').toggle();
		$(this).next().next('.show-more').slideToggle();

		//var url = url.replace(/(\?lang=fr)+/g, '?lang=fr');
		//window.history.pushState({"html":response.html,"pageTitle":response.pageTitle},"", urlPath);
	});
});

function fetchDeviceMethods(intID, actionID)
{
	$.ajax({
		type: "GET",
		url: "core/handlers/Devices_handler.php?json=true&action=getDevice&id=" + intID,
		data: {},
		cache: false,
		success: function(data){
			console.log('Data: ' + data);
			console.log(data['name']);
			console.log("AJAX success");

			/*$.each(data['methods'], function(i, item) {
				console.log(data['methods'][i].host);
			});​
			*/

			var selectAction = '#action-methods-' + actionID;
			var setValue = '#action-setvalue-' + actionID;

			// Clear select options
			$(selectAction).empty();

			// Append new select options
			$.each(data.methods, function(index, element) {
				console.log(element);
				console.log(element.title);
				$(selectAction).append($('<option>').text(element.title).attr('value', element.cmd));
			});

			//$('#action-methods-' + actionID).hide();
		},
		error: function() {
			console.log("AJAX Error");
		},
		dataType: "json"
	});
}



function fetchDeviceUnits(intID, triggerID)
{
	$.ajax({
		type: "GET",
		url: "core/handlers/Devices_handler.php?json=true&action=getDevice&id=" + intID,
		data: {},
		cache: false,
		success: function(data){
			console.log('Data: ' + data);
			console.log(data['name']);
			console.log("AJAX success");

			/*$.each(data['methods'], function(i, item) {
				console.log(data['methods'][i].host);
			});​
			*/

			var selectAction = '#trigger-units-' + triggerID;
			var setValue = '#action-setvalue-' + triggerID;

			// Clear select options
			$(selectAction).empty();

			// Append new select options
			$.each(data.units, function(index, element) {
				console.log(element);
				console.log(element.title);
				$(selectAction).append($('<option>').text(element.title).attr('value', element.id));
			});

			//$('#action-methods-' + actionID).hide();
		},
		error: function() {
			console.log("AJAX Error");
		},
		dataType: "json"
	});
}



function eventTriggerDelete(triggerID)
{
	$.ajax({
		type: "GET",
		url: "core/handlers/Events_handler.php?json=true&action=deleteTrigger&id=" + triggerID,
		data: {},
		cache: false,
		success: function(data){
			console.log('Data: ' + data);
			console.log('triggerID: ' + triggerID);
			console.log('Status: ' + data.status);

			if (data.status == 'success') {
				$('#row-trigger-' + triggerID).fadeOut();
			} else {
				console.log('Feedback error.');
				console.log('Status: ' + data.status);
				console.log('Status message: ' + data.message);
			}
		},
		error: function() {
			console.log("AJAX Error");
		},
		dataType: "json"
	});
}

function eventActionDelete(actionID)
{
	$.ajax({
		type: "GET",
		url: "core/handlers/Events_handler.php?json=true&action=deleteAction&id=" + actionID,
		data: {},
		cache: false,
		success: function(data){
			console.log('Data: ' + data);
			console.log('Status: ' + data.status);

			if (data.status == 'success') {
				$('#row-action-' + actionID).fadeOut();
			} else {
				console.log('Feedback error.');
			}
		},
		error: function() {
			console.log("AJAX Error");
		},
		dataType: "json"
	});
}


function eventsSetSupportedValueInput(actionID)
{

}