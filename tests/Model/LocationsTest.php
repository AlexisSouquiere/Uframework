<?php

namespace Model;

class LocationsTest extends \TestCase
{
    public function testInterfaces()
    {
        $this->assertTrue(interface_exists('Model\Finder\FinderInterface'));
        $this->assertTrue(interface_exists('Model\DataMapper\DataMapperInterface'));
    }
}
