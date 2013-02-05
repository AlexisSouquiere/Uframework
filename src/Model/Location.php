<?php

namespace Model;

class Location
{
    private $id;
    private $name;
    private $createdAt;
    private $comments = array();

    public function __construct($name, \DateTime $createdAt = null)
    {
        $this->name = $name;
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
}
