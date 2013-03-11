<?php

namespace Model\DataMapper;

use Dal\Connection;
use Model\Util;
use Model\Entities\Comment;

class CommentDataMapper implements DataMapperInterface
{
    private $con;

    public function __construct(Connection $con)
    {
        $this->con = $con;
    }

    public function persist($comment)
    {
        if ($comment->isNew()) {
            $query = 'INSERT INTO COMMENTS (USERNAME, BODY, CREATED_AT, LOCATION_ID) VALUES(:username, :body, :date, :id)';
            $parameters = ['username' => $comment->getUsername(), 'body' => $comment->getBody() ,
                           'date' => $comment->getCreatedAt()->format('Y-m-d H:i:s'), 'id' => $comment->getLocation()->getId()];
            $stmt = $this->con->executeQuery($query, $parameters);
            $id = $this->con->LastInsertId();
            Util::setPropertyValue($comment, $id);

            return $stmt;
        } else {
            $query = 'UPDATE COMMENTS SET
                      USERNAME = :username, BODY = :body
                      WHERE ID = :id';
            $parameters = ['username' => $comment->getUsername(), 'body' => $comment->getBody(), 'id' =>  $comment->getId()];

            return $this->con->executeQuery($query, $parameters);
        }
    }

    public function remove($comment)
    {
        $query = 'DELETE FROM COMMENTS WHERE ID = :id';
        $parameters = ['id' =>  $comment->getId()];
        $stmt = $this->con->executeQuery($query, $parameters);
        Util::setPropertyValue($comment, null);

        return $stmt;
    }
}
