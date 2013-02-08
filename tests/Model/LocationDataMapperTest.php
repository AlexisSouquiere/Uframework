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

        $location = new \Model\Location('Paris', new \DateTime(null));

        $mapper->persist($location);

        $rows = $this->con->query('SELECT COUNT(*) FROM locations')->fetch(\PDO::FETCH_NUM);
        $this->assertEquals(1, $rows[0]);
    }

    public function testRemove()
    {
        $mapper = new \Model\LocationDataMapper($this->con);

        $rows = $this->con->query('SELECT COUNT(*) FROM locations')->fetch(\PDO::FETCH_NUM);
        $this->assertEquals(0, $rows[0]);

        $location = new \Model\Location('Paris', new \DateTime(null));

        $id = $mapper->persist($location);
        \Model\Util::setPropertyvalue($location, $id);

        $rows = $this->con->query('SELECT COUNT(*) FROM locations')->fetch(\PDO::FETCH_NUM);
        $this->assertEquals(1, $rows[0]);

        $mapper->remove($location);

        $rows = $this->con->query('SELECT COUNT(*) FROM locations')->fetch(\PDO::FETCH_NUM);
        $this->assertEquals(0, $rows[0]);
    }

    public function testUpdate()
    {
        $mapper = new \Model\LocationDataMapper($this->con);
        
        $location = new \Model\Location('Paris', new \DateTime(null));
        
        $id = $mapper->persist($location);
        \Model\Util::setPropertyvalue($location, $id);

        $location->setName('Monaco');
        
        $mapper->persist($location);

        $query = 'SELECT name FROM locations WHERE id = :id';
        $stmt = $this->con->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->assertEquals('Monaco', $rows[0]['name']);       
    }
}
