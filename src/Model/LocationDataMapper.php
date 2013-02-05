<?php 

namespace Model;

use Dal\Connection;

class LocationDataMapper implements DataMapperInterface
{
    private $con;

    public function __construct(Connection $con)
    {
        $this->con = $con;
    }

    public function persist($location)
    {
        if ($this->isNew($location)) {
            $query = 'INSERT INTO LOCATIONS (NAME, CREATED_AT) VALUES(:name, :date)';
            $parameters = array('name' => $location->getName(), 'date' => $location->getCreatedAt()->format('Y-m-d H:i:s'));
            $stmt = $this->con->executeQuery($query, $parameters);
            return $this->con->LastInsertId();
        } 
        else {
            $query = 'UPDATE LOCATIONS SET NAME = :name WHERE ID = :id';
            $parameters = array('name' => $location->getName(), 'id' =>  $location->getId());
            return $this->con->executeQuery($query, $parameters);
        } 
    }

    public function remove($location)
    {
        $query = 'DELETE FROM LOCATIONS WHERE ID = :id';
        $parameters = array('id' =>  $location->getId());
        $stmt = $this->con->executeQuery($query, $parameters);
        Util::setPropertyValue($location, null);
        return $stmt;
    }

    public function isNew(Location $location)
    {
        return null === $location->getId();
    }
}
