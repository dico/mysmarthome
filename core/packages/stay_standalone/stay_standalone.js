// Mobile Safari in standalone mode
if(("standalone" in window.navigator) && window.navigator.standalone){
 
	// If you want to prevent remote links in standalone web apps opening Mobile Safari, change 'remotes' to true
	var noddy, remotes = false;
	
	document.addEventListener('click', function(event) {
		
		noddy = event.target;

		var modalToggle = noddy.getAttribute("data-toggle");

		if (modalToggle == 'modal') {
			//alert('modal clicked 1');
		}

		else {
		
			// Bubble up until we hit link or top HTML element. Warning: BODY element is not compulsory so better to stop on HTML
			while(noddy.nodeName !== "A" && noddy.nodeName !== "HTML") {
		        noddy = noddy.parentNode;
		    }
			
			if('href' in noddy && noddy.href.indexOf('http') !== -1 && (noddy.href.indexOf(document.location.host) !== -1 || remotes))
			{
				event.preventDefault();
				document.location.href = noddy.href;
			}
		}
		
	
	},false);
}




// Listen for ALL links at the top level of the document. For
// testing purposes, we're not going to worry about LOCAL vs.
// EXTERNAL links - we'll just demonstrate the feature.
/*$( document ).on(
    "click",
    "a",
    function( event ){

    	//data-toggle="modal"

    	console.log('data-toggle: ' + $(this).data("toggle"));

    	if ($(this).data("toggle") == 'modal') {
    		console.log('modal clicked');
    	} else {
    		console.log('NONE modal clicked');
	        // Stop the default behavior of the browser, which
	        // is to change the URL of the page.
	        event.preventDefault();

	        // Manually change the location of the page to stay in
	        // "Standalone" mode and change the URL at the same time.
	        location.href = $( event.target ).attr( "href" );
	    }

    }
);*/