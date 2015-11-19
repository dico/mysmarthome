function setMonitor(deviceIntID, value) {
	$('#spinner-monitor').html("<img style='height:16px; margin-right:5px;' src='msh-core/images/ajax-windows-loader.gif' />");

	$.ajax({
		type: "GET",
		url: "msh-core/common.php",
		data: {
			id: deviceIntID, 
			value: value,
			action: 'setMonitor',
			ajax: 'true'
		},
		cache: false,
		success: function(data){
			$('#spinner-monitor').html('');

			// Change btn classes/color
			if (value == 1) {
				$('#btn-monitor-on').removeClass('btn-default');
				$('#btn-monitor-off').removeClass('btn-danger');

				$('#btn-monitor-on').addClass('btn-success');
				$('#btn-monitor-off').addClass('btn-default');
			}

			else if (value == 0) {
				$('#btn-monitor-on').removeClass('btn-success');
				$('#btn-monitor-off').removeClass('btn-default');

				$('#btn-monitor-on').addClass('btn-default');
				$('#btn-monitor-off').addClass('btn-danger');
			}
		},
		error: function() {
			console.log("AJAX Error");
		},
		dataType: "html"
	});
}






function setPublic(deviceIntID, value) {
	$('#spinner-public').html("<img style='height:16px; margin-right:5px;' src='msh-core/images/ajax-windows-loader.gif' />");

	$.ajax({
		type: "GET",
		url: "msh-core/common.php",
		data: {
			id: deviceIntID, 
			value: value,
			action: 'setPublic',
			ajax: 'true'
		},
		cache: false,
		success: function(data){
			$('#spinner-public').html('');

			// Change btn classes/color
			if (value == 1) {
				$('#btn-public-on').removeClass('btn-default');
				$('#btn-public-off').removeClass('btn-danger');

				$('#btn-public-on').addClass('btn-success');
				$('#btn-public-off').addClass('btn-default');
			}

			else if (value == 0) {
				$('#btn-public-on').removeClass('btn-success');
				$('#btn-public-off').removeClass('btn-default');

				$('#btn-public-on').addClass('btn-default');
				$('#btn-public-off').addClass('btn-danger');
			}
		},
		error: function() {
			console.log("AJAX Error");
		},
		dataType: "html"
	});
}






function setDashboardView(deviceIntID, value) {
	$('#spinner-dashboard').html("<img style='height:16px; margin-right:5px;' src='msh-core/images/ajax-windows-loader.gif' />");

	$.ajax({
		type: "GET",
		url: "msh-core/common.php",
		data: {
			id: deviceIntID, 
			value: value,
			action: 'setDashboard',
			ajax: 'true'
		},
		cache: false,
		success: function(data){
			$('#spinner-dashboard').html('');

			// Change btn classes/color
			if (value == 1) {
				$('#btn-dashboard-on').removeClass('btn-default');
				$('#btn-dashboard-off').removeClass('btn-danger');

				$('#btn-dashboard-on').addClass('btn-success');
				$('#btn-dashboard-off').addClass('btn-default');
			}

			else if (value == 0) {
				$('#btn-dashboard-on').removeClass('btn-success');
				$('#btn-dashboard-off').removeClass('btn-default');

				$('#btn-dashboard-on').addClass('btn-default');
				$('#btn-dashboard-off').addClass('btn-danger');
			}
		},
		error: function() {
			console.log("AJAX Error");
		},
		dataType: "html"
	});
}