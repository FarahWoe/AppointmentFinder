<?php
include("./model/voting.model.php");

class DataHandler
{
    
    public function queryNotes()
    {
        //$result2 = Voting::getAllVotings();
        $result =  $this->getDemoData();
        return $result;
    }

    //Methoden erweiterbar
    public function queryVotings($item)
    {

        $result = array();
        foreach ($this->queryNotes() as $val) {
            if ($val->item == $item) {
                array_push($result, $val);
            }
        }
        json_encode($result);
        return $result;
    }

    private static function getDemoData()
    {
        $demodata = [
            new Voting("Notiz 1", "Rebecca"),
            new Voting("Notiz 2", "Brot"),
            new Voting("Notiz 3", "Nudeln"),
            new Voting("Notiz 4", "Kaffee"),
            new Voting("Notiz 5", "Käse"),
        ];
        return $demodata;
    }

}

