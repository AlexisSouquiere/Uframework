<?php

namespace Model\Entities;

class Party
{
    private $id;
    private $name;
    private $startAt;
    private $description;
    private $createdAt;
    private $location;

    public function __construct($name, \DateTime $startAt, $description = null, \DateTime $createdAt = null)
    {
        $this->name = $name;
        $this->startAt = $startAt;
        $this->description = $description;
        $this->createdAt = $createdAt;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getStartAt()
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTime $startAt)
    {
        $this->startAt = $startAt;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation(Location $location)
    {
        $this->location = $location;
    }

    public function isNew()
    {
        return null === $this->id;
    }
}
