<?php

$method = $_SERVER['REQUEST_METHOD'];
include("server/businesslogic/simpleLogic.php");
var_dump($method);
// get list of available timezones
if ($method === 'GET') {
    header('Content-Type: application/json');
    $logic = new SimpleLogic();
    $result = $logic->handleRequest($method, $param);
    
    // $tz = getTimezones();

    http_response_code(200);
    echo json_encode($result);    

// convert given datetime
} else if ($method === "POST") {

    // read json
    // $jsonObj = json_decode(file_get_contents('php://input'));
    // $timeObj = convertDateTime($jsonObj);
    // if ($timeObj === null) {
    //     http_response_code(400);
    //     echo "Bad request";
    //     exit;    
    // }

    // header('Content-Type: application/json');
    // http_response_code(200);
    // echo json_encode($timeObj);

} else {
    http_response_code(405);
    header("Allow: GET POST");
    echo "Method Not Allowed";
    exit;
}





// $param = "";
// $method = "";

// isset($_GET["method"]) ? $method = $_GET["method"] : false;
// isset($_GET["param"]) ? $param = $_GET["param"] : false;

// $logic = new SimpleLogic();
// $result = $logic->handleRequest($method, $param);
// if ($result == null) {
//     response("GET", 400, null);
// } else {
//     response("GET", 200, $result);
// }

// function response($method, $httpStatus, $data)
// {
//     header('Content-Type: application/json');
//     switch ($method) {
//         case "GET":
//             http_response_code($httpStatus);
//             echo (json_encode($data));
//             break;
//         default:
//             http_response_code(405);
//             echo ("Method not supported yet!");
//     }
// }

