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
 
    public function findAll()
    {
        $query = 'SELECT * FROM LOCATIONS';
        $stmt = $this->con->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $result = array_map(function($row) {
           $date = null;
           if(null !== $row['CREATED_AT']) {
                $date = new \DateTime($row['CREATED_AT']);
           }    
           $location = new Location($row['NAME'], $date);
           Util::setPropertyvalue($location, $row['ID']);
           $location->setComments($this->findCommentsByLocationId($location->getId()));
           return $location;
        }, $result);
        return $result;
    } 

    public function findOneById($id)
    {
        $query = 'SELECT * FROM LOCATIONS WHERE ID = :id';
        $stmt = $this->con->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $row = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        

        if(false === empty($row)) {
           $date = null;
           if(null !== $row[0]['CREATED_AT']) {
               $date = new \DateTime($row[0]['CREATED_AT']);
           }
           $location = new Location($row[0]['NAME'], $date);
           Util::setPropertyValue($location, $row[0]['ID']);
           $location->setComments($this->findCommentsByLocationId($location->getId()));
           return $location;
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
           if(null !== $row['CREATED_AT']) {
                $date = new \DateTime($row['CREATED_AT']);
           }
           $comment = new Comment( $row['USERNAME'], $row['BODY'], $date);
           Util::setPropertyValue($comment, $row['ID']);
           return $comment;
        }, $result);
        return $result;
    }
}
