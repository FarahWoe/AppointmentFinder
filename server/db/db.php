<?php

// require_once 'model/user.model.php';

include("./server/model/appointment.model.php");

class DB
{

    public $pdo = null;

    private $host;
    private $db;
    private $username; 
    private $password; 

    public function __construct()      
    {
        $db_config = parse_ini_file("config/db.ini");

        $this->host = $db_config['host'];
        $this->db = $db_config['db'];
        $this->username = $db_config['username'];
        $this->password = $db_config['password'];

        // dsn = data source name      
        $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db . "";

        try {       //connection to the database 
            $this->pdo = new PDO($dsn, $this->username, $this->password);
        } catch (PDOException $e)    //if connection fails
        {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
            die();
        }
    }
    

    //function to create data entries for multiple purposes like wishes, user, ...
    public function create($table, $values)  
    {
        $column = implode(', ', array_keys($values));
        $params = ':' . implode(', :', array_keys($values));
        $stmt = $this->pdo->prepare("INSERT INTO $table ($column) VALUES($params)");
       
        //Loop for binding all params
        foreach ($values as $key => &$val) 
        {
            $stmt->bindParam(':' . $key, $val, PDO::PARAM_STR); 
        }
        $stmt->execute();  
        
        return $stmt;
    }


  //function to update datas for multiple purposes like wishes, user, ...depending on diffrent attrributes like 'id' or any other database attribute in the table
    public function updateWithAttribut($attr, $table, $values, $db_attr) 
    {
        foreach ($values as $key => $val) 
        {
            if (isset($val) && (!empty($val)))    
            {      
                $stmt = $this->pdo->prepare("UPDATE $table SET $key = \"". $val . "\" WHERE $db_attr = '$attr'");
                $stmt->bindParam(':' . $key, $val, PDO::PARAM_STR);
                $stmt->execute(); 
            }
        }
        return $stmt;

    }


    //deletes one line of datas from a table in database 
    public function delete($db_attr, $table, $delete_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM $table WHERE $db_attr = $delete_id");     
        $stmt->bindParam(':' . $db_attr, $delete_id, PDO::PARAM_STR);
        $result = $stmt->execute(); 

        return $result;
    }


    //for deletes a specific entry like images from user table and setting it NULL
    public function deleteEntry($db_select, $db_attr, $table, $delete_id)
    {
        $stmt = $this->pdo->prepare("UPDATE $table SET $db_select = NULL WHERE $db_attr = $delete_id");     
        $stmt->bindParam(':' . $db_attr, $delete_id, PDO::PARAM_STR);
        $stmt->execute(); 

        return $stmt;
    }


    //selects wishes of user which are marked as fulfilled
    public function getAllFulfilledWishesOfUser($user_id)
    {
        $ctr = array(NULL, NULL, NULL, NULL, NULL); 
        $select = $this->pdo->query(
            "SELECT * 
            FROM wishes 
            WHERE fulfilled = 1 
            AND user_id = $user_id 
            ORDER BY created_at"); 
        //class and constructor is called; class 'Wish' has a constructor with 5 variables, thats why 5times NULL 
        $result = $select->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Wish', $ctr); 
       
        return $result;
    }

   




    public function getWishListUser_rights_anonymous($table, $class, $params = [])
    {
        $stringParams = implode(", ", $params);// Use of implode function 
        $ctr = array(NULL, NULL, NULL, NULL, NULL);
        
        $select = $this->pdo->query(
            "SELECT $stringParams 
            FROM $table 
            WHERE public = 1 
            ORDER BY 'create_at DESC'");
        // var_dump($select);
        if($select)         //so machen?
        {
            $result = $select->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, $class, $ctr);
            return $result;
        }

        return FALSE;
    }


    //use of this function in getAllWishes -> this function is without WHERE term
    public function fetchAllWithParams($table, $class, $params = [])
    {
        $arr = $params; 
        $stringParams = implode(", ",$arr); // Use of implode function 
        $ctr = array(NULL, NULL, NULL, NULL, NULL, NULL);
      
        $stmt = $this->pdo->query("SELECT $stringParams FROM $table ORDER BY 'create_at DESC'");
       
        if($stmt) 
        {
            $result = $stmt->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, $class, $ctr);
            return $result;
        }

        return FALSE;
    }

    //this function fetches a class and has a sql statement with a WHERE term
    public function fetchAllWithAttribut($attr, $table, $class, $db_attr, $params = [])
    {
        $arr = $params;  
        $stringParams = implode(", ",$arr); // Use of implode function 

        switch($class)
        {   // classes has diffrent number of variables, thats why diffrent $ctr; and the order by 
      
            case 'User':    $db_order = "id";
                            $ctr = array(NULL, NULL, NULL, NULL, NULL, NULL);   
            break;
            case 'Comment': $db_order = "created_at DESC";
                            $ctr = array(NULL, NULL, NULL); 
            break;
            case 'Wish':    $db_order = "created_at DESC";
                            $ctr = array(NULL, NULL, NULL, NULL, NULL); 
            break;
            case 'Rate':    $db_order = "id";
                            $ctr = array(NULL, NULL, NULL, NULL, NULL); 
            break;
        }
    
        $stmt = $this->pdo->query("SELECT $stringParams FROM $table WHERE $db_attr = '$attr' ORDER BY $db_order");

        if($stmt) 
        {
            $result = $stmt->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, $class, $ctr);
            return $result;
        }

