<?php

require_once 'server/db/db.php';

class Voting
{
    //creates a voting with values
    private $id_voting;
    private $username;
    private $comment;

    public function __construct($username,$comment) 
    {
        $this->username = $username;
        $this->comment = $comment;
    }

    
    //creates a comment
    public function createVoting(){ // param = UserObject

        $db = new DB();
        
         // mapping
         $mapping = [
            'username' => $this->username,
            'comment' => $this->comment,
        ];

        $result = $db->create('voting', $mapping); 

        return $result;
    }



    //get all comments from certain user
    public static function getAllVotings() 
    {
        echo "bin in funktion getAllvotings";
        $db = new DB();
        $result = $db->fetchAllWithParams("voting", "Voting", array("id_voting", "username", "comment"));  
        var_dump($result);
        return $result;
    }


     //get all comments from certain user
     public static function getAllCommentsOfWish($username) 
     {
         $db = new DB();
         $result = $db->fetchAllWithAttribut($username, "comments", "Comment", 'username', array("id", "username", "user_id", "comment", "created_at"));  
        //  var_dump($result);
         return $result;
     }



    // getter/setter

    public function setComment($comment) {

        $this->comment=$comment;

    }

    public function getComment() {

        return $this->comment;

    }


    public function setUsername($username) {

        $this->username = $username;

    }

    public function getUsername() {

        return $this->username;

    }




    public function getId_voting() {

        return $this->id_voting;

    }



}
