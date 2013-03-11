<?php

class CommentFinderTest extends \TestCase
{
    private $con;

    public function setUp()
    {
        $this->con = new \Dal\Connection('sqlite::memory:');
        $this->con->exec(<<<SQL
CREATE TABLE IF NOT EXISTS locations(
    ID INTEGER NOT NULL PRIMARY KEY,
    NAME VARCHAR(250) NOT NULL,
    ADDRESS VARCHAR(250) NOT NULL,
    DESCRIPTION VARCHAR(500),
    PHONE_NUMBER VARCHAR(20),
    CREATED_AT DATETIME
);
SQL
    );
        $this->con->exec(<<<SQL
CREATE TABLE IF NOT EXISTS comments(
    ID INTEGER NOT NULL PRIMARY KEY,
    USERNAME VARCHAR(250) NOT NULL,
    BODY VARCHAR(250) NOT NULL,
    CREATED_AT DATETIME,
    LOCATION_ID INTEGER NOT NULL,
    FOREIGN KEY (LOCATION_ID) REFERENCES LOCATIONS(ID) ON DELETE CASCADE
);
SQL
    );
        $this->con->exec("INSERT INTO locations (NAME, ADDRESS) VALUES ('BBox', 'Clermont-Ferrand')");
        $this->con->exec("INSERT INTO locations (NAME, ADDRESS) VALUES ('Five', 'Clermont-Ferrand')");

        $this->con->exec("INSERT INTO comments (USERNAME, BODY, LOCATION_ID) VALUES ('Alexis', 'Comment', 1)");
        $this->con->exec("INSERT INTO comments (USERNAME, BODY, LOCATION_ID) VALUES ('Audrey', 'Comment 1', 1)");
        $this->con->exec("INSERT INTO comments (USERNAME, BODY, LOCATION_ID) VALUES ('Alexis', 'Comment 2', 2)");
    }

    public function testFindAll()
    {
        $finder = new \Model\Finder\CommentFinder($this->con);
        $comments = $finder->findAll();
        $this->assertEquals(3, count($comments));
    }

    public function testFindOneById()
    {
        $finder = new \Model\Finder\CommentFinder($this->con);
        $comment = $finder->findOneById(3);
        $this->assertEquals(1, count($comment));
        $this->assertEquals('Alexis', $comment->getUsername());
        $this->assertEquals('Comment 2', $comment->getBody());
    }

    public function testFindCommentsByLocationId()
    {
        $finder = new \Model\Finder\CommentFinder($this->con);
        $comments = $finder->findCommentsByLocationId(1);
        $this->assertEquals(2, count($comments));
        $this->assertEquals('Alexis', $comments[0]->getUsername());
        $this->assertEquals('Audrey', $comments[1]->getUsername());
    }
}
