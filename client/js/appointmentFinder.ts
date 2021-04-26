//Starting point for JQuery init

function loaddata(search: string) {

	$.ajax({
		type: "GET",
		url: "../server/serviceHandler.php",
		cache: false,
		//data: { method: "queryNoteByItem", param: search },	
		data: { param: search },
		dataType: "json",
		success: function (response) {
            console.log(response);
			$.each(response, function (key, value) {

				console.log(value.username, value.comment);
				$("#entries").append("<p>" + value.username + ": " + value.comment + "</p>");

			})
		},
		error: function (response) {
			console.log("Response ERROR");
		}

	});
}


$(document).on("click", "#test", function () {
	console.log("TEST");
	loaddata('Rebecca');

})



//document.addEventListener('click', loaddata(1), false);


