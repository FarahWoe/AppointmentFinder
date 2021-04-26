<?php
include("./model/voting.model.php");

class DataHandler
{
    public function queryVotings()
    {
        $result = array();
        $result2 = Voting::getAllVotings();
        var_dump($result2);
        echo "Hello";
        foreach ($result2 as $val) {
            
                array_push($result, $val);
            
        }
        json_encode($result);
        return $result;

       
    }


    // public function queryNotes()
    // {
    //     $result =  $this->getDemoData();
    //     return $result;
    // }

    //Methoden erweiterbar
    // public function queryNoteByItem($item)
    // {
    //     $result = array();
    //     foreach ($this->queryNotes() as $val) {
    //         if ($val->item == $item) {
    //             array_push($result, $val);
    //         }
    //     }
    //     json_encode($result);
    //     return $result;
    // }

}

