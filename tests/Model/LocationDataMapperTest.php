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
    address VARCHAR(250) NOT NULL,
    description VARCHAR(500),
    phone_number VARCHAR(20),
    created_at DATETIME
);
SQL
    );
    }

    public function testPersist()
    {
        $mapper = new \Model\DataMapper\LocationDataMapper($this->con);

        $rows = $this->con->query('SELECT COUNT(*) FROM locations')->fetch(\PDO::FETCH_NUM);
        $this->assertEquals(0, $rows[0]);

        $location = new \Model\Entities\Location('BBox', '63 000 Clermont-Ferrand', null, null,  new \DateTime());

        $mapper->persist($location);

        $rows = $this->con->query('SELECT COUNT(*) FROM locations')->fetch(\PDO::FETCH_NUM);
        $this->assertEquals(1, $rows[0]);
    }

    public function testRemove()
    {
        $mapper = new \Model\DataMapper\LocationDataMapper($this->con);

        $rows = $this->con->query('SELECT COUNT(*) FROM locations')->fetch(\PDO::FETCH_NUM);
        $this->assertEquals(0, $rows[0]);

        $location = new \Model\Entities\Location('BBox', '63 000 Clermont-Ferrand', null, null,  new \DateTime());

        $mapper->persist($location);

        $rows = $this->con->query('SELECT COUNT(*) FROM locations')->fetch(\PDO::FETCH_NUM);
        $this->assertEquals(1, $rows[0]);

        $mapper->remove($location);

        $rows = $this->con->query('SELECT COUNT(*) FROM locations')->fetch(\PDO::FETCH_NUM);
        $this->assertEquals(0, $rows[0]);
    }

    public function testUpdate()
    {
        $mapper = new \Model\DataMapper\LocationDataMapper($this->con);

        $location = new \Model\Entities\Location('BBox', '63 000 Clermont-Ferrand', null, null,  new \DateTime());

        $mapper->persist($location);

        $location->setName('BBoxxxx');

        $mapper->persist($location);

        $query = 'SELECT name FROM locations WHERE id = :id';
        $stmt = $this->con->prepare($query);
        $stmt->bindValue(':id', $location->getId());
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->assertEquals('BBoxxxx', $rows[0]['name']);
    }
}
