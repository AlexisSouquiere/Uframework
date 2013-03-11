<?php

namespace Model\Finder;

use Dal\Connection;
use Model\Entities\Party;
use Model\Entities\Location;
use Model\Util;

class PartyFinder implements FinderInterface
{
    private $con;

    public function __construct(Connection $con)
    {
        $this->con = $con;
    }

    public function findAll($criterias = null)
    {
        $query = 'SELECT PARTIES.ID AS PARTY_ID, PARTIES.NAME, PARTIES.DESCRIPTION, PARTIES.CREATED_AT, PARTIES.START_AT,
                  LOCATIONS.ID AS LOCATION_ID, LOCATIONS.NAME AS LOCATION_NAME, LOCATIONS.ADDRESS FROM PARTIES
                  JOIN LOCATIONS ON PARTIES.LOCATION_ID=LOCATIONS.ID ';
        if (isset($criterias)) {
            foreach ($criterias as $criteria => $value) {
                if (isset($value)) {
                    $query .= $criteria. ' ' . $value . ' ';
                }
            }
        }
        $stmt = $this->con->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $result = array_map(array($this, 'createParty'), $result);

        return $result;
    }

    public function findOneById($id)
    {
        $query = 'SELECT PARTIES.ID AS PARTY_ID, PARTIES.NAME, PARTIES.DESCRIPTION, PARTIES.CREATED_AT, PARTIES.START_AT,
                  LOCATIONS.ID AS LOCATION_ID, LOCATIONS.NAME AS LOCATION_NAME, LOCATIONS.ADDRESS FROM PARTIES
                  JOIN LOCATIONS ON PARTIES.LOCATION_ID=LOCATIONS.ID
                  WHERE PARTIES.ID = :id ';
        $stmt = $this->con->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        if (false === empty($result)) {
           return array_map(array($this, 'createParty'), $result)[0];
        }

        return null;
    }

    public function findPartiesByLocationId($id)
    {
        $query = 'SELECT PARTIES.ID AS PARTY_ID, PARTIES.NAME, PARTIES.DESCRIPTION, PARTIES.CREATED_AT, PARTIES.START_AT,
                  LOCATIONS.ID AS LOCATION_ID, LOCATIONS.NAME AS LOCATION_NAME, LOCATIONS.ADDRESS FROM PARTIES
                  JOIN LOCATIONS ON PARTIES.LOCATION_ID=LOCATIONS.ID
                  WHERE LOCATION_ID = :id';
        $stmt = $this->con->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        if (false === empty($result)) {
           return array_map(array($this, 'createParty'), $result);
        }

        return array();
    }

    private function createParty ($row)
    {
        $createdAt = null;
        if (null !== $row['CREATED_AT']) {
            $createdAt = new \DateTime($row['CREATED_AT']);
        }
        $startAt = null;
        if (null !== $row['START_AT']) {
            $startAt = new \DateTime($row['START_AT']);
        }

        $party = new Party($row['NAME'], $startAt, $row['DESCRIPTION'], $createdAt);
        Util::setPropertyValue($party, $row['PARTY_ID']);

        $location = new Location($row['LOCATION_NAME'], $row['ADDRESS']);
        Util::setPropertyValue($location, $row['LOCATION_ID']);

        $party->setLocation($location);

        return $party;
    }
}
