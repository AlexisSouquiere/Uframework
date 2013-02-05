<?php

class LocationDataMapperTest extends \TestCase
{
    private $con;

    public function setUp()
    {
        $this->con = new \Dal\Connection('sqlite::memory:');
        $this->con->exec(<<<SQL
    CREATE TABLE IF NOT EXISTS locations(
    id INTEGER NOT NULL PRIMARY KEY,
    name VARCHAR(250) NOT NULL,
    created_at DATETIME 
    );
SQL
        );
    }

    public function testPersist()
    {
        $mapper = new \Model\LocationDataMapper($this->con);

        $rows = $this->con->query('SELECT COUNT(*) FROM locations')->fetch(\PDO::FETCH_NUM);
        $this->assertEquals(0, $rows[0]);

        $location = new \Model\Location();
        $location->setName('Paris');

        $mapper->persist($location);

        $rows = $this->con->query('SELECT COUNT(*) FROM locations')->fetch(\PDO::FETCH_NUM);
        $this->assertEquals(1, $rows[0]);
    }
}