        return FALSE;

    }
 

    //function filters wishes by one tag; public = 1 (true) OR userrights 1 or 2  -> gets all wishes with 2 diffrent tags, just userrights anonymous (3) gets just public wishes 
    public function WishesWithCertanTagAndUserRights($id, $user_rights)   
    {
        $ctr = array(NULL, NULL, NULL, NULL, NULL);
        
        $sql = "SELECT
        w1.*
        FROM wishes w1
            JOIN wishes_tags
                ON wishes_tags.wishes_id = w1.id
        WHERE wishes_tags.tags_id IN ($id)
        AND (w1.public = 1                       
             OR $user_rights IN (1,2))
        ORDER BY created_at DESC";

        $stmt = $this->pdo->query($sql); 
       
        if($stmt) 
        {                                                          
            $result = $stmt->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Wish', $ctr);
            return $result;
        }

        return FALSE;
    }

    //function filters wishes by 2 tags at the same time; public = 1 (true) OR userrights 1 or 2  -> gets all wishes with 2 diffrent tags, just userrights anonymous (3) gets just public wishes 
    public function WishesWithTwoTagsAndUserRights($id1, $id2, $user_rights)  
    {
        $ctr = array(NULL, NULL, NULL, NULL, NULL);
       
        $sql = "SELECT w1.*
        FROM wishes w1
            JOIN wishes_tags wt1
                ON wt1.wishes_id = w1.id
        WHERE wt1.tags_id = $id1
        AND EXISTS (SELECT 1
                    FROM wishes w2
                        JOIN wishes_tags wt2
                            ON wt2.wishes_id = w2.id
                       WHERE wt2.tags_id = $id2
                    AND w2.id = w1.id)
        AND (w1.public = 1                              
             OR $user_rights IN (1,2))
        ORDER BY w1.created_at DESC";

        $stmt = $this->pdo->query($sql); 
       
        if($stmt) 
        {                                                          
            $result = $stmt->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Wish', $ctr);
            return $result;
        }

        return FALSE;
    }


    //function to sort Wishes with for example most likes or dislikes (yes, can help you OR No, i can't help you ) depending on $sort
    public function sortWishesLikes($sort) 
    {
        $ctr = array(NULL, NULL, NULL, NULL, NULL);
        
        $sql = "SELECT * 
        FROM wishes w, rate r
        WHERE w.id = r.wishes_id
        ORDER BY $sort DESC";

        $stmt = $this->pdo->query($sql); 
        
        if($stmt) 
        {                                                          
            $result = $stmt->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Wish', $ctr);
            return $result;
        }

        return FALSE;

    }

    //function to sort Wishes by date ascending OR descending depending on $sort
    public function sortWishesDate($sort)  
    {
        $ctr = array(NULL, NULL, NULL, NULL, NULL);
        
        $sql = "SELECT * 
        FROM wishes
        ORDER BY $sort";

        $stmt = $this->pdo->query($sql); 
                
        if($stmt) 
        {                                                          
            $result = $stmt->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Wish', $ctr);
            return $result;
        }

        return FALSE;
    }
   



    //function gets all tags of a certain wish by wish_id
    public function getAllTagsOfCertainWish($wish_id, $class)
    {
        $ctr = array(NULL, NULL);

        $sql = "SELECT name 
        FROM tags t, wishes_tags wt
        WHERE wishes_id = '$wish_id'
        AND wt.tags_id = t.tag_id"; 

        $stmt = $this->pdo->query($sql); 

        if($stmt) 
        {                                                          
            $result = $stmt->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, $class, $ctr);
            return $result;
        }

        return FALSE;
 
    }


    //function to search in wishes, titles and comments and returns wishes
    public function search($something)
    {          
        $ctr = array(NULL, NULL, NULL, NULL, NULL);
        $class = "Wish";

        
        $sql = "SELECT w.* 
        FROM wishes w
           LEFT JOIN comments c
           ON (w.id = c.wishes_id)
        WHERE w.title LIKE '%$something%' 
            OR w.description LIKE '%$something%' 
            OR c.comment LIKE '%$something%'
        GROUP BY w.id
        ORDER BY GREATEST ( NVL(c.created_at, DATE('1987-12-19')), w.created_at) DESC
        ";

        $stmt = $this->pdo->query($sql);       
       if($stmt) 
       {                                                          
           $result = $stmt->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, $class, $ctr);
           return $result;
       }

       return FALSE;
     
    }




    
    //close db connection
    public function close()
    {
        $this->pdo = NULL;
    }





}

