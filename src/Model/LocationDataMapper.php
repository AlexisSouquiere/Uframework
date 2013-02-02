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

    public function persist(Location $location)
    {
        if ($this->isNew($location)) {
            $stmt = $this->con->prepare('INSERT INTO LOCATIONS (NAME, CREATED_AT)
                                         VALUES(:name, :date)');
            $stmt->bindValue(':name', $location->getName());
            $stmt->bindValue(':date', $location->getCreatedAt()->format('Y-m-d H:i:s'));

            $stmt->execute();

        } 
        else {
            $stmt = $this->con->prepare('UPDATE LOCATIONS SET NAME = :name WHERE ID = :id');
            $stmt->bindValue(':name', $location->getName());
            $stmt->bindValue(':id', $location->getId());
            $stmt->execute();
        } 
    }

    public function remove(Location $location)
    {
        $stmt = $this->con->prepare('DELETE FROM LOCATIONS WHERE ID = :id');
        $stmt->bindValue(':id', $location->getId());
        $stmt->execute();
    }

    public function isNew(Location $location)
    {  
        return null === $location->getId();
    }
}
