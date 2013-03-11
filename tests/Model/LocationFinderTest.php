<?php

class LocationFinderTest extends \TestCase
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
        $this->con->exec("INSERT INTO locations (NAME, ADDRESS) VALUES ('BBox', 'Clermont-Ferrand')");
        $this->con->exec("INSERT INTO locations (NAME, ADDRESS) VALUES ('Five', 'Clermont-Ferrand')");
        $this->con->exec("INSERT INTO locations (NAME, ADDRESS) VALUES ('Le caliente', 'Aurillac')");
    }

    public function testFindAll()
    {
        $finder = new \Model\Finder\LocationFinder($this->con);
        $locations = $finder->findAllJustLocations();
        $this->assertEquals(3, count($locations));
    }

    public function testFindOneBydId()
    {
        $finder = new \Model\Finder\LocationFinder($this->con);
        $location = $finder->findOneByIdJustLocation(2);
        $this->assertEquals(1, count($location));
        $this->assertEquals('Five', $location->getName());
    }
}
