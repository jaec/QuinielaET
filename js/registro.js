$(document).ready(function() {

	$("input.score").on("click", function(e) {
		$(this)[0].focus();

		$(this)[0].setSelectionRange(0, 9999);
		

	})

	$("input.score").on("tap", function(e) {

		$(this)[0].focus();
		$(this)[0].setSelectionRange(0, 9999);

	})
})
