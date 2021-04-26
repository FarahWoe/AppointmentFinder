//Starting point for JQuery init

document.addEventListener('DOMContentLoaded', loaddata, false);
alert("Test");
// $(document).ready(function () {
    
//     $("#btn_Search").click(function (e) {
//        loaddata($("#seachfield").val());
//     });

// });


function loaddata() {

    $.ajax({
        type: "GET",
        url: "../server/serviceHandler.php",
        cache: false,
        data: {method: "queryVotings", 
        // param: searchterm
    },
        dataType: "json",
        success: function (response) {
            console.log("Verbindung mit Datenbank klappt");
            $("#noOfentries").val(response.length);
            $("#searchResult").show();
        }
        
        
    });

    console.log("load data, aber ohne data");
}

