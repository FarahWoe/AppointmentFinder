<?php
include("./model/voting.model.php");

class DataHandler
{
    public function queryVotings()
    {

        $result = Voting::getAllVotings();
        
        return $result;

       
    }


}

