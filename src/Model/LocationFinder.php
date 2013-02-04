<?php

namespace Model;

use Dal\Connection;

class LocationFinder implements FinderInterface
{
    private $con;

    public function __construct(Connection $con)
    {
        $this->con = $con;
    }
 
    /*function createLocation($row)
    {
         $date = null;
         if(false === empty($row['CREATED_AT'])) {
              $date = new DateTime($row['CREATED_AT']);
         }
         return new Location($row['ID'], $row['NAME'], $date);
    }*/

     public function findAll()
     {
        $query = 'SELECT * FROM LOCATIONS';
        $stmt = $this->con->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $result = array_map(function($row) {
           $date = null;
           if(isset($row['CREATED_AT'])) {
                $date = new \DateTime($row['CREATED_AT']);
           }    
           return new Location($row['ID'], $row['NAME'], $date);
        }, $result);
        return $result;
    } 

    public function findOneById($id)
    {
        $query = 'SELECT * FROM LOCATIONS WHERE ID = :id';
        $stmt = $this->con->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        $date = null;
        if(false === empty($row[0]['CREATED_AT'])) {
            $date = new \DateTime($row['CREATED_AT']);
        }

        if(false === empty($result)) {
           return new Location($result[0]['ID'], $result[0]['NAME'], $date);
        } 
        return null;
    }

    public function findCommentsByLocationId($id)
    {
        $query = 'SELECT * FROM COMMENTS WHERE LOCATION_ID = :id';
        $stmt = $this->con->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $result = array_map(function($row) {
           $date = null;
           if(isset($row['CREATED_AT'])) {
                $date = new \DateTime($row['CREATED_AT']);
           }
           return new Comment($row['ID'], $row['USERNAME'], $row['BODY'], $date);
        }, $result);
        return $result;
    }
}
