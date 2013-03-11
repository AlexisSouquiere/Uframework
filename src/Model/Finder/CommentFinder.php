<?php

namespace Model\Finder;

use Dal\Connection;
use Model\Entities\Comment;
use Model\Entities\Location;
use Model\Util;

class CommentFinder implements FinderInterface
{
    private $con;

    public function __construct(Connection $con)
    {
        $this->con = $con;
    }

    public function findAll($criterias = null)
    {
        $query = 'SELECT COMMENTS.ID AS COMMENT_ID, COMMENTS.USERNAME, COMMENTS.BODY, COMMENTS.CREATED_AT,
                  LOCATIONS.ID AS LOCATION_ID, LOCATIONS.NAME, LOCATIONS.ADDRESS
                  FROM COMMENTS JOIN LOCATIONS ON COMMENTS.LOCATION_ID=LOCATIONS.ID ';
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
        $result = array_map(array($this, 'createComment'), $result);

        return $result;
    }

    public function findOneById($id)
    {
        $query = 'SELECT COMMENTS.ID AS COMMENT_ID, COMMENTS.USERNAME, COMMENTS.BODY, COMMENTS.CREATED_AT,
                  LOCATIONS.ID AS LOCATION_ID, LOCATIONS.NAME, LOCATIONS.ADDRESS
                  FROM COMMENTS JOIN LOCATIONS ON COMMENTS.LOCATION_ID=LOCATIONS.ID
                  WHERE COMMENTS.ID = :id ';
        $stmt = $this->con->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        if (false === empty($result)) {
           return array_map(array($this, 'createComment'), $result)[0];
        }
    }

    public function findCommentsByLocationId($id)
    {
        $query = 'SELECT COMMENTS.ID AS COMMENT_ID, COMMENTS.USERNAME, COMMENTS.BODY, COMMENTS.CREATED_AT,
                  LOCATIONS.ID AS LOCATION_ID, LOCATIONS.NAME, LOCATIONS.ADDRESS
                  FROM COMMENTS JOIN LOCATIONS ON COMMENTS.LOCATION_ID=LOCATIONS.ID
                  WHERE LOCATION_ID = :id ORDER BY COMMENTS.CREATED_AT DESC';

        $stmt = $this->con->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (false === empty($result)) {
           return array_map(array($this, 'createComment'), $result);
        }

        return array();
    }

    public function createComment($row)
    {
        $createdAt = null;
        if (null !== $row['CREATED_AT']) {
            $createdAt = new \DateTime($row['CREATED_AT']);
        }

        $comment = new Comment($row['USERNAME'], $row['BODY'], $createdAt);
        Util::setPropertyValue($comment, $row['COMMENT_ID']);

        $location = new Location($row['NAME'], $row['ADDRESS']);
        Util::setPropertyValue($location, $row['LOCATION_ID']);

        $comment->setLocation($location);

        return $comment;
    }
}
