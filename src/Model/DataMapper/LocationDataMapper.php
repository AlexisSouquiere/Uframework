<?php

namespace Model\DataMapper;

use Dal\Connection;
use Model\Util;
use Model\Entities\Location;

class LocationDataMapper implements DataMapperInterface
{
    private $con;

    public function __construct(Connection $con)
    {
        $this->con = $con;
    }

    public function persist($location)
    {
        if ($location->isNew()) {
            $query = 'INSERT INTO LOCATIONS (NAME, ADDRESS, DESCRIPTION, PHONE_NUMBER, CREATED_AT) VALUES(:name, :address, :description, :phone, :date)';
            $parameters = ['name' => $location->getName(), 'address' => $location->getAddress(), 'description' => $location->getDescription(),
                           'phone' => $location->getPhoneNumber(), 'date' => $location->getCreatedAt()->format('Y-m-d H:i:s')];
            $stmt = $this->con->executeQuery($query, $parameters);
            $id = $this->con->LastInsertId();
            Util::setPropertyValue($location, $id);

            return $stmt;
        } else {
            $query = 'UPDATE LOCATIONS SET
                      NAME = :name, ADDRESS = :address, DESCRIPTION = :description, PHONE_NUMBER = :phone WHERE ID = :id';
            $parameters = ['name' => $location->getName(), 'address' => $location->getAddress(), 'description' => $location->getDescription(),
                           'phone' => $location->getPhoneNumber(), 'id' => $location->getId()];

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
}
