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
        $result = array_map('self::createLocation', $result);
        return $result;
    } 

    public function findOneById($id)
    {
        $query = 'SELECT * FROM LOCATIONS WHERE ID = :id';
        $stmt = $this->con->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        if(false === empty($result)) {
           return array_map('self::createLocation', $result)[0];
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
        $result = array_map('self::createComment', $result);
        return $result;
    }
    
    private function createLocation ($row)
    {
      	$date = null;
   	if(null !== $row['CREATED_AT']) {
            $date = new \DateTime($row['CREATED_AT']);
   	}    
   	$location = new Location($row['NAME'], $date);
   	Util::setPropertyValue($location, $row['ID']);
   	$location->setComments($this->findCommentsByLocationId($location->getId()));
        return $location;
    }

    public function createComment($row)
    {
        $date = null;
        if(null !== $row['CREATED_AT']) {
            $date = new \DateTime($row['CREATED_AT']);
        }
        $comment = new Comment( $row['USERNAME'], $row['BODY'], $date);
        Util::setPropertyValue($comment, $row['ID']);
        return $comment;
    }
}
