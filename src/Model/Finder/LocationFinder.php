<?php

namespace Model\Finder;

use Dal\Connection;
use Model\Entities\Location;
use Model\Finder\PartyFinder;
use Model\Finder\CommentFinder;
use Model\Util;

class LocationFinder implements FinderInterface
{
    private $con;

    public function __construct(Connection $con)
    {
        $this->con = $con;
    }

    public function findAll($criterias = null)
    {
        $query = 'SELECT * FROM LOCATIONS ';
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
        $result = array_map(array($this, 'createLocation'), $result);

        return $result;
    }

    public function findAllJustLocations($criterias = null)
    {
        $query = 'SELECT * FROM LOCATIONS ';
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
        $result = array_map(array($this, 'createLocationOnly'), $result);

        return $result;
    }

    public function findOneById($id)
    {
        $query = 'SELECT * FROM LOCATIONS WHERE ID = :id';
        $stmt = $this->con->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        if (false === empty($result)) {
           return array_map(array($this,'createLocation'), $result)[0];
        }

        return null;
    }

    public function findOneByIdJustLocation($id)
    {
        $query = 'SELECT * FROM LOCATIONS WHERE ID = :id';
        $stmt = $this->con->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        if (false === empty($result)) {
           return array_map(array($this,'createLocationOnly'), $result)[0];
        }

        return null;
    }

    private function createLocation ($row)
    {
        $location = $this->createLocationOnly($row);

        $commentFinder = new CommentFinder($this->con);
        $comments = $commentFinder->findCommentsByLocationId($row['ID']);

        $partyFinder = new PartyFinder($this->con);
        $parties = $partyFinder->findPartiesByLocationId($row['ID'], $location);

        $location->setComments($comments);
        $location->setParties($parties);

        return $location;
    }

    private function createLocationOnly ($row)
    {
        $date = null;
        if (null !== $row['CREATED_AT']) {
            $date = new \DateTime($row['CREATED_AT']);
        }

        $location = new Location($row['NAME'], $row['ADDRESS'], $row['DESCRIPTION'], $row['PHONE_NUMBER'], $date);
        Util::setPropertyValue($location, $row['ID']);

        return $location;
    }
}
