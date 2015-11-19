<style type="text/css">
  #googleMap {
  	height: 500px;
  	width:100%;
  }
</style>


<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyATMNiaRSmXdaHPlrXkINWcJPKbAU453PY"></script>
<script type="text/javascript">
/*
  function initialize() {
    var mapOptions = {
      center: { lat: 63.49849901062424, lng: 10.09647471320278},
      zoom: 8
    };
    var map = new google.maps.Map(document.getElementById('map-canvas'),
        mapOptions);
  }
  google.maps.event.addDomListener(window, 'load', initialize);
  */
</script>


<script type="text/javascript">
	var myCenter=new google.maps.LatLng(63.49849901062424, 10.09647471320278);
	var marker;
	var map;
	var mapProp;

	var liveTrackingInterval;

	function initialize()
	{
		mapProp = {
			center:myCenter,
			zoom:15,
			mapTypeId:google.maps.MapTypeId.ROADMAP
		};
		
		map = new google.maps.Map(document.getElementById("googleMap"),mapProp);

		mark();
	}

	function mark()
	{

		

		$.ajax({
			type: "GET",
			url: "bindings/gps.logger/lastPosition.json.php",
			cache: false,
			dataType: 'json',
			success: function(data) {

	
				//console.log(data);

				jsonData = jQuery.parseJSON(data);
				console.log(jsonData);



				clientMarker = new google.maps.Marker({
					position: new google.maps.LatLng(jsonData.lat,jsonData.lon),
					map: map
				});

				

				/*
				marker=new google.maps.Marker({
					  position:new google.maps.LatLng(jsonData.lat,jsonData.lon),
					  });
				marker.setMap(map);
				map.setCenter(new google.maps.LatLng(jsonData.lat,jsonData.lon));
				*/
				
				document.getElementById('sat').innerHTML='';
				document.getElementById('speed').innerHTML=jsonData.speed;
				document.getElementById('course').innerHTML='';
			},
			error: function() {
				console.log("ERROR getting last gps position");
				alert('LiveTracking out of memory - please refresh page');
				clearInterval(liveTrackingInterval);
			},
			dataType: "html"
		});
	}


	function startLiveTracking() {
		//setInterval('mark()', 5000);
		liveTrackingInterval = setInterval('mark()', 5000);

		$('#liveTracking-btn').html('Stop live tracking');
		$('#liveTracking-btn').attr('href','javascript:stopLiveTracking();');
	}

	function stopLiveTracking() {
		clearInterval(liveTrackingInterval);

		$('#liveTracking-btn').html('Start live tracking');
		$('#liveTracking-btn').attr('href','javascript:startLiveTracking();');
	}

	google.maps.event.addDomListener(window, 'load', initialize);
</script>


<a id="liveTracking-btn" href="javascript:startLiveTracking();">Start live tracking</a>

<div id="map-canvas"></div>
<div id="googleMap" style="width:100%;height:700px;"></div>



<?php
		echo '	

		<!-- Draw information table and Google Maps div -->

		<div>
			<center><br />
				<b> SIM908 GPS position DEMO </b><br /><br />
				<div id="superior" style="width:800px;border:1px solid">
					<table style="width:100%">
						<tr>
							<td>Time</td>
							<td>Satellites</td>
							<td>Speed OTG</td>
							<td>Course</td>
						</tr>
						<tr>
							<td id="time">'. date("Y M d - H:m") .'</td>
							<td id="sat"></td>
							<td id="speed"></td>
							<td id="course"></td>
						</tr>
				</table>
				</div>
				<br /><br />
				
			</center>
		</div>';
	?>