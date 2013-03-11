<?php

namespace Model\Entities;

class Comment
{
    private $id;
    private $username;
    private $body;
    private $createdAt;
    private $location;

    public function __construct($username, $body, $createdAt = null)
    {
        $this->username = $username;
        $this->body = $body;
        $this->createdAt = $createdAt;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($name)
    {
        $this->username = $name;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body)
    {
        $this->body = $body;
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
