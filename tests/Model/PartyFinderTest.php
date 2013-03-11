<?php

class PartyFinderTest extends \TestCase
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
        $this->con->exec("INSERT INTO locations (NAME, ADDRESS) VALUES ('BBox', 'Clermont-Ferrand')");
        $this->con->exec("INSERT INTO locations (NAME, ADDRESS) VALUES ('Five', 'Clermont-Ferrand')");

        $this->con->exec("INSERT INTO parties (NAME, START_AT, LOCATION_ID) VALUES ('Soirée infirmière', '2013-02-14 21:00:00', 1)");
        $this->con->exec("INSERT INTO parties (NAME, START_AT, LOCATION_ID) VALUES ('Soirée', '2013-03-14 22:00:00', 1)");
        $this->con->exec("INSERT INTO parties (NAME, START_AT, LOCATION_ID) VALUES ('Test', '2013-02-14 20:00:00', 2)");
    }

    public function testFindAll()
    {
        $finder = new \Model\Finder\PartyFinder($this->con);
        $parties = $finder->findAll();
        $this->assertEquals(3, count($parties));
    }

    public function testFindOneById()
    {
        $finder = new \Model\Finder\PartyFinder($this->con);
        $party = $finder->findOneById(3);
        $this->assertEquals(1, count($party));
        $this->assertEquals('Test', $party->getName());
    }

    public function testFindAllByLocationId()
    {
        $finder = new \Model\Finder\PartyFinder($this->con);
        $parties = $finder->findPartiesByLocationId(1);
        $this->assertEquals(2, count($parties));
        $this->assertEquals('Soirée infirmière', $parties[0]->getName());
        $this->assertEquals('Soirée', $parties[1]->getName());
    }
}
