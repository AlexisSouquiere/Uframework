<?php

class PartyDataMapperTest extends \TestCase
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
CREATE TABLE IF NOT EXISTS parties(
    ID INTEGER NOT NULL PRIMARY KEY,
    NAME VARCHAR(250) NOT NULL,
    START_AT DATETIME NOT NULL,
    DESCRIPTION VARCHAR(500),
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
        $mapper = new \Model\DataMapper\PartyDataMapper($this->con);

        $rows = $this->con->query('SELECT COUNT(*) FROM parties')->fetch(\PDO::FETCH_NUM);
        $this->assertEquals(0, $rows[0]);

        $party = new \Model\Entities\Party('Soiree infirmiere', new \DateTime(), null, new \DateTime());

        $loc = new \Model\Finder\LocationFinder($this->con);
        $location = $loc->findOneByIdJustLocation(1);
        $party->setLocation($location);

        $mapper->persist($party);

        $rows = $this->con->query('SELECT COUNT(*) FROM parties')->fetch(\PDO::FETCH_NUM);
        $this->assertEquals(1, $rows[0]);
    }

    public function testRemove()
    {
        $mapper = new \Model\DataMapper\PartyDataMapper($this->con);

        $rows = $this->con->query('SELECT COUNT(*) FROM parties')->fetch(\PDO::FETCH_NUM);
        $this->assertEquals(0, $rows[0]);

        $party = new \Model\Entities\Party('Soiree infirmiere', new \DateTime(), null, new \DateTime());

        $loc = new \Model\Finder\LocationFinder($this->con);
        $location = $loc->findOneByIdJustLocation(1);
        $party->setLocation($location);

        $mapper->persist($party);

        $rows = $this->con->query('SELECT COUNT(*) FROM parties')->fetch(\PDO::FETCH_NUM);
        $this->assertEquals(1, $rows[0]);

        $mapper->remove($party);

        $rows = $this->con->query('SELECT COUNT(*) FROM parties')->fetch(\PDO::FETCH_NUM);
        $this->assertEquals(0, $rows[0]);
    }

    public function testUpdate()
    {
        $mapper = new \Model\DataMapper\PartyDataMapper($this->con);

        $party = new \Model\Entities\Party('Soiree infirmiere', new \DateTime(), null, new \DateTime());

        $loc = new \Model\Finder\LocationFinder($this->con);
        $location = $loc->findOneByIdJustLocation(1);
        $party->setLocation($location);

        $mapper->persist($party);

        $party->setName('Soiree');

        $mapper->persist($party);

        $query = 'SELECT NAME FROM parties WHERE ID = :id';
        $stmt = $this->con->prepare($query);
        $stmt->bindValue(':id', $party->getId());
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->assertEquals('Soiree', $rows[0]['NAME']);
    }
}
