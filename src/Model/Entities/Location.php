<?php

namespace Model\Entities;

class Location
{
    private $id;
    private $name;
    private $address;
    private $description;
    private $phoneNumber;
    private $createdAt;
    private $comments = array();
    private $parties = array();

    public function __construct($name, $address, $description = null, $phoneNumber = null, \DateTime $createdAt = null)
    {
        $this->name = $name;
        $this->address = $address;
        $this->description = $description;
        $this->phoneNumber = $phoneNumber;
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

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    public function getParties()
    {
        return $this->parties;
    }

    public function setParties($parties)
    {
        $this->parties = $parties;
    }

    public function isNew()
    {
        return null === $this->id;
    }
}
