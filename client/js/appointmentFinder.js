"use strict";
//Starting point for JQuery init
// $(document).ready(function () {
//     $("#btn_Search").click(function (e) {
//        loaddata($("#seachfield").val());
//     });
// });
// const fetchTimezones = async () => {
//     const res = await fetch('../server/serviceHandler.php');
//     const data = await res.json();
//     console.log(data);
//     return data;
// }
document.addEventListener('DOMContentLoaded', loaddata, false);
function loaddata() {
    console.log("load data, aber ohne data");
    $.get("../server/serviceHandler.php", function (data) {
        console.log("get request");
        $.each(JSON.parse(data), function (key, value) {
            var _a;
            console.log("get request each");
            var selectElement = document.createElement("div");
            selectElement.innerHTML = value;
            (_a = document.getElementById("container")) === null || _a === void 0 ? void 0 : _a.appendChild(selectElement);
        });
    });
}
;
//     $.ajax({
//         type: "GET",
//         url: "../server/serviceHandler.php",
//         cache: false,
//         data: {method: "queryVotings"
//         // param: searchterm 
//         }
//         ,
//         dataType: "json",
//         success: function (response) {
//             console.log("Verbindung mit Datenbank klappt");
//             $("#noOfentries").val(response.length);
//             $("#searchResult").show();
//         }
//         // success: function (response) {
//         //     console.log("Verbindung mit Datenbank klappt");
// 		// 	$.each(response, function (key, value) {
// 		// 		console.log(value.item, value.store);
// 		// 		$("#entries").append("<p>" + value.notenumber + ": " + value.item + ", " + value.price + " Euro, " + value.store + "</p>");
// 		// 	})
// 		// }
//         ,
// 		error: function (response) {
// 			console.log("Response ERROR");
// 		}
//     });
// }
