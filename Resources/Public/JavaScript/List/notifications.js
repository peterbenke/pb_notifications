$(document).ready(function() {

	if($("a[rel^='prettyPhoto']").length){
		$("a[rel^='prettyPhoto']").prettyPhoto({
			show_title: false,
			social_tools: false
		});
	}

});