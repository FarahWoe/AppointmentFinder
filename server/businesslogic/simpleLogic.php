<?php
include("db/dataHandler.php");

class SimpleLogic
{
    private $dh;
    function __construct()
    {
        $this->dh = new DataHandler();
    }

    function handleRequest($method)
    {
        var_dump($method);
        switch ($method) {
            case "queryVotings":
                $res = $this->dh->queryVotings();
                var_dump($res);
                break;
            case "queryPersonById":
                // $res = $this->dh->queryPersonById($param);
                break;
            case "queryPersonByName":
                // $res = $this->dh->queryPersonByName($param);
                break;
            default:
                $res = null;
                break;
        }
        return $res;
    }
}
 
