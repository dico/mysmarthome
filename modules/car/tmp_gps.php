<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyATMNiaRSmXdaHPlrXkINWcJPKbAU453PY"></script>
<script>
	var locations = {}; //A repository for markers (and the data from which they were contructed).

	//initial dataset for markers
	var locs = {
	    1: {
	        info: '11111. Some random info here',
	        lat: -37.8139,
	        lng: 144.9634
	    },
	    2: {
	        info: '22222. Some random info here',
	        lat: 46.0553,
	        lng: 14.5144
	    },
	    3: {
	        info: '33333. Some random info here',
	        lat: -33.7333,
	        lng: 151.0833
	    },
	    4: {
	        info: '44444. Some random info here',
	        lat: 27.9798,
	        lng: -81.731
	    }
	};
	var map = new google.maps.Map(document.getElementById('map_2385853'), {
	    zoom: 1,
	    maxZoom: 8,
	    minZoom: 1,
	    streetViewControl: false,
	    center: new google.maps.LatLng(30, 30),
	    mapTypeId: google.maps.MapTypeId.ROADMAP
	});
	var infowindow = new google.maps.InfoWindow();

	function setMarkers(locObj) {
	    $.each(locObj, function (key, loc) {
	        if (!locations[key] && loc.lat !== undefined && loc.lng !== undefined) {
	            //Marker has not yet been made (and there's enough data to create one).

	            //Create marker
	            loc.marker = new google.maps.Marker({
	                position: new google.maps.LatLng(loc.lat, loc.lng),
	                map: map
	            });

	            //Attach click listener to marker
	            google.maps.event.addListener(loc.marker, 'click', (function (key) {
	                return function () {
	                    infowindow.setContent(locations[key].info);
	                    infowindow.open(map, locations[key].marker);
	                }
	            })(key));

	            //Remember loc in the `locations` so its info can be displayed and so its marker can be deleted.
	            locations[key] = loc;
	        } else if (locations[key] && loc.remove) {
	            //Remove marker from map
	            if (locations[key].marker) {
	                locations[key].marker.setMap(null);
	            }
	            //Remove element from `locations`
	            delete locations[key];
	        } else if (locations[key]) {
	            //Update the previous data object with the latest data.
	            $.extend(locations[key], loc);
	            if (loc.lat !== undefined && loc.lng !== undefined) {
	                //Update marker position (maybe not necessary but doesn't hurt).
	                locations[key].marker.setPosition(
	                new google.maps.LatLng(loc.lat, loc.lng));
	            }
	            //locations[key].info looks after itself.
	        }
	    });
	}

	var ajaxObj = { //Object to save cluttering the namespace.
	    options: {
	        url: "........", //The resource that delivers loc data.
	        dataType: "json" //The type of data tp be returned by the server.
	    },
	    delay: 10000, //(milliseconds) the interval between successive gets.
	    errorCount: 0, //running total of ajax errors.
	    errorThreshold: 5, //the number of ajax errors beyond which the get cycle should cease.
	    ticker: null, //setTimeout reference - allows the get cycle to be cancelled with clearTimeout(ajaxObj.ticker);
	    get: function () { //a function which initiates 
	        if (ajaxObj.errorCount < ajaxObj.errorThreshold) {
	            ajaxObj.ticker = setTimeout(getMarkerData, ajaxObj.delay);
	        }
	    },
	    fail: function (jqXHR, textStatus, errorThrown) {
	        console.log(errorThrown);
	        ajaxObj.errorCount++;
	    }
	};

	//Ajax master routine
	function getMarkerData() {
	    $.ajax(ajaxObj.options)
	        .done(setMarkers) //fires when ajax returns successfully
	    .fail(ajaxObj.fail) //fires when an ajax error occurs
	    .always(ajaxObj.get); //fires after ajax success or ajax error
	}

	setMarkers(locs); //Create markers from the initial dataset served with the document.
	//ajaxObj.get(); //Start the get cycle.

	// *******************
	//test: simulated ajax
	var testLocs = {
	    1: {
	        info: '1. New Random info and new position',
	        lat: -37,
	        lng: 124.9634
	    },
	    2: {
	        lat: 70,
	        lng: 14.5144
	    },
	    3: {
	        info: '3. New Random info'
	    },
	    4: {
	        remove: true
	    },
	    5: {
	        info: '55555. Added',
	        lat: -37,
	        lng: 0
	    }
	};
	setTimeout(function () {
	    setMarkers(testLocs);
	}, ajaxObj.delay);
	// *******************
</script>

<div id="map_2385853" style="width:400px; height:300px;"></div>