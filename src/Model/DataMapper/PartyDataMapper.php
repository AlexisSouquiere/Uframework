<?php

namespace Model\DataMapper;

use Dal\Connection;
use Model\Util;
use Model\Entities\Party;

class PartyDataMapper implements DataMapperInterface
{
    private $con;

    public function __construct(Connection $con)
    {
        $this->con = $con;
    }

    public function persist($party)
    {
        if ($party->isNew()) {
            $query = 'INSERT INTO PARTIES (NAME, DESCRIPTION, START_AT, CREATED_AT, LOCATION_ID) VALUES(:name, :description, :startAt, :createdAt, :id)';
            $parameters = ['name' => $party->getName(), 'description' => $party->getDescription(), 'startAt' => $party->getStartAt()->format('Y-m-d H:i:s'),
                           'createdAt' => $party->getCreatedAt()->format('Y-m-d H:i:s'), 'id' => $party->getLocation()->getId()];
            $stmt = $this->con->executeQuery($query, $parameters);
            $id = $this->con->LastInsertId();
        Util::setPropertyValue($party, $id);

        return $stmt;
        } else {
            $query = 'UPDATE PARTIES SET
                      NAME = :name, DESCRIPTION = :description, START_AT = :startAt, CREATED_AT = :createdAt
                      WHERE ID = :id';
            $parameters = ['name' => $party->getName(), 'description' => $party->getDescription(), 'startAt' => $party->getStartAt()->format('Y-m-d H:i:s'),
                           'createdAt' => $party->getCreatedAt()->format('Y-m-d H:i:s'), 'id' => $party->getId()];

            return $this->con->executeQuery($query, $parameters);
        }
    }

    public function remove($party)
    {
        $query = 'DELETE FROM PARTIES WHERE ID = :id';
        $parameters = ['id' =>  $party->getId()];
        $stmt = $this->con->executeQuery($query, $parameters);
        Util::setPropertyValue($party, null);

        return $stmt;
    }
}
