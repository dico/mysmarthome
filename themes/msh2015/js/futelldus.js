$(document).ready(function() {


	/* Get window (div) sizes
	--------------------------------------------------------------------------- */
	var h = ($(window).height());
	var w = ($(window).width());



	//$("body").attr("[data-clock']").html();
	$("body").attr("data-clock", "true");



	// Make a div like a href to a href within
	$('.linkThisDiv').css('cursor' , 'pointer'); // change mouse cursor to pointer on the div
	
	$(".linkThisDiv").click(function(){
		window.location=$(this).find("a").attr("href"); 
		return false;
	});



	$("input[type=text]") // retrieve all inputs
    .keydown(function(e) { // bind keydown on all inputs
        if (e.keyCode == 13) // enter was pressed
            $(this).closest("form").submit(); // submit the current form
    });

    
    $('.toolTip').tooltip({
    	placement: 'top'
    });

	//startclock();
});






$(function() {
  //var $main = $("main");

  $(document).on("click", "a.ajax, area", function() {
    var href = $(this).attr("href");
    

    if ($(this).is('[data-target]')) {
      var target = $(this).attr("data-target");
    } else {
      var target = 'page-content';
    }

    console.log("Link TARGET: " + target);

    $(".ajax").removeClass("active");
    $(this).addClass("active");


    href = encodeURIComponent(href);
    loadPage(href, target);

    return false;
  });


  $(window).on("popstate", function(e) {
    if (e.originalEvent.state !== null) {
      //loadPage(location.href);
      window.location = location.href;
    }
  });


});



function getQueryParams(qs) {
    qs = qs.split("+").join(" ");

    var params = {}, tokens,
        re = /[?&]?([^=]+)=([^&]*)/g;

    while (tokens = re.exec(qs)) {
        params[decodeURIComponent(tokens[1])]
            = decodeURIComponent(tokens[2]);
    }

    return params;
}



function loadPage(href, target) {

	href = decodeURIComponent(href);
  	history.pushState({ path: href }, '', href);

	console.log("Loadpage: " + href);


	var urlParams = getQueryParams(href);

	console.log("Page: " + urlParams.page);

	
    if (urlParams.page === undefined) {
    	var page = "mainpage";
    } else {
		var page = urlParams.page;
    }
    


	ajaxLoader('start');



	$.ajax({
		type: "GET",
		url: "modules/" + urlParams.m + "/" + page + ".php?ajax=true",
		data: href,
		cache: false,
		success: function(data){
			$('#' + target).hide().html(data).fadeIn(300);
			ajaxLoader('end');
			//history.pushState(null, 'Test', '?m=' + module + '&page=' + page);

			// Make a div like a href to a href within
			$('.linkThisDiv').css('cursor' , 'pointer'); // change mouse cursor to pointer on the div
			
			$(".linkThisDiv").click(function(){
				window.location=$(this).find("a").attr("href"); 
				return false;
			});


			$('.toolTip').tooltip({
				placement: 'top'
			});


			var $container = $('#tiles-container').packery({
				columnWidth: 120,
				rowHeight: 120,
				gutter: 10
			});

		},
		error: function() {
			console.log("ERROR loading page");
			console.log("Module: " + urlParams.m);
			console.log("Page: " + page);
			console.log("Href: " + href);
			//alert("Error loading AJAX page...");
		},
		dataType: "html"
	});

}



function viewActivityLog() {

	$('.activityLog-container').show();

	$.ajax({
		type: "GET",
		url: "activitylog.php?ajax=true",
		cache: false,
		dataType: 'json',
		success: function(data){
			//$('#activitylog-content').html(data);
			var d = $.parseJSON(data);

			for (var key in d) {
				var item = d[key];

				//console.log(item);
				//console.log(item.message);

				$('#activitylog-table').append('<tr><td style="width:150px;">'+item.time_human+'</td><td>'+item.message+'</td></tr>');

				/*
				for (var key2 in item) {
					//console.log(item[key2]);
				}
				*/
			}

		},
		error: function() {
			console.log("ERROR loading activitylog");
		},
		dataType: "html"
	});
}

