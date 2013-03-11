<?php

class CommentDataMapperTest extends \TestCase
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
        $this->con->exec("INSERT INTO locations (name, address) VALUES ('BBox', 'Clermont-Ferrand')");
    }

    public function testPersist()
    {
        $mapper = new \Model\DataMapper\CommentDataMapper($this->con);

        $rows = $this->con->query('SELECT COUNT(*) FROM comments')->fetch(\PDO::FETCH_NUM);
        $this->assertEquals(0, $rows[0]);

        $comment = new \Model\Entities\Comment('Alexis', "Test commentaire", new \DateTime());

        $loc = new \Model\Finder\LocationFinder($this->con);
        $location = $loc->findOneByIdJustLocation(1);
        $comment->setLocation($location);

        $mapper->persist($comment);

        $rows = $this->con->query('SELECT COUNT(*) FROM comments')->fetch(\PDO::FETCH_NUM);
        $this->assertEquals(1, $rows[0]);
    }

    public function testRemove()
    {
        $mapper = new \Model\DataMapper\CommentDataMapper($this->con);

        $rows = $this->con->query('SELECT COUNT(*) FROM comments')->fetch(\PDO::FETCH_NUM);
        $this->assertEquals(0, $rows[0]);

        $comment = new \Model\Entities\Comment('Alexis', "Test commentaire", new \DateTime());

        $loc = new \Model\Finder\LocationFinder($this->con);
        $location = $loc->findOneByIdJustLocation(1);
        $comment->setLocation($location);

        $mapper->persist($comment);

        $rows = $this->con->query('SELECT COUNT(*) FROM comments')->fetch(\PDO::FETCH_NUM);
        $this->assertEquals(1, $rows[0]);

        $mapper->remove($comment);

        $rows = $this->con->query('SELECT COUNT(*) FROM comments')->fetch(\PDO::FETCH_NUM);
        $this->assertEquals(0, $rows[0]);
    }

    public function testUpdate()
    {
        $mapper = new \Model\DataMapper\CommentDataMapper($this->con);

        $comment = new \Model\Entities\Comment('Alexis', "Test commentaire", new \DateTime());

        $loc = new \Model\Finder\LocationFinder($this->con);
        $location = $loc->findOneByIdJustLocation(1);
        $comment->setLocation($location);

        $mapper->persist($comment);

        $comment->setUsername('Audrey');

        $mapper->persist($comment);

        $query = 'SELECT USERNAME FROM comments WHERE ID = :id';
        $stmt = $this->con->prepare($query);
        $stmt->bindValue(':id', $comment->getId());
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->assertEquals('Audrey', $rows[0]['USERNAME']);
    }
}
