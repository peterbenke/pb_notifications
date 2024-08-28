define(['jquery'], function($) {


  $(document).ready(function () {
    // on load
    //console.log('hallo');
    if($("a[rel^='prettyPhoto']").length){
      $("a[rel^='prettyPhoto']").prettyPhoto({
        show_title: false,
        social_tools: false
      });
    }

    $('.panel-body a[href^="http"]:not(a[rel^="prettyPhoto"]):not(a[href$=".zip"])').each(function(){
      $(this).attr('target', '_blank');
    });

  });

});

/*
$(document).ready(function() {

	if($("a[rel^='prettyPhoto']").length){
		$("a[rel^='prettyPhoto']").prettyPhoto({
			show_title: false,
			social_tools: false
		});
	}

	$('.panel-body a[href^="http"]:not(a[rel^="prettyPhoto"]):not(a[href$=".zip"])').each(function(){
		$(this).attr('target', '_blank');
	});

});

 */
